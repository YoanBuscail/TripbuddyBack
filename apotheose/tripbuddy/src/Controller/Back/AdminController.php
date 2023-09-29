<?php

namespace App\Controller\Back;

use App\Entity\Itinerary;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\ItineraryRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/login", name="app_back_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // Check if the user is already authenticated
        if ($this->getUser()) {
        // User is already authenticated, redirect to the desired page
        return $this->redirectToRoute('app_back_index');
        }

        return $this->render('back/admin/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error
        ]);
    }

    /**
     * @Route("/", name="app_back_logout")
     */
    public function logout(): void
    {
    }

    /**
     * @Route("/dashboard", name="app_back_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('back/admin/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
    
    /**
     * @Route("/users", name="app_back_user_list", methods={"GET"})
     */
    public function list(UserRepository $userRepository): Response
    {
        return $this->render('back/user/list.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/users/new", name="app_back_user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // on récupère le mot de pass en clair
            $plaintextPassword = $user->getPassword();
            // je hash le mot de passe à l'aide du hasher
            $hashedPassword = $passwordHasher->hashPassword($user,$plaintextPassword);
            // me reste plus qu'à setter le nouveau mot de passe 
            $user->setPassword($hashedPassword);

            $userRepository->add($user, true);

            return $this->redirectToRoute('app_back_user_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/users/{id}", name="app_back_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('back/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/users/{id}/edit", name="app_back_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user, ["custom_option" => "edit"]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userRepository->add($user, true);

            return $this->redirectToRoute('app_back_user_list', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/admin/users/{id}/delete", name="app_back_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_back_user_list', [], Response::HTTP_SEE_OTHER);
    }

     /**
     * @Route("/itineraries", name="app_back_itinerary_list", methods={"GET"})
     */
    public function listItineraries(ItineraryRepository $itineraryRepository): Response
    {
        return $this->render('back/itinerary/list.html.twig', [
            'itineraries' => $itineraryRepository->findAll()
        ]);
    }

    /**
     * @Route("/itineraries/{id}", name="app_back_itinerary_show", methods={"GET"})
     */
    public function showItinerary(Itinerary $itinerary): Response
    {
        return $this->render('back/itinerary/show.html.twig', [
            'itinerary' => $itinerary,
        ]);
    }
}
