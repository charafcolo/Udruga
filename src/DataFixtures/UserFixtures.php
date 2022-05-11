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

        $plaintextPassword = "user";

        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);

        $user->setRoles(["ROLE_USER"]);
        $user->setFirstName("Jean");
        $user->setLastName("Némar");
        $manager->persist($user);

        // ------------userAdmin-----------

        $admin = new User();
        $admin->setEmail("admin@admin.com");

        $plaintextPassword = "admin";

        $hashedPassword = $this->passwordHasher->hashPassword(
            $admin,
            $plaintextPassword
        );
        $admin->setPassword($hashedPassword);

        $admin->setRoles(["ROLE_ADMIN"]);
        $admin->setFirstName("Billy");
        $admin->setLastName("Tropfort");
        $manager->persist($admin);

        $manager->flush();

    }
}

