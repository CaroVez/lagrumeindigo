<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    public function become(): Response
    {
        return $this->render('home/become.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
    
    public function index(): Response
    {
        // access only while connected //
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY', null, 'Vous devez être connecté pour accéder à cette page');

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
