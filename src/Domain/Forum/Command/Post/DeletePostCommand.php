<?php

namespace App\Domain\Forum\Command\Post;

use Happyr\Validator\Constraint\EntityExist;
use Symfony\Component\Validator\Constraints as Assert;

final class DeletePostCommand
{
    #[Assert\NotBlank()]
    /**
     * @EntityExist(entity="App\Domain\Forum\Entity\Thread", message="Thread does not exist.")
     *
     * @todo use translation messages
     * @todo use attribute for EntityExist constraint
     */
    public $threadId;

    #[Assert\NotBlank()]
    /**
     * @EntityExist(entity="App\Domain\Forum\Entity\Post", message="Post does not exist.")
     *
     * @todo use translation messages
     * @todo use attribute for EntityExist constraint
     */
    public $postId;
}
