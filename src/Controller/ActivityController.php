<?php

namespace App\Controller;

use Endroid\QrCode\Writer\FileWriter;
use App\Entity\ActivityImages;
use App\Entity\Comment;
use App\Entity\Reservation;
use App\Form\ActivityType;
use App\Form\CommentType;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Activity;
use App\Entity\FavActivities;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ActivityRepository;
use App\Service\PictureService;
use App\Services\MailerService;
use Doctrine\ORM\EntityManager;
use Psr\Log\LoggerInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;


class ActivityController extends AbstractController
{
    #[Route('/activities', name: 'activities')]
    public function showAll(EntityManagerInterface $entityManager): Response
    {
        $activities = $entityManager->getRepository(Activity::class)->findAll();

        return $this->render('activities/activities.html.twig', [
            'activities' => $activities,
        ]);
    }


    #[Route("/activity/{id}", name: "activityController_details", methods: ['GET', 'POST'])]
    public function details($id, Request $request, EntityManagerInterface $entityManager, MailerService $mailerService): Response
    {
        // Fetch the activity from the database using the ID
        $activity = $entityManager->getRepository(Activity::class)->find($id);
        $isActivityInWishlist = $entityManager->getRepository(FavActivities::class)->findOneBy([
            'activity' => $activity,
        ]);
        // should be find by type
        $activities = $entityManager->getRepository(Activity::class)->findAll();
        // Booking form
        $reservation = new Reservation();
        $formR = $this->createForm(ReservationType::class, $reservation);
        $formR->handleRequest($request);
        $reservation->setActivity($activity);

        // Comments form
        $comment = new Comment();
        $formC = $this->createForm(CommentType::class, $comment);
        $formC->handleRequest($request);
        $comment->setCreatedAtValue();
        $comment->setActivity($activity);

        // Check which form is submitted and handle its validation
        if ($formR->isSubmitted() && $formR->isValid()) {
            $formData = $formR->getData();
            // Extract the email address from the form data          
            $entityManager->persist($reservation);
            $entityManager->flush();
            $email = $formData->getUserEmail();
            $mailerService->send('Booking confirmation', 'advanture@gmail.com', $email, 'email/emailReservationConfirmation.html.twig',null,$reservation->getId());
            $reservation = new Reservation();
            $formR = $this->createForm(ReservationType::class, $reservation); 
            //$this->addFlash('success', 'Votre reservation est effectuée!');
            //return $this->redirectToRoute('activities');
            return $this->render('email/emailReservationConfirmation.html.twig', [
                'id' => $reservation->getId(),
            ]);
        }

        if ($formC->isSubmitted() && $formC->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();
            $comment = new Comment();
            $formC = $this->createForm(CommentType::class, $comment);
            $this->addFlash('success', 'commentaire ajouté!');
            //return $this->redirectToRoute('activities');
        }

        // Render the activity_details.html.twig template, passing the activity data
        return $this->render('activities/activity_details.html.twig', [
            'activity' => $activity,
            'formR' => $formR->createView(),
            'formC' => $formC->createView(),
            'activities' => $activities,
            'isActivityInWishlist' => $isActivityInWishlist
        ]);
    }


    
    #[Route("/activities/filter", name:"filter_activities")] 
    public function filterActivities(Request $request, ActivityRepository $activityRepository): Response
    {
        $type = $request->query->get('type');
        $minPrice = $request->query->get('min_price');
        $maxPrice = $request->query->get('max_price');
        $maxGuests = $request->query->get('max_guests');
        if ($type === 'Type') {
            $type = null;
        }

        $activities = $activityRepository->findByExampleField($type, $minPrice, $maxPrice, $maxGuests);

        return $this->render('activities/activities.html.twig', [
            'activities' => $activities,
        ]);
    }

    #[Route('/admin/activities', name: 'admin_activities')]
    public function showAllAdmin(EntityManagerInterface $entityManager, Request $request): Response
    {
        // Create a new instance of Activity entity
        $activity = new Activity();
        // Create a form for the Activity entity
        $form = $this->createForm(ActivityType::class, $activity);

        // Handle the form submission
        $form->handleRequest($request);

        // If the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            $images = $form->get('images')->getData();
            $this->uploadImages($activity, $form->get('images')->getData());

            // Persist the new activity to the database
            $entityManager->persist($activity);
            $entityManager->flush();
            $this->addFlash('success', 'activity added successfully!');
            $activity = new Activity();
            $form = $this->createForm(ActivityType::class, $activity);
        }

        $activities = $entityManager->getRepository(Activity::class)->findAll();
        $comments = $entityManager->getRepository(Comment::class)->findAll();
        $reservations = $entityManager->getRepository(Reservation::class)->findAll();

        return $this->render('activity_admin/activity_admin.html.twig', [
            'activities' => $activities,
            'form' => $form->createView(),
            'comments' => $comments,
            'reservations' => $reservations,
        ]);
    }

    #[Route('/admin/activity/{id}/edit', name: 'activity_edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, Activity $activity): Response
    {
        // Create a form for the Activity entity, pre-filled with the existing activity data
        $form = $this->createForm(ActivityType::class, $activity);
        // Handle the form submission
        $form->handleRequest($request);
        // If the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the updated activity to the database
            $entityManager->flush();
            $this->addFlash('success', 'activity modified successfully!');
            // Redirect to the activities list page or to the activity details page
            return $this->redirectToRoute('admin_activities');
        }
        // Render the form template
        return $this->render('activity_admin/edit.html.twig', [
            'form' => $form->createView(),
            'activity' => $activity
        ]);
    }




    #[Route('/admin/activity/{id}/delete', name: 'activity_delete')]
    public function delete(EntityManagerInterface $entityManager, Activity $activity): Response
    {
        $imageDirectory = $this->getParameter('kernel.project_dir') . '/public/assets/uploads/activities/';
        $activity->removeImages($imageDirectory);
        $entityManager->remove($activity);
        $entityManager->flush();
        $this->addFlash('success', 'activity deleted!');
        // Redirect to the activities list page or to a specific route
        return $this->redirectToRoute('admin_activities');
    }



    #[Route('/admin/activity/{id}', name: 'activity_show')]
    public function show(Activity $activity, EntityManagerInterface $entityManager, Request $request): Response
    {
        $activeTab = 1;
        $form = $this->createForm(ActivityType::class, $activity);
        $comments = $entityManager->getRepository(Comment::class)->findBy(['activity' => $activity]);
        $reservation = $entityManager->getRepository(Reservation::class)->findBy(['activity' => $activity]);

        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $entityManager->flush();
                $this->addFlash('success', 'activity modified successfully!');
                return $this->redirectToRoute('admin_activities');
            } else {
                $activeTab = 2;
            }
        }

        return $this->render('activity_admin/show_activity.html.twig', [
            'activity' => $activity,
            'form' => $form->createView(),
            'comments' => $comments,
            'reservations' => $reservation,
            'activeTab' => $activeTab,
        ]);
    }



    private function uploadImages(Activity $activity, array $images): void
    {
        $destination = $this->getParameter('kernel.project_dir') . '/public/assets/uploads/activities';

        foreach ($images as $image) {
            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $slugger = new AsciiSlugger();
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $image->guessExtension();

            $image->move($destination, $newFilename);

            $activityImage = new ActivityImages();
            $activityImage->setUrl($newFilename);
            $activity->addActivityImage($activityImage);
        }
    }

    
}
