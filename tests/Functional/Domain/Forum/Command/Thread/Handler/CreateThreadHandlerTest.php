<?php

namespace App\Tests\Functional\Domain\Forum\Command\Thread\Handler;

use App\DataFixtures\Forum\TestAuthorFixture;
use App\Domain\Forum\Command\Thread\CreateThreadCommand;
use App\Domain\Forum\Entity\AuthorInterface;
use App\Domain\Forum\Repository\ThreadRepository;
use App\Tests\Functional\FunctionalTestCase;

final class CreateThreadHandlerTest extends FunctionalTestCase
{
    private AuthorInterface $author;
    private ThreadRepository $threadRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $referenceRepository = $this->getDatabaseTool()->loadFixtures([TestAuthorFixture::class])->getReferenceRepository();
        $this->author = $referenceRepository->getReference(TestAuthorFixture::REFERENCE_NAME);

        $this->threadRepository = $this->getContainer()->get(ThreadRepository::class);
    }

    public function testThreadMustBeCreatedAfterHandleCommand(): void
    {
        $command = new CreateThreadCommand();
        $command->title = 'title';
        $command->text = 'text';
        $command->authorId = (string) $this->author->getId();

        $this->getCommandBus()->dispatch($command);

        $actualThread = $this->threadRepository->findById($command->getThreadId());

        self::assertSame($command->title, $actualThread->getTitle());
        self::assertSame($command->text, $actualThread->getText());
        self::assertSame($this->author, $actualThread->getAuthor());
    }
}
