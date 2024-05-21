<?php

namespace App\Controller;

use App\Entity\Filiere;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MaquetteParticulierController extends AbstractController
{
    #[Route('/maquette/particulier/{codefiliere}', name: 'app_maquette_particulier')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $codefiliere = $request->get('codefiliere');
        $filieres = $em->getRepository(Filiere::class)->findAll();
        
        $filiere = $em->getRepository(Filiere::class)->findOneBy(["codefiliere" => $codefiliere]);
        return $this->render('maquette_particulier/index.html.twig', [
            'controller_name' => 'MaquetteParticulierController',
            'filiere' => $filiere,
            'filieres' => $filieres
        ]);
    }
}
