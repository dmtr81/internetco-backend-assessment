<?php

namespace App\Domain\Forum\Command\Post\Handler;

use App\Domain\Forum\Command\Post\PostMessageToThreadCommand;
use App\Domain\Forum\Event\PostCreatedEvent;
use App\Domain\Forum\Repository\AuthorRepositoryInterface;
use App\Domain\Forum\Repository\ThreadRepository;
use Symfony\Component\Messenger\MessageBusInterface;

final class PostMessageToThreadHandler
{
    public function __construct(
        private ThreadRepository $threadRepository,
        private AuthorRepositoryInterface $authorRepository,
        private MessageBusInterface $eventBus,
    ) {
    }

    public function __invoke(PostMessageToThreadCommand $command): void
    {
        $author = $this->authorRepository->findById($command->authorId);
        $thread = $this->threadRepository->findById($command->threadId);

        $post = $thread->postMessage($command->getPostId(), $author, $command->message);

        $this->threadRepository->save($thread);

        $this->eventBus->dispatch(new PostCreatedEvent($post));
    }
}
