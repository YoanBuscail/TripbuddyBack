<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

class AuthApiController extends AbstractController
{
    /**
     * @Route("/api/logout", name="api_logout", methods={"POST"})
     */
    public function logout(): JsonResponse
    {
        // TODO Renvoyez une réponse JSON de succès (DECONNEXION) ou un message d'erreur
        // On supprime le jeton JWT en envoyant un cookie d'expiration
        $response = new JsonResponse(['message' => 'Déconnecté avec succès']);

        // On définit la date d'expiration du cookie à une date passée pour l'invalidation
        $response->headers->clearCookie('jwt', '/', null, true);

        return $response;
    }
}
