<?php

namespace App\Domain\User\Entity;

use App\Domain\Forum\Entity\AuthorInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;

/**
 * @final
 */
#[ORM\Entity()]
class User implements AuthorInterface
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private UuidV4 $id;

    #[ORM\Column(type: 'string', unique: true)]
    private string $username;

    #[ORM\Column(type: 'string', unique: true)]
    private string $email;

    #[ORM\Column(type: 'string')]
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
