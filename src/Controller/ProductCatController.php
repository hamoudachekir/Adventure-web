<?php

namespace App\Controller;

use App\Entity\ProductCat;
use App\Form\ProductCatType;
use App\Repository\ProductCatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/product')]
class ProductCatController extends AbstractController
{
    #[Route('/', name: 'app_product_cat_index', methods: ['GET'])]
    public function index(ProductCatRepository $productCatRepository): Response
    {
        return $this->render('product_cat/index.html.twig', [
            'product_cats' => $productCatRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_product_cat_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,ProductCatRepository $productCatRepository): Response
    {
        $productCat = new ProductCat();
        $formCat = $this->createForm(ProductCatType::class, $productCat);
        $formCat->handleRequest($request);

        if ($formCat->isSubmitted() && $formCat->isValid()) {
            $entityManager->persist($productCat);
            $entityManager->flush();
        }


        return $this->renderForm('admin_dashboard/admin_cat.html.twig', [
            'product_cats' => $productCatRepository -> findAll(),
            'formCat' => $formCat,
        ]);
    }

    #[Route('/admin/cat', name: 'app_admin_cat_index', methods: ['GET', 'POST'])]
    public function indexCat(Request $request, EntityManagerInterface $entityManager, ProductCatRepository $productCatRepository): Response
    {
        $productCat = new ProductCat();
        $formCat = $this->createForm(ProductCatType::class, $productCat);
        $formCat->handleRequest($request);

        if ($formCat->isSubmitted() && $formCat->isValid()) {
            $entityManager->persist($productCat);
            $entityManager->flush();
        }

        return $this->renderForm('admin_dashboard/admin_cat.html.twig', [
            'product_cats' => $productCatRepository -> findAll(),
            'formCat' => $formCat,
        ]);
    }

    #[Route('/{id}', name: 'app_product_cat_show', methods: ['GET'])]
    public function show(ProductCat $productCat): Response
    {
        return $this->render('product_cat/show.html.twig', [
            'product_cat' => $productCat,
        ]);
    }
    
    #[Route('/{id}', name: 'app_product_cat_show', methods: ['GET'])]
    public function showAdmin(ProductCat $productCat): Response
    {
        return $this->render('admin_dashboard/show_Cat.html.twig', [
            'product_cat' => $productCat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_product_cat_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProductCat $productCat, EntityManagerInterface $entityManager): Response
    {
        $formCat = $this->createForm(ProductCatType::class, $productCat);
        $formCat->handleRequest($request);

        if ($formCat->isSubmitted() && $formCat->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_product_cat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product_cat/edit.html.twig', [
            'product_cat' => $productCat,
            'formCat' => $formCat,
        ]);
    }

    #[Route('/admin/cat/{id}/edit', name: 'app_product_cat_edit', methods: ['GET', 'POST'])]
    public function editAdmin(Request $request, ProductCat $productCat, EntityManagerInterface $entityManager): Response
    {
        $formCat = $this->createForm(ProductCatType::class, $productCat);
        $formCat->handleRequest($request);

        if ($formCat->isSubmitted() && $formCat->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_product_cat_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('product_cat/edit.html.twig', [
            'product_cat' => $productCat,
            'formCat' => $formCat,
        ]);
    }

    #[Route('/cat/{id}', name: 'app_product_cat_delete', methods: ['POST'])]
    public function delete(Request $request, ProductCat $productCat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$productCat->getId(), $request->request->get('_token'))) {
            $entityManager->remove($productCat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_product_cat_index', [], Response::HTTP_SEE_OTHER);
    }
}
