<?php

namespace App\Domain\Forum\Collection;

use App\Domain\Forum\Entity\Post;
use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;

final class PostCollection implements IteratorAggregate, Countable
{
    /**
     * @var Post[]
     */
    private array $posts;

    /**
     * @param Post[] $posts
     */
    public function __construct(array $posts)
    {
        self::assertItemsArePosts($posts);

        $this->posts = $posts;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->posts);
    }

    public function count(): int
    {
        return count($this->posts);
    }

    public function contains(Post $expectedPost): bool
    {
        foreach ($this->posts as $post) {
            if ($expectedPost === $post) {
                return true;
            }
        }

        return false;
    }

    public function findById(string $postId): ?Post
    {
        foreach ($this->posts as $post) {
            if ($postId === (string) $post->getId()) {
                return $post;
            }
        }

        return null;
    }

    public function first(): ?Post
    {
        $post = current($this->posts);

        return $post ?: null;
    }

    public function getAuthors(): AuthorCollection
    {
        $authors = [];

        foreach ($this as $post) {
            assert($post instanceof Post);

            $authors[] = $post->getAuthor();
        }

        return new AuthorCollection($authors);
    }

    private static function assertItemsArePosts(array $items): void
    {
        foreach ($items as $item) {
            if (!$item instanceof Post) {
                throw new InvalidArgumentException(sprintf('Collection must contains %s only.', Post::class));
            }
        }
    }
}
