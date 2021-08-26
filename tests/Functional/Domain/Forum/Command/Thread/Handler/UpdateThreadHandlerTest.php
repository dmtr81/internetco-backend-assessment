<?php

namespace App\Tests\Functional\Domain\Forum\Command\Thread\Handler;

use App\DataFixtures\Forum\TestThreadWithAPostFixture;
use App\Domain\Forum\Command\Thread\UpdateThreadCommand;
use App\Domain\Forum\Entity\Thread;
use App\Tests\Functional\FunctionalTestCase;

final class UpdateThreadHandlerTest extends FunctionalTestCase
{
    private Thread $thread;

    protected function setUp(): void
    {
        parent::setUp();

        $referenceRepository = $this->getDatabaseTool()->loadFixtures([TestThreadWithAPostFixture::class])->getReferenceRepository();
        $this->thread = $referenceRepository->getReference(TestThreadWithAPostFixture::REFERENCE_NAME);
    }

    public function testThreadMustBeUpdatedAfterHandleCommand(): void
    {
        $command = new UpdateThreadCommand();
        $command->threadId = (string) $this->thread->getId();
        $command->title = 'new title';
        $command->text = 'new text';

        $this->getCommandBus()->dispatch($command);

        self::assertSame($command->title, $this->thread->getTitle());
        self::assertSame($command->text, $this->thread->getText());
    }
}
