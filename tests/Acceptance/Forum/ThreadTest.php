<?php

namespace App\Tests\Acceptance\Forum;

use App\DataFixtures\Forum\TestThreadWithAPostFixture;
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

        $response = self::createClient()->request('GET', '/api/threads?page=1');

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
        self::createClient()->request('GET', $threadUrl);

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
        $referenceRepository = $this->getDatabaseTool()->loadFixtures([TestUserFixture::class])->getReferenceRepository();

        $author = $referenceRepository->getReference(TestUserFixture::REFERENCE_NAME);
        assert($author instanceof AuthorInterface);

        $expectedThreadData = [
            'authorId' => (string) $author->getId(),
            'title' => 'title',
            'text' => 'text',
        ];

        self::createClient()->request('POST', '/api/threads', ['json' => $expectedThreadData]);

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
        self::createClient()->request('PUT', $threadUrl, ['json' => $expectedThreadData]);

        self::assertResponseStatusCodeSame(200);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains(['@type' => 'UpdateThreadCommand']);
        self::assertJsonContains($expectedThreadData);

        self::assertMatchesResourceItemJsonSchema(Thread::class);
    }

    public function testDeleteThread(): void
    {
        $referenceRepository = $this->getDatabaseTool()->loadFixtures([TestThreadWithAPostFixture::class])->getReferenceRepository();

        $thread = $referenceRepository->getReference(TestThreadWithAPostFixture::REFERENCE_NAME);
        assert($thread instanceof Thread);

        $threadUrl = $this->findIriBy(Thread::class, ['id' => (string) $thread->getId()]);
        self::createClient()->request('DELETE', $threadUrl);

        self::assertResponseStatusCodeSame(204);
    }
}
