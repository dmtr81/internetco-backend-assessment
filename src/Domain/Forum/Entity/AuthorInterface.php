<?php

namespace App\Domain\Forum\Entity;

use Symfony\Component\Uid\UuidV4;

interface AuthorInterface
{
    public function getId(): UuidV4;
    public function getUsername(): string;
}
