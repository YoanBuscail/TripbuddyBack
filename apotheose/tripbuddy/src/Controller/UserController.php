<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Read - Read
     * @Route("/users", name="app_user")
     */
    public function index(): JsonResponse
    {
        return new JsonResponse(['message' => 'Bienvenue'], 200);
    }

    /**
     * Get a specific user by ID
     * @Route("/api/users/{user_id}", name="get_user", methods={"GET"})
     */
    public function getUserById($user_id)
    {
        // Récupérer l'utilisateur à partir de la base de données en utilisant $user_id.
        $user = $this->entityManager->getRepository(User::class)->find($user_id);

        if (!$user) {
            // si L'utilisateur n'a pas été trouvé, renvoyer une réponse 404.
            return new JsonResponse(['message' => 'Utilisateur non trouvé'], 404);
        }

        // Convertir l'objet utilisateur en tableau ou en JSON pour la réponse.
        $userData = [
                'id' => $user->getId(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            ];

        return new JsonResponse($userData, 200);
    }
    /**
     * Create a new user
     * @Route("/api/users", name="create_user", methods={"POST"})
     */
    public function createUser(Request $request): JsonResponse
    {
        // Récupérer les données JSON de la requête
        $data = json_decode($request->getContent(), true);

        // Créer une nouvelle instance de l'entité User
        $user = new User();

        // Remplir les attributs de l'utilisateur avec les données de la requête
        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }
        if (isset($data['firstname'])) {
            $user->setFirstname($data['firstname']);
        }
        if (isset($data['lastname'])) {
            $user->setLastname($data['lastname']);
        }
        if (isset($data['password'])) {
            // Gérer le hachage du mot de passe ici
            $user->setPassword($data['password']);
        }
        if (isset($data['roles'])) {
            // Gérer les rôles ici
            $user->setRoles($data['roles']);
        }

        // Persister le nouvel utilisateur dans la base de données
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Retourner une réponse JSON avec l'utilisateur créé et un code de statut 201 (Created)
        $userData = [
            'id' => $user->getId(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ];

        return new JsonResponse($userData, 201);
    }

    /**
     * Update User
     * @Route("/api/users/{user_id}", name="update_user", methods={"PUT"})
     */
    public function updateUser(Request $request, $user_id): JsonResponse
    {
        $user = $this->entityManager->getRepository(User::class)->find($user_id);

        if (!$user) {
            // Charge la vue d'erreur personnalisée 
            $errorView = $twig->render('error/error404.html.twig');
            return new Response($errorView, Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        // Mettre à jour les attributs de l'utilisateur en fonction des données de la requête.
        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }
        if (isset($data['firstname'])) {
            $user->setFirstname($data['firstname']);
        }
        if (isset($data['lastname'])) {
            $user->setLastname($data['lastname']);
        }
        if (isset($data['password'])) {
            // Gérer la mise à jour du mot de passe ici
            $user->setPassword($data['password']);
        }
        if (isset($data['roles'])) {
            // Gérer la mise à jour des rôles ici 
            $user->setRoles($data['roles']);
        }

        // Persister les changements dans la base de données.
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Utilisateur mis à jour avec succès']);
    }

    /**
     * Delete User
     * @Route("/api/users/{user_id}", name="delete_user", methods={"DELETE"})
     */
    public function deleteUser($user_id)
    {
        $user = $this->entityManager->getRepository(User::class)->find($user_id);

        if (!$user) {
            // L'utilisateur n'a pas été trouvé, renvoyez une réponse 404.
            return new JsonResponse(['message' => 'Utilisateur non trouvé'], 404);
        }

        // Supprimer l'utilisateur de la base de données.
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Utilisateur supprimé avec succès']);
    }
}
