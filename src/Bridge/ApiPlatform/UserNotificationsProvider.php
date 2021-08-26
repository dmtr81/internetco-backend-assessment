<?php

namespace App\Bridge\ApiPlatform;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Bridge\Security\SecurityUser;
use App\Domain\User\Entity\Notification;
use Symfony\Component\Security\Core\Security;

final class UserNotificationsProvider implements ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    public function __construct(private Security $security)
    {
    }

    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        $securityUser = $this->security->getUser();
        assert($securityUser instanceof SecurityUser);

        $user = $securityUser->getUser();

        return $user->getNotifications();
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === Notification::class;
    }
}
