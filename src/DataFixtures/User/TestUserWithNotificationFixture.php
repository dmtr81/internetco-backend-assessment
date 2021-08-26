<?php

namespace App\DataFixtures\User;

use App\Domain\User\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

final class TestUserWithNotificationFixture extends Fixture
{
    public const REFERENCE_NAME = 'test-user-with-notification';

    public function load(ObjectManager $manager)
    {
        $user = new User(Uuid::v4(), 'notified-user', 'notified@test.loc', 'test');
        $user->receiveNotification(Uuid::v4(), 'Notification message');

        $this->addReference(self::REFERENCE_NAME, $user);

        $manager->persist($user);
        $manager->flush();
    }
}
