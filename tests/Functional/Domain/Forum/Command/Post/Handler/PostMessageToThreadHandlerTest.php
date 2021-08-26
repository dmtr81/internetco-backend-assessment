<?php

namespace App\Tests\Functional\Domain\Forum\Command\Post\Handler;

use App\DataFixtures\Forum\TestThreadWithAPostFixture;
use App\Domain\Forum\Command\Post\PostMessageToThreadCommand;
use App\Domain\Forum\Entity\Thread;
use App\Tests\Functional\FunctionalTestCase;

final class PostMessageToThreadHandlerTest extends FunctionalTestCase
{
    private Thread $thread;

    protected function setUp(): void
    {
        parent::setUp();

        $referenceRepository = $this->getDatabaseTool()->loadFixtures([TestThreadWithAPostFixture::class])->getReferenceRepository();
        $this->thread = $referenceRepository->getReference(TestThreadWithAPostFixture::REFERENCE_NAME);
    }

    public function testPostMustBeCreatedAfterHandleCommand(): void
    {
        $command = new PostMessageToThreadCommand();
        $command->authorId = (string) $this->thread->getAuthor()->getId();
        $command->threadId = (string) $this->thread->getId();
        $command->message = 'title';

        $this->getCommandBus()->dispatch($command);

        $actualPost = $this->thread->getPosts()->findById($command->getPostId());

        self::assertNotNull($actualPost);
        self::assertSame($command->message, $actualPost->getMessage());
        self::assertSame($this->thread->getAuthor(), $actualPost->getAuthor());
    }
}
