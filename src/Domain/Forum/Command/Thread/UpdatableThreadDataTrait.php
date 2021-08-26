<?php

namespace App\Domain\Forum\Command\Thread;

use Symfony\Component\Validator\Constraints as Assert;

trait UpdatableThreadDataTrait
{
    #[Assert\NotBlank()]
    #[Assert\Length(min: 4, max: 64)]
    public $title;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 4, max: 1024)]
    public $text;
}
