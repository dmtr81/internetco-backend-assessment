<?php

namespace App\Bridge\Security;

use App\Domain\User\Entity\User;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class SecurityUser implements UserInterface, PasswordAuthenticatedUserInterface
{
    private const USER_ROLE = 'ROLE_USER'; // can be moved to enum

    public function __construct(private User $user)
    {
    }

    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @inheritDoc
     */
    public function getRoles(): array
    {
        return [self::USER_ROLE];
    }

    public function getPassword(): string
    {
        return $this->getUser()->getPasswordHash();
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }

    public function getUserIdentifier(): string
    {
        return $this->getUser()->getEmail();
    }

    public function is(User $user): bool
    {
        return $this->getUser() === $user;
    }
}
