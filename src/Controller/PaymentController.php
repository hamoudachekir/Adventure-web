<?php

namespace App\Controller;

use App\Manager\CartManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentController extends AbstractController
{
    #[Route('/checkout', name:"checkout")]
    public function checkout(CartManager $cartManager): Response{
        $cart = $cartManager->getCurrentCart();
        \Stripe\Stripe::setApiKey('sk_test_51OqyUqDMNIJF98gk6q0VddJmIvxvfoWdZaVYt3wvENfHaTI7uEpvXZvE8sUgu53UP02USs1hY3cO8s0nY5aXI5Gy00XEoeKw59');
        
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'usd',
                        'unit_amount' => $cart->getTotal() * 100,
                        'product_data' => [
                            'name' => 'You will be paying',
                        ],
                    ],
                    'quantity' => 1,
                ],
            ],
         'mode' => 'payment',
         'success_url' => $this->generateUrl('success_url', [],UrlGeneratorInterface::ABSOLUTE_URL),
          'cancel_url' => $this->generateUrl('cancel_url', [],UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return $this->redirect($session->url, 303);
    }

    #[Route('/success-url', name: 'success_url')]
    public function successUrl(): Response{
        return $this->render('payment/success.html.twig',[]);
    }

    #[Route('/cancel-url', name: 'cancel_url')]
    public function cancelUrl(): Response{
        return $this->render('payment/cancel.html.twig',[]);
    }
}
