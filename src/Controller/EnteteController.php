<?php

namespace App\Controller;

use App\Entity\Filiere;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Fiber;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted as AttributeIsGranted;
use Symfony\Component\Validator\Constraints\Isbn;

class EnteteController extends AbstractController
{
    #[Route('/entete', name: 'app_entete')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {

        $filieres = $em->getRepository(Filiere::class)->findAll();
        return $this->render('entete/index.html.twig', [
            'controller_name' => 'EnteteController',
            'filieres' => $filieres
        ]);
    }
}
