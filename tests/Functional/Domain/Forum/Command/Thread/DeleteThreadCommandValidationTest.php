<?php

namespace App\Tests\Functional\Domain\Forum\Command\Thread;

use App\DataFixtures\Forum\TestThreadWithAPostFixture;
use App\Domain\Forum\Command\Thread\DeleteThreadCommand;
use App\Domain\Forum\Entity\Thread;
use App\Tests\Functional\FunctionalTestCase;
use App\Tests\Functional\ViolationAssertTrait;
use Symfony\Component\Uid\Uuid;

final class DeleteThreadCommandValidationTest extends FunctionalTestCase
{
    use ViolationAssertTrait;

    private DeleteThreadCommand $command;

    protected function setUp(): void
    {
        parent::setUp();

        $this->command = new DeleteThreadCommand();
    }

    public function testThreadMustExists(): void
    {
        $notExistingThreadId = (string) Uuid::v4();
        $this->command->threadId = $notExistingThreadId;

        $violations = $this->getValidator()->validate($this->command);

        self::assertPropertyIsInvalid('threadId', 'Thread does not exist.', $violations);
    }

    public function testValidCommandShouldNotCauseViolations(): void
    {
        $referenceRepository = $this->getDatabaseTool()->loadFixtures([TestThreadWithAPostFixture::class])->getReferenceRepository();

        $thread = $referenceRepository->getReference(TestThreadWithAPostFixture::REFERENCE_NAME);
        assert($thread instanceof Thread);

        $this->command->threadId = (string) $thread->getId();

        $violations = $this->getValidator()->validate($this->command);

        self::assertCount(0, $violations);
    }
}
