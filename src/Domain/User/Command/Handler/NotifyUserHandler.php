<?php

namespace App\Domain\User\Command\Handler;

use App\Domain\User\Command\NotifyUserCommand;
use App\Domain\User\Repository\UserRepository;

final class NotifyUserHandler
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function __invoke(NotifyUserCommand $command): void
    {
        $user = $this->userRepository->findById($command->userId);

        $user->receiveNotification($command->getNotificationId(), $command->message);

        $this->userRepository->save($user);
    }
}
