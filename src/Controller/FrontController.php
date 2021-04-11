<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Repository\CartRepository;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class FrontController extends AbstractController
{
    /**
     * @Route("/", name="app_front")
     */
    public function index(CategoryRepository $categoryRepository, ProductRepository $productRepository, SessionInterface $session, Request $request, CartRepository $cartRepository): Response
    {
        $categories = $categoryRepository->findByParentNotNull("all");

        foreach($categories as $category){
            $products[$category->getId()] = $productRepository->findBy([
                    "category"=> $category->getId(),
                ], [
                    "price"=>"ASC",
                ]);
        }
        $session->set("app_categories", $categories);

        /*if( $session->get("app_current_cart") == null){

            if($request->cookies->get('app_delifood_cart')){
                $cart = $cartRepository->findOneBy(["uuid"=>$request->cookies->get('app_delifood_cart')]);
                $session->set("app_current_cart", $cart);
            } else {

                $cart = new Cart();
                $entityManager->persist($cart);
                $entityManager->flush();

                $cookie = Cookie::create("app_delifood_cart")
                    ->withValue($cart->getUuid())
                    ->withExpires(strtotime('Fri, 20-May-2022 15:00:00 GMT'))
                    ->withSecure(true);
                $session->set("app_current_cart", $cart);
            }

        } else {
            $cart = $cartRepository->find($session->get("app_current_cart")->getId());
            $session->set("app_current_cart", $cart);
        }*/


        return $this->render('front/index.html.twig', [
            'products' => $products,
            'categories' => $categories,
        ]);


    }

    /**
     * @Route("/category/{id}/{name}", name="app_front_listByCategory")
     */
    public function indexCategory(int $id, CategoryRepository $categoryRepository, ProductRepository $productRepository, SessionInterface $session): Response
    {
        $categories = $categoryRepository->findByParentNotNull($id);

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
