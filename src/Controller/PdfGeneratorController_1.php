<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Manager\CartManager;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;

class PdfGeneratorController_1 extends AbstractController
{

    #[Route("/pdf/generator", name: "pdf_generator1")]
    public function index(CartManager $cartManager): Response
    {
        $cart = $cartManager->getCurrentCart();
        $dompdf = new Dompdf();
        
        $cartData = []; // Initialize an empty array to store cart item data
        
        foreach ($cart->getItems() as $item) {
            $productId = $item->getProduct();
            
            $cartData[] = [
                'name' => $productId->getName(),
                'reservationId' => $productId->getId(),
                'price' => $productId->getPrice(),
                'category' => $productId->getCategory()->getName(),
            ];
        }
        
        $html =  $this->renderView('pdf_generator_1/index.html.twig', ['cartData' => $cartData]);
    
        $dompdf->loadHtml($html);
        $dompdf->render();
        
        return new Response(
            $dompdf->output(),
            Response::HTTP_OK,
            ['Content-Type' => 'application/pdf']
        );
    }
    

    private function imageToBase64($path)
    {
        $path = $path;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }
}