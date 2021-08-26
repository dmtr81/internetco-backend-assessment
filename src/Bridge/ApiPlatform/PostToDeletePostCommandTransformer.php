<?php

namespace App\Bridge\ApiPlatform;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Domain\Forum\Command\Post\DeletePostCommand;
use App\Domain\Forum\Entity\Post;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

final class PostToDeletePostCommandTransformer implements DataTransformerInterface
{
    /**
     * @param DeletePostCommand $command
     *
     * @inheritDoc
     */
    public function transform($command, string $to, array $context = []): DeletePostCommand
    {
        $post = $context[AbstractNormalizer::OBJECT_TO_POPULATE];
        assert($post instanceof Post);

        $command->threadId = $post->getThread()->getId();
        $command->postId = $post->getId();

        return $command;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if (($context['input']['class'] ?? null) !== DeletePostCommand::class) {
            return false;
        }

        return ($context[AbstractNormalizer::OBJECT_TO_POPULATE] ?? null) instanceof Post;
    }
}
