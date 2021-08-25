<?php

namespace App\Domain\Forum\Entity;

use DateTimeImmutable;
use Symfony\Component\Uid\UuidV4;

/**
 * @final
 */
class Post
{
    private UuidV4 $id;
    private AuthorInterface $author;
    private string $message;
    private DateTimeImmutable $createdAt;

    public function __construct(UuidV4 $id, AuthorInterface $author, string $message)
    {
        $this->id = $id;
        $this->author = $author;
        $this->message = $message;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): UuidV4
    {
        return $this->id;
    }

    public function getAuthor(): AuthorInterface
    {
        return $this->author;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
