<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{


    /**
     * Permet de faire de l'injection de dépendance
     * car la méthode load() ne l'autorise pas
     * 
     * @link https://symfony.com/doc/current/security/passwords.html#hashing-the-password
     * 
     * @param UserPasswordHasherInterface $hasher
     */
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->passwordHasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail("user@user.com");
        $user->setPassword("1234");
        $user->setRole(["ROLE_USER"]);
        $user->setFirstName("Bill");
        $user->setLastName("Boquet");
        $manager->persist($user);

        // ------------userAdmin-----------
    
        $newUserAdmin = new User();
        $newUserAdmin->setFirstName("Jean");
        $newUserAdmin->setLastName("Neymar");
        $newUserAdmin->setEmail('admin@admin.com')
            ->setPassword("1234")
            ->setRole(['ROLE_ADMIN']);
        $manager->persist($newUserAdmin);

        $manager->flush();

    }
}

