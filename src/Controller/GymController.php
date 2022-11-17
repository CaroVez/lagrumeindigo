<?php

namespace App\Controller;

use App\Entity\Gym;
use App\Form\GymType;
use App\Form\GymEditType;
use App\Repository\GymRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class GymController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    // ####################### INDEX GYM ####################### //
    public function index(GymRepository $gymRepository): Response
    {
        // access only by ROLE_ADMIN //
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Vous n\'avez pas les droits d\'administrateur pour accéder à cette page');

        // VIEW //
        return $this->render('gym/index.html.twig', [
            'gyms' => $gymRepository->findAll(),
        ]);
    }

    // ####################### NEW GYM ####################### //
    public function new(Request $request, GymRepository $gymRepository): Response
    {
        // access only by ROLE_ADMIN //
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Vous n\'avez pas les droits d\'administrateur pour accéder à cette page');

        // new FORM //
        $gym = new Gym();
        $form = $this->createForm(GymType::class, $gym);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gymRepository->save($gym, true);

            // REDIRECT after submit //
            return $this->redirectToRoute('contract_gym_new', [], Response::HTTP_SEE_OTHER);
        }

        // VIEW //
        return $this->renderForm('gym/new.html.twig', [
            'gym' => $gym,
            'form' => $form,
        ]);
    }

    // ####################### SHOW GYM ####################### //
    public function show(Gym $gym): Response
    {
        // access by ROLE_GYM //
        if ($this->denyAccessUnlessGranted('ROLE_GYM', null, 'Vous n\'avez pas les droits d\'un gérant pour accéder à cette page') ) {
            // access only HIS OWN page //
            $userGym = $this->security->getUser()->getGym(); 

            if ($userGym !== null) {
                if ($gym->getUser() !== $this->getUser()) {
                    throw $this->createAccessDeniedException('accès interdit : cette salle de sport n\'est pas reliée à votre compte');
                }
            }
        }

        // VIEW //
        return $this->render('gym/show.html.twig', [
            'gym' => $gym,
        ]);
    }

    // ####################### EDIT GYM ####################### //
    public function edit(Request $request, Gym $gym, GymRepository $gymRepository, MailerInterface $mailer): Response
    {
        // access only by ROLE_ADMIN //
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Vous n\'avez pas les droits d\'administrateur pour accéder à cette page');

        // edit FORM //
        $form = $this->createForm(GymEditType::class, $gym);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gymRepository->save($gym, true);

        // SENDING EMAIL after submit, if director
        $email = (new TemplatedEmail())
            ->from(new Address('tech@lagrumeindigo.com', 'l\'agrume indigo'))
            ->to(new Address($gym->getUser()->getEmail(), $gym->getUser()->getName()))
            ->cc(new Address($gym->getFranchise()->getUser()->getEmail(), $gym->getFranchise()->getUser()->getName()))
            ->subject('Modification de votre salle de sport - l\'agrume indigo !')
            ->htmlTemplate('email/edit.html.twig');

        //$mailer->send($email); //deactivated because the email address are not real

            // REDIRECT after submit //
            return $this->redirectToRoute('franchise_show', [
                'id' => $gym->getFranchise()->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        // VIEW //
        return $this->renderForm('gym/edit.html.twig', [
            'gym' => $gym,
            'form' => $form,
        ]);
    }

}
