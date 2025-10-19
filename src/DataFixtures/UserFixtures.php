<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture {
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $users = [
            ['username' => 'admin', 'email' => 'adminCharleHLeBg@example.com', 'roles' => ['ROLE_ADMIN'], 'password' => 'password123', 'subscription_to_newsletter' => true],
            ['username' => 'arthur', 'email' => 'arthur@example.com', 'roles' => ['ROLE_USER'], 'password' => 'password123', 'subscription_to_newsletter' => false],
            ['username' => 'john', 'email' => 'john.doe@example.com', 'roles' => ['ROLE_USER'], 'password' => 'password123', 'subscription_to_newsletter' => true],
            ['username' => 'jane', 'email' => 'jane.doe@example.com', 'roles' => ['ROLE_USER'], 'password' => 'password123', 'subscription_to_newsletter' => false],
            ['username' => 'mike', 'email' => 'mike.smith@example.com', 'roles' => ['ROLE_USER'], 'password' => 'password123', 'subscription_to_newsletter' => true],
            ['username' => 'susan', 'email' => 'susan.jones@example.com', 'roles' => ['ROLE_USER'], 'password' => 'password123', 'subscription_to_newsletter' => false],
            ['username' => 'david', 'email' => 'david.brown@example.com', 'roles' => ['ROLE_USER'], 'password' => 'password123', 'subscription_to_newsletter' => true],
            ['username' => 'emma', 'email' => 'emma.wilson@example.com', 'roles' => ['ROLE_USER'], 'password' => 'password123', 'subscription_to_newsletter' => false],
            ['username' => 'oliver', 'email' => 'oliver.taylor@example.com', 'roles' => ['ROLE_USER'], 'password' => 'password123', 'subscription_to_newsletter' => true],
            ['username' => 'ava', 'email' => 'ava.moore@example.com', 'roles' => ['ROLE_USER'], 'password' => 'password123', 'subscription_to_newsletter' => false],
            ['username' => 'liam', 'email' => 'liam.anderson@example.com', 'roles' => ['ROLE_USER'], 'password' => 'password123', 'subscription_to_newsletter' => true],
            ['username' => 'sophia', 'email' => 'sophia.thomas@example.com', 'roles' => ['ROLE_USER'], 'password' => 'password123', 'subscription_to_newsletter' => false],
        ];

        foreach ($users as $userData) {
            $user = new User();
            $user->setEmail($userData['email']);
            $user->setPassword($this->hasher->hashPassword($user, $userData['password']));
            $user->setRoles($userData['roles']);
            $user->setSubscriptionToNewsletter($userData['subscription_to_newsletter']);
            $manager->persist($user);
        }

        $manager->flush();
    }
}