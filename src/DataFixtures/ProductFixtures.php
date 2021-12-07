<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Product;

class ProductFixtures extends Fixture
{

    private $defaults = [
        'Chocolate' => 1.99,
        'Chips' => 2.99,
        'Beer' => 3.99,
        'Pineapple' => 4.99,
        'Car' => 5675.99
    ];

    public function load(ObjectManager $manager): void
    {
        foreach($this->defaults as $title => $price) {
            $product = new Product();
            $product->setTitle($title);
            $product->setPrice($price);
            $product->setCurrency('USD');
            $manager->persist($product);
        }

        $manager->flush();
    }
}
