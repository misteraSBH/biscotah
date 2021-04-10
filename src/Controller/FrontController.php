<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="app_front")
     */
    public function index(CategoryRepository $categoryRepository, ProductRepository $productRepository): Response
    {
        $categories = $categoryRepository->findByParentNotNull();

        foreach($categories as $category){
            $products[$category->getId()] = $productRepository->findBy([
                    "category"=> $category->getId(),
                ], [
                    "price"=>"ASC",
                ]);
        }



        return $this->render('front/index.html.twig', [
            'products' => $products,
            'categories' => $categories,
        ]);


    }
}
