<?php

namespace App\Controller;

use App\Entity\ColorProduct;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Repository\PurchaseRepository;
use App\Service\ImageUploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class AdminController
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
{
    #-----------
    # Product
    #-----------
    /**
     * @Route("/product", name="app_product_list")
     */
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findby([],["category"=>"DESC"]);

        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/product/add", name="app_product_add")
     */
    public function add(Request $request, EntityManagerInterface $entityManager, ImageUploaderHelper $fileUploader): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /**@var $product Product */



            $product = $form->getData();
            $uploadedFile = $form->get('photo')->getData();

            if($uploadedFile) {
                $fileName = $fileUploader->upload($uploadedFile);
                $product->setPhoto($fileName);
            }

            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'The product has been added');
            return $this->redirectToRoute("app_product_list");
        }

        return $this->render('product/add_product.html.twig', [
            'form'=>$form->createView(),
            'product'=>$product,
        ]);
    }

    /**
     * @Route("/product/{id}/edit", name="app_product_edit")
     */
    public function edit(int $id, Product $product, Request $request, EntityManagerInterface $entityManager, ImageUploaderHelper $fileUploader): Response
    {

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /**@var $product Product */
            $product = $form->getData();

            /**@var $color ColorProduct */
            $colors = $form->get('colors')->getData();
            foreach($colors as $color){
                if($color->getName() != null){
                    $color->setProduct($product);
                }
            }

            $uploadedFile = $form->get('photo')->getData();

            if($uploadedFile) {
                $fileName = $fileUploader->upload($uploadedFile);
                $product->setPhoto($fileName);
            }


            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'The product has been edited');
            return $this->redirectToRoute("app_product_list");
        }

        return $this->render('product/edit_product.html.twig', [
            'form'=>$form->createView(),
            'product'=>$product,
        ]);
    }


    /**
     * @Route("/product/{id}/delete", name="app_product_delete")
     */
    public function delete(int $id, Product $product, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($product);
        $entityManager->flush();

        $this->addFlash('success', 'The product has been deleted');

        return $this->redirectToRoute("app_product_list");

    }

    #-----------
    # Orders
    #-----------

    /**
     * @Route("/orders", name="app_orders")
     */
    public function orders_list(PurchaseRepository $purchaseRepository): Response
    {
        $purchases = $purchaseRepository->findAll();

        return $this->render('admin/orders_list.html.twig', [
            'purchases' => $purchases,
        ]);
    }
}
