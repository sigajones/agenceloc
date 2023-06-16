<?php

namespace App\Controller;

use App\Repository\VehiculeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(VehiculeRepository $repo): Response
    {
        $vehicules = $repo->findAll();
        return $this->render('app/index.html.twig', [
            'vehicules' => $vehicules
        ]);
    }

    #[Route('/vehicule', name:"vehicule")]
    public function blog(VehiculeRepository $repo): Response
    {
        $vehicules = $repo->findAll();
        return $this->render('app/vehicule.html.twig', [
            'vehicules' => $vehicules
        ]);
    }


}


