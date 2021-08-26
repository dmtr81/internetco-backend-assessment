<?php

namespace App\Domain\Forum\Collection;

use App\Domain\Forum\Entity\AuthorInterface;
use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;

final class AuthorCollection implements IteratorAggregate, Countable
{
    /**
     * @var AuthorInterface[]
     */
    private array $authors;

    /**
     * @param AuthorInterface[] $authors
     */
    public function __construct(array $authors)
    {
        self::assertItemsAreAuthors($authors);

        $this->authors = $authors;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->authors);
    }

    public function count(): int
    {
        return count($this->authors);
    }

    public function without(AuthorInterface $authorToExclude): self
    {
        $authors = array_filter($this->authors, static fn (AuthorInterface $author) => $author !== $authorToExclude);

        return new self($authors);
    }

    public function with(AuthorInterface $newAuthor): self
    {
        return new self(array_merge($this->authors, [$newAuthor]));

    }

    public function unique(): self
    {
        $authors = [];

        foreach ($this as $author) {
            if (!in_array($author, $authors, true)) {
                $authors[] = $author;
            }
        }

        return new self($authors);
    }

    private static function assertItemsAreAuthors(array $items): void
    {
        foreach ($items as $item) {
            if (!$item instanceof AuthorInterface) {
                throw new InvalidArgumentException(sprintf('Collection must contains %s only.', AuthorInterface::class));
            }
        }
    }
}
