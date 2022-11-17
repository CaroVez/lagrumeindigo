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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

/**
 * Require ROLE_ADMIN for all the actions of this controller
 */
#[IsGranted('ROLE_ADMIN', null, 'Vous n\'avez pas les droits d\'administrateur pour accéder à cette page')]
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
    public function newFranchise(Request $request, ContractRepository $contractRepository, MailerInterface $mailer): Response
    {
        // new FORM //
        $contract = new Contract();
        $form = $this->createForm(ContractFranchiseType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contractRepository->save($contract, true);

            // SENDING EMAIL after submit
            $email = (new TemplatedEmail())
                ->from(new Address('tech@lagrumeindigo.com', 'l\'agrume indigo'))
                ->to(new Address($contract->getFranchise()->getUser()->getEmail(), $contract->getFranchise()->getUser()->getName()))
                ->subject('Bienvenue dans le groupe de l\'agrume indigo !')
                ->htmlTemplate('email/welcomeFranchise.html.twig');

            //$mailer->send($email); //deactivated because the email address are not real

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
        public function newGym(Request $request, ContractRepository $contractRepository, MailerInterface $mailer): Response
        {
            // new FORM //
            $contract = new Contract();
            $form = $this->createForm(ContractGymType::class, $contract);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $contractRepository->save($contract, true);
    
            // SENDING EMAIL after submit
            $email = (new TemplatedEmail())
                ->from(new Address('tech@lagrumeindigo.com', 'l\'agrume indigo'))
                ->to(new Address($contract->getGym()->getUser()->getEmail(), $contract->getGym()->getUser()->getName()))
                ->cc(new Address($contract->getGym()->getFranchise()->getUser()->getEmail(), $contract->getGym()->getFranchise()->getUser()->getName()))
                ->subject('Bienvenue dans le groupe de l\'agrume indigo !')
                ->htmlTemplate('email/welcomeGym.html.twig');

            //$mailer->send($email); //deactivated because the email address are not real

                // REDIRECT after submit //
                return $this->redirectToRoute('gym_index', [], Response::HTTP_SEE_OTHER);
            }
    
            // VIEW //
            return $this->renderForm('contract/newGym.html.twig', [
                'contract' => $contract,
                'form' => $form,
            ]);
        }

    // ####################### EDIT CONTRACT ####################### //
    public function edit(Request $request, Contract $contract, ContractRepository $contractRepository, MailerInterface $mailer): Response
    {
        // edit FORM //
        $form = $this->createForm(ContractType::class, $contract);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contractRepository->save($contract, true);

            if ($contract->getFranchise() !== null) {
                // SENDING EMAIL after submit, if director
                $email = (new TemplatedEmail())
                    ->from(new Address('tech@lagrumeindigo.com', 'l\'agrume indigo'))
                    ->to(new Address($contract->getFranchise()->getUser()->getEmail(), $contract->getFranchise()->getUser()->getName()))
                    ->subject('Modification de votre contrat - l\'agrume indigo !')
                    ->htmlTemplate('email/edit.html.twig');

                //$mailer->send($email); //deactivated because the email address are not real

                // REDIRECT after submit //
                return $this->redirectToRoute('franchise_show', [
                    'id' => $contract->getFranchise()->getId()
                ], Response::HTTP_SEE_OTHER);

            } else {
                // SENDING EMAIL after submit, if manager
                $email = (new TemplatedEmail())
                    ->from(new Address('tech@lagrumeindigo.com', 'l\'agrume indigo'))
                    ->to(new Address($contract->getGym()->getUser()->getEmail(), $contract->getGym()->getUser()->getName()))
                    ->cc(new Address($contract->getGym()->getFranchise()->getUser()->getEmail(), $contract->getGym()->getFranchise()->getUser()->getName()))
                    ->subject('Modification de votre contrat - l\'agrume indigo !')
                    ->htmlTemplate('email/edit.html.twig');

                //$mailer->send($email); //deactivated because the email address are not real

                // REDIRECT after submit //
                return $this->redirectToRoute('franchise_show', [
                    'id' => $contract->getGym()->getFranchise()->getId()
                ], Response::HTTP_SEE_OTHER);
            }
        }

        // VIEW //
        return $this->renderForm('contract/edit.html.twig', [
            'contract' => $contract,
            'form' => $form,
        ]);
    }

}
