<?php

namespace App\Tests\Functional\Domain\Forum\Command\Post\Handler;

use App\DataFixtures\Forum\TestThreadWithAPostFixture;
use App\Domain\Forum\Command\Post\DeletePostCommand;
use App\Domain\Forum\Entity\Thread;
use App\Tests\Functional\FunctionalTestCase;

final class DeletePostHandlerTest extends FunctionalTestCase
{
    private Thread $thread;

    protected function setUp(): void
    {
        parent::setUp();

        $referenceRepository = $this->getDatabaseTool()->loadFixtures([TestThreadWithAPostFixture::class])->getReferenceRepository();
        $this->thread = $referenceRepository->getReference(TestThreadWithAPostFixture::REFERENCE_NAME);
    }

    public function testPostMustBeDeletedAfterHandleCommand(): void
    {
        $post = $this->thread->getPosts()->first();

        $command = new DeletePostCommand();
        $command->threadId = (string) $this->thread->getId();
        $command->postId = (string) $post->getId();

        $this->getCommandBus()->dispatch($command);

        self::assertNotContains($post, $this->thread->getPosts());
    }
}
