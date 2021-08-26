<?php

namespace App\Tests\Functional\Domain\EventSubscriber;

use App\DataFixtures\Forum\TestThreadWithPostsFromDifferentAuthorsFixture;
use App\Domain\Forum\Entity\Thread;
use App\Domain\Forum\Event\PostCreatedEvent;
use App\Domain\User\Entity\User;
use App\Tests\Functional\FunctionalTestCase;

final class PostCreatedEventSubscriberTest extends FunctionalTestCase
{
    public function testThreadInterlocutorsMustReceiveNotificationsOnPostCreated(): void
    {
        $referenceRepository = $this->getDatabaseTool()->loadFixtures([TestThreadWithPostsFromDifferentAuthorsFixture::class])->getReferenceRepository();
        $thread = $referenceRepository->getReference(TestThreadWithPostsFromDifferentAuthorsFixture::REFERENCE_NAME);
        assert($thread instanceof Thread);

        $post = $thread->getPosts()->first();

        $expectedRecipients = $thread->getInterlocutors()->without($post->getAuthor());

        $this->getEventBus()->dispatch(new PostCreatedEvent($post));

        self::assertGreaterThan(0, count($expectedRecipients));

        foreach ($expectedRecipients as $recipient) {
            assert($recipient instanceof User);

            self::assertCount(1, $recipient->getNotifications());
        }
    }
}
