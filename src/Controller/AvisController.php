<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Repository\AvisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;

#[Route('/avis')]
class AvisController extends AbstractController
{
    #[Route('/admin/', name: 'app_avis_index', methods: ['GET'])]
    public function index(AvisRepository $avisRepository): Response
    {
        // Retrieve statistics data
        $totalAvis = $avisRepository->count([]);
        $avisParNote = $avisRepository->countAvisParNote();
        $derniersAjoutes = $avisRepository->findDerniersAjoutes(5); // Les 5 derniers véhicules ajoutés
        // You can add more statistics queries here

        return $this->render('admin_dashboard/avis_admin.html.twig', [
            'avis' => $avisRepository->findAll(),
            'totalAvis' => $totalAvis,
            'avisParNote' => $avisParNote,
            'derniersAjoutes' => $derniersAjoutes,
            // Pass additional statistics data to the template as needed
        ]);
    }

    #[Route('/new', name: 'app_avis_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $avi = new Avis();
        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($avi);
            $entityManager->flush();

            return $this->redirectToRoute('app_avis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('blog/blog_details.html.twig', [
            'avi' => $avi,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_avis_show', methods: ['GET'])]
    public function show(Avis $avi): Response
    {
        return $this->render('admin_dashboard/avis_show.html.twig', [
            'avi' => $avi,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_avis_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Avis $avi, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AvisType::class, $avi);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_avis_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('avis/edit.html.twig', [
            'avi' => $avi,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{id}', name: 'app_avis_delete')]
    public function delete(Request $request, Avis $avi, EntityManagerInterface $entityManager): RedirectResponse
    {
        $entityManager->remove($avi);
        $entityManager->flush();
        $referrer = $request->headers->get('referer');
        return new RedirectResponse($referrer);
    }
    #[Route('/avis/like/{id}', name: 'app_avis_like')]
    public function likeAvis(Request $request, Avis $avis,EntityManagerInterface $entityManager): Response
    {
        $avis->incrementLikes();
        $entityManager->flush();

        $referrer = $request->headers->get('referer');
        return new RedirectResponse($referrer);    }
    #[Route('/avis/download-pdf', name: 'avis_download_pdf')]
    public function downloadAvisPdf(Pdf $snappy, AvisRepository $repository): Response
    {
        $avis = $repository->findAll();

        $html = $this->renderView('admin_dashboard/pdf.html.twig', [
            'avis' => $avis,
        ]);

        $pdfContent = $snappy->getOutputFromHtml($html);

        return new Response(
            $pdfContent,
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="avis.pdf"'
            ]
        );
    }
}
