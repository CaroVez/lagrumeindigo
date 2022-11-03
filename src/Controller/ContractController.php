<?php

namespace App\Controller;

use App\Entity\Contract;
use App\Form\ContractType;
use App\Form\ContractFranchiseType;
use App\Form\ContractGymType;
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

    // ####################### NEW CONTRACT for FRANCHISE ####################### //
    public function newFranchise(Request $request, ContractRepository $contractRepository): Response
    {
        // new FORM //
        $contract = new Contract();
        $form = $this->createForm(ContractFranchiseType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contractRepository->save($contract, true);

            // REDIRECT after submit //
            return $this->redirectToRoute('franchise_index', [], Response::HTTP_SEE_OTHER);
        }

        // VIEW //
        return $this->renderForm('contract/newFranchise.html.twig', [
            'contract' => $contract,
            'form' => $form,
        ]);
    }

        // ####################### NEW CONTRACT for GYM ####################### //
        public function newGym(Request $request, ContractRepository $contractRepository): Response
        {
            // new FORM //
            $contract = new Contract();
            $form = $this->createForm(ContractGymType::class, $contract);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $contractRepository->save($contract, true);
    
                // REDIRECT after submit //
                return $this->redirectToRoute('franchise_index', [], Response::HTTP_SEE_OTHER);
            }
    
            // VIEW //
            return $this->renderForm('contract/newGym.html.twig', [
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
            return $this->redirectToRoute('franchise_show', [
                'id' => $contract->getFranchise()->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        // VIEW //
        return $this->renderForm('contract/edit.html.twig', [
            'contract' => $contract,
            'form' => $form,
        ]);
    }

}
