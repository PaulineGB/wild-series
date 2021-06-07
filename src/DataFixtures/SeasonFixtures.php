<?php

namespace App\DataFixtures;

use App\Entity\Season;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SeasonFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i=0; $i<=45; $i++) {
            $season = new Season();
            $season->setProgram($this->getReference('program_' . $faker->numberBetween(0, 15)));
            $season->setNumber($faker->numberBetween(1, 3));
            $season->setYear($faker->numberBetween(1995, 2020));
            $season->setDescription($faker->paragraph());
            $manager->persist($season);
            $this->addReference('season_' . $i, $season);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          ProgramFixtures::class,
        ];
    }
}
