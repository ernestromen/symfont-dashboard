<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Category;
use App\Entity\Product;
use Faker\Factory;
class CategoryProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = Factory::create();

        for ($i = 0; $i < 3; $i++) {
            $category = new Category();
            $category->setName($faker->word);

            $manager->persist($category);

            for ($j = 0; $j < 3; $j++) {
                $product = new Product();
                $product->setName(ucwords($faker->words(2, true)));
                $product->setCategory($category); // Assuming setCategory exists and is the owning side

                $manager->persist($product);
            }
        }
        $manager->flush();
    }
}
