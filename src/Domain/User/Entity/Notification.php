<?php

namespace App\Domain\User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;

/**
 * @final
 */
#[ORM\Entity()]
class Notification
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private UuidV4 $id;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private User $owner;

    #[ORM\Column(type: 'string')]
    private string $message;

    public function __construct(UuidV4 $id, User $owner, string $message)
    {
        $this->id = $id;
        $this->owner = $owner;
        $this->message = $message;
    }

    public function getId(): UuidV4
    {
        return $this->id;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
