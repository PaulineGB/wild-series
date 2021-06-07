<?php

namespace App\DataFixtures;

use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Faker\Factory;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i=0; $i<=100; $i++) {
            $episode = new Episode();
            $episode->setSeason($this->getReference('season_' . $faker->numberBetween(0, 45)));
            $episode->setTitle($faker->word());
            $episode->setNumber($faker->numberBetween(1, 5));
            $episode->setSynopsis($faker->paragraph());
            $manager->persist($episode);
            $this->addReference('episode_' . $i, $episode);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          SeasonFixtures::class,
        ];
    }
}
