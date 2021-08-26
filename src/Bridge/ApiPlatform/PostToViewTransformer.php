<?php

namespace App\Bridge\ApiPlatform;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Domain\Forum\Entity\Post;
use App\View\Forum\PostView;
use App\View\Forum\PostViewFactory;

final class PostToViewTransformer implements DataTransformerInterface
{
    public function __construct(private PostViewFactory $postViewFactory)
    {
    }

    /**
     * @param Post $post
     *
     * @inheritDoc
     */
    public function transform($post, string $to, array $context = []): PostView
    {
        return $this->postViewFactory->create($post);
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return $data instanceof Post;
    }
}
