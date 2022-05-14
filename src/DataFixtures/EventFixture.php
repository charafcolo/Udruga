<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Association;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EventFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        // Fixtures for some random events
        
        $event = new Event();

        $event->setTitle("Assemblée générale");
        $event->setType("Réunion");
        $event->setDescription("Notre assembléé générale anuelle, échange avec les membres de l'associations, problèmatique, budget, etc.");
        
        $date = new DateTime();
        $date = $date->setDate((2022),(07),(06));

        $event->setDate($date);

        $event->setMaxMember(15);
        $event->setPrice(20,00);
        $event->setStatus("En cours");
        $event->setImage("https://loremflickr.com/320/240/assembly");
        
        $event->getAssociation();

        // $product = new Product();
        $manager->persist($event);

        $manager->flush();
    }
}
