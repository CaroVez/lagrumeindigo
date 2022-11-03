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
        // VIEW //
        return $this->render('gym/index.html.twig', [
            'gyms' => $gymRepository->findAll(),
        ]);
    }

    // ####################### NEW GYM ####################### //
    public function new(Request $request, GymRepository $gymRepository): Response
    {
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
        $userGym = $this->security->getUser()->getGym(); 

        if ($userGym !== null) {
            if ($gym->getUser() !== $this->getUser()) {
                throw $this->createAccessDeniedException('accès interdit : cette salle de sport n\'est pas reliée à votre compte');
            }
        }

        // VIEW //
        return $this->render('gym/show.html.twig', [
            'gym' => $gym,
        ]);
    }

    // ####################### EDIT GYM ####################### //
    public function edit(Request $request, Gym $gym, GymRepository $gymRepository): Response
    {
        // edit FORM //
        $form = $this->createForm(GymEditType::class, $gym);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gymRepository->save($gym, true);

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
