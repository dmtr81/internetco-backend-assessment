<?php

namespace App\Domain\Forum\Command\Thread\Handler;

use App\Domain\Forum\Command\Thread\CreateThreadCommand;
use App\Domain\Forum\Entity\Thread;
use App\Domain\Forum\Repository\AuthorRepositoryInterface;
use App\Domain\Forum\Repository\ThreadRepository;

final class CreateThreadHandler
{
    public function __construct(private ThreadRepository $threadRepository, private AuthorRepositoryInterface $authorRepository)
    {
    }

    public function __invoke(CreateThreadCommand $command): void
    {
        $author = $this->authorRepository->findById($command->authorId);

        $thread = new Thread($command->id, $author, $command->title, $command->text);

        $this->threadRepository->save($thread);
    }
}
