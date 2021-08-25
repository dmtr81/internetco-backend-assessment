<?php

namespace App\Tests\Unit\Domain\User\Entity;

use App\Domain\User\Entity\User;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

final class UserTest extends TestCase
{
    public function testUserCanBeCreated(): void
    {
        $expectedId = Uuid::v4();
        $expectedUsername = 'username';
        $expectedEmail = 'email@domain.com';
        $expectedPasswordHash = 'hash';

        $user = new User($expectedId, $expectedUsername, $expectedEmail, $expectedPasswordHash);

        self::assertSame($expectedId, $user->getId());
        self::assertSame($expectedUsername, $user->getUsername());
        self::assertSame($expectedEmail, $user->getEmail());
        self::assertSame($expectedPasswordHash, $user->getPasswordHash());
    }
}
