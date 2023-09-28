<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ErrorController extends AbstractController
{
    /**
     * @Route("/error404", name="error_404")
     */
    public function error404(): Response
    {
        return $this->render('error/error404.html.twig', [], Response::HTTP_NOT_FOUND);
    }
}