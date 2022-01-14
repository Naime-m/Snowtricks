<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TrickFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; ++$i) {
            $trick = new Trick();
            $trick->setName("Trick n°$i")
            ->setDescription("Content for trick n°$i")
            ->setDate(new \DateTime());

            $manager->persist($trick);
        }
        $manager->flush();
    }
}
