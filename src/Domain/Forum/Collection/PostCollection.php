<?php

namespace App\Domain\Forum\Collection;

use App\Domain\Forum\Entity\Post;
use ArrayIterator;
use InvalidArgumentException;
use IteratorAggregate;

final class PostCollection implements IteratorAggregate
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

    public function contains(Post $expectedPost): bool
    {
        foreach ($this->posts as $post) {
            if ($expectedPost === $post) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Post[]
     */
    public function toArray(): array
    {
        return $this->posts;
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
