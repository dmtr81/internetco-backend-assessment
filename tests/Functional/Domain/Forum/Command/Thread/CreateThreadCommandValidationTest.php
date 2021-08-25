<?php

namespace App\Tests\Functional\Domain\Forum\Command\Thread;

use App\Domain\Forum\Command\Thread\CreateThreadCommand;
use App\Tests\Functional\FunctionalTestCase;
use App\Tests\Functional\ViolationAssertTrait;
use Symfony\Component\Uid\Uuid;

final class CreateThreadCommandValidationTest extends FunctionalTestCase
{
    use ViolationAssertTrait;

    private CreateThreadCommand $command;

    protected function setUp(): void
    {
        parent::setUp();

        $this->command = new CreateThreadCommand(Uuid::v4());
    }

    public function testAllPropertiesMustBeFilled(): void
    {
        $violations = $this->getValidator()->validate($this->command);

        foreach (['authorId', 'title', 'text'] as $property) {
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
}
