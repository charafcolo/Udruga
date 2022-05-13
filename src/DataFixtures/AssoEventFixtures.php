<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Association;
use App\Entity\User;
use App\Repository\AssociationRepository;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;

class AssoEventFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        // Association create :
        $association = new Association();
        $association->setName("Association des paralysés de France");
        $association->setDescription("Une association qui essaye de faire bouger les choses en France. Mais c’est pas toujours facile.");
        $association->setSiren(829320007);
        $association->setEmail("jeanjambedebois@gmail.com");
        $association->setRegistrationCode(123456);
        
        $manager->persist($association);
       
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
        
        
        $event->setAssociation($association);

        // $product = new Product();
        $manager->persist($event);

        $manager->flush();
    }
}
