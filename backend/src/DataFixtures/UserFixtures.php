<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use App\Entity\Role;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // Create Super Admin
        $superAdmin = new User();
        $superAdmin->setUsername('Ernest');
        $superAdmin->setPassword($this->passwordHasher->hashPassword($superAdmin, 'password'));
        $superAdmin->addRole($this->getReference(RoleFixtures::ROLE_SUPER_ADMIN, Role::class));
        $manager->persist($superAdmin);

        // Create Admin
        $admin = new User();
        $admin->setUsername('Tom');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'password'));
        $admin->addRole($this->getReference(RoleFixtures::ROLE_ADMIN, Role::class));
        $manager->persist($admin);

        // Create Regular User 1
        $user1 = new User();
        $user1->setUsername('Harry');
        $user1->setPassword($this->passwordHasher->hashPassword($user1, 'password'));
        $user1->addRole($this->getReference(RoleFixtures::ROLE_REGULAR_USER, Role::class));
        $manager->persist($user1);

        // Create Regular User 2
        $user2 = new User();
        $user2->setUsername('Sally');
        $user2->setPassword($this->passwordHasher->hashPassword($user2, 'password'));
        $user2->addRole($this->getReference(RoleFixtures::ROLE_REGULAR_USER, Role::class));
        $manager->persist($user2);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            RoleFixtures::class,
            PermissionFixtures::class,
        ];
    }
}