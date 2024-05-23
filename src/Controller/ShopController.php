<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ProductCat;
use App\Form\AddToCartType;
use App\Manager\CartManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use App\Repository\ProductCatRepository;

class ShopController extends AbstractController
{
    #[Route('/shop', name: 'app_shop_index', methods: ['GET'])]
    public function index(ProductRepository $productRepository, ProductCatRepository $productCatRepository): Response
    {
        return $this->render('shop/shop.html.twig', [
            'products' => $productRepository->findAll(),
            'productCats' => $productCatRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'product_details', methods: ['GET', 'POST'])]
    public function details(Product $product, ProductRepository $productRepository, Request $request, CartManager $cartManager): Response
    {
        $form = $this->createForm(AddToCartType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $item = $form->getData();
            $item->setProduct($product);
            $cart = $cartManager->getCurrentCart();
            $cart -> addItem($item)
                  -> setUpdateAt(new \Datetime);

            $cartManager->save($cart);

            return $this->redirectToRoute('product_details',['id' => $product->getId()]);
        }

        return $this->render('shop/product_details.html.twig', [
            'product' => $product,
            'related' => $productRepository->findProductByCategory($product->getCategory()->getId()),
            'form' => $form->createView()
        ]);
    }

    #[Route('/cat/{id}', name: 'CategorySort', methods: ['GET'])]
    public function CategorySort(ProductRepository $productRepository, ProductCat $category, ProductCatRepository $productCatRepository): Response
    {
        return $this->render('shop/shop.html.twig', [
            'products' => $productRepository->findProductByCategory($category->getId()),
            'productCats' => $productCatRepository->findAll(),
        ]);
    }
}
