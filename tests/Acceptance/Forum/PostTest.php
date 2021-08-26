<?php

namespace App\Tests\Acceptance\Forum;

use App\DataFixtures\Forum\TestThreadWithAPostFixture;
use App\DataFixtures\User\TestUserFixture;
use App\Domain\Forum\Entity\AuthorInterface;
use App\Domain\Forum\Entity\Post;
use App\Domain\Forum\Entity\Thread;
use App\Tests\Acceptance\AcceptanceTestCase;

final class PostTest extends AcceptanceTestCase
{
    private Thread $thread;

    protected function setUp(): void
    {
        parent::setUp();

        $referenceRepository = $this->getDatabaseTool()->loadFixtures([TestThreadWithAPostFixture::class])->getReferenceRepository();
        $this->thread = $referenceRepository->getReference(TestThreadWithAPostFixture::REFERENCE_NAME);
    }

    public function testGetAllPosts(): void
    {
        $expectedPostsCount = count($this->thread->getPosts());

        $response = self::createClient()->request('GET', '/api/posts?page=1', ['auth_basic' => self::resolveUserCredentials($this->thread->getAuthor())]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@context' => '/api/contexts/Post',
            '@id' => '/api/posts',
            '@type' => 'hydra:Collection',
            'hydra:totalItems' => $expectedPostsCount,
        ]);

        self::assertCount($expectedPostsCount, $response->toArray()['hydra:member']);

        self::assertMatchesResourceCollectionJsonSchema(Post::class);
    }

    public function testGetAPost(): void
    {
        $post = $this->thread->getPosts()->first();

        $postUrl = $this->findIriBy(Post::class, ['id' => (string) $post->getId()]);
        self::createClient()->request('GET', $postUrl, ['auth_basic' => self::resolveUserCredentials($post->getAuthor())]);

        self::assertResponseIsSuccessful();
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        self::assertJsonContains([
            '@id' => $postUrl,
            '@type' => 'Post',
        ]);

        self::assertMatchesResourceItemJsonSchema(Post::class);
    }

    public function testPostMessage(): void
    {
        $expectedPostData = [
            'threadId' => (string) $this->thread->getId(),
            'message' => 'title',
        ];

        self::createClient()->request('POST', '/api/posts', [
            'json' => $expectedPostData,
            'auth_basic' => self::resolveUserCredentials($this->thread->getAuthor()),
        ]);

        self::assertResponseStatusCodeSame(201);
        self::assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        self::assertJsonContains(['@type' => 'PostMessageToThreadCommand']);
        self::assertJsonContains($expectedPostData);

        self::assertMatchesResourceItemJsonSchema(Post::class);
    }

    public function testDeletePost(): void
    {
        $post = $this->thread->getPosts()->first();

        $postUrl = $this->findIriBy(Post::class, ['id' => (string) $post->getId()]);
        self::createClient()->request('DELETE', $postUrl, ['auth_basic' => self::resolveUserCredentials($post->getAuthor())]);

        self::assertResponseStatusCodeSame(204);
    }

    public function testUserCannotDeleteNotOwnPost(): void
    {
        $referenceRepository = $this->getDatabaseTool()->loadFixtures([TestUserFixture::class], true)->getReferenceRepository();
        $notPostAuthor = $referenceRepository->getReference(TestUserFixture::REFERENCE_NAME);
        assert($notPostAuthor instanceof AuthorInterface);

        $post = $this->thread->getPosts()->first();

        $postUrl = $this->findIriBy(Post::class, ['id' => (string) $post->getId()]);
        self::createClient()->request('DELETE', $postUrl, ['auth_basic' => self::resolveUserCredentials($notPostAuthor)]);

        self::assertResponseStatusCodeSame(403);
    }
}
