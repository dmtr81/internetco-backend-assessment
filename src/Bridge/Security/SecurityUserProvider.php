<?php

namespace App\Bridge\Security;

use App\Domain\User\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

final class SecurityUserProvider implements UserProviderInterface
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function refreshUser(UserInterface $securityUser)
    {
        return $this->loadUserByUsername($securityUser->getUserIdentifier());
    }

    public function supportsClass(string $class)
    {
        return $class === SecurityUser::class || is_subclass_of($class, SecurityUser::class);
    }

    public function loadUserByUsername(string $username)
    {
        return $this->loadUserByIdentifier($username);
    }

    public function loadUserByIdentifier(string $identifier)
    {
        $user = $this->userRepository->findByEmail($identifier);

        if (!$user) {
            throw new UserNotFoundException();
        }

        return new SecurityUser($user);
    }
}
