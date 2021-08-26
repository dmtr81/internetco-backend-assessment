<?php

namespace App\Domain\Forum\Command\Post\Handler;

use App\Domain\Forum\Command\Post\DeletePostCommand;
use App\Domain\Forum\Repository\ThreadRepository;

final class DeletePostHandler
{
    public function __construct(private ThreadRepository $threadRepository)
    {
    }

    public function __invoke(DeletePostCommand $command): void
    {
        $thread = $this->threadRepository->findById($command->threadId);
        $post = $thread->getPosts()->findById($command->postId);

        $thread->deletePost($post);

        $this->threadRepository->save($thread);
    }
}
