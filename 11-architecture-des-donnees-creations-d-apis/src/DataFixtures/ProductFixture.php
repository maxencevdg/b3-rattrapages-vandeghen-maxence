<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;
use Faker\Factory;

class ProductFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i = 0; $i < 8; $i++) {
            $product = new Product();
            $product->setName($faker->words(2, true))
                ->setImage($faker->imageUrl(400, 400, 'food', true, 'Picard'))
                ->setDescription($faker->sentence(12))
                ->setPrice($faker->randomFloat(2, 2, 20))
                ->setRating($faker->optional()->randomFloat(1, 2, 5))
                ->setIsAvailable($faker->boolean(80));
            $manager->persist($product);
        }
        $manager->flush();
    }
}
