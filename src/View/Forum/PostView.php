<?php

namespace App\View\Forum;

use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

final class PostView
{
    public Uuid $id;
    public string $message;
    public AuthorView $author;
    public DateTimeImmutable $createdAt;
}
