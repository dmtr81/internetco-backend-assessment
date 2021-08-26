<?php

namespace App\Bridge\ApiPlatform;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Domain\User\Entity\Notification;
use App\View\User\NotificationView;
use App\View\User\NotificationViewFactory;

final class NotificationToViewTransformer implements DataTransformerInterface
{
    public function __construct(private NotificationViewFactory $notificationViewFactory)
    {
    }

    /**
     * @param Notification $notification
     *
     * @inheritDoc
     */
    public function transform($notification, string $to, array $context = []): NotificationView
    {
        return $this->notificationViewFactory->create($notification);
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return $data instanceof Notification;
    }
}
