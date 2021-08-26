<?php

namespace App\View\Forum;

use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

final class ThreadView
{
    public Uuid $id;
    public AuthorView $author;
    public string $title;
    public string $text;
    public DateTimeImmutable $createdAt;

    /**
     * @var PostView[]
     */
    public array $posts;
}
