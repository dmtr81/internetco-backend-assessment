<?php

namespace App\View\Forum;

use App\Domain\Forum\Entity\AuthorInterface;

final class AuthorViewFactory
{
    public function create(AuthorInterface $author): AuthorView
    {
        $authorView = new AuthorView();
        $authorView->id = $author->getId();
        $authorView->username = $author->getUsername();

        return $authorView;
    }
}
