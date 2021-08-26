<?php

namespace App\Tests\Functional\Domain\Forum\Command\Post;

use App\DataFixtures\Forum\TestThreadWithAPostFixture;
use App\Domain\Forum\Command\Post\DeletePostCommand;
use App\Domain\Forum\Entity\Thread;
use App\Tests\Functional\FunctionalTestCase;
use App\Tests\Functional\ViolationAssertTrait;
use Symfony\Component\Uid\Uuid;

final class DeletePostCommandValidationTest extends FunctionalTestCase
{
    use ViolationAssertTrait;

    private DeletePostCommand $command;

    protected function setUp(): void
    {
        parent::setUp();

        $this->command = new DeletePostCommand();
    }

    public function testThreadMustExists(): void
    {
        $notExistingThreadId = (string) Uuid::v4();
        $this->command->threadId = $notExistingThreadId;

        $violations = $this->getValidator()->validate($this->command);

        self::assertPropertyIsInvalid('threadId', 'Thread does not exist.', $violations);
    }

    public function testPostMustExists(): void
    {
        $notExistingPostId = (string) Uuid::v4();
        $this->command->postId = $notExistingPostId;

        $violations = $this->getValidator()->validate($this->command);

        self::assertPropertyIsInvalid('postId', 'Post does not exist.', $violations);
    }

    public function testValidCommandShouldNotCauseViolations(): void
    {
        $referenceRepository = $this->getDatabaseTool()->loadFixtures([TestThreadWithAPostFixture::class])->getReferenceRepository();

        $thread = $referenceRepository->getReference(TestThreadWithAPostFixture::REFERENCE_NAME);
        assert($thread instanceof Thread);

        $post = $thread->getPosts()->first();

        $this->command->threadId = (string) $thread->getId();
        $this->command->postId = (string) $post->getId();

        $violations = $this->getValidator()->validate($this->command);

        self::assertCount(0, $violations);
    }
}
