<?php

declare(strict_types=1);

namespace App\Tests\Functional;


use App\Entity\Customer;
use App\Tests\DatabaseDependantTestCase;

/**
 * Class CustomerApiTest
 */
class CustomerApiTest extends DatabaseDependantTestCase
{
    public function testCanGetCustomerById(): void
    {
        $customer = new Customer();
        $customer->setUsername('Test');
        $customer->setFullName('Full Name');
        $customer->setPhone('123123');
        $customer->setGender('Male');
        $customer->setEmail('test@email.com');
        $customer->setCountry('Australia');
        $customer->setCity('Sydney');
        $this->entityManager->persist($customer);
        $this->entityManager->flush();
        $customerId = $customer->getId();

        self::$client->request('GET', "/api/customers/$customerId.json");
        $response = self::$client->getResponse();
        $apiResponse = json_decode($response->getContent(), true);
        self::assertEquals(200, $response->getStatusCode());
        self::assertEquals($apiResponse['fullName'], $customer->getFullName());
    }

    public function testCanGetCustomers(): void
    {
        self::$client->request('GET', '/api/customers.json');
        $response = self::$client->getResponse();
        self::assertEquals(200, $response->getStatusCode());
    }
}
