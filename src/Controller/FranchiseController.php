<?php

namespace App\Controller;

use App\Entity\Franchise;
use App\Form\FranchiseType;
use App\Form\FranchiseEditType;
use App\Repository\FranchiseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class FranchiseController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    // ####################### INDEX FRANCHISE ####################### //
    public function index(FranchiseRepository $franchiseRepository): Response
    {
        // access only by ROLE_ADMIN //
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Vous n\'avez pas les droits d\'administrateur pour accéder à cette page');

        // VIEW //
        return $this->render('franchise/index.html.twig', [
            'franchises' => $franchiseRepository->findAll(),
        ]);
    }

    // ####################### NEW FRANCHISE ####################### //
    public function new(Request $request, FranchiseRepository $franchiseRepository): Response
    {
        // access only by ROLE_ADMIN //
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Vous n\'avez pas les droits d\'administrateur pour accéder à cette page');

        // new FORM //
        $franchise = new Franchise();
        $form = $this->createForm(FranchiseType::class, $franchise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $franchiseRepository->save($franchise, true);

            // REDIRECT after submit //
            return $this->redirectToRoute('contract_franchise_new', [], Response::HTTP_SEE_OTHER);
        }

        // VIEW //
        return $this->renderForm('franchise/new.html.twig', [
            'franchise' => $franchise,
            'form' => $form,
        ]);
    }

    // ####################### SHOW FRANCHISE ####################### //
    public function show(Franchise $franchise): Response
    {
        // access by ROLE_FRANCHISE //
        if ($this->denyAccessUnlessGranted('ROLE_FRANCHISE', null, 'Vous n\'avez pas les droits d\'un directeur pour accéder à cette page') ) {
            // access only HIS OWN page //
            $userFranchise = $this->security->getUser()->getFranchise();

            if ($userFranchise !== null ) {
                if ($franchise->getUser() !== $this->getUser()) {
                    throw $this->createAccessDeniedException('accès interdit : cette franchise n\'est pas reliée à votre compte');
                }
            }
        }        

        // VIEW //
        return $this->render('franchise/show.html.twig', [
            'franchise' => $franchise,
        ]);
    }

    // ####################### EDIT FRANCHISE ####################### //
    public function edit(Request $request, Franchise $franchise, FranchiseRepository $franchiseRepository, MailerInterface $mailer): Response
    {
        // access only by ROLE_ADMIN //
        $this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Vous n\'avez pas les droits d\'administrateur pour accéder à cette page');

        // edit FORM //
        $form = $this->createForm(FranchiseEditType::class, $franchise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $franchiseRepository->save($franchise, true);

        // SENDING EMAIL after submit, if director
        $email = (new TemplatedEmail())
            ->from(new Address('tech@lagrumeindigo.com', 'l\'agrume indigo'))
            ->to(new Address($franchise->getUser()->getEmail(), $franchise->getUser()->getName()))
            ->subject('Modification de votre franchise - l\'agrume indigo !')
            ->htmlTemplate('email/edit.html.twig');

        //$mailer->send($email); //deactivated because the email address are not real

            // REDIRECT after submit //
            return $this->redirectToRoute('franchise_show', [
                'id' => $franchise->getUser()->getFranchise()->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        // VIEW //
        return $this->renderForm('franchise/edit.html.twig', [
            'franchise' => $franchise,
            'form' => $form,
        ]);
    }

}
