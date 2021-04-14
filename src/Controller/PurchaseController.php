<?php

namespace App\Controller;

use App\Entity\Cart;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseController extends AbstractController
{
    /**
     * @Route("/purchase", name="app_purchase_choose_payment")
     */
    public function index(): Response
    {
        return $this->render('/front/purchase_choose_payment.html.twig');
    }


    /**
     * @Route("/purchase/add", name="app_purchase_add")
     */
    public function add(SessionInterface $session, EntityManagerInterface $entityManager):Response
    {
        /**
         * @var Cart $cart
         */
      //  $cart = $session->get("app_current_cart");
        $cart = $entityManager->getRepository(Cart::class)->find( $session->get("app_current_cart")->getId());
        $purchase = new Purchase();

        $purchase->setPurchaseNumber(date("Ym"));
        $purchase->setTotalAmount( $cart->getTotalAmount());
        $purchase->setUser( $this->getUser() );


        foreach($cart->getCartItems() as $cartItem){
            $purchaseItem = new PurchaseItem();
            $purchaseItem->setProduct( $cartItem->getProduct());
            $purchaseItem->setQuantity( $cartItem->getQuantity());
            $purchaseItem->setOptions( $cartItem->getOptions());
            $purchase->addPurchaseItem($purchaseItem);
        }

        //dd($purchase);
        $entityManager->persist($purchase);
        $entityManager->flush();

        $purchase->setPurchaseNumber(date('Ym#'.$purchase->getId()));
        $entityManager->persist($purchase);

        $entityManager->remove($cart);
        $entityManager->flush();

        $session->clear();

        return $this->redirectToRoute('app_purchase_confirmed');
    }


    /**
     * @Route("/purchase/confirm", name="app_purchase_confirmed")
     */
    public function confirmed():Response
    {

        return new Response('OK');
    }
}
