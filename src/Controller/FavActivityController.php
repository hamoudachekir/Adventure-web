<?php

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\FavActivities;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class FavActivityController extends AbstractController
{
    #[Route('/activities/favourites', name: 'fav_activities')]
    public function showFavActivities(EntityManagerInterface $entityManager): Response
    {
        $favActivities = $entityManager->getRepository(FavActivities::class)->findAll();

        return $this->render('activities/fav_activities.html.twig', [
            'favActivities' => $favActivities,
        ]);
    }

    #[Route('/activities/favourites/add/{id}', name: 'fav_activities_add', methods: ['GET','POST'])]
    public function addFavActivities($id, EntityManagerInterface $entityManager): Response
    {
        $activity = $entityManager->getRepository(Activity::class)->find($id);
        $userEmail = 'mannaiomar28@gmail.com';

        // Check if the activity is already in favorites
        $existingFav = $entityManager->getRepository(FavActivities::class)->findOneBy([
            'activity' => $activity,
            'userEmail' => $userEmail,
        ]);

        // If the activity is not in favorites, add it
        if (!$existingFav) {
            $fav = new FavActivities();
            $fav->setActivity($activity);
            $fav->setUserEmail($userEmail);
            $entityManager->persist($fav);
            $entityManager->flush();
        }else{

            $entityManager->remove($existingFav);
            $entityManager->flush();
        }

        return new Response(null, 204);
    }
}
