<?php

namespace App\Domain\User\Collection;

use App\Domain\User\Entity\Notification;
use ArrayIterator;
use Countable;
use InvalidArgumentException;
use IteratorAggregate;

final class NotificationCollection implements IteratorAggregate, Countable
{
    /**
     * @var Notification[]
     */
    private array $notifications;

    /**
     * @param Notification[] $notifications
     */
    public function __construct(array $notifications)
    {
        self::assertItemsAreNotifications($notifications);

        $this->notifications = $notifications;
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->notifications);
    }

    public function count(): int
    {
        return count($this->notifications);
    }

    public function findById(string $notificationId): ?Notification
    {
        foreach ($this->notifications as $notification) {
            if ($notificationId === (string) $notification->getId()) {
                return $notification;
            }
        }

        return null;
    }

    private static function assertItemsAreNotifications(array $items): void
    {
        foreach ($items as $item) {
            if (!$item instanceof Notification) {
                throw new InvalidArgumentException(sprintf('Collection must contains %s only.', Notification::class));
            }
        }
    }
}
