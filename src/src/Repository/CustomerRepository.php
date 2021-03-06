<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Customer>
 *
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    private $manager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $manager)
    {
        parent::__construct($registry, Customer::class);
        $this->manager = $manager;
    }

    public function addCustomer($firstName, $lastName, $email, $phoneNumber, $company)
    {
        $customer = new Customer();

        $customer
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setEmail($email)
            ->setPhone($phoneNumber)
            ->setCompany($company);

        $this->manager->persist($customer);
        $this->manager->flush();
    }

    public function updateCustomer(Customer $customer): Customer
    {
        $this->manager->persist($customer);
        $this->manager->flush();

        return $customer;
    }

    public function removeCustomer(Customer $customer)
    {
        $this->manager->remove($customer);
        $this->manager->flush();
    }
}
