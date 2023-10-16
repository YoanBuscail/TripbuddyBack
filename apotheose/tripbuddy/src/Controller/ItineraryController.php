<?php
namespace App\Controller;

use App\Entity\Itinerary;
use App\Entity\Step;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class ItineraryController extends AbstractController
{
    /**
     * Create a new itinerary.
     * 
     * @Route("/api/itineraries", name="create_itinerary", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données JSON de la requête
        $data = json_decode($request->getContent(), true);
        
        $itinerary = new Itinerary();
        $itinerary->setTitle($data['title']); 
        $itinerary->setStartDate(new \DateTimeImmutable($data['startDate'])); 
        $itinerary->setEndDate(new \DateTimeImmutable($data['endDate'])); 
        $itinerary->setFavorite($data['favorite']); 

        // Traitez les étapes
        foreach ($data['steps'] as $stepData) {
            $step = new Step();
            $step->setName($stepData['name']);
            $step->setLatitude($stepData['coordinates'][0]);
            $step->setLongitude($stepData['coordinates'][1]);
            // Associez le step à l'itinéraire
            $itinerary->addStep($step);
        }

        // Valider les données avec le Validator
        $errors = $validator->validate($itinerary);

        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], 400);
        }

        // Enregistrer l'itinéraire en base de données
        $entityManager->persist($itinerary);
        $entityManager->flush();

        // Retourner une réponse 201 Created avec l'itinéraire créé en JSON)
        return $this->json($itinerary, 201);
    }

    /**
     * Get a specific itinerary by its ID
     * 
     * @Route("/api/itineraries/{id}", name="get_itinerary", methods={"GET"})
     * 
     * @ParamConverter("itinerary", class="App\Entity\Itinerary")
     */
    public function show(Itinerary $itinerary, SerializerInterface $serializer): Response
    {
        // Sérialiser l'itinéraire en JSON
        $jsonItinerary = $serializer->serialize($itinerary, 'json');

        // Retourner une réponse avec l'itinéraire en JSON
        return new Response($jsonItinerary, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     *  Update Itinerary.
     * 
     * @Route("/api/itineraries/{id}", name="update_itinerary", methods={"PUT"})
     */
    public function update(Request $request, Itinerary $itinerary, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données JSON de la requête
        $data = json_decode($request->getContent(), true);

        // Mettre à jour l'itinéraire existant avec les données de la requête JSON
        $itinerary->setTitle($data['title']); 
        $itinerary->setStartDate(new \DateTimeImmutable($data['startDate'])); 
        $itinerary->setEndDate(new \DateTimeImmutable($data['endDate'])); 
        $itinerary->setFavorite($data['favorite']); 

        // Valider les données avec le Validator
        $errors = $validator->validate($itinerary);

        if (count($errors) > 0) {
            return $this->json(['errors' => (string) $errors], 400);
        }

        // Enregistrer les modifications en base de données
        $entityManager->flush();

        // Retourner une réponse 200 OK avec l'itinéraire mis à jour en JSON)
        return $this->json($itinerary, 200);
    }

    /**
     * Delete Itinerary.
     * 
     * @Route("/api/itineraries/{id}", name="delete_itinerary", methods={"DELETE"})
     */
    public function delete(Itinerary $itinerary, EntityManagerInterface $entityManager): Response
    {
        // Supprimer l'itinéraire existant
        $entityManager->remove($itinerary);
        $entityManager->flush();

        // Retourner une réponse 204 No Content)
        return new Response(null, 204);
    }
}