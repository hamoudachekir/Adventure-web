<?php

namespace App\Controller;

use App\Entity\ReservationHebergement;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Exception\ApiErrorException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class StripeController extends AbstractController
{
    #[Route('/stripe', name: 'app_stripe')]
    public function index(): Response
    {
        return $this->render('stripe/index.html.twig', [
            'controller_name' => 'StripeController',
        ]);
    }

    /**
     * @throws ApiErrorException
     */
    #[Route('/create-checkout-session/{id}', name: 'create-checkout-session')]
    public function paiement(EntityManagerInterface $entityManager, $id, Request $request, UrlGeneratorInterface $urlGenerator): Response
    {
        // Récupérer le panier depuis la base de données en utilisant l'EntityManager
        $reservationHebergement = $entityManager->getRepository(ReservationHebergement::class)->find($id);

        // Vérifier si le panier existe
        if (!$reservationHebergement) {
            throw $this->createNotFoundException('Aucun panier trouvé pour cet ID: '.$id);
        }

        // Créer une session de paiement Stripe
        $secretKey = $_ENV['STRIPE_SECRET_KEY_TEST'];
        $stripe = new \Stripe\StripeClient($secretKey);

        $checkout_session = $stripe->checkout->sessions->create([
            'payment_method_types' => ['card'], // Ajoutez d'autres méthodes de paiement si nécessaire
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'product_data' => [
                            'name' => 'Montant de la réservation',
                        ],
                        'unit_amount' => $reservationHebergement->getTotalPrice() * 100, // Convertissez le montant en centimes
                    ],
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => $urlGenerator->generate('showReservation', ['id' => $id], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $urlGenerator->generate('cancelReservation', ['id' => $id], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return new RedirectResponse($checkout_session->url);
    }

    #[Route('/cancel-reservation/{id}', name: 'cancelReservation')]
    public function annuler(EntityManagerInterface $entityManager, $id): Response
    {
        // Récupérer la commande associée au panier
        $reservationHebergement = $entityManager->getRepository(ReservationHebergement::class)->find($id);

        // Vérifier si une commande a été trouvée
        if (!$reservationHebergement) {
            throw $this->createNotFoundException('Aucune commande trouvée pour ce panier');
        }

        $reservationHebergement->setEtat('Annulée');

        // Mettre à jour la commande dans la base de données
        $entityManager->flush();

        return $this->redirectToRoute('app_hebergement_index_client');
    }
    #[Route('/confirm-reservation/{id}', name: 'showReservation')]
    public function Confirmer(EntityManagerInterface $entityManager, $id): Response
    {
        // Récupérer la commande associée au panier
        $reservationHebergement = $entityManager->getRepository(ReservationHebergement::class)->find($id);

        // Vérifier si une commande a été trouvée
        if (!$reservationHebergement) {
            throw $this->createNotFoundException('Aucune commande trouvée pour ce panier');
        }

        $reservationHebergement->setEtat('Confirmée');

        // Mettre à jour la commande dans la base de données
        $entityManager->flush();
        return $this->redirectToRoute('app_hebergement_index_client');
    }

}
