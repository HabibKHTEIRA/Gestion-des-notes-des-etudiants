<?php

namespace App\Controller;

use App\Entity\Bloc;
use App\Entity\Element;
use App\Entity\Filiere;
use App\Entity\Unite;
use App\Form\BlocType;
use App\Form\UniteType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UniteController extends AbstractController
{
    #[Route('/unite', name: 'app_unite')]
    public function index(): Response
    {
        return $this->render('unite/index.html.twig', [
            'controller_name' => 'UniteController',
        ]);
    }

    #[Route('/unite/ajouter/{codebloc}', name: 'unite.ajouter')]
    public function ajouter($codebloc, Request $request, managerRegistry $doctrine): Response
    {
        $unite = new Unite();
        $entityManager = $doctrine->getManager();
        $bloc = $entityManager->getRepository(Bloc::class)->findOneBy(['codebloc' => $codebloc]);

        if (!$bloc) {
            throw $this->createNotFoundException('La bloc avec le code '.$codebloc.' n\'existe pas.');
        }
        $unite->setBloc($bloc);
        $form = $this->createForm(UniteType::class, $unite);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($unite);
            $entityManager->flush();
            $element = New Element();
            $element->setUnite($unite);
            $element->setCodeelt($unite->getCodeunite());
            $entityManager->persist($element);
            $entityManager->flush();
            return$this->redirectToRoute('app_maquette');
        }
        return $this->render('unite/ajouter.html.twig', [
            'form' => $form,
        ]);
    }
}
