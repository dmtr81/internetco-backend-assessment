<?php

namespace App\Domain\Forum\Entity;

use App\Domain\Forum\Collection\PostCollection;
use DateTimeImmutable;
use DomainException;
use Symfony\Component\Uid\UuidV4;

/**
 * @final
 */
class Thread
{
    private UuidV4 $id;
    private AuthorInterface $author;
    private string $title;
    private string $text;
    private DateTimeImmutable $createdAt;

    /**
     * @var Post[]
     */
    private array $posts = [];

    public function __construct(UuidV4 $id, AuthorInterface $author, string $title, string $text)
    {
        $this->id = $id;
        $this->author = $author;
        $this->title = $title;
        $this->text = $text;
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
        $post = new Post($id, $author, $message);

        $this->posts[] = $post;

        return $post;
    }

    /**
     * @return Post[]
     */
    public function getPosts(): PostCollection
    {
        return new PostCollection($this->posts);
    }

    public function rewrite(string $title, string $text): void
    {
        $this->title = $title;
        $this->text = $text;
    }

    public function deletePost(Post $post): void
    {
        self::assertThreadPostExists($this, $post);

        $this->posts = $this->getPosts()->without($post)->toArray();
    }

    private static function assertThreadPostExists(Thread $thread, Post $post): void
    {
        if (!$thread->getPosts()->contains($post)) {
            throw new DomainException('Post does not exist.');
        }
    }
}
