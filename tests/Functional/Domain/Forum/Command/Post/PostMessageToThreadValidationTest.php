<?php

namespace App\Tests\Functional\Domain\Forum\Command\Post;

use App\DataFixtures\Forum\TestThreadWithAPostFixture;
use App\Domain\Forum\Command\Post\PostMessageToThreadCommand;
use App\Domain\Forum\Entity\Thread;
use App\Tests\Functional\FunctionalTestCase;
use App\Tests\Functional\ViolationAssertTrait;
use Symfony\Component\Uid\Uuid;

final class PostMessageToThreadValidationTest extends FunctionalTestCase
{
    use ViolationAssertTrait;

    private PostMessageToThreadCommand $command;

    protected function setUp(): void
    {
        parent::setUp();

        $this->command = new PostMessageToThreadCommand();
    }

    public function testAllPropertiesMustBeFilled(): void
    {
        $violations = $this->getValidator()->validate($this->command);

        foreach (['authorId', 'message', 'threadId'] as $property) {
            self::assertPropertyIsInvalid($property, 'This value should not be blank.', $violations);
        }
    }

    public function testAuthorMustExists(): void
    {
        $notExistingAuthorId = (string) Uuid::v4();
        $this->command->authorId = $notExistingAuthorId;

        $violations = $this->getValidator()->validate($this->command);

        self::assertPropertyIsInvalid('authorId', 'Author does not exist.', $violations);
    }

    public function testThreadMustExists(): void
    {
        $notExistingThreadId = (string) Uuid::v4();
        $this->command->threadId = $notExistingThreadId;

        $violations = $this->getValidator()->validate($this->command);

        self::assertPropertyIsInvalid('threadId', 'Thread does not exist.', $violations);
    }

    public function testMessageMustNotBeShort(): void
    {
        $shortText = 'shr';
        $this->command->message = $shortText;

        $violations = $this->getValidator()->validate($this->command);

        self::assertPropertyIsInvalid('message', 'This value is too short. It should have 4 characters or more.', $violations);
    }

    public function testMessageMustNotBeSoLong(): void
    {
        $longText = str_repeat('c', 1025);
        $this->command->message = $longText;

        $violations = $this->getValidator()->validate($this->command);

        self::assertPropertyIsInvalid('message', 'This value is too long. It should have 512 characters or less.', $violations);
    }

    public function testValidCommandShouldNotCauseViolations(): void
    {
        $referenceRepository = $this->getDatabaseTool()->loadFixtures([TestThreadWithAPostFixture::class])->getReferenceRepository();

        $thread = $referenceRepository->getReference(TestThreadWithAPostFixture::REFERENCE_NAME);
        assert($thread instanceof Thread);

        $this->command->threadId = (string) $thread->getId();
        $this->command->authorId = (string) $thread->getAuthor()->getId();
        $this->command->message = 'valid message';

        $violations = $this->getValidator()->validate($this->command);

        self::assertCount(0, $violations);
    }
}
