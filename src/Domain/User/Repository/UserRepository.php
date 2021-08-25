<?php

namespace App\Domain\User\Repository;

use App\Domain\Forum\Repository\AuthorRepositoryInterface;
use App\Domain\User\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class UserRepository implements AuthorRepositoryInterface
{
    private EntityRepository $doctrineRepository;

    public function __construct(private EntityManagerInterface $entityManager)
    {
        $this->doctrineRepository = $this->entityManager->getRepository(User::class);
    }

    public function findById(string $id): ?User
    {
        return $this->doctrineRepository->find($id);
    }
}
