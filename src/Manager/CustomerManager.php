<?php

namespace App\Manager;

use App\Entity\Customer;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class CustomerManager
 */
class CustomerManager
{
    /** @var EntityManagerInterface */
    private EntityManagerInterface $em;

    /**
     * CustomerManager constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Insert or update customers, checked by unique email
     *
     * @param \App\DTO\CustomerDTOInterface[] $customers
     */
    public function store(array $customers): void
    {
        $batchSize = 500;

        for ($i = 0; $i < count($customers); ++$i) {
            $customer = $customers[$i];
            // Prevent race conditions by putting this into a transaction.
            $this->em->transactional(function($em) use ($customer, $i) {
                /** @var EntityManagerInterface $em */
                /** @var Customer $counter */
                $counter = $em->createQueryBuilder()
                    ->select('c')
                    ->from(Customer::class, 'c')
                    ->where('c.email = :email')
                    ->setParameters([
                        'email' => $customer->getEmail(),
                    ])
                    ->getQuery()
                    ->getOneOrNullResult();

                // Update if existing.
                if ($counter) {
                    $counter->setFullName($customer->getFullName());
                    $counter->setCountry($customer->getCountry());
                    $counter->setUsername($customer->getUsername());
                    $counter->setGender($customer->getGender());
                    $counter->setCity($customer->getCity());
                    $counter->setPhone($customer->getPhone());
                    $this->em->persist($counter);
                } else {
                    $user = new Customer();
                    $user->setFullName($customer->getFullName());
                    $user->setEmail($customer->getEmail());
                    $user->setCountry($customer->getCountry());
                    $user->setUsername($customer->getUsername());
                    $user->setGender($customer->getGender());
                    $user->setCity($customer->getCity());
                    $user->setPhone($customer->getPhone());
                    $this->em->persist($user);
                }
            });

            if (($i % $batchSize) === 0) {
                $this->em->flush();
                $this->em->clear();
            }
        }

        $this->em->flush();
        $this->em->clear();
    }
}