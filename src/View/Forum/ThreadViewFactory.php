<?php

namespace App\View\Forum;

use App\Domain\Forum\Entity\Thread;

final class ThreadViewFactory
{
    public function __construct(private AuthorViewFactory $authorViewFactory, private PostViewFactory $postViewFactory)
    {
    }

    public function create(Thread $thread): ThreadView
    {
        $threadView = new ThreadView();
        $threadView->id = $thread->getId();
        $threadView->author = $this->authorViewFactory->create($thread->getAuthor());
        $threadView->title = $thread->getTitle();
        $threadView->text = $thread->getText();
        $threadView->createdAt = $thread->getCreatedAt();
        $threadView->posts = $this->postViewFactory->createCollection($thread->getPosts());

        return $threadView;
    }
}
