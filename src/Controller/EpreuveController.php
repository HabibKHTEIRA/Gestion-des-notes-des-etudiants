<?php

namespace App\Controller;

use App\Entity\Bloc;
use App\Entity\Element;
use App\Entity\Epreuve;
use App\Entity\Matiere;
use App\Form\EpreuveType;
use App\Entity\Unite;
use App\Form\UniteType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EpreuveController extends AbstractController
{
    #[Route('/epreuve', name: 'app_epreuve')]
    public function index(): Response
    {
        return $this->render('epreuve/index.html.twig', [
            'controller_name' => 'EpreuveController',
        ]);
    }

    #[Route('/epreuve/ajouter/{codematiere}', name: 'epreuve.ajouter')]
    public function ajouter($codematiere, Request $request, managerRegistry $doctrine): Response
    {
        $epreuve = new Epreuve();
        $entityManager = $doctrine->getManager();
        $matiere = $entityManager->getRepository(Matiere::class)->findOneBy(['codemat' => $codematiere]);

        if (!$matiere) {
            throw $this->createNotFoundException('La matiere avec le code ' . $codematiere . ' n\'existe pas.');
        }
        $epreuve->setMatiere($matiere);
        $form = $this->createForm(EpreuveType::class, $epreuve);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($epreuve);
            $entityManager->flush();
            $element = New Element();
            $element->setEpreuve($epreuve);
            $element->setCodeelt($epreuve->getCodeepreuve());
            $entityManager->persist($element);
            $entityManager->flush();
            return $this->redirectToRoute('app_maquette');
        }
        return $this->render('epreuve/ajouter.html.twig', [
            'form' => $form,
        ]);
    }
}
