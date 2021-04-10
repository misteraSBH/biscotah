<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Faker\Provider\Lorem($faker));

        #$categoryRepository = $this->getDoctrine()->getManager();


        for($i=1;$i<=50;$i++){
            $product = new Product();
            $product->setName("Product ".$i);
            $product->setDescription($faker->text);
            #$product->setCategory($categoryRepository->findOneBy("parent" != 0));
            $product->setPrice(rand(25,75));
            $manager->persist($product);
        }
        $manager->flush();




    }
}
