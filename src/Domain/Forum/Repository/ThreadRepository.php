<?php

namespace App\Domain\Forum\Repository;

use App\Domain\Forum\Entity\Thread;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class ThreadRepository
{
    private EntityRepository $doctrineRepository;

    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->doctrineRepository = $this->entityManager->getRepository(Thread::class);
    }

    public function findById(string $id): ?Thread
    {
        return $this->doctrineRepository->find($id);
    }

    public function save(Thread $thread): void
    {
        $this->entityManager->persist($thread);
    }

    public function delete(Thread $thread): void
    {
        $this->entityManager->remove($thread);
    }
}
