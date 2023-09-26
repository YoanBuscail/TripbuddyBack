<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class AuthApiController extends AbstractController
{
    private $jwtManager;

    public function __construct(JWTTokenManagerInterface $jwtManager)
    {
        $this->jwtManager = $jwtManager;
    }

    /**
     * @Route("/api/login", name="api_login", methods={"POST"})
     */
    public function login(Request $request, Security $security, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        // TODO Renvoyez une réponse JSON avec le token JWT ou un message d'erreur
        $user = $security->getUser();

        if ($user) {
            // Si l'utilisateur est déjà authentifié, on retourne un jeton JWT
            $token = $this->jwtManager->create($user);

            return new JsonResponse(['token' => $token]);
        }

        // On récupère les données JSON de la requête
        $data = json_decode($request->getContent(), true);

        // On récupère l'email et le mot de passe de la demande
        $email = $data['email'];
        $password = $data['password'];

        // On récupère l'utilisateur dans la base de données par son email
        $user = $this->$userRepository->findOneBy(['email' => $email]);

        // Si l'utilisateur n'existe pas ou le mot de passe est incorrect, on récupère une erreur
        if (!$user || !$passwordHasher->isPasswordValid($user, $password)) {
            return new JsonResponse(['erreur' => 'L\'authentification a échoué'], 401);
        }

        // Si l'authentification réussit, on crée un jeton JWT et on la retourne
        $token = $this->jwtManager->create($user);

        return new JsonResponse(['token' => $token]);   
    }

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
