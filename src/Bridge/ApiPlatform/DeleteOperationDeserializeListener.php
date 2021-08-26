<?php

namespace App\Bridge\ApiPlatform;

use ApiPlatform\Core\Api\FormatsProviderInterface;
use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use ApiPlatform\Core\Serializer\SerializerContextBuilderInterface;
use ApiPlatform\Core\Util\RequestAttributesExtractor;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * This is a hack to be able deserialize input for delete operations
 *
 * @see ApiPlatform\Core\EventListener\DeserializeListener
 *
 * @todo refactoring
 */
final class DeleteOperationDeserializeListener
{
    public const OPERATION_ATTRIBUTE_KEY = 'deserialize';

    private $serializer;
    private $serializerContextBuilder;
    private $formats;
    private $formatsProvider;

    /**
     * @param ResourceMetadataFactoryInterface|FormatsProviderInterface|array $resourceMetadataFactory
     */
    public function __construct(SerializerInterface $serializer, SerializerContextBuilderInterface $serializerContextBuilder, $resourceMetadataFactory, ResourceMetadataFactoryInterface $legacyResourceMetadataFactory = null)
    {
        $this->serializer = $serializer;
        $this->serializerContextBuilder = $serializerContextBuilder;
        $this->resourceMetadataFactory = $resourceMetadataFactory instanceof ResourceMetadataFactoryInterface ? $resourceMetadataFactory : $legacyResourceMetadataFactory;

        if (!$resourceMetadataFactory instanceof ResourceMetadataFactoryInterface) {
            @trigger_error(sprintf('Passing an array or an instance of "%s" as 3rd parameter of the constructor of "%s" is deprecated since API Platform 2.5, pass an instance of "%s" instead', FormatsProviderInterface::class, __CLASS__, ResourceMetadataFactoryInterface::class), \E_USER_DEPRECATED);
        }

        if (\is_array($resourceMetadataFactory)) {
            $this->formats = $resourceMetadataFactory;
        } elseif ($resourceMetadataFactory instanceof FormatsProviderInterface) {
            $this->formatsProvider = $resourceMetadataFactory;
        }
    }

    /**
     * Deserializes the data sent in the requested format.
     *
     * @throws UnsupportedMediaTypeHttpException
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $method = $request->getMethod();

        if ('DELETE' !== $method) {
            return;
        }

        $attributes = RequestAttributesExtractor::extractAttributes($request);

        if ($this->isOperationAttributeDisabled($attributes, self::OPERATION_ATTRIBUTE_KEY)) {
            return;
        }

        $context = $this->serializerContextBuilder->createFromRequest($request, false, $attributes);
        $data = $request->attributes->get('data');

        if (null !== $data) {
            $context[AbstractNormalizer::OBJECT_TO_POPULATE] = $data;
        }

        $request->attributes->set(
            'data',
            $this->serializer->deserialize('{}', $context['resource_class'], JsonEncoder::FORMAT, $context)
        );
    }

    private function isOperationAttributeDisabled(array $attributes, string $attribute, bool $default = false, bool $resourceFallback = true): bool
    {
        if (null === $this->resourceMetadataFactory) {
            return $default;
        }

        $resourceMetadata = $this->resourceMetadataFactory->create($attributes['resource_class']);

        return !((bool) $resourceMetadata->getOperationAttribute($attributes, $attribute, !$default, $resourceFallback));
    }
}
