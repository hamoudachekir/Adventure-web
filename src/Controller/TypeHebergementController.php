<?php

namespace App\Controller;

use App\Entity\TypeHebergement;
use App\Form\TypeHebergementType;
use App\Repository\TypeHebergementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/type/hebergement')]
class TypeHebergementController extends AbstractController
{
    #[Route('/', name: 'app_type_hebergement_index', methods: ['GET'])]
    public function index(TypeHebergementRepository $typeHebergementRepository): Response
    {
        return $this->render('admin_dashboard/type_hebergement/index.html.twig', [
            'type_hebergements' => $typeHebergementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_type_hebergement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $typeHebergement = new TypeHebergement();
        $form = $this->createForm(TypeHebergementType::class, $typeHebergement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($typeHebergement);
            $entityManager->flush();

            return $this->redirectToRoute('app_type_hebergement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_dashboard/type_hebergement/new.html.twig', [
            'type_hebergement' => $typeHebergement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_hebergement_show', methods: ['GET'])]
    public function show(TypeHebergement $typeHebergement): Response
    {
        return $this->render('admin_dashboard/type_hebergement/show.html.twig', [
            'type_hebergement' => $typeHebergement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_type_hebergement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TypeHebergement $typeHebergement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypeHebergementType::class, $typeHebergement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_type_hebergement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_dashboard/type_hebergement/edit.html.twig', [
            'type_hebergement' => $typeHebergement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_type_hebergement_delete', methods: ['POST'])]
    public function delete(Request $request, TypeHebergement $typeHebergement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$typeHebergement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($typeHebergement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_type_hebergement_index', [], Response::HTTP_SEE_OTHER);
    }
}
