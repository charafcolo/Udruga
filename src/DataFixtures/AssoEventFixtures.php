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
        $association1 = new Association();
        $association1->setName("Les amis des animaux");
        $association1->setDescription("Une association de personnes qui aiment et qui aident les animaux.");
        $association1->setSiren(829320007);
        $association1->setEmail("animaux@gmail.com");
        $association1->setRegistrationCode(123456);

        $manager->persist($association1);

        // Association number 2
        $association2 = new Association();
        $association2->setName("Teen Football Club de Bordeaux");
        $association2->setDescription("L'association sportif de football pour les jeunes de la ville de Bordeaux");
        $association2->setSiren(123456789);
        $association2->setEmail("footballclubbordeaux@gmail.com");
        $association2->setRegistrationCode(123457);

        $manager->persist($association2);
       

        // Association number 3
        $association3 = new Association();
        $association3->setName("Aide aux sans abris");
        $association3->setDescription("L'association qui aide les démunis de la ville de Romans");
        $association3->setSiren(123456759);
        $association3->setEmail("demunisromans@gmail.com");
        $association3->setRegistrationCode(123459);
        
        $manager->persist($association3);
       
        // Fixtures for event1

       
            $event1 = new Event();

            $event1->setTitle("Assemblée générale");
            $event1->setType("Réunion");
            $event1->setDescription("Notre assembléé générale anuelle, échange avec les membres de l'associations, problèmatique, budget, etc.");
            
            $date1 = new DateTime();
            $date1 = $date1->setDate((2022),(07),(06));
    
            $event1->setDate($date1);
    
            $event1->setMaxMember(15);
            $event1->setPrice(20,00);
            $event1->setStatus("En cours");
            $event1->setImage("https://loremflickr.com/320/240/assembly");
            
            
            $event1->setAssociation($association1);

             // $product = new Product();
        $manager->persist($event1);
       

             // Event number 2 for association1

        $event2 = new Event();

        $event2->setTitle("Visite dans un centre animalier");
        $event2->setType("Activité");
        $event2->setDescription("Visite du centre animalier dans la ville de Tours, avec la participation des employés du parc, activités, soins, repas, etc.");
        
        $date2 = new DateTime();
        $date2 = $date2->setDate((2023),(04),(10));
        // dd($date2);
        $event2->setDate($date2);

        $event2->setMaxMember(30);
        $event2->setPrice(25,00);
        $event2->setStatus("En cours");
        $event2->setImage("https://loremflickr.com/320/240/pets");
        
        
        $event2->setAssociation($association1);

         // $product = new Product();
         $manager->persist($event2);
         
        // Event number 3 for association1
        
        $event3 = new Event();

        $event3->setTitle("Formation dresseur canin");
        $event3->setType("Formation");
        $event3->setDescription("Cette formation est dédidée aux personnes qui souhaitent drésser leurs chiens.");
        
        $date3 = new DateTime();
        $date3 = $date3->setDate((2022),(8),(2));

        $event3->setDate($date3);

        $event3->setMaxMember(10);
        $event3->setPrice(650,00);
        $event3->setStatus("En cours");
        $event3->setImage("https://loremflickr.com/320/240/dog_trainer");
        
        
        $event3->setAssociation($association1);

        // $product = new Product();
        $manager->persist($event3);

        $manager->flush();
    }
}
