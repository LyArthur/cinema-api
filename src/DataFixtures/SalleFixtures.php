<?php

namespace App\DataFixtures;

use App\Entity\Salle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SalleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 5; $i++) {
            $salle = new Salle();
            $salle->setNom($faker->sentence(2));
            $salle->setNbPlaces($faker->numberBetween(20, 60));
            $manager->persist($salle);
        }
        $manager->flush();
    }
}
