<?php

namespace App\Domain\User\Command;

use Happyr\Validator\Constraint\EntityExist;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

final class NotifyUserCommand
{
    #[Assert\NotBlank()]
    /**
     * @EntityExist(entity="App\Domain\User\Entity\User", message="User does not exist.")
     *
     * @todo use translation messages
     * @todo use attribute for EntityExist constraint
     */
    public $userId;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 4, max: 255)]
    public $message;

    private UuidV4 $notificationId;

    public function __construct()
    {
        $this->notificationId = Uuid::v4();
    }

    public function getNotificationId(): UuidV4
    {
        return $this->notificationId;
    }
}
