<?php

namespace App\Tests\Unit\Domain\Forum\Collection;

use App\Domain\Forum\Collection\PostCollection;
use App\Domain\Forum\Entity\Post;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class PostCollectionTest extends TestCase
{
    public function testCollectionMustContainsPostsOnly(): void
    {
        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('Collection must contains');

        $notPost = 'not a post';

        new PostCollection([$notPost]);
    }
}
