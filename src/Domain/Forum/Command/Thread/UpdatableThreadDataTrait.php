<?php

namespace App\Domain\Forum\Command\Thread;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

trait UpdatableThreadDataTrait
{
    #[Assert\NotBlank()]
    #[Assert\Length(min: 4, max: 64)]
    /** @Groups({"read", "write"}) */
    public $title;

    #[Assert\NotBlank()]
    #[Assert\Length(min: 4, max: 1024)]
    /** @Groups({"read", "write"}) */
    public $text;
}
