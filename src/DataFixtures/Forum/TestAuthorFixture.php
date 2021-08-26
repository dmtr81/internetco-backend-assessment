<?php

namespace App\DataFixtures\Forum;

use App\Domain\User\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

final class TestAuthorFixture extends Fixture
{
    public const REFERENCE_NAME = 'test-author';

    public function load(ObjectManager $manager)
    {
        $user = new User(Uuid::v4(), 'author', 'author@test.loc', 'test');

        $this->addReference(self::REFERENCE_NAME, $user);

        $manager->persist($user);
        $manager->flush();
    }
}
