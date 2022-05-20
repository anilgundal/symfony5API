<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CustomerFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        for ($i = 0; $i < 10; $i++) {
            $customer = new Customer();
            $customer->setFirstName($faker->firstName);
            $customer->setLastName($faker->lastName);
            $customer->setEmail($faker->email);
            $customer->setPhone($faker->phoneNumber);
            $customer->setCompany($this->getReference(CompanyFixtures::COMPANY_REFERENCE . '-' . rand(0,2)));
            $manager->persist($customer);
            $manager->flush();
        }
    }
}
