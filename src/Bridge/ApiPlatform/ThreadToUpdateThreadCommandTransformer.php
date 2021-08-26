<?php

namespace App\Bridge\ApiPlatform;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Domain\Forum\Command\Thread\UpdateThreadCommand;
use App\Domain\Forum\Entity\Thread;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

final class ThreadToUpdateThreadCommandTransformer implements DataTransformerInterface
{
    /**
     * @param UpdateThreadCommand $command
     *
     * @inheritDoc
     */
    public function transform($command, string $to, array $context = []): UpdateThreadCommand
    {
        $command->threadId = $context[AbstractNormalizer::OBJECT_TO_POPULATE]->getId();

        return $command;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if (($context['input']['class'] ?? null) !== UpdateThreadCommand::class) {
            return false;
        }

        return ($context[AbstractNormalizer::OBJECT_TO_POPULATE] ?? null) instanceof Thread;
    }
}
