<?php

namespace App\Domain\Forum\Command\Thread\Handler;

use App\Domain\Forum\Command\Thread\UpdateThreadCommand;
use App\Domain\Forum\Repository\ThreadRepository;

final class UpdateThreadHandler
{
    public function __construct(private ThreadRepository $threadRepository)
    {
    }

    public function __invoke(UpdateThreadCommand $command): void
    {
        $thread = $this->threadRepository->findById($command->threadId);

        $thread->rewrite($command->title, $command->text);

        $this->threadRepository->save($thread);
    }
}
