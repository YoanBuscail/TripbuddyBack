<?php

namespace App\Controller;

use App\Entity\Step;
use App\Repository\StepRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;



class StepController extends AbstractController
{
    /**
     * @Route("/api/steps", name="create_step", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager, StepRepository $stepRepository): Response
    {
        // ICI je récupère le contenu de la requête à ce stade c'est du json
        $jsonContent = $request->getContent();
        // J'ai besoin d'une entité pour faire l'ajout en bdd donc je transforme le json en entité à l'aide du serializer
        // la méthode veut dire ce contenu, tu le transformes en step, le contenu de base est du json.
        #dd($jsonContent);
        // mettre un try catch au cas ou le json n'est pas bon
        try {
            $step = $serializer->deserialize($jsonContent, Step::class, 'json');
        } catch (NotEncodableValueException $e) {
            // si je suis ici c'est que le json n'est pas bon
            return $this->json(["error" => "json invalide"], Response::HTTP_BAD_REQUEST);
        }

        // je check si mon step contient des erreurs
        $errors = $validator->validate($step);

        // est ce qu'il y a au moins une erreur
        if (count($errors) > 0) {

            foreach($errors as $error) {
                // je me crée un tableau avec les erreurs en valeur et les champs concernés en index
                $dataErrors[$error->getPropertyPath()][] = $error->getMessage();
            }

            return $this->json($dataErrors, Response::HTTP_UNPROCESSABLE_ENTITY);

        }

        // ! j'arrive je sais que mes constraints sont bien passés
        $stepRepository->add($step, true);

        // on retour le step en json

        return $this->json($step, Response::HTTP_CREATED, ["groups" => "step"]);
    }

    /**
     * @Route("/api/steps/{id}", name="get_steps", methods={"GET"})
     */
    public function show(Step $step, SerializerInterface $serializer): Response
    {
        // Sérialiser l'étape en JSON
        $jsonStep = $serializer->serialize($step, 'json');

        // Retourner une réponse avec l'étape en JSON
        return new Response($jsonStep, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * @Route("/api/steps/{id}", name="update_step", methods={"PUT"})
     */
    public function update(Request $request, Step $step, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données JSON de la requête
        $data = json_decode($request->getContent(), true);

        // Mettre à jour l'étape existante avec les données de la requête JSON
        $step->setName($data['name']);
        $step->setLatitude($data['latitude']);
        $step->setLongitude($data['longitude']);
        $step->setDescription($data['description']);

        // Valider les données avec le Validator
        $errors = $validator->validate($step);

        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], 400);
        }

        // Enregistrer les modifications en base de données
        $entityManager->flush();

        // Retourner une réponse 200 OK avec l'étape mise à jour en JSON
        return $this->json($step, 200);
    }

    /**
     * @Route("/api/steps/{id}", name="delete_step", methods={"DELETE"})
     */
    public function delete(Step $step, EntityManagerInterface $entityManager): Response
    {
        // Supprimer l'étape existante
        $entityManager->remove($step);
        $entityManager->flush();

        // Retourner une réponse 204 No Content
        return new Response(null, 204);
    }


    /**
     * @Route("/api/steps/favorites", name="get_steps_favorites", methods={"GET"})
     */
    public function getStepFavorites(StepRepository $stepRepository): JsonResponse
    {
        $results = $stepRepository->getStepFavorites();

        // Crée un tableau de résultats
        $formattedResults = [];
        foreach ($results as $result) {
            $formattedResults[] = [
                'name' => $result['name'],
                'latitude' => $result['latitude'],
                'longitude' => $result['longitude'],
                'description' => $result['description'],
                'nombre_de_fois_choisie' => $result['nombre_de_fois_choisie'],
            ];
        }

        // Retourne les résultats en format JSON
        return new JsonResponse($formattedResults, 200);
    }
}