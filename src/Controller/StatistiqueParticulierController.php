<?php

namespace App\Controller;

use App\Entity\Filiere;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StatistiqueParticulierController extends AbstractController
{
    #[Route('/statistique/particulier/{codefiliere}', name: 'app_statistique_particulier')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $codefiliere = $request->get('codefiliere');
        $filieres = $em->getRepository(Filiere::class)->findOneBy(["codefiliere" => $codefiliere]);
        return $this->render('statistique_particulier/index.html.twig', [
            'controller_name' => 'StatistiqueParticulierController',
        ]);
    }
}
