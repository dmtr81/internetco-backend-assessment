<?php

namespace App\Domain\Forum\Entity;

use App\Domain\Forum\Collection\PostCollection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Domain\Forum\Command\Thread\CreateThreadCommand;
use App\Domain\Forum\Command\Thread\DeleteThreadCommand;
use App\Domain\Forum\Command\Thread\UpdateThreadCommand;
use App\View\Forum\ThreadView;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;
use DomainException;
use Symfony\Component\Uid\UuidV4;

/**
 * @final
 */
#[ORM\Entity()]
#[ApiResource(
    output: ThreadView::class,
    collectionOperations: [
        'get',
        'post' => ['messenger' => 'input', 'input' => CreateThreadCommand::class],
    ],
    itemOperations: [
        'get',
        'put' => ['messenger' => 'input', 'input' => UpdateThreadCommand::class],
        'delete' => ['messenger' => 'input', 'input' => DeleteThreadCommand::class, 'output' => false],
    ],
)]
class Thread
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private UuidV4 $id;

    #[ORM\ManyToOne(targetEntity: 'App\Domain\Forum\Entity\AuthorInterface')]
    private AuthorInterface $author;

    #[ORM\Column(type: 'string', length: 64)]
    private string $title;

    #[ORM\Column(type: 'text', length: 1024)]
    private string $text;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\OneToMany(targetEntity: 'App\Domain\Forum\Entity\Post', mappedBy: 'thread', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $posts;

    public function __construct(UuidV4 $id, AuthorInterface $author, string $title, string $text)
    {
        $this->id = $id;
        $this->author = $author;
        $this->title = $title;
        $this->text = $text;
        $this->createdAt = new DateTimeImmutable();

        $this->posts = new ArrayCollection();
    }

    public function getId(): UuidV4
    {
        return $this->id;
    }

    public function getAuthor(): AuthorInterface
    {
        return $this->author;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function postMessage(UuidV4 $id, AuthorInterface $author, string $message): Post
    {
        $post = new Post($id, $this, $author, $message);

        $this->posts->add($post);

        return $post;
    }

    /**
     * @return Post[]
     */
    public function getPosts(): PostCollection
    {
        return new PostCollection(iterator_to_array($this->posts));
    }

    public function deletePosts(): void
    {
        $this->posts->clear();
    }

    public function rewrite(string $title, string $text): void
    {
        $this->title = $title;
        $this->text = $text;
    }

    public function deletePost(Post $post): void
    {
        self::assertThreadPostExists($this, $post);

        $this->posts->removeElement($post);
    }

    private static function assertThreadPostExists(Thread $thread, Post $post): void
    {
        if (!$thread->getPosts()->contains($post)) {
            throw new DomainException('Post does not exist.');
        }
    }
}
