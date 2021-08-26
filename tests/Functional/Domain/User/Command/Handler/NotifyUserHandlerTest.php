<?php

namespace App\Tests\Functional\Domain\User\Command\Handler;

use App\DataFixtures\User\TestUserFixture;
use App\Domain\User\Command\NotifyUserCommand;
use App\Domain\User\Entity\User;
use App\Tests\Functional\FunctionalTestCase;

final class NotifyUserHandlerTest extends FunctionalTestCase
{
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $referenceRepository = $this->getDatabaseTool()->loadFixtures([TestUserFixture::class])->getReferenceRepository();
        $this->user = $referenceRepository->getReference(TestUserFixture::REFERENCE_NAME);
    }

    public function testPostMustBeCreatedAfterHandleCommand(): void
    {
        $command = new NotifyUserCommand();
        $command->userId = (string) $this->user->getId();
        $command->message = 'title';

        $this->getCommandBus()->dispatch($command);

        $notification = $this->user->getNotifications()->findById($command->getNotificationId());

        self::assertNotNull($notification);
        self::assertSame($command->message, $notification->getMessage());
    }
}
