<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();
    }
    
    public function getDependencies(): array
    {
        // List the classes that should be loaded before this one
        return [
            // CategoryProductFixtures::class,
            // UserFixtures::class,
            // RoleFixtures::class,
        ];
    }
}
