<?php

namespace App\Controller;

use App\Entity\Step;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;


class StepController extends AbstractController
{
    /**
     * @Route("/api/steps", name="create_step", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données JSON de la requête
        $data = json_decode($request->getContent(), true);

        // Créer une nouvelle étape à partir des données de la requête JSON
        $step = new Step();
        $step->setName($data['name']); 
        $step->setLatitude($data['latitude']); 
        $step->setLongitude($data['longitude']); 
        $step->setDescription($data['description']); 

        // Valider les données avec le Validator
        $errors = $validator->validate($step);

        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], 400);
        }

        // Enregistrer l'étape en base de données
        $entityManager->persist($step);
        $entityManager->flush();

        // Retourner une réponse 201 Created avec l'étape créée en JSON)
        return $this->json($step, 201);
    }

    /**
     * @Route("/api/steps/{id}", name="get_step", methods={"GET"})
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

        // Retourner une réponse 200 OK avec l'étape mise à jour en JSON)
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

        // Retourner une réponse 204 No Content)
        return new Response(null, 204);
    }
}