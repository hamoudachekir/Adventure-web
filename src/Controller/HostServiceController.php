<?php

namespace App\Controller;

use App\Entity\HostService;
use App\Form\HostServiceType;
use App\Repository\HostServiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/HostService')]
class HostServiceController extends AbstractController
{
    #[Route('/', name: 'app_HostService_index', methods: ['GET'])]
    public function index(HostServiceRepository $HostServiceRepository): Response
    {
        return $this->render('admin_dashboard/HostService/index.html.twig', [
            'HostServices' => $HostServiceRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_HostService_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $HostService = new HostService();
        $form = $this->createForm(HostServiceType::class, $HostService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($HostService);
            $entityManager->flush();

            return $this->redirectToRoute('app_HostService_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_dashboard/HostService/new.html.twig', [
            'HostService' => $HostService,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_HostService_show', methods: ['GET'])]
    public function show(HostService $HostService): Response
    {
        return $this->render('admin_dashboard/HostService/show.html.twig', [
            'HostService' => $HostService,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_HostService_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, HostService $HostService, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(HostServiceType::class, $HostService);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_HostService_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_dashboard/HostService/edit.html.twig', [
            'HostService' => $HostService,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_HostService_delete', methods: ['POST'])]
    public function delete(Request $request, HostService $HostService, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$HostService->getId(), $request->request->get('_token'))) {
            $entityManager->remove($HostService);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_HostService_index', [], Response::HTTP_SEE_OTHER);
    }
}
