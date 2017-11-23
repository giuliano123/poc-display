<?php

namespace DisplayBundle\DataFixtures\ORM;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class Place extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; $i++) {
            $place = new \DisplayBundle\Entity\Place();
            $place->setTitle('Title '.$i);
            $place->setImage('Image '.$i);
            $place->setCreatedAt(new \DateTime('now'));
            $manager->persist($place);
        }

        $manager->flush();
    }
}