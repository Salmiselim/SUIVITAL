<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Medicament;


class MedicamentFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $medicament = new Medicament();
        $medicament->setOrdonnance(null);
        $medicament->setName('Paracetamol');
        $medicament->setDosage('500mg');
        $medicament->setDuration('7 days');
        $medicament->setFrequency('Twice a day');

        $manager->persist($medicament);

        $medicament = new Medicament();
        $medicament->setOrdonnance(null);
        $medicament->setName('Ibuprofen');
        $medicament->setDosage('200mg');
        $medicament->setDuration('5 days');
        $medicament->setFrequency('Three times a day');

        $manager->flush();
    }
}
