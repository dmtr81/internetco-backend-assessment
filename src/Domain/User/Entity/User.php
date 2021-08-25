<?php

namespace App\Domain\User\Entity;

use App\Domain\Forum\Entity\AuthorInterface;
use Symfony\Component\Uid\UuidV4;

/**
 * @final
 */
class User implements AuthorInterface
{
    private UuidV4 $id;
    private string $username;
    private string $email;
    private string $passwordHash;

    public function __construct(UuidV4 $id, string $username, string $email, string $passwordHash)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
    }

    public function getId(): UuidV4
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }
}
