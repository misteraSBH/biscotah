<?php

namespace App\Controller;

use App\Entity\Cart;

use App\Entity\ColorProduct;
use App\Entity\CartItem;
use App\Repository\CartRepository;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart", name="app_cart")
     */
    public function index(SessionInterface $masession, EntityManagerInterface $entityManager, CartRepository $cartRepository): Response
    {

        #dd($masession);
        if(!$masession->get("app_current_cart")){

            $cart = new Cart();
            $entityManager->persist($cart);
            $entityManager->flush();
            $masession->set("app_current_cart", $cart);
        } else {
            $cart = $cartRepository->find($masession->get("app_current_cart"));
        }

        return $this->render('cart/purchase_choose_payment.html.twig', [
            'cart' => $cart,
        ]);
    }

    /**
     * @Route(path="/cart/{id}/addProduct/{idproduct}", name="app_cart_cartitem_product_add")
     * @Route(path="/front/cart/{id}/addProduct/{idproduct}", name="app_front_cart_cartitem_product_add")
     */
    public function addProduct(int $id, int $idproduct, Cart $cart, SessionInterface $session, ProductRepository $productRepository, EntityManagerInterface $entityManager, \Symfony\Component\HttpFoundation\Request $request)
    {
        #$cart = $session->get("app_current_cart");
        #dd($cart);


        $product = $productRepository->find($idproduct);

        $cartItem = new CartItem();
        $cartItem->setProduct($product);
        $cartItem->setQuantity(1);
        $cartItem->setCart($cart);

        $cart->addCartitem($cartItem);

        $entityManager->persist($cart);
        $entityManager->flush();

        $session->set("app_current_cart", $cart);

        $origine = $request->headers->get('referer');

        return $this->redirect($origine);
    }


    /**
     * @Route(path="/cart/modifyCartItem/{id}/{action}", name="app_cart_cartitem_product_modify")
     */
    public function modifyCartItem(int $id, string $action, SessionInterface $session, EntityManagerInterface $entityManager,CartRepository $cartRepository,Request $request)
    {
        $cartItem = $entityManager->getRepository(CartItem::class)->find($id);
        $cartItem->getCart()->getCartItems()->count();

        $newQty = 0;
        if($action == "add"){
            $newQty = $cartItem->getQuantity() + 1;
        } else {
            if($cartItem->getQuantity() >= 1) {
                $newQty = $cartItem->getQuantity() - 1;
            }
        }

        $cartItem->setQuantity($newQty);
        $entityManager->persist($cartItem);
        $entityManager->flush();

        $session->set("app_current_cart", $cartItem->getCart() );
        $session->save();

        $origine = $request->headers->get('referer');

        return $this->redirect($origine);
        //  return $response;
        // return new RedirectResponse($origine);
        //return $this->redirectToRoute('app_front_listByCategory',['id'=>4]);

    }



     /**
    * @Route(path="/cart/addProduct", name="app_cart_cartitem_shoes_add")
    */
    public function addShoes(SessionInterface $session, CartRepository $cartRepository, ProductRepository $productRepository, EntityManagerInterface $entityManager, \Symfony\Component\HttpFoundation\Request $request )
    {
        if( $session->get("app_current_cart") == null){

            if($request->cookies->get('app_biscotah_cart')){
                $cart = $cartRepository->findOneBy(["uuid"=>$request->cookies->get('app_biscotah_cart')]);
                $session->set("app_current_cart", $cart);
            } else {

                $cart = new Cart();
                $entityManager->persist($cart);
                $entityManager->flush();

                $cookie = Cookie::create("app_biscotah_cart")
                    ->withValue($cart->getUuid())
                    ->withExpires(strtotime('Fri, 20-May-2022 15:00:00 GMT'))
                    ->withSecure(true);
                $session->set("app_current_cart", $cart);
            }

        } else {
            $cart = $cartRepository->find($session->get("app_current_cart")->getId());
            $session->set("app_current_cart", $cart);
        }

        $idproduct = $request->request->get("productId");
        $options   = $request->request->get("option");
        $cartItemOptions = [];

        if($options['color']){
            $color = $this->getDoctrine()->getRepository(ColorProduct::class)->find($options['color']);
            $cartItemOptions[] = $color;
        }


        $product = $productRepository->find($idproduct);

        $cartItem = new CartItem();
        $cartItem->setProduct($product);
        $cartItem->setQuantity(1);
        $cartItem->setCart($cart);

        $cartItem->setOptions($cartItemOptions);

        $cart->addCartitem($cartItem);

        $entityManager->persist($cart);
        $entityManager->flush();

        $session->set("app_current_cart", $cart);

        $origine = $request->headers->get('referer');

        return $this->redirect($origine);
    }

    /**
     * @Route(path="/cart/removeCartItem/{id}", name="app_cart_cartitem_product_delete")
     */
    public function deleteCartItem(int $id, CartItem $cartItem, SessionInterface $session, EntityManagerInterface $entityManager, \Symfony\Component\HttpFoundation\Request $request, CartRepository $cartRepository)
    {
        $cart = $cartItem->getCart();
        $cart->removeCartItem($cartItem->getProduct());
        $entityManager->persist($cart);
        $entityManager->flush();

        $session->set("app_current_cart", $cart );
        $origine = $request->headers->get('referer');

        return $this->redirect($origine);
    }
}
