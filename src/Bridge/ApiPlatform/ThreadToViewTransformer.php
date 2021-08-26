<?php

namespace App\Bridge\ApiPlatform;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Domain\Forum\Entity\Thread;
use App\View\Forum\ThreadView;
use App\View\Forum\ThreadViewFactory;

final class ThreadToViewTransformer implements DataTransformerInterface
{
    public function __construct(private ThreadViewFactory $threadViewFactory)
    {
    }

    /**
     * @param Thread $thread
     *
     * @inheritDoc
     */
    public function transform($thread, string $to, array $context = []): ThreadView
    {
        return $this->threadViewFactory->create($thread);
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return $data instanceof Thread;
    }
}
