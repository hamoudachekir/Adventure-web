<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Activity;
use App\Services\MailerService;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Label\Label;
use Endroid\QrCode\Logo\Logo;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Label\Font\NotoSans;

#[Route('/reservation')]
class ReservationController extends AbstractController
{



    #[Route('/admin/activities/reservations', name: 'admin_activities_reservations')]
    public function showAllAdmin(EntityManagerInterface $entityManager): Response
    {
        $pending = $entityManager->getRepository(Reservation::class)->findBy(['status' => 'Pending']);
        $cancelled = $entityManager->getRepository(Reservation::class)->findBy(['status' => 'canceled']);
        $confirmed = $entityManager->getRepository(Reservation::class)->findBy(['status' => 'Confirmed']);
        $reservations = $entityManager->getRepository(Reservation::class)->findAll();
        return $this->render('activity_admin/reservations_activity_admin.html.twig', [
            'reservations' => $reservations,
            'pending' => $pending,
            'cancelled' => $cancelled,
            'confirmed' => $confirmed,
        ]);
    }

    #[Route('/admin/reservation/{id}/show', name: 'app_reservation_show',)]
    public function show(Reservation $reservation): Response
    {
        return $this->render('activity_admin/show_reservation.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/admin/reservation/{id}/edit', name: 'app_reservation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, Reservation $reservation): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        // Handle the form submission
        $form->handleRequest($request);
        // If the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();
            $this->addFlash('success', 'reservation modified successfully!');
            return $this->redirectToRoute('admin_activities_reservations');
        }
        // Render the form template
        return $this->render('activity_admin/edit_reservation.html.twig', [
            'form' => $form->createView(),
            'reservation' => $reservation
        ]);
    }
    #[Route('/admin/reservations_in_activity/{activity}/show', name: 'reservations_in_activity_show')]
    public function showInActivity(EntityManagerInterface $entityManager, $activity): Response
    {
        $reservations = $entityManager->getRepository(Reservation::class)->findBy(['activity' => $activity]);

        return $this->render('activity_admin/reservationsInActivity.html.twig', [
            'reservations' => $reservations,
        ]);
    }


    #[Route('/{id}', name: 'app_reservation_delete')]
    public function delete(Reservation $reservation, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($reservation);
        $entityManager->flush();
        $this->addFlash('success', 'Reservation deleted successfully!');
        return $this->redirectToRoute('admin_activities_reservations');
    }

    #[Route('/cancel/{id}/{status}', name: 'app_reservation_update_status')]
    public function updateStatus(Reservation $reservation, string $status, EntityManagerInterface $entityManager): Response
    {
        $reservation->setStatus($status);
        $entityManager->flush();

        $this->addFlash('success', 'Reservation status updated successfully!');

        return $this->redirectToRoute('admin_activities_reservations');
    }

    #[Route('/confirm/{id}/', name: 'app_confirm_reservation')]
    public function ConfirmReservation(Request $request,Reservation $reservation, EntityManagerInterface $entityManager, MailerService $mailerService): Response
    {
        // Check if the form is submitted
        if ($request->isMethod('POST')) {
            // Set the status to "Confirmed" and save to database
            $reservation->setStatus('Confirmed');
            $entityManager->flush();
            $qrCode=$this->generateQrCode($reservation->getId());
            $mailerService->send('Booking confirmation', 'advanture@gmail.com', $reservation->getUserEmail(), 'email/emailReservation.html.twig',$this->imageToBase64($this->getParameter('kernel.project_dir') . '/public/assets/images/qrcode.png'),$reservation->getId());
            return $this->redirectToRoute('activities');
        }

        return $this->render('activities/confirmReservation.html.twig', [
            'id' => $reservation->getId(),
        ]);
    }

    public function generateQrCode($reservationId): String
    {
        $url = 'http://127.0.0.1:8000/pdf/generator/' . $reservationId;
        $writer = new PngWriter();
        $qrCode = QrCode::create($url)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::Low)
            ->setSize(120)
            ->setMargin(0)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));
        $logo = Logo::create('assets\images\email\Advanture.png')
            ->setResizeToWidth(60);
        $label = Label::create('')->setFont(new NotoSans(8));

        
        $qrCode->setForegroundColor(new color(0, 150, 0));
        $qrCodes = $writer->write(
            $qrCode,
            null,
        );
        $qrCodes->saveToFile(__DIR__ . '/../../public/assets/images/qrcode.png');
        return 'assets/images/qrcode.png';
    }

    private function imageToBase64($path)
    {
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }
}
