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

                        $this->comptbloc = 1;
                        $existingCodeEntity = $entityManager->getRepository(Codes::class)->findOneBy(['Nom' => $field_0, 'nature' => ['SOCL', 'PAR']]);
                        if ($existingCodeEntity) {
                            $codeFiliere = $existingCodeEntity->getCode();
                        } else {
                            // Générer un nouveau code
                            $codeFiliere = 'T' . substr($field_0, 0, 2) . $anne;
                        }
                        //substr($field_0, 0, 2)
                        if ($entityManager->getRepository(Filiere::class)->findOneBy(["codefiliere" => $codeFiliere])) {
                            $this->comptfiliere++;
                            break;
                        }
                        $filiere = new Filiere();
                        $filiere->setCodefiliere($codeFiliere);
                        $filiere->setNomfiliere($field_0);

                        $entityManager->persist($filiere);
                        $element = new Element();
                        $element->setFiliere($filiere);
                        $element->setCodeelt($filiere->getCodefiliere());
                        $em->persist($element);
                        $em->flush();

                        $this->derfiliere = $filiere;
                        break;
                    case 'BLCT':
                        $this->comptunite = 1;
                        $existingCodeEntity = $entityManager->getRepository(Codes::class)->findOneBy(['Nom' => $field_0, 'nature' => ['BLOC', 'BLCT']]);
                        if ($existingCodeEntity) {
                            $codeBloc = $existingCodeEntity->getCode();
                        } else {
                            // Générer un nouveau code
                            $codeBloc = $this->derfiliere->getCodefiliere() . 'B' . $this->comptbloc;
                        }
                        $this->comptbloc++;
                        $bloc = new Bloc();
                        $bloc->setCodebloc($codeBloc);
                        $bloc->setNombloc($field_0);
                        $bloc->setNoteplancher(filter_var($field_45, FILTER_SANITIZE_NUMBER_INT));
                        $bloc->setFiliere($this->derfiliere);
                        $entityManager->flush();

                        $entityManager->persist($bloc);
                        $element = new Element();
                        $element->setBloc($bloc);
                        $element->setCodeelt($bloc->getCodebloc());
                        $entityManager->persist($element);
                        $this->derbloc = $bloc;
                        break;
                    case 'UE':
                        $this->comptmatiere = 1;
                        if (!$this->derbloc) {
                            $this->comptunite = 1;
                            $existingCodeEntity = $entityManager->getRepository(Codes::class)->findOneBy(['Nom' => 'Transversaux', 'nature' => ['BLOC', 'BLCT']]);
                            if ($existingCodeEntity) {
                                $codebloc = $existingCodeEntity->getCode();
                            } else {
                                // Générer un nouveau code
                                $codebloc = $this->derfiliere->getCodefiliere() . 'B' . $this->comptbloc;
                            }
                            $this->comptbloc++;
                            $bloc = new Bloc();
                            $bloc->setCodebloc($codebloc);
                            $bloc->setNombloc('Transversaux');
                            $bloc->setFiliere($this->derfiliere);
                            $entityManager->persist($bloc);
                            $element = new Element();
                            $element->setBloc($bloc);
                            $element->setCodeelt($bloc->getCodebloc());
                            $entityManager->persist($element);
                            $this->derbloc = $bloc;
                        }
                        $codebloc = $this->derbloc->getCodebloc();
                        $existingCodeEntity = $entityManager->getRepository(Codes::class)->findOneBy(['Nom' => $field_0, 'nature' => $field_1]);
                        if ($existingCodeEntity) {
                            $codeUnite = $existingCodeEntity->getCode();
                        } else {
                            // Générer un nouveau code
                            $codeUnite = $this->derbloc->getCodebloc() . 'U' . $this->comptunite;
                        }
                        $this->comptunite++;
                        $unite = new Unite();
                        $unite->setCodeunite($codeUnite);
                        $unite->setNomunite($field_0);
                        $unite->setCoeficient((int)$field_12);
                        $unite->setBloc($this->derbloc);

                        $entityManager->persist($unite);
                        $element = new Element();
                        $element->setUnite($unite);
                        $element->setCodeelt($unite->getCodeunite());
                        $entityManager->persist($element);
                        $this->derunite = $unite;
                        break;
                    case 'MATI':
                        $this->comptepreuve = 1;
                        $parts = explode(' P', $field_0);
                        $nomMatiere = $parts[0];
                        $periode = isset($parts[1]) ? 'P' . substr($parts[1], 0, 2) : 'P1';
                        $codeMatiere = $this->derunite->getCodeunite() . 'M' . $this->comptmatiere;
                        $existingCodeEntity = $entityManager->getRepository(Codes::class)->findOneBy(['Nom' => $nomMatiere .' '. $periode, 'nature' => 'MAT']);
                        if ($existingCodeEntity) {
                            $codeMatiere = $existingCodeEntity->getCode();
                        } else {
                            // Générer un nouveau code
                            $codeMatiere = $this->derunite->getCodeunite() . 'M' . $this->comptmatiere;
                        }
                        $this->comptmatiere = $this->comptmatiere + 1;
                        $matiere = new Matiere();
                        $matiere->setCodemat($codeMatiere);
                        $matiere->setNommat($nomMatiere);
                        $matiere->setPeriode($periode);
                        $entityManager->persist($matiere);
                        $element = new Element();
                        $element->setMatiere($matiere);
                        $element->setCodeelt($matiere->getCodemat());
                        $entityManager->persist($element);
                        $matiere->setUnite($this->derunite);
                        $this->dermatier = $matiere;
                        if ($record->field_13) {
                            $epreuve = new Epreuve();
                            $epreuve->setMatiere($this->dermatier);
                            $parts = explode(' ', $record->field_13);
                            $existingCodeEntity = $entityManager->getRepository(Codes::class)->findOneBy(['Nom' => $this->dermatier->getNommat() .' '. $this->dermatier->getPeriode(), 'nature' => $parts[0]]);
                            if ($existingCodeEntity) {
                                $codeepr = $existingCodeEntity->getCode();
                            } else {
                                // Générer un nouveau code
                                $codeepr =  $this->dermatier->getCodemat() . 'E' . $this->comptepreuve;
                            }
                            $this->comptepreuve++;
                            $epreuve->setCodeepreuve($codeepr);
                            $epreuve->setPourcentage(filter_var($record->field_13, FILTER_SANITIZE_NUMBER_INT));
                            $epreuve->setTypeepreuve($parts[0]);
                            if ($record->field_14) {
                                $epreuve->setDuree($record->field_14);
                            }
                            $epreuve->setNumchance(1);
                            $entityManager->persist($epreuve);
                            $element = new Element();
                            $element->setEpreuve($epreuve);
                            $element->setCodeelt($epreuve->getCodeepreuve());
                            $entityManager->persist($element);
                        }
                        if ($record->field_17) {
                            $epreuve = new Epreuve();
                            $epreuve->setMatiere($this->dermatier);
                            $parts = explode(' ', $record->field_17);
                            $existingCodeEntity = $entityManager->getRepository(Codes::class)->findOneBy(['Nom' => $this->dermatier->getNommat() .' '. $this->dermatier->getPeriode(), 'nature' => $parts[0]]);
                            if ($existingCodeEntity) {
                                $codeepr = $existingCodeEntity->getCode();
                            } else {
                                // Générer un nouveau code
                                $codeepr =  $this->dermatier->getCodemat() . 'E' . $this->comptepreuve;
                            }
                            $this->comptepreuve++;
                            $epreuve->setCodeepreuve($codeepr);
                            $epreuve->setPourcentage(filter_var($record->field_17, FILTER_SANITIZE_NUMBER_INT));
                            $epreuve->setTypeepreuve($parts[0]);
                            if ($record->field_18) {
                                $epreuve->setDuree($record->field_18);
                            }
                            $epreuve->setNumchance(1);
                            $entityManager->persist($epreuve);
                            $element = new Element();
                            $element->setEpreuve($epreuve);
                            $element->setCodeelt($epreuve->getCodeepreuve());
                            $entityManager->persist($element);
                        }
                        if ($record->field_21) {
                            $epreuve = new Epreuve();
                            $parts = explode(' ', $record->field_21);
                            $epreuve->setMatiere($this->dermatier);
                            $existingCodeEntity = $entityManager->getRepository(Codes::class)->findOneBy(['Nom' => $this->derunite->getNomunite(), 'nature' => $parts[0]]);
                            if ($existingCodeEntity) {
                                $codeepr = $existingCodeEntity->getCode();
                            } else {
                                // Générer un nouveau code
                                $codeepr =  $this->dermatier->getCodemat() . 'E' . $this->comptepreuve;
                            }
                            $this->comptepreuve++;
                            $epreuve->setCodeepreuve($codeepr);
                            $epreuve->setPourcentage(filter_var($record->field_21, FILTER_SANITIZE_NUMBER_INT));
                            
                            $epreuve->setTypeepreuve($parts[0]);
                            if ($record->field_22) {
                                $epreuve->setDuree($record->field_22);
                            }
                            $epreuve->setNumchance(2);
                            $entityManager->persist($epreuve);
                            $element = new Element();
                            $element->setEpreuve($epreuve);
                            $element->setCodeelt($epreuve->getCodeepreuve());
                            $entityManager->persist($element);
                        }
                        if ($record->field_27) {
                            $epreuve = new Epreuve();
                            $epreuve->setMatiere($this->dermatier);
                            $parts = explode(' ', $record->field_27);
                            $existingCodeEntity = $entityManager->getRepository(Codes::class)->findOneBy(['Nom' => $this->dermatier->getNommat() .' '. $this->dermatier->getPeriode(), 'nature' => $parts[0]]);
                            if ($existingCodeEntity) {
                                $codeepr = $existingCodeEntity->getCode();
                            } else {
                                // Générer un nouveau code
                                $codeepr =  $this->dermatier->getCodemat() . 'E' . $this->comptepreuve;
                            }
                            $this->comptepreuve++;
                            $epreuve->setCodeepreuve($codeepr);
                            $pourcentage = filter_var($record->field_27, FILTER_SANITIZE_NUMBER_INT);
                            if ($pourcentage) {
                                $epreuve->setPourcentage($pourcentage);
                            }
                            $epreuve->setTypeepreuve($parts[0]);
                            if ($record->field_28) {
                                $epreuve->setDuree($record->field_28);
                            }
                            $epreuve->setNumchance(1);
                            $entityManager->persist($epreuve);
                            $element = new Element();
                            $element->setEpreuve($epreuve);
                            $element->setCodeelt($epreuve->getCodeepreuve());
                            $entityManager->persist($element);
                        }
                        if ($record->field_31) {
                            $epreuve = new Epreuve();
                            $epreuve->setMatiere($this->dermatier);
                            $parts = explode(' ', $record->field_31);
                            $existingCodeEntity = $entityManager->getRepository(Codes::class)->findOneBy(['Nom' => $this->dermatier->getNommat() .' '. $this->dermatier->getPeriode(), 'nature' => $parts[0]]);
                            if ($existingCodeEntity) {
                                $codeepr = $existingCodeEntity->getCode();
                            } else {
                                // Générer un nouveau code
                                $codeepr =  $this->dermatier->getCodemat() . 'E' . $this->comptepreuve;
                            }
                            $this->comptepreuve++;
                            $epreuve->setCodeepreuve($codeepr);
                            $pourcentage = filter_var($record->field_31, FILTER_SANITIZE_NUMBER_INT);
                            if ($pourcentage) {
                                $epreuve->setPourcentage($pourcentage);
                            }
                            $epreuve->setTypeepreuve($parts[0]);
                            if ($record->field_32) {
                                $epreuve->setDuree($record->field_32);
                            }
                            $epreuve->setNumchance(1);
                            $entityManager->persist($epreuve);
                            $element = new Element();
                            $element->setEpreuve($epreuve);
                            $element->setCodeelt($epreuve->getCodeepreuve());
                            $entityManager->persist($element);
                        }
                        if ($record->field_35) {
                            $epreuve = new Epreuve();
                            $epreuve->setMatiere($this->dermatier);
                            $parts = explode(' ', $record->field_35);
                            $existingCodeEntity = $entityManager->getRepository(Codes::class)->findOneBy(['Nom' => $this->dermatier->getNommat() .' '. $this->dermatier->getPeriode(), 'nature' => $parts[0]]);
                            if ($existingCodeEntity) {
                                $codeepr = $existingCodeEntity->getCode();
                            } else {
                                // Générer un nouveau code
                                $codeepr =  $this->dermatier->getCodemat() . 'E' . $this->comptepreuve;
                            }
                            $this->comptepreuve++;
                            $epreuve->setCodeepreuve($codeepr);
                            $pourcentage = filter_var($record->field_35, FILTER_SANITIZE_NUMBER_INT);
                            if ($pourcentage) {
                                $epreuve->setPourcentage($pourcentage);
                            }
                            $epreuve->setTypeepreuve($parts[0]);
                            if ($record->field_36) {
                                $epreuve->setDuree($record->field_36);
                            }
                            $epreuve->setNumchance(1);
                            $entityManager->persist($epreuve);
                            $element = new Element();
                            $element->setEpreuve($epreuve);
                            $element->setCodeelt($epreuve->getCodeepreuve());
                            $entityManager->persist($element);
                        }
                        if ($record->field_39) {
                            $epreuve = new Epreuve();
                            $parts = explode(' ', $record->field_39);
                            $epreuve->setMatiere($this->dermatier);
                            $existingCodeEntity = $entityManager->getRepository(Codes::class)->findOneBy(['Nom' => $this->derunite->getNomunite(), 'nature' => $parts[0]]);
                            if ($existingCodeEntity) {
                                $codeepr = $existingCodeEntity->getCode();
                            } else {
                                // Générer un nouveau code
                                $codeepr =  $this->dermatier->getCodemat() . 'E' . $this->comptepreuve;
                            }
                            $this->comptepreuve++;
                            $epreuve->setCodeepreuve($codeepr);



                            $pourcentagestring = filter_var($record->field_39, FILTER_SANITIZE_NUMBER_INT);
                            $pourcnetage = intval($pourcentagestring);
                            if ($pourcentage) {
                                $epreuve->setPourcentage($pourcentage);
                            }
                            $epreuve->setTypeepreuve($parts[0]);
                            if ($record->field_40) {
                                $epreuve->setDuree($record->field_40);
                            }
                            $epreuve->setNumchance(2);
                            $entityManager->persist($epreuve);
                            $element = new Element();
                            $element->setEpreuve($epreuve);
                            $element->setCodeelt($epreuve->getCodeepreuve());
                            $entityManager->persist($element);
                        }
                        break;
                    default:
                        break;
                }
            }
            $entityManager->flush();
            $em->flush();

            return $this->redirectToRoute('app_maquette');
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

                if ($nature == 'SOCL' || $nature == 'PAR') {
                    $nom = str_replace(['Parcours', 'filiere'], '', $nom);
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
                    if (strpos($nom, 'Transversaux') !== false) {
                        $nom = 'Transversaux';
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
                    }
                    
                } elseif ($nature == 'CC' || $nature == 'TP') {
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
                } elseif ($nature == 'ECR') {
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


        return $this->render('maquette/importCode.html.twig', [
            'form' => $form->createView(),
            'filieres' => $filieres,
        ]);
    }
}
