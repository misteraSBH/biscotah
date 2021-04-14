<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\ColorProduct;
use App\Entity\Product;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('price')
            ->add('description')
            ->add('category', EntityType::class,[
                'class' => Category::class,
                'required' => false,
            ])
            ->add('photo', FileType::class, [
                "mapped"=>false,
            ])
            ->add('colors', CollectionType::class,[
                    'entry_type' => ColorProductType::class,

                    'allow_add' => true,
                    'prototype' => true,
                ]
            )

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
