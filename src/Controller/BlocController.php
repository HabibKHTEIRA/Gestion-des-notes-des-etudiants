<?php

namespace App\Controller;

use App\Entity\Bloc;
use App\Entity\Element;
use App\Entity\Filiere;
use App\Form\BlocType;
use App\Form\FiliereType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class BlocController extends AbstractController
{
    #[Route('/bloc', name: 'app_bloc')]
    public function index(): Response
    {
        return $this->render('bloc/index.html.twig', [
            'controller_name' => 'BlocController',
        ]);
    }

    #[Route('/bloc/ajouter/{codefiliere}', name: 'bloc.ajouter')]
    public function ajouter($codefiliere, Request $request, managerRegistry $doctrine): Response
    {
        $bloc = new Bloc();
        $entityManager = $doctrine->getManager();
        $filiere = $entityManager->getRepository(Filiere::class)->findOneBy(['codefiliere' => $codefiliere]);

        if (!$filiere) {
            throw $this->createNotFoundException('La filière avec le code '.$codefiliere.' n\'existe pas.');
        }
        $bloc->setFiliere($filiere);
        $form = $this->createForm(BlocType::class, $bloc);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($bloc);
            $entityManager->flush();
            $element = New Element();
            $element->setBloc($bloc);
            $element->setCodeelt($bloc->getCodebloc());
            $entityManager->persist($element);
            $entityManager->flush();
            return$this->redirectToRoute('app_maquette');
        }
        return $this->render('bloc/ajouter.html.twig', [
            'form' => $form,
        ]);
    }
}
