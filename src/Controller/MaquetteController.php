<?php

namespace App\Controller;

use App\Entity\Bloc;
use App\Entity\Element;
use App\Entity\Epreuve;
use App\Entity\Filiere;
use App\Entity\Matiere;
use App\Entity\Unite;
use App\Entity\Codes;
use App\Repository\EtudiantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validation;

class MaquetteController extends AbstractController
{

    private $derfiliere;
    private $derbloc;
    private $derunite;
    private $dermatier;
    private $comptfiliere = 1;
    private $comptbloc = 1;
    private $comptunite = 1;
    private $comptmatiere = 1;
    private $comptepreuve = 1;
    private $choix = false;
    private $nontrouve = false;


    #[Route('/maquette', name: 'app_maquette')]
    public function index(managerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $filieres = $entityManager->getRepository(Filiere::class)->findAll();


        $path = 'maquette_modifier';
        $message = 'modifier la filière';
        $path2 = 'filiere.ajouter';
        $message2 = 'ajouter une filière';

        return $this->render('maquette/index.html.twig', [
            'controller_name' => 'MaquetteController',
            'filieres'  => $filieres,
            'path' => $path,
            'message' => $message,
            'path2' => $path2,
            'message2' => $message2,
        ]);
    }

    #[Route('/modifier/{codefiliere}', name: 'maquette_modifier')]
    public function modifier($codefiliere, managerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $filiere = $entityManager->getRepository(Filiere::class)->findOneBy(['codefiliere' => $codefiliere]);
        $filieres = $em->getRepository(Filiere::class)->findAll();



        return $this->render('maquette/modifier.html.twig', [
            'controller_name' => 'MaquetteController',
            'filiere'  => $filiere,
            'filieres' => $filieres,
        ]);
    }

    #[Route('/maquette/importer', name: 'maquette_importer')]
    public function importXML(Request $request, EtudiantRepository $etudiantRepository, EntityManagerInterface $em, managerRegistry $doctrine): Response
    {
        $filieres = $em->getRepository(Filiere::class)->findAll();


        $entityManager = $doctrine->getManager();
        $validator = Validation::createValidator();
        $form = $this->createFormBuilder()
            ->add('xml_file', FileType::class, [
                'label' => 'Fichier XML',
                'attr' => [
                    'accept' => '.xml',
                    'multiple' => false,
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'bottom',
                ],
                'help' => 'Cliquez ici pour obtenir de l\'aide sur le format du fichier xml.',
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $xmlFile = $form->get('xml_file')->getData();

            $content = file_get_contents(($xmlFile));

            $xml = simplexml_load_string(($content));

            foreach ($xml->record as $record) {
                if ($this->comptfiliere == 2) {
                    break;
                }
                $field_0 = $record->field_0;
                $field_1 = $record->field_1;
                $field_3 = $record->field_3;
                $field_11 = $record->field_11;
                $field_12 = $record->field_12;
                $field_45 = $record->field_45;
                $field_24 = $record->field_24;

                if ($field_0 == 'CFVU du :') {
                    $anne = substr($field_24, 0, 2);
                }

                switch ($field_1) {
                    case 'SOCL':
                        $filiere = new Filiere();
                        $this->comptbloc = 1;
                        $existingCodeEntity = $entityManager->getRepository(Codes::class)->findOneBy(['Nom' => strtolower($field_0), 'nature' => ['SOCL', 'PAR']]);
                        if ($existingCodeEntity) {
                            $filiere->setCodeTrouve('oui');
                            $codeFiliere = $existingCodeEntity->getCode();
                        } else {
                            // Générer un nouveau code
                            $temp = substr($field_0, 0, 2) == 'MI' ? 'MI' : 'INF';
                            $codeFiliere = 'T' . $temp . $anne;
                            $filiere->setCodeTrouve('non');
                        }
                        //substr($field_0, 0, 2)
                        if ($entityManager->getRepository(Filiere::class)->findOneBy(["codefiliere" => $codeFiliere])) {
                            $this->comptfiliere++;
                            break;
                        }
                        
                        $filiere->setCodefiliere($codeFiliere);
                        $filiere->setNomfiliere($field_0);

                        $entityManager->persist($filiere);
                        $element = $entityManager->getRepository(Element::class)->findOneBy(["codeelt" => $codeFiliere]);
                        if($element){
                            $element->setFiliere($filiere);
                            $element->setType('filiere');
                            $em->persist($element);
                        } else{
                            $element = new Element();
                            $element->setFiliere($filiere);
                            $element->setCodeelt($filiere->getCodefiliere());
                            $element->setType('filiere');
                            $em->persist($element);
                        }
                        
                        $em->flush();

                        $this->derfiliere = $filiere;
                        break;
                    case 'BLCT':
                        $this->comptunite = 1;
                        $bloc = new Bloc();
                        $existingCodeEntity = $entityManager->getRepository(Codes::class)->findOneBy(['Nom' => strtolower($field_0), 'nature' => ['BLOC', 'BLCT']]);
                        if ($existingCodeEntity) {
                            $codeBloc = $existingCodeEntity->getCode();
                            $bloc->setCodeTrouve('oui');
                        } else {
                            // Générer un nouveau code
                            $codeBloc = $this->derfiliere->getCodefiliere() . 'B' . $this->comptbloc;
                            $bloc->setCodeTrouve('non');
                        }
                        $this->comptbloc++;
                        
                        $bloc->setCodebloc($codeBloc);
                        $bloc->setNombloc($field_0);
                        $bloc->setNoteplancher(filter_var($field_45, FILTER_SANITIZE_NUMBER_INT));
                        $bloc->setFiliere($this->derfiliere);
                        //$entityManager->flush();

                        $entityManager->persist($bloc);
                        $element = $entityManager->getRepository(Element::class)->findOneBy(["codeelt" => $codeBloc]);
                        if($element){
                            $element->setBloc($bloc);
                            $element->setType('bloc');
                            $entityManager->persist($element);
                        } else{
                            $element = new Element();
                            $element->setBloc($bloc);
                            $element->setCodeelt($codeBloc);
                            $element->setType('filiere');
                            $entityManager->persist($element);
                        }
                        $this->derbloc = $bloc;
                        break;
                    case 'UE':
                        $this->comptmatiere = 1;
                        if (!$this->derbloc) {
                            $this->comptunite = 1;
                            $bloc = new Bloc();
                            $existingCodeEntity = $entityManager->getRepository(Codes::class)->findOneBy(['Nom' => 'transversaux', 'nature' => ['BLOC', 'BLCT']]);
                            if ($existingCodeEntity) {
                                $bloc->setCodeTrouve('oui');
                                $codebloc = $existingCodeEntity->getCode();
                            } else {
                                // Générer un nouveau code
                                $codebloc = $this->derfiliere->getCodefiliere() . 'B' . $this->comptbloc;
                                $bloc->setCodeTrouve('non');
                            }
                            $this->comptbloc++;
                            $bloc->setCodebloc($codebloc);
                            $bloc->setNombloc('Transversaux');
                            $bloc->setFiliere($this->derfiliere);
                            $entityManager->persist($bloc);
                            $element = $entityManager->getRepository(Element::class)->findOneBy(["codeelt" => $codebloc]);
                            if($element){
                                $element->setBloc($bloc);
                                $element->setType('bloc');
                                $entityManager->persist($element);
                            } else{
                                $element = new Element();
                                $element->setBloc($bloc);
                                $element->setCodeelt($codebloc);
                                $element->setType('bloc');
                                $entityManager->persist($element);
                            }
                            $this->derbloc = $bloc;
                        }
                        $codebloc = $this->derbloc->getCodebloc();
                        $unite = new Unite();
                        $existingCodeEntity = $entityManager->getRepository(Codes::class)->findOneBy(['Nom' => strtolower($field_0), 'nature' => $field_1]);
                        if ($existingCodeEntity) {
                            $codeUnite = $existingCodeEntity->getCode();
                            $unite->setCodeTrouve('oui');
                        } else {
                            // Générer un nouveau code
                            $codeUnite = $this->derbloc->getCodebloc() . 'U' . $this->comptunite;
                            $unite->setCodeTrouve('non');
                        }
                        $this->comptunite++;
                        $unite->setCodeunite($codeUnite);
                        $unite->setNomunite($field_0);
                        $unite->setCoeficient((int)$field_12);
                        $unite->setBloc($this->derbloc);

                        $entityManager->persist($unite);
                        $element = $entityManager->getRepository(Element::class)->findOneBy(["codeelt" => $unite->getCodeunite()]);
                        if($element){
                            $element->setUnite($unite);
                            $element->setType('unite');
                            $entityManager->persist($element);
                        } else{
                            $element = new Element();
                            $element->setUnite($unite);
                            $element->setCodeelt($unite->getCodeunite());
                            $element->setType('unite');
                            $entityManager->persist($element);
                        }
                        $this->derunite = $unite;
                        $epreuveTemp = [];
                        break;
                    case 'MATI':
                        $this->comptepreuve = 1;
                        $parts = explode(' P', $field_0);
                        $nomMatiere = $parts[0];
                        $periode = isset($parts[1]) ? 'P' . substr($parts[1], 0, 2) : 'P1';
                        $codeMatiere = $this->derunite->getCodeunite() . 'M' . $this->comptmatiere;
                        $existingCodeEntity = $entityManager->getRepository(Codes::class)->findOneBy(['Nom' => strtolower($nomMatiere .' '. $periode), 'nature' => 'MAT']);
                        $matiere = new Matiere();
                        if ($existingCodeEntity) {
                            $codeMatiere = $existingCodeEntity->getCode();
                            $matiere->setCodeTrouve('oui');
                        } else {
                            // Générer un nouveau code
                            $codeMatiere = $this->derunite->getCodeunite() . 'M' . $this->comptmatiere;
                            $matiere->setCodeTrouve('non');

                        }
                        $this->comptmatiere = $this->comptmatiere + 1;
                        $matiere->setCodemat($codeMatiere);
                        $matiere->setNommat($nomMatiere);
                        $matiere->setPeriode($periode);
                        $entityManager->persist($matiere);
                        $element = $entityManager->getRepository(Element::class)->findOneBy(["codeelt" => $matiere->getCodemat()]);
                        if($element){
                            $element->setMatiere($matiere);
                            $element->setType('matiere');
                            $entityManager->persist($element);
                        } else{
                            $element = new Element();
                            $element->setMatiere($matiere);
                            $element->setCodeelt($matiere->getCodemat());
                            $element->setType('matiere');
                            $entityManager->persist($element);
                        }
                        $matiere->setUnite($this->derunite);
                        $this->dermatier = $matiere;
                        if ($record->field_13) {
                            $epreuveSegments = explode('%', $record->field_13); // Séparation initiale par %

                            foreach ($epreuveSegments as $segment) {
                                $segment = trim($segment); // Enlever les espaces inutiles
                                if ($segment) {
                                    $parts = explode(' ', $segment);
                                    $type = $parts[0]; // Le type est le premier élément
                                    $pourcentage = intval($parts[count($parts) - 1]); // Le pourcentage est le dernier élément

                                    $epreuve = new Epreuve();
                                    $epreuve->setMatiere($this->dermatier);
                                    $existingCodeEntity = $entityManager->getRepository(Codes::class)->findOneBy(['Nom' => strtolower($this->dermatier->getNommat() .' '. $this->dermatier->getPeriode()), 'nature' => ['CC', 'TP', 'ECR']]);
                                    if ($existingCodeEntity) {
                                        $codeepr = $existingCodeEntity->getCode();
                                        $epreuve->setCodeTrouve('oui');
                                    } else {
                                        // Générer un nouveau code
                                        $codeepr =  $this->dermatier->getCodemat() . 'E' . $this->comptepreuve;
                                        $epreuve->setCodeTrouve('non');
                                        $this->nontrouve = true;
                                    }
                                    if (!$entityManager->getRepository(Epreuve::class)->findOneBy(["codeepreuve" => $codeepr]) && !in_array($codeepr, $epreuveTemp)) {
                                        $this->comptepreuve++;
                                        $epreuve->setCodeepreuve($codeepr);

                                        $epreuve->setPourcentage($pourcentage);
                                        $epreuve->setTypeepreuve($type);

                                        if ($record->field_14) {
                                            $epreuve->setDuree($record->field_14);
                                        }
                                        $epreuve->setNumchance(1);
                                        $entityManager->persist($epreuve);
                                        $element = $entityManager->getRepository(Element::class)->findOneBy(["codeelt" => $epreuve->getCodeepreuve()]);
                                        if($element){
                                            $element->setEpreuve($epreuve);
                                            $element->setType('epreuve');
                                            $entityManager->persist($element);
                                        } else{
                                            $element = new Element();
                                            $element->setEpreuve($epreuve);
                                            $element->setCodeelt($epreuve->getCodeepreuve());
                                            $element->setType('epreuve');
                                            $entityManager->persist($element);
                                        }
                                        $epreuveTemp[] = $codeepr;
                                    }
                                }
                            }
                        }
                        if ($record->field_17) {
                            $epreuveSegments = explode('%', $record->field_17); // Séparation initiale par %

                            foreach ($epreuveSegments as $segment) {
                                $segment = trim($segment); // Enlever les espaces inutiles
                                if ($segment) {
                                    $parts = explode(' ', $segment);
                                    $type = $parts[0]; // Le type est le premier élément
                                    $pourcentage = intval($parts[count($parts) - 1]); // Le pourcentage est le dernier élément

                                    $epreuve = new Epreuve();
                                    $epreuve->setMatiere($this->dermatier);
                                    $existingCodeEntity = $entityManager->getRepository(Codes::class)->findOneBy(['Nom' => strtolower($this->dermatier->getNommat() .' '. $this->dermatier->getPeriode()), 'nature' => ['CC', 'TP', 'ECR']]);
                                    if ($existingCodeEntity) {
                                        $codeepr = $existingCodeEntity->getCode();
                                        $epreuve->setCodeTrouve('oui');
                                    } else {
                                        // Générer un nouveau code
                                        $codeepr =  $this->dermatier->getCodemat() . 'E' . $this->comptepreuve;
                                        $epreuve->setCodeTrouve('non');
                                        $this->nontrouve = true;
                                    }
                                    if (!$entityManager->getRepository(Epreuve::class)->findOneBy(["codeepreuve" => $codeepr]) && !in_array($codeepr, $epreuveTemp)) {
                                        $this->comptepreuve++;
                                        $epreuve->setCodeepreuve($codeepr);

                                        $epreuve->setPourcentage($pourcentage);
                                        $epreuve->setTypeepreuve($type);

                                        if ($record->field_18) {
                                            $epreuve->setDuree($record->field_18);
                                        }
                                        $epreuve->setNumchance(1);
                                        $entityManager->persist($epreuve);

                                        $element = $entityManager->getRepository(Element::class)->findOneBy(["codeelt" => $epreuve->getCodeepreuve()]);
                                        if($element){
                                            $element->setEpreuve($epreuve);
                                            $element->setType('epreuve');
                                            $entityManager->persist($element);
                                        } else{
                                            $element = new Element();
                                            $element->setEpreuve($epreuve);
                                            $element->setCodeelt($epreuve->getCodeepreuve());
                                            $element->setType('epreuve');
                                            $entityManager->persist($element);
                                        }
                                        $epreuveTemp[] = $codeepr;
                                    }
                                }
                            }
                        }
                        if ($record->field_21) {
                            $epreuveSegments = explode('%', $record->field_21); // Séparation initiale par %

                            foreach ($epreuveSegments as $segment) {
                                $segment = trim($segment); // Enlever les espaces inutiles
                                if ($segment) {
                                    $parts = explode(' ', $segment);
                                    $type = $parts[0]; // Le type est le premier élément
                                    $pourcentage = intval($parts[count($parts) - 1]); // Le pourcentage est le dernier élément

                                    $epreuve = new Epreuve();
                                    $epreuve->setMatiere($this->dermatier);
                                    $existingCodeEntity = $entityManager->getRepository(Codes::class)->findOneBy(['Nom' => strtolower($this->derunite->getNomunite()), 'nature' => ['TP', 'ECR']]);
                                    if ($existingCodeEntity) {
                                        $codeepr = $existingCodeEntity->getCode();
                                        $epreuve->setCodeTrouve('oui');
                                    } else {
                                        // Générer un nouveau code
                                        $codeepr =  $this->dermatier->getCodemat() . 'E' . $this->comptepreuve;
                                        $epreuve->setCodeTrouve('non');
                                        $this->nontrouve = true;
                                    }
                                    if (!$entityManager->getRepository(Epreuve::class)->findOneBy(["codeepreuve" => $codeepr]) && !in_array($codeepr, $epreuveTemp)) {
                                        $this->comptepreuve++;
                                        $epreuve->setCodeepreuve($codeepr);

                                        $epreuve->setPourcentage($pourcentage);
                                        $epreuve->setTypeepreuve($type);

                                        if ($record->field_22) {
                                            $epreuve->setDuree($record->field_22);
                                        }
                                        $epreuve->setNumchance(2);
                                        $entityManager->persist($epreuve);

                                        $element = $entityManager->getRepository(Element::class)->findOneBy(["codeelt" => $epreuve->getCodeepreuve()]);
                                        if($element){
                                            $element->setEpreuve($epreuve);
                                            $element->setType('epreuve');
                                            $entityManager->persist($element);
                                        } else{
                                            $element = new Element();
                                            $element->setEpreuve($epreuve);
                                            $element->setCodeelt($epreuve->getCodeepreuve());
                                            $element->setType('epreuve');
                                            $entityManager->persist($element);
                                        }
                                        $epreuveTemp[] = $codeepr;

                                    }
                                }
                            }
                        }
                        if ($record->field_27) {
                            $epreuveSegments = explode('%', $record->field_27); // Séparation initiale par %

                            foreach ($epreuveSegments as $segment) {
                                $segment = trim($segment); // Enlever les espaces inutiles
                                if ($segment) {
                                    $parts = explode(' ', $segment);
                                    $type = $parts[0]; // Le type est le premier élément
                                    $pourcentage = intval($parts[count($parts) - 1]); // Le pourcentage est le dernier élément

                                    $epreuve = new Epreuve();
                                    $epreuve->setMatiere($this->dermatier);
                                    $existingCodeEntity = $entityManager->getRepository(Codes::class)->findOneBy(['Nom' => strtolower($this->dermatier->getNommat() .' '. $this->dermatier->getPeriode()), 'nature' => ['CC', 'TP', 'ECR']]);
                                    if ($existingCodeEntity) {
                                        $codeepr = $existingCodeEntity->getCode();
                                        $epreuve->setCodeTrouve('oui');
                                    } else {
                                        // Générer un nouveau code
                                        $codeepr =  $this->dermatier->getCodemat() . 'E' . $this->comptepreuve;
                                        $epreuve->setCodeTrouve('non');
                                        $this->nontrouve = true;
                                    }
                                    if (!$entityManager->getRepository(Epreuve::class)->findOneBy(["codeepreuve" => $codeepr]) && !in_array($codeepr, $epreuveTemp)) {
                                        $this->comptepreuve++;
                                        $epreuve->setCodeepreuve($codeepr);

                                        $epreuve->setPourcentage($pourcentage);
                                        $epreuve->setTypeepreuve($type);

                                        if ($record->field_28) {
                                            $epreuve->setDuree($record->field_28);
                                        }
                                        $epreuve->setNumchance(1);
                                        $entityManager->persist($epreuve);

                                        $element = $entityManager->getRepository(Element::class)->findOneBy(["codeelt" => $epreuve->getCodeepreuve()]);
                                        if($element){
                                            $element->setEpreuve($epreuve);
                                            $element->setType('epreuve');
                                            $entityManager->persist($element);
                                        } else{
                                            $element = new Element();
                                            $element->setEpreuve($epreuve);
                                            $element->setCodeelt($epreuve->getCodeepreuve());
                                            $element->setType('epreuve');
                                            $entityManager->persist($element);
                                        }
                                        $epreuveTemp[] = $codeepr;

                                    }
                                }
                            }
                        }
                        if ($record->field_31) {
                            $epreuveSegments = explode('%', $record->field_31); // Séparation initiale par %

                            foreach ($epreuveSegments as $segment) {
                                $segment = trim($segment); // Enlever les espaces inutiles
                                if ($segment) {
                                    $parts = explode(' ', $segment);
                                    $type = $parts[0]; // Le type est le premier élément
                                    $pourcentage = intval($parts[count($parts) - 1]); // Le pourcentage est le dernier élément

                                    $epreuve = new Epreuve();
                                    $epreuve->setMatiere($this->dermatier);
                                    $existingCodeEntity = $entityManager->getRepository(Codes::class)->findOneBy(['Nom' => strtolower($this->dermatier->getNommat() .' '. $this->dermatier->getPeriode()), 'nature' => ['CC', 'TP', 'ECR']]);
                                    if ($existingCodeEntity) {
                                        $codeepr = $existingCodeEntity->getCode();
                                        $epreuve->setCodeTrouve('oui');
                                    } else {
                                        // Générer un nouveau code
                                        $codeepr =  $this->dermatier->getCodemat() . 'E' . $this->comptepreuve;
                                        $epreuve->setCodeTrouve('non');
                                        $this->nontrouve = true;
                                    }
                                    if (!$entityManager->getRepository(Epreuve::class)->findOneBy(["codeepreuve" => $codeepr]) && !in_array($codeepr, $epreuveTemp)) {
                                        $this->comptepreuve++;
                                        $epreuve->setCodeepreuve($codeepr);

                                        $epreuve->setPourcentage($pourcentage);
                                        $epreuve->setTypeepreuve($type);

                                        if ($record->field_32) {
                                            $epreuve->setDuree($record->field_32);
                                        }
                                        $epreuve->setNumchance(1);
                                        $entityManager->persist($epreuve);

                                        $element = $entityManager->getRepository(Element::class)->findOneBy(["codeelt" => $epreuve->getCodeepreuve()]);
                                        if($element){
                                            $element->setEpreuve($epreuve);
                                            $element->setType('epreuve');
                                            $entityManager->persist($element);
                                        } else{
                                            $element = new Element();
                                            $element->setEpreuve($epreuve);
                                            $element->setCodeelt($epreuve->getCodeepreuve());
                                            $element->setType('epreuve');
                                            $entityManager->persist($element);
                                        }
                                        $epreuveTemp[] = $codeepr;

                                    }
                                }
                            }
                        }
                        if ($record->field_35) {
                            $epreuveSegments = explode('%', $record->field_35); // Séparation initiale par %

                            foreach ($epreuveSegments as $segment) {
                                $segment = trim($segment); // Enlever les espaces inutiles
                                if ($segment) {
                                    $parts = explode(' ', $segment);
                                    $type = $parts[0]; // Le type est le premier élément
                                    $pourcentage = intval($parts[count($parts) - 1]); // Le pourcentage est le dernier élément

                                    $epreuve = new Epreuve();
                                    $epreuve->setMatiere($this->dermatier);
                                    $existingCodeEntity = $entityManager->getRepository(Codes::class)->findOneBy(['Nom' => strtolower($this->dermatier->getNommat() .' '. $this->dermatier->getPeriode()), 'nature' => ['CC', 'TP', 'ECR']]);
                                    if ($existingCodeEntity) {
                                        $codeepr = $existingCodeEntity->getCode();
                                        $epreuve->setCodeTrouve('oui');
                                    } else {
                                        // Générer un nouveau code
                                        $codeepr =  $this->dermatier->getCodemat() . 'E' . $this->comptepreuve;
                                        $epreuve->setCodeTrouve('non');
                                        $this->nontrouve = true;
                                    }
                                    if (!$entityManager->getRepository(Epreuve::class)->findOneBy(["codeepreuve" => $codeepr]) && !in_array($codeepr, $epreuveTemp)) {
                                        $this->comptepreuve++;
                                        $epreuve->setCodeepreuve($codeepr);

                                        $epreuve->setPourcentage($pourcentage);
                                        $epreuve->setTypeepreuve($type);

                                        if ($record->field_36) {
                                            $epreuve->setDuree($record->field_36);
                                        }
                                        $epreuve->setNumchance(1);
                                        $entityManager->persist($epreuve);

                                        $element = $entityManager->getRepository(Element::class)->findOneBy(["codeelt" => $epreuve->getCodeepreuve()]);
                                        if($element){
                                            $element->setEpreuve($epreuve);
                                            $element->setType('epreuve');
                                            $entityManager->persist($element);
                                        } else{
                                            $element = new Element();
                                            $element->setEpreuve($epreuve);
                                            $element->setCodeelt($epreuve->getCodeepreuve());
                                            $element->setType('epreuve');
                                            $entityManager->persist($element);
                                        }
                                        $epreuveTemp[] = $codeepr;

                                    }
                                }
                            }
                        }
                        if ($record->field_39) {

                            $epreuveSegments = explode('%', $record->field_39); // Séparation initiale par %

                            foreach ($epreuveSegments as $segment) {
                                $segment = trim($segment); // Enlever les espaces inutiles
                                if ($segment) {
                                    $parts = explode(' ', $segment);
                                    $type = $parts[0]; // Le type est le premier élément
                                    $pourcentage = intval($parts[count($parts) - 1]); // Le pourcentage est le dernier élément

                                    $epreuve = new Epreuve();
                                    $epreuve->setMatiere($this->dermatier);
                                    $existingCodeEntity = $entityManager->getRepository(Codes::class)->findOneBy(['Nom' => strtolower($this->derunite->getNomunite()), 'nature' => ['TP', 'ECR']]);
                                    if ($existingCodeEntity) {
                                        $codeepr = $existingCodeEntity->getCode();
                                        $epreuve->setCodeTrouve('oui');
                                    } else {
                                        // Générer un nouveau code
                                        $codeepr =  $this->dermatier->getCodemat() . 'E' . $this->comptepreuve;
                                        $epreuve->setCodeTrouve('non');
                                        $this->nontrouve = true;
                                    }
                                    if (!$entityManager->getRepository(Epreuve::class)->findOneBy(["codeepreuve" => $codeepr]) && !in_array($codeepr, $epreuveTemp)) {
                                        $this->comptepreuve++;
                                        $epreuve->setCodeepreuve($codeepr);

                                        $epreuve->setPourcentage($pourcentage);
                                        $epreuve->setTypeepreuve($type);

                                        if ($record->field_40) {
                                            $epreuve->setDuree($record->field_40);
                                        }
                                        $epreuve->setNumchance(2);
                                        $entityManager->persist($epreuve);

                                        $element = $entityManager->getRepository(Element::class)->findOneBy(["codeelt" => $epreuve->getCodeepreuve()]);
                                        if($element){
                                            $element->setEpreuve($epreuve);
                                            $element->setType('epreuve');
                                            $entityManager->persist($element);
                                        } else{
                                            $element = new Element();
                                            $element->setEpreuve($epreuve);
                                            $element->setCodeelt($epreuve->getCodeepreuve());
                                            $element->setType('epreuve');
                                            $entityManager->persist($element);
                                        }
                                        $epreuveTemp[] = $codeepr;

                                    }
                                    
                                }
                            }
                        }
                        break;
                    default:
                        break;
                }
            }
            $entityManager->flush();
            $em->flush();
            $codeRepository = $em->getRepository(Codes::class);

            $codeRepository->deleteAll();
            if($this->nontrouve){
                return $this->redirectToRoute('app_code_non_trouve', ['codefiliere' => $this->derfiliere->getCodefiliere()]);
            }else{
                return $this->redirectToRoute('app_maquette');
            }
            
        }


        return $this->render('maquette/import.html.twig', [
            'form' => $form->createView(),
            'filieres' => $filieres,
        ]);
    }

    #[Route('/maquette/importer_codes', name: 'codes_importer')]
    public function importcodeXML(Request $request, EtudiantRepository $etudiantRepository, EntityManagerInterface $em, managerRegistry $doctrine): Response
    {
        $filieres = $em->getRepository(Filiere::class)->findAll();


        $entityManager = $doctrine->getManager();
        $validator = Validation::createValidator();
        $form = $this->createFormBuilder()
            ->add('xml_file', FileType::class, [
                'label' => 'Fichier XML',
                'attr' => [
                    'accept' => '.xml',
                    'multiple' => false,
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'bottom',
                ],
                'help' => 'Cliquez ici pour obtenir de l\'aide sur le format du fichier xml.',
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $xmlFile = $form->get('xml_file')->getData();

            $content = file_get_contents(($xmlFile));

            $xml = simplexml_load_string(($content));

            $compteurMatiere = 1;
            foreach($xml->record as $record){
                $nom = $record->field_0;
                $code = $record->field_1;
                $nature = $record->field_2;
                $nom = strtolower($nom);

                if ($nature == 'SOCL' || $nature == 'PAR') {
                    $nom = str_replace(['parcours', 'filiere'], '', $nom);
                    $nom = trim($nom);

                    $existingCode = $em->getRepository(Codes::class)->findOneBy([
                        'Nom' => $nom,
                        'nature' => $nature,
                        'code' => $code
                    ]);
            
                    if (!$existingCode) {
                        $codeEntity = new Codes();
                        $codeEntity->setNom($nom);
                        $codeEntity->setNature($nature);
                        $codeEntity->setCode($code);
                        $em->persist($codeEntity);
                        $em->flush();
                    }
                } elseif ($nature == 'BLCT' || $nature == 'BLOC') {
                    if (strpos($nom, 'transversaux') !== false) {
                        $nom = 'transversaux';
                    }
                    $existingCode = $em->getRepository(Codes::class)->findOneBy([
                        'Nom' => $nom,
                        'nature' => $nature,
                        'code' => $code
                    ]);
            
                    if (!$existingCode) {
                        $codeEntity = new Codes();
                        $codeEntity->setNom($nom);
                        $codeEntity->setNature($nature);
                        $codeEntity->setCode($code);
                        $em->persist($codeEntity);
                        $em->flush();
                        $nouvnom = str_replace('bloc ', '', $nom);
                        if($nouvnom != $nom){
                            $codeEntity = new Codes();
                            $codeEntity->setNom($nouvnom);
                            $codeEntity->setNature($nature);
                            $codeEntity->setCode($code);
                            $em->persist($codeEntity);
                            $em->flush();
                        }
                    }
                } elseif ($nature == 'UE') {
                    $nom = explode('-', $nom)[0];
                    $nom = str_replace('*', '', $nom);
                    $nom = trim($nom);
                    $compteurMatiere = 1;
                    $existingCode = $em->getRepository(Codes::class)->findOneBy([
                        'Nom' => $nom,
                        'nature' => $nature,
                        'code' => $code
                    ]);
            
                    if (!$existingCode) {
                        $codeEntity = new Codes();
                        $codeEntity->setNom($nom);
                        $codeEntity->setNature($nature);
                        $codeEntity->setCode($code);
                        $em->persist($codeEntity);
                        $em->flush();
                        $this->choix = false;
                    }
                    
                }elseif ($nature == 'CHOI') {
                    $this->choix = true;
                } elseif ($nature == 'CC' || $nature == 'TP' || $nature == 'ECR') {
                    if($this->choix == false){
                        $nomArray = explode('-', $nom);
                        $nom = $nomArray[0] . $nomArray[1];
                        $nom = str_replace('*', '', $nom);
                        $nom = trim($nom);
                        $nom = substr($nom, 3);
                        $existingCode = $em->getRepository(Codes::class)->findOneBy([
                            'Nom' => $nom,
                            'nature' => $nature,
                            'code' => $code
                        ]);
                
                        if (!$existingCode) {
                            $codeEntity = new Codes();
                            $codeEntity->setNom($nom);
                            $codeEntity->setNature($nature);
                            $codeEntity->setCode($code);
                            $em->persist($codeEntity);
                            $code = substr($code, 0, -2);
                            $matiere = new Codes();
                            $matiere->setNom($nom);
                            $matiere->setNature('MAT'); 
                            $matiere->setCode($code . 'M' . $compteurMatiere); 
                            $em->persist($matiere);
                            $compteurMatiere++;
                            $em->flush();
                        }
                    }else{
                        $nom = explode('-', $nom)[0];
                        $nom = str_replace('*', '', $nom);
                        //$nom = substr($nom, 0, -3);
                        $nom = substr($nom, 3);
                        $existingCode = $em->getRepository(Codes::class)->findOneBy([
                            'Nom' => $nom,
                            'nature' => $nature,
                            'code' => $code
                        ]);
                
                        if (!$existingCode) {
                            $codeEntity = new Codes();
                            $codeEntity->setNom($nom);
                            $codeEntity->setNature($nature);
                            $codeEntity->setCode($code);
                            $em->persist($codeEntity);
                            $em->flush();
                        }
                    }
                }


                

            }
            $entityManager = $doctrine->getManager();
            $filieres = $entityManager->getRepository(Filiere::class)->findAll();


            return $this->redirectToRoute('app_maquette');
        }


        return $this->render('maquette/importCode.html.twig', [
            'form' => $form->createView(),
            'filieres' => $filieres,
        ]);
    }
}
