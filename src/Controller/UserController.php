<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class UserController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    // ####################### INDEX USER ####################### //
    public function index(UserRepository $userRepository): Response
    {
        // access only by ROLE_ADMIN //
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Vous n\'avez pas les droits d\'administrateur pour accéder à cette page');

        // VIEW //
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    // ####################### EDIT USER ####################### //
    public function edit(Request $request, User $user, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, MailerInterface $mailer): Response
    {
        if ($this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY', null, 'Vous devez être connecté pour accéder à cette page') ) {
            // access only HIS OWN profile //
            if ($user !== $this->getUser()) {
                throw $this->createAccessDeniedException('accès interdit : ce profile n\'est pas reliée à votre compte');
            }
        }
        
        // edit FORM //
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if($user->getPlainPassword()) {
                $pwd = $userPasswordHasher->hashPassword($user, $user->getPlainPassword());
                $user->setPassword($pwd);
            }
            $entityManager->persist($user);
            $entityManager->flush();

            if ($user->getFranchise() !== null) {
                // SENDING EMAIL after submit, if director
                $email = (new TemplatedEmail())
                    ->from(new Address('tech@lagrumeindigo.com', 'l\'agrume indigo'))
                    ->to(new Address($user->getEmail(), $user->getName()))
                    ->subject('Modification de votre profile - l\'agrume indigo !')
                    ->htmlTemplate('email/edit.html.twig');
        
                //$mailer->send($email); //deactivated because the email address are not real

                // REDIRECT after submit //
                return $this->redirectToRoute('franchise_show', [
                    'id' => $user->getFranchise()->getId()
                ], Response::HTTP_SEE_OTHER);
            
            } else if ($user->getGym() !== null) {
                // SENDING EMAIL after submit, if manager
                $email = (new TemplatedEmail())
                    ->from(new Address('tech@lagrumeindigo.com', 'l\'agrume indigo'))
                    ->to(new Address($user->getEmail(), $user->getName()))
                    ->cc(new Address($user->getGym()->getFranchise()->getUser()->getEmail(), $user->getGym()->getFranchise()->getUser()->getName()))
                    ->subject('Modification de votre profile - l\'agrume indigo !')
                    ->htmlTemplate('email/edit.html.twig');
        
                //$mailer->send($email); //deactivated because the email address are not real

                // REDIRECT after submit //
                return $this->redirectToRoute('franchise_show', [
                    'id' => $user->getGym()->getFranchise()->getId()
                ], Response::HTTP_SEE_OTHER);

            } else {
                // SENDING EMAIL after submit, if admin
                $email = (new TemplatedEmail())
                    ->from(new Address('tech@lagrumeindigo.com', 'l\'agrume indigo'))
                    ->to(new Address($user->getEmail(), $user->getName()))
                    ->subject('Modification de votre profile - l\'agrume indigo !')
                    ->htmlTemplate('email/edit.html.twig');
        
                //$mailer->send($email); //deactivated because the email address are not real

                // REDIRECT after submit //
                return $this->redirectToRoute('franchise_index', [], Response::HTTP_SEE_OTHER);
            }
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
        // access only by ROLE_ADMIN //
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Vous n\'avez pas les droits d\'administrateur pour accéder à cette page');

        $submittedToken = $request->request->get('_token');

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $submittedToken)) {
            $userRepository->remove($user, true);
        }

        // REDIRECT after submit //
        return $this->redirectToRoute('franchise_index', [], Response::HTTP_SEE_OTHER);
    }
}
