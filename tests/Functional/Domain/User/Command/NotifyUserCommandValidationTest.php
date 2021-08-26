<?php

namespace App\Tests\Functional\Domain\User\Command;

use App\DataFixtures\User\TestUserFixture;
use App\Domain\User\Command\NotifyUserCommand;
use App\Domain\User\Entity\User;
use App\Tests\Functional\FunctionalTestCase;
use App\Tests\Functional\ViolationAssertTrait;
use Symfony\Component\Uid\Uuid;

final class NotifyUserCommandValidationTest extends FunctionalTestCase
{
    use ViolationAssertTrait;

    private NotifyUserCommand $command;

    protected function setUp(): void
    {
        parent::setUp();

        $this->command = new NotifyUserCommand();
    }

    public function testAllPropertiesMustBeFilled(): void
    {
        $violations = $this->getValidator()->validate($this->command);

        foreach (['userId', 'message'] as $property) {
            self::assertPropertyIsInvalid($property, 'This value should not be blank.', $violations);
        }
    }

    public function testUserMustExists(): void
    {
        $notExistingUserId = (string) Uuid::v4();
        $this->command->userId = $notExistingUserId;

        $violations = $this->getValidator()->validate($this->command);

        self::assertPropertyIsInvalid('userId', 'User does not exist.', $violations);
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

        self::assertPropertyIsInvalid('message', 'This value is too long. It should have 255 characters or less.', $violations);
    }

    public function testValidCommandShouldNotCauseViolations(): void
    {
        $referenceRepository = $this->getDatabaseTool()->loadFixtures([TestUserFixture::class])->getReferenceRepository();

        $user = $referenceRepository->getReference(TestUserFixture::REFERENCE_NAME);
        assert($user instanceof User);

        $this->command->userId = (string) $user->getId();
        $this->command->message = 'valid message';

        $violations = $this->getValidator()->validate($this->command);

        self::assertCount(0, $violations);
    }
}
