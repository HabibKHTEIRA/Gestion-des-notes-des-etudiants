<?php

namespace App\Controller;

use App\Entity\Filiere;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Fiber;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Attribute\IsGranted as AttributeIsGranted;
use Symfony\Component\Validator\Constraints\Isbn;

class AdministrationController extends AbstractController
{
    #[Route('/administration/user', name: 'app_administration_user')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        //$this->denyAccessUnlessGranted('ROLE_USER');
        $filieres = $em->getRepository(Filiere::class)->findAll();
        return $this->render('administration/index.html.twig', [
            'filieres' => $filieres
        ]);
    }

    #[Route('/administration/admin', name: 'app_administration_admin')]
    public function admin(Request $request, EntityManagerInterface $em): Response
    {
        //$this->denyAccessUnlessGranted('ROLE_ADMIN');
        $filieres = $em->getRepository(Filiere::class)->findAll();
        return $this->render('administration/admin.html.twig', [
            'filieres' => $filieres
        ]);
    }
}
