<?php

namespace App\Bridge\ApiPlatform;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Bridge\Security\SecurityUser;
use App\Domain\Forum\Command\Thread\CreateThreadCommand;
use Symfony\Component\Security\Core\Security;

final class CreateThreadCommandTransformer implements DataTransformerInterface
{
    public function __construct(private Security $security)
    {
    }

    /**
     * @param CreateThreadCommand $command
     *
     * @inheritDoc
     */
    public function transform($command, string $to, array $context = []): CreateThreadCommand
    {
        $securityUser = $this->security->getUser();
        assert($securityUser instanceof SecurityUser);

        $command->authorId = (string) $securityUser->getUser()->getId();

        return $command;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return ($context['input']['class'] ?? null) === CreateThreadCommand::class;
    }
}
