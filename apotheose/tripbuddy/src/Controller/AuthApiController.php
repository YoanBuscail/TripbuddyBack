<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthApiController extends AbstractController
{
    /**
     * @Route("/api/login", name="api_login", methods={"POST"})
     */
    public function login(): JsonResponse
    {
        // Implémentez la logique de connexion ici
        // Renvoyez une réponse JSON avec le token JWT ou un message d'erreur
    }

    /**
     * @Route("/api/logout", name="api_logout", methods={"POST"})
     */
    public function logout(): JsonResponse
    {
        // Implémentez la logique de déconnexion ici
        // Renvoyez une réponse JSON de succès ou un message d'erreur
    }
}
