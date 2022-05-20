<?php

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CompanyFixtures extends Fixture
{
    public const COMPANY_REFERENCE = 'customer-company';

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 5; $i++) {
            $company = new Company();
            $company->setName($faker->company);
            $manager->persist($company);
            $manager->flush();

            $this->addReference(self::COMPANY_REFERENCE . '-' . $i, $company);
        }
    }
}
