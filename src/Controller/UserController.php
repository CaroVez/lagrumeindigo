<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    // ####################### INDEX USER ####################### //
    public function index(UserRepository $userRepository): Response
    {
        // VIEW //
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    // ####################### SHOW USER ####################### //
    public function show(User $user): Response
    {
        // VIEW //
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    // ####################### EDIT USER ####################### //
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        // edit FORM //
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->save($user, true);

            // REDIRECT after submit //
            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        // VIEW //
        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    // ####################### DELETE USER ####################### //
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        // REDIRECT after submit //
        return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
    }
}
