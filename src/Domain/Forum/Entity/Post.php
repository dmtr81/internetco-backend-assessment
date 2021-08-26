<?php

namespace App\Domain\Forum\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Domain\Forum\Command\Post\DeletePostCommand;
use App\Domain\Forum\Command\Post\PostMessageToThreadCommand;
use App\View\Forum\PostView;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;

/**
 * @final
 */
#[ORM\Entity()]
#[ApiResource(
    output: PostView::class,
    collectionOperations: [
        'get',
        'post' => ['messenger' => 'input', 'input' => PostMessageToThreadCommand::class],
    ],
    itemOperations: [
        'get',
        'delete' => ['messenger' => 'input', 'input' => DeletePostCommand::class, 'output' => false],
    ],
)]
class Post
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private UuidV4 $id;

    #[ORM\ManyToOne(targetEntity: 'App\Domain\Forum\Entity\Thread', inversedBy: 'posts')]
    private Thread $thread;

    #[ORM\ManyToOne(targetEntity: 'App\Domain\Forum\Entity\AuthorInterface')]
    private AuthorInterface $author;

    #[ORM\Column(type: 'text', length: 512)]
    private string $message;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    public function __construct(UuidV4 $id, Thread $thread, AuthorInterface $author, string $message)
    {
        $this->id = $id;
        $this->thread = $thread;
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

    public function getThread(): Thread
    {
        return $this->thread;
    }
}
