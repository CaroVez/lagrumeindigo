<?php

namespace App\Controller;

use App\Entity\Gym;
use App\Form\GymType;
use App\Repository\GymRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GymController extends AbstractController
{
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
            return $this->redirectToRoute('gym_index', [], Response::HTTP_SEE_OTHER);
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
        // VIEW //
        return $this->render('gym/show.html.twig', [
            'gym' => $gym,
        ]);
    }

    // ####################### EDIT GYM ####################### //
    public function edit(Request $request, Gym $gym, GymRepository $gymRepository): Response
    {
        // edit FORM //
        $form = $this->createForm(GymType::class, $gym);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $gymRepository->save($gym, true);

            // REDIRECT after submit //
            return $this->redirectToRoute('gym_index', [], Response::HTTP_SEE_OTHER);
        }

        // VIEW //
        return $this->renderForm('gym/edit.html.twig', [
            'gym' => $gym,
            'form' => $form,
        ]);
    }

    // ####################### DELETE GYM ####################### //
    public function delete(Request $request, Gym $gym, GymRepository $gymRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$gym->getId(), $request->request->get('_token'))) {
            $gymRepository->remove($gym, true);
        }

        // REDIRECT after submit //
        return $this->redirectToRoute('gym_index', [], Response::HTTP_SEE_OTHER);
    }
}
