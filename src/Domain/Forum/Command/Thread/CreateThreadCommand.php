<?php

namespace App\Domain\Forum\Command\Thread;

use Happyr\Validator\Constraint\EntityExist;
use Symfony\Component\Uid\UuidV4;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateThreadCommand
{
    #[Assert\NotBlank()]
    /**
     * @EntityExist(entity="App\Domain\Forum\Entity\AuthorInterface", message="Author does not exist.")
     *
     * @todo use translation messages
     * @todo use attribute
     */
    public $authorId;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 4, max: 64)]
    public $title;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 4, max: 1024)]
    public $text;

    public function __construct(public UuidV4 $id)
    {
    }
}
