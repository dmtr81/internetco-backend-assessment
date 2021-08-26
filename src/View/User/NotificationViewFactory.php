<?php

namespace App\View\User;

use App\Domain\User\Entity\Notification;

final class NotificationViewFactory
{
    public function create(Notification $notification): NotificationView
    {
        $authorView = new NotificationView();
        $authorView->id = $notification->getId();
        $authorView->message = $notification->getMessage();

        return $authorView;
    }
}
