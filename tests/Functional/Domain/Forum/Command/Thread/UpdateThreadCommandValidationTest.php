<?php

namespace App\Tests\Functional\Domain\Forum\Command\Thread;

use App\DataFixtures\Forum\TestThreadWithAPostFixture;
use App\Domain\Forum\Command\Thread\UpdateThreadCommand;
use App\Domain\Forum\Entity\Thread;
use App\Tests\Functional\FunctionalTestCase;
use App\Tests\Functional\ViolationAssertTrait;
use Symfony\Component\Uid\Uuid;

final class UpdateThreadCommandValidationTest extends FunctionalTestCase
{
    use ViolationAssertTrait;

    private UpdateThreadCommand $command;

    protected function setUp(): void
    {
        parent::setUp();

        $this->command = new UpdateThreadCommand();
    }

    public function testAllPropertiesMustBeFilled(): void
    {
        $violations = $this->getValidator()->validate($this->command);

        foreach (['threadId', 'title', 'text'] as $property) {
            self::assertPropertyIsInvalid($property, 'This value should not be blank.', $violations);
        }
    }

    public function testThreadMustExists(): void
    {
        $notExistingThreadId = (string) Uuid::v4();
        $this->command->threadId = $notExistingThreadId;

        $violations = $this->getValidator()->validate($this->command);

        self::assertPropertyIsInvalid('threadId', 'Thread does not exist.', $violations);
    }

    public function testTitleAndTextMustNotBeShort(): void
    {
        $expectedViolationMessage = 'This value is too short. It should have 4 characters or more.';

        $shortText = 'shr';
        $this->command->title = $shortText;
        $this->command->text = $shortText;

        $violations = $this->getValidator()->validate($this->command);

        self::assertPropertyIsInvalid('title', $expectedViolationMessage, $violations);
        self::assertPropertyIsInvalid('text', $expectedViolationMessage, $violations);
    }

    public function testTitleAndTextMustNotBeSoLong(): void
    {
        $longText = str_repeat('c', 1025);
        $this->command->title = $longText;
        $this->command->text = $longText;

        $violations = $this->getValidator()->validate($this->command);

        self::assertPropertyIsInvalid('title', 'This value is too long. It should have 64 characters or less.', $violations);
        self::assertPropertyIsInvalid('text', 'This value is too long. It should have 1024 characters or less.', $violations);
    }

    public function testValidCommandShouldNotCauseViolations(): void
    {
        $referenceRepository = $this->getDatabaseTool()->loadFixtures([TestThreadWithAPostFixture::class])->getReferenceRepository();

        $thread = $referenceRepository->getReference(TestThreadWithAPostFixture::REFERENCE_NAME);
        assert($thread instanceof Thread);

        $this->command->threadId = (string) $thread->getId();
        $this->command->title = 'valid title';
        $this->command->text = 'valid text';

        $violations = $this->getValidator()->validate($this->command);

        self::assertCount(0, $violations);
    }
}
