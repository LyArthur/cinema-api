<?php

namespace App\DataFixtures;

use App\Entity\Film;
use App\Entity\Salle;
use App\Entity\Seance;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SeanceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void {
        $faker = Factory::create('fr_FR');
        $films = $manager->getRepository(Film::class)->findAll();
        $salles = $manager->getRepository(Salle::class)->findAll();
        for ($i = 0; $i < 20; $i++) {
            $seance = new Seance();
            $seance->setDateProjection($faker->dateTimeBetween("-3 days", "+1 week"));
            $seance->setTarifNormal($faker->numberBetween(10, 15));
            $seance->setTarifReduit($faker->numberBetween(5, 7));
            $seance->setSalle($salles[$faker->numberBetween(0, 4)]);
            $seance->setFilm($films[$faker->numberBetween(0, 14)]);

            $manager->persist($seance);
        }
        $manager->flush();
    }

    public function getDependencies() {
        return [
            SalleFixtures::class,
            FilmFixtures::class,
        ];
    }
}
