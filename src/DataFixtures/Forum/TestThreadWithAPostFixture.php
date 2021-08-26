<?php

namespace App\DataFixtures\Forum;

use App\DataFixtures\User\TestUserFixture;
use App\Domain\Forum\Entity\Thread;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

final class TestThreadWithAPostFixture extends Fixture implements DependentFixtureInterface
{
    public const REFERENCE_NAME = 'test-thread-without-posts';

    public function load(ObjectManager $manager)
    {
        $author = $this->getReference(TestUserFixture::REFERENCE_NAME);

        $thread = new Thread(Uuid::v4(), $author, 'thread without posts', 'text');
        $thread->postMessage(Uuid::v4(), $author, 'test');

        $this->addReference(self::REFERENCE_NAME, $thread);

        $manager->persist($thread);
        $manager->flush();
    }

    /**
     * @inheritDoc
     */
    public function getDependencies(): array
    {
        return [TestUserFixture::class];
    }
}
