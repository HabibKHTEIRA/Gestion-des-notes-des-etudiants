<?php

namespace App\Controller;

use App\Entity\Bloc;
use App\Entity\Element;
use App\Entity\Matiere;
use App\Entity\Unite;
use App\Form\MatiereType;
use App\Form\UniteType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MatiereController extends AbstractController
{
    #[Route('/matiere', name: 'app_matiere')]
    public function index(): Response
    {
        return $this->render('matiere/index.html.twig', [
            'controller_name' => 'MatiereController',
        ]);
    }

    #[Route('/matiere/ajouter/{codeunite}', name: 'matiere.ajouter')]
    public function ajouter($codeunite, Request $request, managerRegistry $doctrine): Response
    {
        $matiere = new Matiere();
        $entityManager = $doctrine->getManager();
        $unite = $entityManager->getRepository(Unite::class)->findOneBy(['codeunite' => $codeunite]);

        if (!$unite) {
            throw $this->createNotFoundException('L\'unite avec le code '.$codeunite.' n\'existe pas.');
        }
        $matiere->setUnite($unite);
        $form = $this->createForm(MatiereType::class, $matiere);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($matiere);
            $entityManager->flush();
            $element = New Element();
            $element->setMatiere($matiere);
            $element->setCodeelt($matiere->getCodemat());
            $entityManager->persist($element);
            $entityManager->flush();
            return$this->redirectToRoute('app_maquette');
        }
        return $this->render('matiere/ajouter.html.twig', [
            'form' => $form,
        ]);
    }
}
