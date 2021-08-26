<?php

namespace App\Domain\Forum\Command\Thread;

use Happyr\Validator\Constraint\EntityExist;
use Symfony\Component\Validator\Constraints as Assert;

final class UpdateThreadCommand
{
    use UpdatableThreadDataTrait;

    #[Assert\NotBlank()]
    /**
     * @EntityExist(entity="App\Domain\Forum\Entity\Thread", message="Thread does not exist.")
     *
     * @todo use translation messages
     * @todo use attribute for EntityExist constraint
     */
    public $threadId;
}
