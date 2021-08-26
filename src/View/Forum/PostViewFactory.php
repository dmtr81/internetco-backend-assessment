<?php

namespace App\View\Forum;

use App\Domain\Forum\Collection\PostCollection;
use App\Domain\Forum\Entity\Post;

final class PostViewFactory
{
    public function __construct(private AuthorViewFactory $authorViewFactory)
    {
    }

    public function create(Post $post): PostView
    {
        $postView = new PostView();
        $postView->id = $post->getId();
        $postView->message = $post->getMessage();
        $postView->author = $this->authorViewFactory->create($post->getAuthor());
        $postView->createdAt = $post->getCreatedAt();

        return $postView;
    }

    /**
     * @return PostView[]
     */
    public function createCollection(PostCollection $posts): array
    {
        $postsViews = [];

        foreach ($posts as $post) {
            $postsViews[] = $this->create($post);
        }

        return $postsViews;
    }
}
