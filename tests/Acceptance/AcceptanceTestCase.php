<?php

namespace App\Tests\Acceptance;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Domain\User\Entity\User;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;

abstract class AcceptanceTestCase extends ApiTestCase
{
    final protected function getDatabaseTool(): AbstractDatabaseTool
    {
        return static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    /**
     * @return string[]
     */
    final protected static function resolveUserCredentials(User $user): array
    {
        return [$user->getEmail(), $user->getPasswordHash()];
    }
}
