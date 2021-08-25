<?php

namespace App\Tests\Functional\Domain\Forum\Command\Thread\Handler;

use App\DataFixtures\User\TestUserFixture;
use App\Domain\Forum\Command\Thread\CreateThreadCommand;
use App\Domain\Forum\Entity\AuthorInterface;
use App\Domain\Forum\Repository\ThreadRepository;
use App\Tests\Functional\FunctionalTestCase;
use Symfony\Component\Uid\Uuid;

final class CreateThreadHandlerTest extends FunctionalTestCase
{
    private AuthorInterface $author;
    private ThreadRepository $threadRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $referenceRepository = $this->getDatabaseTool()->loadFixtures([TestUserFixture::class])->getReferenceRepository();
        $this->author = $referenceRepository->getReference(TestUserFixture::REFERENCE_NAME);

        $this->threadRepository = $this->getContainer()->get(ThreadRepository::class);
    }

    public function testThreadMustBeCreatedAfterHandleCommand(): void
    {
        $command = new CreateThreadCommand(Uuid::v4());
        $command->title = 'title';
        $command->text = 'text';
        $command->authorId = (string) $this->author->getId();

        $this->getCommandBus()->dispatch($command);

        $actualThread = $this->threadRepository->findById($command->id);

        self::assertSame($command->title, $actualThread->getTitle());
        self::assertSame($command->text, $actualThread->getText());
        self::assertSame($this->author, $actualThread->getAuthor());
    }
}
