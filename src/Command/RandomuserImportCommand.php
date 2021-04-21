<?php

declare(strict_types=1);

namespace App\Command;

use App\Http\CustomerApiClientInterface;
use App\Manager\CustomerManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class RandomuserImportCommand
 * @package App\Command
 */
class RandomuserImportCommand extends Command
{
    protected static $defaultName = 'app:randomuser-import';

    /** @var \App\Manager\CustomerManager */
    private CustomerManager $customerManager;

    /** @var \App\Http\CustomerApiClientInterface */
    private CustomerApiClientInterface $customerApiClient;

    /** @var \Symfony\Component\Serializer\SerializerInterface */
    private SerializerInterface $serializer;

    protected function configure()
    {
        $this->setDescription('Import customers from Randomuser API.');
    }

    /**
     * RandomuserImportCommand constructor.
     *
     * @param \App\Http\CustomerApiClientInterface $customerApiClient
     * @param \App\Manager\CustomerManager $customerManager
     * @param \Symfony\Component\Serializer\SerializerInterface $serializer
     */
    public function __construct(
        CustomerApiClientInterface $customerApiClient,
        CustomerManager $customerManager,
        SerializerInterface $serializer
    )
    {
        $this->customerApiClient = $customerApiClient;
        $this->customerManager = $customerManager;
        $this->serializer = $serializer;

        parent::__construct();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = $this->customerApiClient->fetchCustomers(100, 'au');

        if ($response->getStatusCode() !== Response::HTTP_OK) {

            $output->writeln('Customers not imported.');

            return Command::FAILURE;
        }

        $customers = $this->serializer->deserialize(
            $response->getContent(),
            'App\DTO\RandomuserCustomerDTO[]',
            'json'
        );

        $this->customerManager->store($customers);

        return Command::SUCCESS;
    }
}
