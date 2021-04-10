<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $category = new Category();
        $category->setName("CHAUSSURES");
        $category->setParent(null);
        $manager->persist($category);

        $category2 = new Category();
        $category2->setName("Homme");
        $category2->setParent($category);
        $manager->persist($category2);

        $category3 = new Category();
        $category3->setName("Femme");
        $category3->setParent($category);
        $manager->persist($category3);

        $category4 = new Category();
        $category4->setName("Enfant");
        $category4->setParent($category);
        $manager->persist($category4);

        $manager->flush();
    }
}
