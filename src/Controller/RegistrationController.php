<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationAdminFormType;
use App\Form\RegistrationFranchiseFormType;
use App\Form\RegistrationGymFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    // ####################### NEW ADMIN USER ####################### //
    public function registerAdmin(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        // new FORM //
        $user = new User();
        $form = $this->createForm(RegistrationAdminFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            // REDIRECT after submit //
            return $this->redirectToRoute('home');
        }

        // VIEW //
        return $this->render('registration/registerAdmin.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    // ####################### NEW FRANCHISE USER ####################### //
    public function registerFranchise(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        // new FORM //
        $user = new User();
        $form = $this->createForm(RegistrationFranchiseFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            // REDIRECT after submit //
            return $this->redirectToRoute('franchise_new');
        }

        // VIEW //
        return $this->render('registration/registerFranchise.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    // ####################### NEW GYM USER ####################### //
    public function registerGym(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        // new FORM //
        $user = new User();
        $form = $this->createForm(RegistrationGymFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            // REDIRECT after submit //
            return $this->redirectToRoute('gym_new');
        }

        // VIEW //
        return $this->render('registration/registerGym.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
