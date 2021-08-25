<?php

namespace App\Domain\Forum\Repository;

use App\Domain\Forum\Entity\AuthorInterface;

interface AuthorRepositoryInterface
{
    public function findById(string $id): ?AuthorInterface;
}
