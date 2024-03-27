<?php

namespace App\DataFixtures;

use App\Entity\Film;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Xylis\FakerCinema\Provider\Movie;

class FilmFixtures extends Fixture
{
    public function load(ObjectManager $manager): void {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new Movie($faker));
        for ($i = 0; $i < 15; $i++) {
            $film = new Film();
            $film->setTitre($faker->movie());
            $film->setDuree($faker->numberBetween(60,180));
            $manager->persist($film);
        }
        $manager->flush();
    }
}
