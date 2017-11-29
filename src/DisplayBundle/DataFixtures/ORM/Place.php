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

        for ($i = 0; $i < 20; $i++) {
            $event = new \DisplayBundle\Entity\Event();
            $event->setTitle('Title '.$i);
            $event->setSubtitle('SubTitle '.$i);
            $event->setEventDate('Event Date '.$i);
            $event->setPublicationDate(new \DateTime('now'));
            $event->setPublicationEndDate(new \DateTime('+1 day'));
            $event->setPlace($place);
            $event->setPoster('Poster '.$i);
            $event->setPicture('Picture '.$i);
            $event->setCreatedAt(new \DateTime('now'));
            $manager->persist($event);
        }

        $manager->flush();
    }
}