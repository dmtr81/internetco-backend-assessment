<?php

namespace App\Domain\Forum\Command\Post;

use Happyr\Validator\Constraint\EntityExist;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

final class PostMessageToThreadCommand
{
    #[Assert\NotBlank()]
    /**
     * @EntityExist(entity="App\Domain\Forum\Entity\AuthorInterface", message="Author does not exist.")
     *
     * @todo use translation messages
     * @todo use attribute for EntityExist constraint
     */
    public $authorId;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 4, max: 512)]
    public $message;

    #[Assert\NotBlank()]
    /**
     * @EntityExist(entity="App\Domain\Forum\Entity\Thread", message="Thread does not exist.")
     *
     * @todo use translation messages
     * @todo use attribute for EntityExist constraint
     */
    public $threadId;

    private UuidV4 $postId;

    public function __construct()
    {
        $this->postId = Uuid::v4();
    }

    public function getPostId(): UuidV4
    {
        return $this->postId;
    }
}
