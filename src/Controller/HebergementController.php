<?php

namespace App\Controller;

use App\Entity\Hebergement;
use App\Form\HebergementType;
use App\Repository\HebergementRepository;
use App\Services\PdfService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/hebergement')]
class  HebergementController extends AbstractController
{
    #[Route('/', name: 'app_hebergement_index_client', methods: ['GET'])]
    public function index(HebergementRepository $hebergementRepository,Request $request, PaginatorInterface $paginator): Response
    {
        $hebergements = $hebergementRepository->findAll();
       $hebergements = $paginator->paginate(
            $hebergements,
            $request->query->getInt('page', 1),
            2);
        return $this->render('Hebergements/hebergements.html.twig', [
             'hebergements' => $hebergements,
        ]);
    }
    #[Route('/pdflist', name: 'listhebergment.pdf')]
    public function generatePdflist(HebergementRepository $hebergementRepository = null, PdfService $pdf) {
        $html = $this->render('Hebergements/hebergements.html.twig', ['HebergementRepository' => $hebergementRepository]);
        $pdf->showPdfFile($html);
    }
    #[Route('/admin', name: 'app_hebergement_index', methods: ['GET'])]
    public function indexAdmin(HebergementRepository $hebergementRepository): Response
    {
        return $this->render('admin_dashboard/hebergement/index.html.twig', [
            'hebergements' => $hebergementRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_hebergement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $hebergement = new Hebergement();
        $form = $this->createForm(HebergementType::class, $hebergement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            $imageFile = md5(uniqid()) . 'Controller' . $image->guessExtension();
            $image->move($this->getParameter('hebergement_images'),$imageFile);
            $hebergement->setImage($imageFile);
            $entityManager->persist($hebergement);
            $entityManager->flush();
            return $this->redirectToRoute('app_hebergement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_dashboard/hebergement/new.html.twig', [
            'hebergement' => $hebergement,
            'form' => $form,
        ]);
    }


    #[Route('/pdf/{id}', name: 'hebergement.pdf')]
    public function generatePdf(Hebergement $hebergement = null, PdfService $pdf) {
        $html = $this->render('admin_dashboard/hebergement/showPdf.html.twig', ['hebergement' => $hebergement]);
        $pdf->showPdfFile($html);
    }


    #[Route('/{id}', name: 'app_hebergement_show', methods: ['GET'])]
    public function show(Hebergement $hebergement): Response
    {
        return $this->render('admin_dashboard/hebergement/show.html.twig', [
            'hebergement' => $hebergement,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_hebergement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Hebergement $hebergement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(HebergementType::class, $hebergement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle file upload
            $image = $form->get('image')->getData();

            if ($image instanceof UploadedFile) {
                $imageFile = md5(uniqid()) . 'Controller' . $image->guessExtension();
                $image->move($this->getParameter('hebergement_images'), $imageFile);
                $hebergement->setImage($imageFile);
            }

            $entityManager->flush();
            return $this->redirectToRoute('app_hebergement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_dashboard/hebergement/edit.html.twig', [
            'hebergement' => $hebergement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_hebergement_delete', methods: ['POST'])]
    public function delete(Request $request, Hebergement $hebergement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $hebergement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($hebergement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_hebergement_index', [], Response::HTTP_SEE_OTHER);
    }
}
