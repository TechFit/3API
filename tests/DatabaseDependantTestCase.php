<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Input\StringInput;

/**
 * Class DatabaseDependantTestCase
 * @package App\Tests
 */
class DatabaseDependantTestCase extends KernelTestCase
{
    /** @var EntityManagerInterface */
    protected $entityManager;
    protected static $client;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()->get('doctrine')->getManager();
        self::$client = $kernel->getContainer()->get('test.client');
        $app = new Application($kernel);
        $app->setAutoExit(false);
        $app->run(new StringInput('doctrine:schema:drop -e test --force --quiet'));
        $app->run(new StringInput('doctrine:database:create -e test --quiet'));
        $app->run(new StringInput('doctrine:schema:update -e test --force --quiet'));
    }


    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }
}