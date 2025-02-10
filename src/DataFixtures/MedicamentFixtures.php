<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Medicament;
use App\Entity\Ordonnance;
use App\Entity\Patient;

class MedicamentFixtures extends Fixture
{
    public function load(ObjectManager $manager) : void
    {
        // Create a Patient instance
        $patient = new Patient();
        $patient->setName('John Doe'); // Set other necessary fields
        $patient->setEmail('john.doe@example.com'); // Ensure email is not null

        $manager->persist($patient);

        // Create an Ordonnance instance
        $ordonnance = new Ordonnance();
        $ordonnance->setDatePrescription(new \DateTime());
        $ordonnance->setPatientId($patient);

        $manager->persist($ordonnance);

        // Create Medicament instances
        $medicament1 = new Medicament();
        $medicament1->setName('Paracetamol');
        $medicament1->setDosage('500mg');
        $medicament1->setDuration('7 days');
        $medicament1->setFrequency('Twice a day');
        $medicament1->setOrdonnance($ordonnance);

        $manager->persist($medicament1);

        $medicament2 = new Medicament();
        $medicament2->setName('Ibuprofen');
        $medicament2->setDosage('200mg');
        $medicament2->setDuration('5 days');
        $medicament2->setFrequency('Three times a day');
        $medicament2->setOrdonnance($ordonnance);

        $manager->persist($medicament2);

        // Flush to save all changes
        $manager->flush();
    }
}