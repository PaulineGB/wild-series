<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProgramFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        for ($i=0; $i<=15; $i++) {
            $program = new Program();
            $program->setTitle($faker->word());
            $program->setSummary($faker->paragraph());
            $program->setPoster($faker->imageUrl(480, 640, 'cats', true));
            $program->setCountry($faker->word());
            $program->setYear($faker->numberBetween($min = 1995, $max = 2020));
            $program->setCategory($this->getReference('category_' . $faker->numberBetween(0, 4)));
            for ($j=0; $j < count(ActorFixtures::ACTORS); $j++) {
                $program->addActor($this->getReference('actor_' . $j));
            }
            $manager->persist($program);
            $this->addReference('program_' . $i, $program);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
          ActorFixtures::class,
          CategoryFixtures::class,
        ];
    }
}
