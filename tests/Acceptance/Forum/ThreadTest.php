<?php

namespace App\Tests\Acceptance\Forum;

use App\DataFixtures\Forum\TestThreadWithAPostFixture;
use App\DataFixtures\Forum\TestAuthorFixture;
use App\DataFixtures\User\TestUserFixture;
use App\Domain\Forum\Entity\AuthorInterface;
use App\Domain\Forum\Entity\Thread;
use App\Tests\Acceptance\AcceptanceTestCase;

final class ThreadTest extends AcceptanceTestCase
{
    public function testGetAllThreads(): void
    {
        $referenceRepository = $this->getDatabaseTool()->loadFixtures([TestThreadWithAPostFixture::class])->getReferenceRepository();

        $thread = $referenceRepository->getReference(TestThreadWithAPostFixture::REFERENCE_NAME);
        assert($thread instanceof Thread);

        $response = self::createClient()->request('GET', '/api/threads?page=1', ['auth_basic' => self::resolveUserCredentials($thread->getAuthor())]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/api/contexts/Thread',
            '@id' => '/api/threads',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => 1,
        ]);

        self::assertCount(1, $response->toArray()['hydra:member']);

        self::assertMatchesResourceCollectionJsonSchema(Thread::class);
    }

    public function testGetAThread(): void
    {
        $referenceRepository = $this->getDatabaseTool()->loadFixtures([TestThreadWithAPostFixture::class])->getReferenceRepository();

        $thread = $referenceRepository->getReference(TestThreadWithAPostFixture::REFERENCE_NAME);
        assert($thread instanceof Thread);

        $threadUrl = $this->findIriBy(Thread::class, ['id' => (string) $thread->getId()]);
        self::createClient()->request('GET', $threadUrl, ['auth_basic' => self::resolveUserCredentials($thread->getAuthor())]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@id' => $threadUrl,
            '@type' => 'Thread',
        ]);

        self::assertMatchesResourceItemJsonSchema(Thread::class);
    }

    public function testCreateThread(): void
    {
        $referenceRepository = $this->getDatabaseTool()->loadFixtures([TestAuthorFixture::class])->getReferenceRepository();

        $author = $referenceRepository->getReference(TestAuthorFixture::REFERENCE_NAME);
        assert($author instanceof AuthorInterface);

        $expectedThreadData = [
            'title' => 'title',
            'text' => 'text',
        ];

        self::createClient()->request('POST', '/api/threads', [
            'json' => $expectedThreadData,
            'auth_basic' => self::resolveUserCredentials($author),
        ]);

        self::assertResponseStatusCodeSame(201);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains(['@type' => 'CreateThreadCommand']);
        self::assertJsonContains($expectedThreadData);

        self::assertMatchesResourceItemJsonSchema(Thread::class);
    }

    public function testUpdatedThread(): void
    {
        $referenceRepository = $this->getDatabaseTool()->loadFixtures([TestThreadWithAPostFixture::class])->getReferenceRepository();

        $thread = $referenceRepository->getReference(TestThreadWithAPostFixture::REFERENCE_NAME);
        assert($thread instanceof Thread);

        $expectedThreadData = [
            'title' => 'new title',
            'text' => 'new text',
        ];

        $threadUrl = $this->findIriBy(Thread::class, ['id' => (string) $thread->getId()]);
        self::createClient()->request('PUT', $threadUrl, [
            'json' => $expectedThreadData,
            'auth_basic' => self::resolveUserCredentials($thread->getAuthor()),
        ]);

        self::assertResponseStatusCodeSame(200);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains(['@type' => 'UpdateThreadCommand']);
        self::assertJsonContains($expectedThreadData);

        self::assertMatchesResourceItemJsonSchema(Thread::class);
    }

    public function testUserCannotUpdateNotOwnThread(): void
    {
        $referenceRepository = $this->getDatabaseTool()->loadFixtures([
            TestThreadWithAPostFixture::class,
            TestUserFixture::class,
        ])->getReferenceRepository();

        $thread = $referenceRepository->getReference(TestThreadWithAPostFixture::REFERENCE_NAME);
        assert($thread instanceof Thread);

        $notThreadAuthor = $referenceRepository->getReference(TestUserFixture::REFERENCE_NAME);
        assert($notThreadAuthor instanceof AuthorInterface);

        $threadUrl = $this->findIriBy(Thread::class, ['id' => (string) $thread->getId()]);
        self::createClient()->request('PUT', $threadUrl, [
            'json' => [
                'title' => 'new title',
                'text' => 'new text',
            ],
            'auth_basic' => self::resolveUserCredentials($notThreadAuthor),
        ]);

        self::assertResponseStatusCodeSame(403);
    }

    public function testDeleteThread(): void
    {
        $referenceRepository = $this->getDatabaseTool()->loadFixtures([TestThreadWithAPostFixture::class])->getReferenceRepository();

        $thread = $referenceRepository->getReference(TestThreadWithAPostFixture::REFERENCE_NAME);
        assert($thread instanceof Thread);

        $threadUrl = $this->findIriBy(Thread::class, ['id' => (string) $thread->getId()]);
        self::createClient()->request('DELETE', $threadUrl, ['auth_basic' => self::resolveUserCredentials($thread->getAuthor())]);

        self::assertResponseStatusCodeSame(204);
    }

    public function testUserCannotDeleteNotOwnThread(): void
    {
        $referenceRepository = $this->getDatabaseTool()->loadFixtures([
            TestThreadWithAPostFixture::class,
            TestUserFixture::class,
        ])->getReferenceRepository();

        $thread = $referenceRepository->getReference(TestThreadWithAPostFixture::REFERENCE_NAME);
        assert($thread instanceof Thread);

        $notThreadAuthor = $referenceRepository->getReference(TestUserFixture::REFERENCE_NAME);
        assert($notThreadAuthor instanceof AuthorInterface);

        $threadUrl = $this->findIriBy(Thread::class, ['id' => (string) $thread->getId()]);
        self::createClient()->request('DELETE', $threadUrl, ['auth_basic' => self::resolveUserCredentials($notThreadAuthor)]);

        self::assertResponseStatusCodeSame(403);
    }
}
