<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestInterfacesController extends AbstractController
{
/**
     * @Route("/{folder}/{templateName}", name="test_template")

    public function testAction(string $templateName, string $folder): Response
    {
        return $this->render("$folder/$templateName.html.twig");
    }*/
}
