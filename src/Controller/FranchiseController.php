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
        // VIEW //
        return $this->render('franchise/index.html.twig', [
            'franchises' => $franchiseRepository->findAll(),
        ]);
    }

    // ####################### NEW FRANCHISE ####################### //
    public function new(Request $request, FranchiseRepository $franchiseRepository): Response
    {
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
        $userFranchise = $this->security->getUser()->getFranchise();

        if ($userFranchise !== null ) {
            if ($franchise->getUser() !== $this->getUser()) {
                throw $this->createAccessDeniedException('accès interdit : cette franchise n\'est pas reliée à votre compte');
            }
        }

        // VIEW //
        return $this->render('franchise/show.html.twig', [
            'franchise' => $franchise,
        ]);
    }

    // ####################### EDIT FRANCHISE ####################### //
    public function edit(Request $request, Franchise $franchise, FranchiseRepository $franchiseRepository): Response
    {
        // edit FORM //
        $form = $this->createForm(FranchiseEditType::class, $franchise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $franchiseRepository->save($franchise, true);

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
