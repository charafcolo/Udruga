<?php

namespace App\DataFixtures;

use App\Entity\Association;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AssociationFixtures extends Fixture 
{

    public function load(ObjectManager $manager)
    {
     
        $association = new Association();
        $association->setName("Association des paralysés de France");
        $association->setDescription("Une association qui essaye de faire bouger les choses en France. Mais c’est pas toujours facile.");
        $association->setSiren(829320007);
        $association->setEmail("jeanjambedebois@gmail.com");
        $association->setRegistrationCode(123456);
        
        $manager->persist($association);
        $manager->flush();

    }



}