<?php

namespace App\View\User;

use Symfony\Component\Uid\Uuid;

final class NotificationView
{
    public Uuid $id;
    public string $message;
}
