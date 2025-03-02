<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Medicament;
use Faker\Factory;
class MedicamentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) { 
            $medicament = new Medicament();
            $medicament->setName($faker->name());
            $medicament->setDosage($faker->randomElement(['100mg', '250mg', '500mg', '1g'])); 
            $medicament->setDuration($faker->numberBetween(3, 14));
            $medicament->setFrequency($faker->randomElement(['Once a day', 'Twice a day', 'Three times a day']));

            $manager->persist($medicament);
        }

        $manager->flush();
    }
}
