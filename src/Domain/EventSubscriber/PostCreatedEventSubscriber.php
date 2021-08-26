<?php

namespace App\Domain\EventSubscriber;

use App\Domain\Forum\Event\PostCreatedEvent;
use App\Domain\User\Command\NotifyUserCommand;
use App\Domain\User\Entity\User;
use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class PostCreatedEventSubscriber implements MessageSubscriberInterface
{
    /**
     * @todo move to command factory
     */
    private const NOTIFICATION_TEXT = 'You have new message.';

    private MessageBusInterface $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @inheritDoc
     */
    public static function getHandledMessages(): iterable
    {
        yield PostCreatedEvent::class => ['method' => 'notifyPostThreadUsers'];
    }

    /**
     * @todo use command factory
     */
    public function notifyPostThreadUsers(PostCreatedEvent $event): void
    {
        $post = $event->post;
        $thread = $post->getThread();

        $recipients = $thread->getInterlocutors()->without($post->getAuthor());

        foreach ($recipients as $recipient) {
            assert($recipient instanceof User);

            $command = new NotifyUserCommand();
            $command->userId = (string) $recipient->getId();
            $command->message = self::NOTIFICATION_TEXT;

            $this->commandBus->dispatch($command);
        }
    }
}
