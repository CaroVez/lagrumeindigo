<?php

namespace App\Controller;

use App\Entity\Contract;
use App\Form\ContractType;
use App\Repository\ContractRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ContractController extends AbstractController
{
    // ####################### INDEX CONTRACT ####################### //
    public function index(ContractRepository $contractRepository): Response
    {
        // VIEW //
        return $this->render('contract/index.html.twig', [
            'contracts' => $contractRepository->findAll(),
        ]);
    }

    // ####################### NEW CONTRACT ####################### //
    public function new(Request $request, ContractRepository $contractRepository): Response
    {
        // new FORM //
        $contract = new Contract();
        $form = $this->createForm(ContractType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contractRepository->save($contract, true);

            // REDIRECT after submit //
            return $this->redirectToRoute('franchise_index', [], Response::HTTP_SEE_OTHER);
        }

        // VIEW //
        return $this->renderForm('contract/new.html.twig', [
            'contract' => $contract,
            'form' => $form,
        ]);
    }

    // ####################### SHOW CONTRACT ####################### //
    public function show(Contract $contract): Response
    {
        // VIEW //
        return $this->render('contract/show.html.twig', [
            'contract' => $contract,
        ]);
    }

    // ####################### EDIT CONTRACT ####################### //
    public function edit(Request $request, Contract $contract, ContractRepository $contractRepository): Response
    {
        // edit FORM //
        $form = $this->createForm(ContractType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contractRepository->save($contract, true);

            // REDIRECT after submit //
            return $this->redirectToRoute('franchise_index', [], Response::HTTP_SEE_OTHER);
        }

        // VIEW //
        return $this->renderForm('contract/edit.html.twig', [
            'contract' => $contract,
            'form' => $form,
        ]);
    }

    // ####################### DELETE CONTRACT ####################### //
    public function delete(Request $request, Contract $contract, ContractRepository $contractRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contract->getId(), $request->request->get('_token'))) {
            $contractRepository->remove($contract, true);
        }

        // REDIRECT after submit //
        return $this->redirectToRoute('franchise_index', [], Response::HTTP_SEE_OTHER);
    }
}
