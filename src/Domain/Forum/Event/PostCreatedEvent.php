<?php

namespace App\Domain\Forum\Event;

use App\Domain\Forum\Entity\Post;

final class PostCreatedEvent
{
    public function __construct(public Post $post)
    {
    }
}
