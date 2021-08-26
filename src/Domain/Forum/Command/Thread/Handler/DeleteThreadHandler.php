<?php

namespace App\Domain\Forum\Command\Thread\Handler;

use App\Domain\Forum\Command\Thread\DeleteThreadCommand;
use App\Domain\Forum\Repository\ThreadRepository;

final class DeleteThreadHandler
{
    public function __construct(private ThreadRepository $threadRepository)
    {
    }

    public function __invoke(DeleteThreadCommand $command): void
    {
        $thread = $this->threadRepository->findById($command->threadId);

        $thread->deletePosts();

        $this->threadRepository->delete($thread);
    }
}
