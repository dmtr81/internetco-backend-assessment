<?php

namespace App\Tests\Unit\Domain\Forum\Entity;

use App\Domain\Forum\Entity\AuthorInterface;
use App\Domain\Forum\Entity\Post;
use App\Domain\Forum\Entity\Thread;
use DomainException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class ThreadTest extends TestCase
{
    public function testThreadCanBeCreated(): void
    {
        $expectedId = Uuid::v4();
        $expectedAuthor = $this->createMock(AuthorInterface::class);
        $expectedTitle = 'title';
        $expectedText = 'text';

        $thread = new Thread($expectedId, $expectedAuthor, $expectedTitle, $expectedText);

        self::assertTrue($expectedId->equals($thread->getId()));
        self::assertSame($expectedAuthor, $thread->getAuthor());
        self::assertSame($expectedTitle, $thread->getTitle());
        self::assertSame($expectedText, $thread->getText());
    }

    public function testThreadCanBeRewrittenByAuthor(): void
    {
        $expectedNewTitle = 'new title';
        $expectedNewText = 'new text';

        $thread = $this->createValidThread();

        $thread->rewrite($expectedNewTitle, $expectedNewText);

        self::assertSame($expectedNewTitle, $thread->getTitle());
        self::assertSame($expectedNewText, $thread->getText());
    }

    public function testAnyAuthorCanPostMessageToThread(): void
    {
        $expectedPostId = Uuid::v4();
        $expectedPostAuthor = $this->createMock(AuthorInterface::class);
        $expectedPostMessage = 'message';

        $thread = $this->createValidThread();

        $post = $thread->postMessage($expectedPostId, $expectedPostAuthor, $expectedPostMessage);

        self::assertContains($post, $thread->getPosts());
        self::assertTrue($expectedPostId->equals($post->getId()));
        self::assertSame($expectedPostAuthor, $post->getAuthor());
        self::assertSame($expectedPostMessage, $post->getMessage());
    }

    public function testPostAuthorCanDeleteExistingPost(): void
    {
        $thread = $this->createValidThread();
        $post = $this->postValidMessageToThread($thread);

        $thread->deletePost($post);

        self::assertNotContains($post, $thread->getPosts());
    }

    public function testNotExisingPostCannotBeDeleted(): void
    {
        self::expectException(DomainException::class);
        self::expectExceptionMessage('Post does not exist.');

        $thread = $this->createValidThread();

        $thread->deletePost($this->createMock(Post::class));
    }

    public function testAllPostsCanBeDeleted(): void
    {
        $thread = $this->createValidThread();
        $post = $this->postValidMessageToThread($thread);

        $thread->deletePosts();

        self::assertNotContains($post, $thread->getPosts());
    }

    private function createValidThread(): Thread
    {
        return new Thread(Uuid::v4(), $this->createMock(AuthorInterface::class), 'title', 'text');
    }

    private function postValidMessageToThread(Thread $thread): Post
    {
        return $thread->postMessage(Uuid::v4(), $this->createMock(AuthorInterface::class), 'message');
    }
}
