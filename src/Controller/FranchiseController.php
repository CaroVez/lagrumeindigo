<?php

namespace App\Controller;

use App\Entity\Franchise;
use App\Form\FranchiseType;
use App\Repository\FranchiseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FranchiseController extends AbstractController
{
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
            return $this->redirectToRoute('franchise_index', [], Response::HTTP_SEE_OTHER);
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
        // VIEW //
        return $this->render('franchise/show.html.twig', [
            'franchise' => $franchise,
        ]);
    }

    // ####################### EDIT FRANCHISE ####################### //
    public function edit(Request $request, Franchise $franchise, FranchiseRepository $franchiseRepository): Response
    {
        // edit FORM //
        $form = $this->createForm(FranchiseType::class, $franchise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $franchiseRepository->save($franchise, true);

            // REDIRECT after submit //
            return $this->redirectToRoute('franchise_index', [], Response::HTTP_SEE_OTHER);
        }

        // VIEW //
        return $this->renderForm('franchise/edit.html.twig', [
            'franchise' => $franchise,
            'form' => $form,
        ]);
    }

    // ####################### DELETE FRANCHISE ####################### //
    public function delete(Request $request, Franchise $franchise, FranchiseRepository $franchiseRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$franchise->getId(), $request->request->get('_token'))) {
            $franchiseRepository->remove($franchise, true);
        }

        // REDIRECT after submit //
        return $this->redirectToRoute('franchise_index', [], Response::HTTP_SEE_OTHER);
    }
}
