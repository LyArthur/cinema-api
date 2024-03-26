<?php

namespace App\DataFixtures;

use App\Entity\Film;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class FilmFixtures extends Fixture
{
    public function load(ObjectManager $manager): void {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 15; $i++) {
            $film = new Film();
            $film->setTitre($faker->sentence(3));
            $film->setDuree(new \DateInterval("PT{$faker->numberBetween(1,2)}H{$faker->numberBetween(0,59)}M"));
            $manager->persist($film);
        }
        $manager->flush();
    }
}
