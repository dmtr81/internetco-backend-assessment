<?php

namespace App\Bridge\ApiPlatform;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Bridge\Security\SecurityUser;
use App\Domain\Forum\Command\Post\PostMessageToThreadCommand;
use Symfony\Component\Security\Core\Security;

final class PostMessageToThreadCommandTransformer implements DataTransformerInterface
{
    public function __construct(private Security $security)
    {
    }

    /**
     * @param PostMessageToThreadCommand $command
     *
     * @inheritDoc
     */
    public function transform($command, string $to, array $context = []): PostMessageToThreadCommand
    {
        $securityUser = $this->security->getUser();
        assert($securityUser instanceof SecurityUser);

        $command->authorId = (string) $securityUser->getUser()->getId();

        return $command;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return ($context['input']['class'] ?? null) === PostMessageToThreadCommand::class;
    }
}
