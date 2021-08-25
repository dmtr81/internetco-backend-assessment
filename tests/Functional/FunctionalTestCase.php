<?php

namespace App\Tests\Functional;

use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FunctionalTestCase extends WebTestCase
{
    final protected function getValidator(): ValidatorInterface
    {
        return static::getContainer()->get(ValidatorInterface::class);
    }

    final protected function getDatabaseTool(): AbstractDatabaseTool
    {
        return static::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    final protected function getCommandBus(): MessageBusInterface
    {
        return static::getContainer()->get('command.bus');
    }
}
