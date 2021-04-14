<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Shoes;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Faker\Provider\Lorem($faker));

        //$categoryRepository = $this->getDoctrine()->getManager();


        for($i=1;$i<=50;$i++){
            $shoes = new Shoes();
            $shoes->setName("Shoes ".$i);
            $shoes->setDescription($faker->text);
            $shoes->setCategory($manager->getRepository(Category::class)->findAll()[rand(1,3)]);
            $shoes->setPrice(rand(25,75));
            $manager->persist($shoes);
        }
        $manager->flush();




    }
}
