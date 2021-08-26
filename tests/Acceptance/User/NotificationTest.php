<?php

namespace App\Tests\Acceptance\User;

use App\DataFixtures\User\TestUserWithNotificationFixture;
use App\Domain\User\Entity\Notification;
use App\Domain\User\Entity\User;
use App\Tests\Acceptance\AcceptanceTestCase;

final class NotificationTest extends AcceptanceTestCase
{
    public function testGetUserNotifications(): void
    {
        $referenceRepository = $this->getDatabaseTool()->loadFixtures([TestUserWithNotificationFixture::class])->getReferenceRepository();

        $user = $referenceRepository->getReference(TestUserWithNotificationFixture::REFERENCE_NAME);
        assert($user instanceof User);

        $expectedNotificationCount = count($user->getNotifications());

        $response = self::createClient()->request('GET', '/api/notifications', ['auth_basic' => self::resolveUserCredentials($user)]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/api/contexts/Notification',
            '@id' => '/api/notifications',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => $expectedNotificationCount,
        ]);

        self::assertCount($expectedNotificationCount, $response->toArray()['hydra:member']);

        self::assertMatchesResourceCollectionJsonSchema(Notification::class);
    }
}
