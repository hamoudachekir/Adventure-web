<?php

namespace App\Controller;

use App\Entity\Hebergement;
use App\Entity\ReservationHebergement;
use App\Form\ReservationHebergementType;
use App\Repository\HebergementRepository;
use App\Repository\ReservationHebergementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reservation/hebergement')]
class ReservationHebergementController extends AbstractController
{
    #[Route('/', name: 'app_reservation_hebergement_index', methods: ['GET'])]
    public function index(ReservationHebergementRepository $reservationHebergementRepository): Response
    {
        return $this->render('admin_dashboard/reservation_hebergement/index.html.twig', [

            'reservations' => $reservationHebergementRepository->findAll(),
        ]);
    }

    #[Route('/{id}/new', name: 'app_reservation_hebergement_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,
    HebergementRepository $hebergementRepository,Hebergement $hebergement): Response
    {
        $reservationHebergement = new ReservationHebergement();

        $related_hebergements = $hebergementRepository->findRelatedHebergements($hebergement);
        $form = $this->createForm(ReservationHebergementType::class, $reservationHebergement);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $reservationHebergement->setHebergement($hebergement);
            $reservationHebergement->setEtat("En Attente");
            $entityManager->persist($reservationHebergement);
            $entityManager->flush();
            return $this->redirectToRoute('app_reservation_show', ['id'=>$reservationHebergement->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('Hebergements/reservation.html.twig', [
            'hebergement' => $hebergement,
            'hebergements' => $related_hebergements,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_hebergement_show', methods: ['GET'])]
    public function show(ReservationHebergement $reservationHebergement): Response
    {
        return $this->render('admin_dashboard/reservation_hebergement/show.html.twig', [
            'reservation' => $reservationHebergement,
        ]);
    }
    #[Route('/{id}/conf', name: 'app_reservation_show', methods: ['GET'])]
    public function showClient(ReservationHebergement $reservationHebergement,HebergementRepository $hebergementRepository): Response
    {
        $hebergement= $reservationHebergement->getHebergement();
        $related_hebergements = $hebergementRepository->findRelatedHebergements($hebergement);


        return $this->render('Hebergements/reservSub.html.twig', [
            'reservation' => $reservationHebergement,
            'hebergement'=>$hebergement,
            'hebergements' => $related_hebergements,

        ]);
    }

    #[Route('/{id}/edit', name: 'app_reservation_hebergement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReservationHebergement $reservationHebergement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReservationHebergementType::class, $reservationHebergement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_reservation_hebergement_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_dashboard/reservation_hebergement/edit.html.twig', [
            'reservation' => $reservationHebergement,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_reservation_hebergement_delete', methods: ['POST'])]
    public function delete(Request $request, ReservationHebergement $reservationHebergement, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservationHebergement->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reservationHebergement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_reservation_hebergement_index', [], Response::HTTP_SEE_OTHER);
    }

}
