<?php

namespace App\Bridge\ApiPlatform;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Domain\Forum\Command\Thread\DeleteThreadCommand;
use App\Domain\Forum\Entity\Thread;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

final class ThreadToDeleteThreadCommandTransformer implements DataTransformerInterface
{
    /**
     * @param DeleteThreadCommand $command
     *
     * @inheritDoc
     */
    public function transform($command, string $to, array $context = []): DeleteThreadCommand
    {
        $command->threadId = $context[AbstractNormalizer::OBJECT_TO_POPULATE]->getId();

        return $command;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if (($context['input']['class'] ?? null) !== DeleteThreadCommand::class) {
            return false;
        }

        return ($context[AbstractNormalizer::OBJECT_TO_POPULATE] ?? null) instanceof Thread;
    }
}
