<?php

namespace App\Domain\Forum\Command\Thread;

use Happyr\Validator\Constraint\EntityExist;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateThreadCommand
{
    use UpdatableThreadDataTrait;

    #[Assert\NotBlank()]
    /**
     * @EntityExist(entity="App\Domain\Forum\Entity\AuthorInterface", message="Author does not exist.")
     *
     * @todo use translation messages
     * @todo use attribute for EntityExist constraint
     */
    public $authorId;

    private UuidV4 $threadId;

    public function __construct()
    {
        $this->threadId = Uuid::v4();
    }

    public function getThreadId(): UuidV4
    {
        return $this->threadId;
    }
}
