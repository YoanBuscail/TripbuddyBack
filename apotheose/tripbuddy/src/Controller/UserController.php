<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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
        // Récupère l'utilisateur à partir de la base de données en utilisant $user_id.
        $user = $this->entityManager->getRepository(User::class)->find($user_id);

        if (!$user) {
            // si L'utilisateur n'a pas été trouvé, renvoie une réponse 404.
            return new JsonResponse(['message' => 'Utilisateur non trouvé'], 404);
        }

        // Convertit l'objet utilisateur en tableau ou en JSON pour la réponse.
        $userData = [
            'id' => $user->getId(),
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'itineraries' => [], //tableau vide pour stocker les itinéraires
        ];

        // Récupère les itinéraires de l'utilisateur
        $itineraries = $user->getItinerary();

        // Ajoute les itinéraires à la réponse JSON
        foreach ($itineraries as $itinerary) {
            $itineraryData = [
                'id' => $itinerary->getId(),
                'title' => $itinerary->getTitle(),
                'steps' => [], //tableau vide pour stocker les steps
            ];

            // Récupère les étapes de l'itinéraire
            $steps = $itinerary->getStep();

            // Ajoute les étapes à la réponse JSON
            foreach ($steps as $step) {
                $stepData = [
                    'id' => $step->getId(),
                    'description' => $step->getDescription(),
                    'latitude' => $step->getLatitude(),
                    'longitude' => $step->getLongitude(),
                    'name' => $step->getName(),
                ];
                $itineraryData['steps'][] = $stepData;
            }

            $userData['itineraries'][] = $itineraryData;
        }
        
        return new JsonResponse($userData, 200);
    }


    /**
     * Create a new user
     * @Route("/api/users", name="create_user", methods={"POST"})
     */
    public function createUser(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        // Récupérer les données JSON de la requête
        $data = json_decode($request->getContent(), true);

        $data['roles'] = ['ROLE_USER'];
        
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
            // Hacher le mot de passe
            $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
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
            $errorView = $this->render('error/error404.html.twig');
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
