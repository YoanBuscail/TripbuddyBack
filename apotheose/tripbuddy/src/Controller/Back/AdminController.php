<?php

namespace App\Controller\Back;

use App\Entity\User;
use App\Entity\Itinerary;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/login", name="admin_login")
     */
    public function adminLogin(AuthorizationCheckerInterface $authorizationChecker)
{
    // Vérifiez si l'utilisateur a le rôle ROLE_ADMIN
    if ($authorizationChecker->isGranted('ROLE_ADMIN')) {
        // Redirigez l'utilisateur vers la page du dashboard
        return $this->redirectToRoute('admin_dashboard');
    }

    return $this->render('back/admin/login.html.twig');
}

    /**
     * @Route("/admin/dashboard", name="admin_dashboard")
     */
    public function adminDashboard()
    {
        return $this->render('back/admin/dashboard.html.twig');
    }

    /**
     * @Route("/admin/users", name="list_users")
     */
    public function listUsers(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        
        return $this->render('back/user/list.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/admin/users/{user_id}", name="view_user")
     */
    public function viewUserDetails($user_id, UserRepository $userRepository)
    {
        $user = $this->$userRepository->find($user_id);
        
        return $this->render('back/user/list.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/admin/users/new", name="create_user", methods={"GET", "POST"})
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

            return $this->redirectToRoute('list_users', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user/list.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/admin/users/{user_id}/edit", name="edit_user")
     */
    public function editUser(Request $request, User $user, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user, ["custom_option" => "edit"]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $userRepository->add($user, true);

            return $this->redirectToRoute('list_users', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/admin/users/{user_id}/update", name="update_user", methods={"POST"})
     */
    public function updateUser(Request $request, $user_id, UserRepository $userRepository)
    {
        $user = $this->$userRepository->find($user_id);
        // Code pour valider et mettre à jour les données de l'utilisateur
    }

    /**
     * @Route("/admin/users/{user_id}/delete", name="delete_user", methods={"DELETE"})
     */
    public function deleteUser($user_id, UserRepository $userRepository)
    {
        $user = $this->$userRepository->find($user_id);
        // Code pour supprimer l'utilisateur
    }
}