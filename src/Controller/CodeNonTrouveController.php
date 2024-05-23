<?php

namespace App\Controller;

use App\Entity\Filiere;
use App\Entity\Element;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CodeNonTrouveController extends AbstractController
{
    #[Route('/code/non/trouve/{codefiliere}', name: 'app_code_non_trouve')]
    public function index($codefiliere, Request $request, EntityManagerInterface $em): Response
    {
        $filiere = $em->getRepository(Filiere::class)->findOneBy(['codefiliere' => $codefiliere]);
        $filieres = $em->getRepository(Filiere::class)->findAll();
        $formBuilder = $this->createFormBuilder();


        foreach ($filiere->getBlocs() as $bloc) {

            foreach ($bloc->getUnites() as $unite) {

                foreach ($unite->getMatieres() as $matiere) {

                    foreach ($matiere->getEpreuves() as $epreuve) {
                        $epreuveKey1 = $this->generateFieldName($epreuve->getTypeepreuve().' '.$matiere->getNommat().' '.$matiere->getPeriode());
                        $epreuveKey2 = $this->generateFieldName($epreuve->getTypeepreuve().' '.$unite->getNomunite());

                        if ($epreuve->getCodeTrouve() == 'non' && $epreuve->getNumchance() == 1) {
                            $formBuilder->add($epreuveKey1, TextType::class, [
                                'label' => strtolower($epreuve->getTypeepreuve().' '.$matiere->getNommat().' '.$matiere->getPeriode()),
                            ]);
                        } elseif ($epreuve->getCodeTrouve() == 'non' && $epreuve->getNumchance() == 2) {
                            $formBuilder->add($epreuveKey2, TextType::class, [
                                'label' => strtolower($epreuve->getTypeepreuve().' '.$unite->getNomunite()),
                            ]);
                        }
                    }
                }
            }
        }

        $form = $formBuilder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();


            foreach ($filiere->getBlocs() as $bloc) {

                foreach ($bloc->getUnites() as $unite) {

                    foreach ($unite->getMatieres() as $matiere) {
                        foreach ($matiere->getEpreuves() as $epreuve) {
                            $epreuveKey1 = $this->generateFieldName($epreuve->getTypeepreuve().' '.$matiere->getNommat().' '.$matiere->getPeriode());
                            $epreuveKey2 = $this->generateFieldName($epreuve->getTypeepreuve().' '.$unite->getNomunite());

                            if ($epreuve->getNumchance() == 1 && isset($data[$epreuveKey1])) {
                                $epreuve->setCodeepreuve($data[$epreuveKey1]);
                                $epreuve->setCodeTrouve('oui');
                                
                                $em->persist($epreuve);
                                $element = $em->getRepository(Element::class)->findOneBy(["codeelt" => $data[$epreuveKey1]]);
                                if($element){
                                    $element->setEpreuve($epreuve);
                                    $element->setType('epreuve');
                                    $em->persist($element);
                                } else{
                                    $element = new Element();
                                    $element->setEpreuve($epreuve);
                                    $element->setCodeelt($data[$epreuveKey1]);
                                    $element->setType('epreuve');
                                    $em->persist($element);
                                }
                            } elseif ($epreuve->getNumchance() == 2 && isset($data[$epreuveKey2])) {
                                $epreuve->setCodeepreuve($data[$epreuveKey2]);
                                $epreuve->setCodeTrouve('oui');
                                $em->persist($epreuve);
                                $element = $em->getRepository(Element::class)->findOneBy(["codeelt" => $data[$epreuveKey1]]);
                                if($element){
                                    $element->setEpreuve($epreuve);
                                    $element->setType('epreuve');
                                    $em->persist($element);
                                } else{
                                    $element = new Element();
                                    $element->setEpreuve($epreuve);
                                    $element->setCodeelt($data[$epreuveKey1]);
                                    $element->setType('epreuve');
                                    $em->persist($element);
                                }
                            }
                        }
                    }
                }
            }

            $em->flush();

            return $this->redirectToRoute('app_maquette');
        }

        return $this->render('code_non_trouve/index.html.twig', [
            'controller_name' => 'CodeNonTrouveController',
            'filieres' => $filieres,
            'form' => $form->createView(),
        ]);
    }

    private function generateFieldName($name)
    {
        return preg_replace('/[^a-zA-Z0-9_]/', '_', $name);
    }
}