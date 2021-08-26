<?php

namespace App\Tests\Functional\Domain\Forum\Command\Thread\Handler;

use App\DataFixtures\Forum\TestThreadWithAPostFixture;
use App\Domain\Forum\Command\Thread\DeleteThreadCommand;
use App\Domain\Forum\Entity\Thread;
use App\Domain\Forum\Repository\ThreadRepository;
use App\Tests\Functional\FunctionalTestCase;

final class DeleteThreadHandlerTest extends FunctionalTestCase
{
    private Thread $thread;
    private ThreadRepository $threadRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $referenceRepository = $this->getDatabaseTool()->loadFixtures([TestThreadWithAPostFixture::class])->getReferenceRepository();
        $this->thread = $referenceRepository->getReference(TestThreadWithAPostFixture::REFERENCE_NAME);

        $this->threadRepository = self::getContainer()->get(ThreadRepository::class);
    }

    public function testThreadMustBeDeletedAfterHandleCommand(): void
    {
        $command = new DeleteThreadCommand();
        $command->threadId = (string) $this->thread->getId();

        $this->getCommandBus()->dispatch($command);

        $actualThread = $this->threadRepository->findById($command->threadId);

        self::assertNull($actualThread);
    }
}
