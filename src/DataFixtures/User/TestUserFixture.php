<?php

namespace App\DataFixtures\User;

use App\Domain\User\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

final class TestUserFixture extends Fixture
{
    public const REFERENCE_NAME = 'test-user';

    public function load(ObjectManager $manager)
    {
        $user = new User(Uuid::v4(), 'test', 'test@test.loc', 'test');

        $this->addReference(self::REFERENCE_NAME, $user);

        $manager->persist($user);
        $manager->flush();
    }
}
