<?php

namespace App\DataFixtures\Forum;

use App\DataFixtures\User\TestUserFixture;
use App\Domain\Forum\Entity\AuthorInterface;
use App\Domain\Forum\Entity\Thread;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

final class TestThreadWithPostsFromDifferentAuthorsFixture extends Fixture implements DependentFixtureInterface
{
    public const REFERENCE_NAME = 'test-thread-with-posts-from-different-authors';

    public function load(ObjectManager $manager)
    {
        $author = $this->getReference(TestAuthorFixture::REFERENCE_NAME);
        assert($author instanceof AuthorInterface);

        $otherAuthor = $this->getReference(TestUserFixture::REFERENCE_NAME);
        assert($otherAuthor instanceof AuthorInterface);

        $thread = new Thread(Uuid::v4(), $author, 'thread with different authors', 'text');
        $thread->postMessage(Uuid::v4(), $author, 'test');
        $thread->postMessage(Uuid::v4(), $otherAuthor, 'test');

        $this->addReference(self::REFERENCE_NAME, $thread);

        $manager->persist($thread);
        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public function getDependencies(): array
    {
        return [TestAuthorFixture::class, TestUserFixture::class];
    }
}
