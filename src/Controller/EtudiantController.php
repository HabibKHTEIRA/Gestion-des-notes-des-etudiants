<?php

namespace App\Controller;

use App\Entity\AnneeUniversitaire;
use App\Entity\Bac;
use App\Entity\Bloc;
use App\Entity\Filiere;
use App\Entity\Element;
use App\Entity\Epreuve;
use App\Entity\Etudiant;
use App\Entity\Matiere;
use App\Entity\Resultatbac;
use App\Entity\FormationInt;
use App\Entity\Note;
use App\Entity\Unite;
use App\Form\NoteType;
use App\Repository\EtudiantRepository;
use App\Repository\NoteRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\Constraint\FileExists;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Validator\Constraints\Date;


class EtudiantController extends AbstractController
{
    /*private $entityManager;
    function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager=$entityManager;
    }*/
    private $codeep;
    private $insertionReussie = 0;
    private $insertionNonReussie = [];
    private $elt = 'TINL2B4U4M1E1';
    private $session = 0;

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('etudiant', name: 'app_etudiant')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        
        $filieres = $em->getRepository(Filiere::class)->findAll();
        return $this->render('etudiant/index.html.twig', [
            'controller_name' => 'EtudiantController',
            'filieres' => $filieres,
        ]);
    }

    #[Route('/etudiant/information', name: 'etudiant_information')]
    public function etudiant_information(Request $request, EtudiantRepository $etudiantRepository, EntityManagerInterface $em, managerRegistry $doctrine): Response
    {
        $filieres = $em->getRepository(Filiere::class)->findAll();
        $entityManager = $doctrine->getManager();
        $validator = Validation::createValidator();
        $form = $this->createFormBuilder()
            ->add('csv_file', FileType::class, [
                'label' => 'Fichier CSV',
                'attr' => [
                    'accept' => '.csv',
                    'multiple' => false,
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'bottom',
                ],
                'help' => 'Cliquez ici pour obtenir de l\'aide sur le format du fichier CSV.',
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $csvFile = $form->get('csv_file')->getData();

            $file = fopen($csvFile->getPathname(), 'r');
            $csvData = [];

            $headers = fgetcsv($file);

            while (($data = fgetcsv($file)) !== false) {
                $rowData = [];
                foreach ($headers as $index => $header) {
                    $rowData[$header] = $data[$index] ?? null;
                }
                $csvData[] = $rowData;
            }

            fclose($file);

            foreach ($csvData as $record) {
                if (!empty($record['numetd'])) {
                    $etudiant = $entityManager->getRepository(Etudiant::class)->findOneBy(["numetd" => $record['numetd']]);

                    if (!$etudiant) {
                        $etudiant = new Etudiant();
                    }

                    $etudiant->setNumetd($record['numetd']);
                    $etudiant->setPrenom($record['prenom']);
                    $etudiant->setNom($record['nom']);
                    $etudiant->setSexe($record['sexe']);
                    $etudiant->setEmail($record['email']);
                    $etudiant->setVillnaiss($record['villnaiss']);
                    $etudiant->setDepnaiss($record['depnaiss']);
                    $etudiant->setNationalite($record['nationalite']);
                    $etudiant->setTel($record['tel']);
                    $etudiant->setDerdiplome($record['derdiplome']);
                    $etudiant->setRegistre($record['registre']);
                    $etudiant->setStatut($record['statut']);
                    $etudiant->setSports($record['sports']);
                    $etudiant->setHandicape($record['handicape']);

                    // Vérifier et ajouter le type de bac
                    $typeBac = $record['Type_bac'];
                    $bac = $entityManager->getRepository(Bac::class)->findOneBy(['typebac' => $typeBac]);

                    if (!$bac) {
                        $bac = new Bac();
                        $bac->setTypebac($typeBac);
                        $entityManager->persist($bac);

                        
                    }

                    $resultbac = New Resultatbac();
                    $resultbac->setBac($bac);
                    $resultbac->setEtudiant($etudiant);
                    $resultbac->setAnneebac($record['anneebac']);
                    $resultbac->setMention($record['mention']);
                    $resultbac->setMoyennebac($record['moyennebac']);
                    $entityManager->presist($resultbac);

                    $filiere = $entityManager->getRepository(Filiere::class)->findOneBy(['nomfiliere' => $record['filiere']]);
                    if($filiere){
                        $formationInt = New FormationInt();
                        $formationInt->setEtudiant($etudiant);
                        $formationInt->setFiliere($filiere);
                        $entityManager->presist($filiere);
                    }

                    // Ajouter les spécialités
                    /*$specialites = $entityManager->getRepository(Specialite::class);

                    foreach (['Spé-terminale Ecologie', 'Spé-terminale Maths', 'Spé-terminale Numérique', 'Spé-terminale Physique Chimie', 'Spé-terminale SVT', 'Spé-terminale Sc Eco', 'Spé-terminale Sc. Ingénieur', 'Spé-terminale Autre'] as $specialiteName) {
                        if ($record[$specialiteName] === 'Oui') {
                            $specialite = $specialites->findOneBy(['nom' => $specialiteName]);

                            if (!$specialite) {
                                $specialite = new Specialite();
                                $specialite->setNom($specialiteName);
                                $entityManager->persist($specialite);
                            }

                            $etudiant->addSpecialite($specialite);
                        }
                    }

                    // Vérifier si l'étudiant est redoublant
                    /*if ($record['Si non Redoublant'] !== 'non' && $record['Si non Redoublant'] !== '') {
                        $etudiant->setRedoublant(true);
                    }*/

                    // Validation et enregistrement de l'étudiant
                    $errors = $validator->validate($etudiant);

                    if (count($errors) > 0) {
                        foreach ($errors as $error) {
                            echo $error->getMessage() . "\n";
                        }
                    } else {
                        $entityManager->persist($etudiant);
                        $entityManager->flush();
                        $this->insertionReussie++;
                    }
                }
            }

            // Redirection après traitement
            return $this->redirectToRoute('etudiant_visualisation');
        }

        return $this->render('etudiant/info.html.twig', [
            'form' => $form->createView(),
            'insertionReussie' => $this->insertionReussie,
            'filieres' => $filieres,
            // Passer le formulaire à la vue Twig
        ]);
    }


    #[Route('etudiant/note/insertion_mail', name: 'insertion_mail')]
    public function insertion_mail(Request $request, EntityManagerInterface $em)
    {
        $validator = Validation::createValidator();
        $filieres = $em->getRepository(Filiere::class)->findAll();

        $form = $this->createFormBuilder()
            ->add('email_data', TextareaType::class, [
                'label' => 'Données reçues par email',
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'rows' => 10,
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ Données reçues par email ne peut pas être vide.',
                    ])
                ],
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->get('email_data')->getData();

            $pattern = '/Des notes ont été saisies par l\'utilisateur :\s*(?P<user>.+)\s*Elles concernent l\'élément ou l\'épreuve :\s*(?P<element>.+)\s*Pour l\'exercice\s*(?P<year>\d{4})\s*et la session\s*(?P<session>\d+)\s*(Commentaire enseignant :\s*(?P<comment>.+))?\s*===========================\s*(?P<students>.+)/s';
            $matches = [];
            preg_match($pattern, $data, $matches);

            if (isset($matches['user'], $matches['element'], $matches['year'], $matches['session'], $matches['students'])) {
                $user = $matches['user'];
                $elementCode = trim($matches['element']);
                $year = intval($matches['year']);
                $session = $matches['session'];
                $students = $matches['students'];
                $annee = $em->getRepository(AnneeUniversitaire::class)->findOneBy(['annee' => $year]);

                $pattern = '/(\d+)\s*-\s*(.+?)\s*-\s*(\d+\.\d+)/';
                preg_match_all($pattern, $students, $matches, PREG_SET_ORDER);

                if ($session === 2) {
                    foreach ($matches as $match) {
                        $matricule = $match[1];
                        $nomPrenom = $match[2];
                        $noteetud = floatval($match[3]);
                        $element = $em->getRepository(Element::class)->findOneBy(['codeelt' => $elementCode]);
                        $etudiant = $em->getRepository(Etudiant::class)->findOneBy(['numetd' => $matricule]);

                        if (!$etudiant) {
                            $this->logger->error('L\'étudiant avec le numéro {numero} n\'existe pas dans la base de données.', [
                                'numero' => $matricule,
                            ]);
                            dd("une erreur --->" . $matricule);
                        }

                        if (!$element) {
                            $this->logger->error('L\'élément avec le code {code} n\'existe pas dans la base de données.', [
                                'code' => $elementCode,
                            ]);
                            dd("erreur cet element : {elementcode} n'est pas present dans la table element ", ['elementcode' => $elementCode]);
                        }

                        $existingNote = $em->getRepository(Note::class)->findOneBy([
                            'etudiant' => $etudiant,
                            'element' => $element,
                            'anneeuniversitaire' => $annee,
                        ]);

                        if ($existingNote) {
                            $this->logger->info('La note pour l\'étudiant {etudiant}, l\'élément {element} et l\'année universitaire {annee} existe déjà.', [
                                'etudiant' => $etudiant->getNumetd(),
                                'element' => $elementCode,
                                'annee' => $year,
                            ]);
                            $existingNote->setNote($noteetud);
                        } else {
                            $note = new Note();
                            $note->setNote($noteetud);
                            $note->setEtudiant($etudiant);
                            $note->setElement($element);
                            $note->setAnneeuniversitaire($annee);

                            $errors = $validator->validate($note);

                            if (
                                count($errors) > 0
                            ) {
                                // Gérer les erreurs de validation pour l'entité
                                dd('stop');
                            } else {
                                $em->persist($note);
                                $em->flush();
                            }

                            $matiere = $element->getEpreuve()->getMatiere();
                            $unite = $matiere->getUnite();
                            $noteUnite = $em->getRepository(Note::class)->findOneBy([
                                "etudiant" => $etudiant,
                                "element" => $unite->getElement()
                            ]);

                            if ($noteUnite->getNote() < $noteetud) {
                                $noteUnite->setNote($noteetud);
                                $em->flush();

                                $bloc = $unite->getBloc();

                                if ($bloc) {
                                    $unites = $em->getRepository(Unite::class)->findBy([
                                        'bloc' => $bloc,
                                    ]);

                                    $totalPercentage = 0;
                                    $Score = 0;

                                    foreach ($unites as $uni) {
                                        $noteUnite = $em->getRepository(Note::class)->findOneBy([
                                            'etudiant' => $etudiant,
                                            'element' => $uni->getElement(),
                                        ]);

                                        if ($noteUnite) {
                                            $percentage = $uni->getCoeficient();
                                            $totalPercentage += $percentage;
                                            $Score += $noteUnite->getNote() * $percentage;
                                        }
                                    }

                                    if ($totalPercentage > 0) {
                                        $finalNote = $Score / $totalPercentage;
                                        $existingNote = $em->getRepository(Note::class)->findOneBy([
                                            'etudiant' => $etudiant,
                                            'element' => $bloc->getElement(),
                                            'anneeuniversitaire' => $annee,
                                        ]);

                                        if ($existingNote) {
                                            $existingNote->setNote($finalNote);
                                            $em->flush();
                                        } else {
                                            $note = new Note;
                                            $note->setNote($finalNote);
                                            $note->setEtudiant($etudiant);
                                            $note->setElement($bloc->getElement());
                                            $note->setAnneeuniversitaire($annee);
                                            $em->persist($note);
                                            $em->flush();
                                        }


                                        $filiere = $bloc->getFiliere();

                                        if ($filiere) {
                                            $blocs = $em->getRepository(Bloc::class)->findBy([
                                                'filiere' => $filiere,
                                            ]);

                                            $totalPercentage = 0;
                                            $Score = 0;

                                            foreach ($blocs as $blc) {
                                                $noteBloc = $em->getRepository(Note::class)->findOneBy([
                                                    'etudiant' => $etudiant,
                                                    'element' => $blc->getElement(),
                                                ]);

                                                if ($noteBloc) {
                                                    $bloc_unites = $blc->getUnites();
                                                    $sommeCoefficients = 0;

                                                    foreach ($bloc_unites as $un) {
                                                        $sommeCoefficients += $unite->getCoeficient();
                                                    }
                                                    $percentage = $sommeCoefficients;
                                                    $totalPercentage += $percentage;
                                                    $Score += $noteBloc->getNote() * $percentage;
                                                }
                                            }

                                            if ($totalPercentage > 0) {
                                                $finalNote = $Score / $totalPercentage;
                                                $existingNote = $em->getRepository(Note::class)->findOneBy([
                                                    'etudiant' => $etudiant,
                                                    'element' => $filiere->getElement(),
                                                    'anneeuniversitaire' => $annee,
                                                ]);

                                                if ($existingNote) {
                                                    $existingNote->setNote($finalNote);
                                                    $em->flush();
                                                } else {
                                                    $note = new Note;
                                                    $note->setNote($finalNote);
                                                    $note->setEtudiant($etudiant);
                                                    $note->setElement($filiere->getElement());
                                                    $note->setAnneeuniversitaire($annee);
                                                    $em->persist($note);
                                                    $em->flush();
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    foreach ($matches as $match) {
                        $matricule = $match[1];
                        $nomPrenom = $match[2];
                        $noteetud = floatval($match[3]);
                        $element = $em->getRepository(Element::class)->findOneBy(['codeelt' => $elementCode]);
                        $etudiant = $em->getRepository(Etudiant::class)->findOneBy(['numetd' => $matricule]);

                        if (!$etudiant) {
                            $this->logger->error('L\'étudiant avec le numéro {numero} n\'existe pas dans la base de données.', [
                                'numero' => $matricule,
                            ]);
                            dd("une erreur --->" . $matricule);
                        }

                        if (!$element) {
                            $this->logger->error('L\'élément avec le code {code} n\'existe pas dans la base de données.', [
                                'code' => $elementCode,
                            ]);
                            dd("erreur cet element : {elementcode} n'est pas present dans la table element ", ['elementcode' => $elementCode]);
                        }

                        $existingNote = $em->getRepository(Note::class)->findOneBy([
                            'etudiant' => $etudiant,
                            'element' => $element,
                            'anneeuniversitaire' => $annee,
                        ]);

                        if ($existingNote) {
                            $this->logger->info('La note pour l\'étudiant {etudiant}, l\'élément {element} et l\'année universitaire {annee} existe déjà.', [
                                'etudiant' => $etudiant->getNumetd(),
                                'element' => $elementCode,
                                'annee' => $year,
                            ]);
                            $existingNote->setNote($noteetud);
                        } else {
                            $note = new Note();
                            $note->setNote($noteetud);
                            $note->setEtudiant($etudiant);
                            $note->setElement($element);
                            $note->setAnneeuniversitaire($annee);

                            $errors = $validator->validate($note);

                            if (
                                count($errors) > 0
                            ) {
                                // Gérer les erreurs de validation pour l'entité
                                dd('stop');
                            } else {
                                $em->persist($note);
                                $em->flush();
                            }

                            $matiere = $element->getEpreuve()->getMatiere();

                            if ($matiere) {
                                $epreuves = $em->getRepository(Epreuve::class)->findBy([
                                    'matiere' => $matiere,
                                    'numchance' => 1,
                                ]);

                                $totalPercentage = 0;
                                $Score = 0;

                                foreach ($epreuves as $epreuve) {
                                    $noteEpreuve = $em->getRepository(Note::class)->findOneBy([
                                        'etudiant' => $etudiant,
                                        'element' => $epreuve->getElement(),
                                    ]);

                                    if ($noteEpreuve) {
                                        $percentage = $epreuve->getPourcentage();
                                        $totalPercentage += $percentage;
                                        $Score += $noteEpreuve->getNote() * $percentage;
                                    }
                                }

                                if ($totalPercentage > 0) {
                                    $finalNote = $Score / $totalPercentage;
                                    $existingNote = $em->getRepository(Note::class)->findOneBy([
                                        'etudiant' => $etudiant,
                                        'element' => $matiere->getElement(),
                                        'anneeuniversitaire' => $annee,
                                    ]);

                                    if ($existingNote) {
                                        $existingNote->setNote($finalNote);
                                        $em->flush();
                                    } else {
                                        $note = new Note;
                                        $note->setNote($finalNote);
                                        $note->setEtudiant($etudiant);
                                        $note->setElement($matiere->getElement());
                                        $note->setAnneeuniversitaire($annee);
                                        $em->persist($note);
                                        $em->flush();
                                    }


                                    $unite = $matiere->getUnite();

                                    if ($unite) {
                                        $matieres = $em->getRepository(Matiere::class)->findBy([
                                            'unite' => $unite,
                                        ]);

                                        $totalPercentage = 0;
                                        $Score = 0;

                                        foreach ($matieres as $mat) {
                                            $noteMatier = $em->getRepository(Note::class)->findOneBy([
                                                'etudiant' => $etudiant,
                                                'element' => $mat->getElement(),
                                            ]);

                                            if ($noteMatier) {
                                                $percentage = 50;
                                                $totalPercentage += $percentage;
                                                $Score += $noteMatier->getNote() * $percentage;
                                            }
                                        }

                                        if ($totalPercentage > 0) {
                                            $finalNote = $Score / $totalPercentage;
                                            $existingNote = $em->getRepository(Note::class)->findOneBy([
                                                'etudiant' => $etudiant,
                                                'element' => $unite->getElement(),
                                                'anneeuniversitaire' => $annee,
                                            ]);

                                            if ($existingNote) {
                                                $existingNote->setNote($finalNote);
                                                $em->flush();
                                            } else {
                                                $note = new Note;
                                                $note->setNote($finalNote);
                                                $note->setEtudiant($etudiant);
                                                $note->setElement($unite->getElement());
                                                $note->setAnneeuniversitaire($annee);
                                                $em->persist($note);
                                                $em->flush();
                                            }


                                            $bloc = $unite->getBloc();

                                            if ($bloc) {
                                                $unites = $em->getRepository(Unite::class)->findBy([
                                                    'bloc' => $bloc,
                                                ]);

                                                $totalPercentage = 0;
                                                $Score = 0;

                                                foreach ($unites as $uni) {
                                                    $noteUnite = $em->getRepository(Note::class)->findOneBy([
                                                        'etudiant' => $etudiant,
                                                        'element' => $uni->getElement(),
                                                    ]);

                                                    if ($noteUnite) {
                                                        $percentage = $uni->getCoeficient();
                                                        $totalPercentage += $percentage;
                                                        $Score += $noteUnite->getNote() * $percentage;
                                                    }
                                                }

                                                if ($totalPercentage > 0) {
                                                    $finalNote = $Score / $totalPercentage;
                                                    $existingNote = $em->getRepository(Note::class)->findOneBy([
                                                        'etudiant' => $etudiant,
                                                        'element' => $bloc->getElement(),
                                                        'anneeuniversitaire' => $annee,
                                                    ]);

                                                    if ($existingNote) {
                                                        $existingNote->setNote($finalNote);
                                                        $em->flush();
                                                    } else {
                                                        $note = new Note;
                                                        $note->setNote($finalNote);
                                                        $note->setEtudiant($etudiant);
                                                        $note->setElement($bloc->getElement());
                                                        $note->setAnneeuniversitaire($annee);
                                                        $em->persist($note);
                                                        $em->flush();
                                                    }


                                                    $filiere = $bloc->getFiliere();

                                                    if ($filiere) {
                                                        $blocs = $em->getRepository(Bloc::class)->findBy([
                                                            'filiere' => $filiere,
                                                        ]);

                                                        $totalPercentage = 0;
                                                        $Score = 0;

                                                        foreach ($blocs as $blc) {
                                                            $noteBloc = $em->getRepository(Note::class)->findOneBy([
                                                                'etudiant' => $etudiant,
                                                                'element' => $blc->getElement(),
                                                            ]);

                                                            if ($noteBloc) {
                                                                $bloc_unites = $blc->getUnites();
                                                                $sommeCoefficients = 0;

                                                                foreach ($bloc_unites as $un) {
                                                                    $sommeCoefficients += $unite->getCoeficient();
                                                                }
                                                                $percentage = $sommeCoefficients;
                                                                $totalPercentage += $percentage;
                                                                $Score += $noteBloc->getNote() * $percentage;
                                                            }
                                                        }

                                                        if ($totalPercentage > 0) {
                                                            $finalNote = $Score / $totalPercentage;
                                                            $existingNote = $em->getRepository(Note::class)->findOneBy([
                                                                'etudiant' => $etudiant,
                                                                'element' => $filiere->getElement(),
                                                                'anneeuniversitaire' => $annee,
                                                            ]);

                                                            if ($existingNote) {
                                                                $existingNote->setNote($finalNote);
                                                                $em->flush();
                                                            } else {
                                                                $note = new Note;
                                                                $note->setNote($finalNote);
                                                                $note->setEtudiant($etudiant);
                                                                $note->setElement($filiere->getElement());
                                                                $note->setAnneeuniversitaire($annee);
                                                                $em->persist($note);
                                                                $em->flush();
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                return $this->redirectToRoute('login');

            } else {
                // Gérez le cas où les informations n'ont pas été trouvées
                $this->addFlash(
                    'error',
                    'Les informations nécessaires n\'ont pas été trouvées dans les données reçues par email.'
                );
            }
        }

        return $this->render('etudiant/mail.html.twig', [
            'form' => $form->createView(),
            'filieres'=> $filieres,
        ]);
    }


    #[Route('etudiant/note/insertion_file', name: 'insertion_file')]
    public function importCSV(Request $request, EntityManagerInterface $em)
    {
        $filieres = $em->getRepository(Filiere::class)->findAll();
        
        $validator = Validation::createValidator();
        $form = $this->createFormBuilder()
            ->add('csv_file', FileType::class, [
                'label' => 'Fichier CSV',
                'attr' => [
                    'accept' => '.csv',
                    'multiple' => false,
                    'data-toggle' => 'tooltip',
                    'data-placement' => 'bottom',
                ],
                'help' => 'Cliquez ici pour obtenir de l\'aide sur le format du fichier CSV.',
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $csvFile = $data["csv_file"];
            $file = fopen($csvFile->getPathname(), 'r');
            $csvData = [];
            $i = 0;
            while (($donnees = fgetcsv($file)) !== false) {
                if ($i == 0) {
                    $date = DateTime::createFromFormat('d/m/Y', $donnees[0]);
                    if ($date !== false) {
                        $anneeuniversitaire = $date->format('Y');
                    } else {
                        $currentYear = (new \DateTime())->format('Y');
                        $anneeuniversitaire  = $currentYear;
                    }
                } else {
                    $csvData[] = [
                        'admission' => $donnees[1],
                        'epreuve' => $donnees[4],
                        'numetd' => $donnees[7],
                        'nom' => $donnees[8],
                        'prenom' => $donnees[9],
                        'resultat' => $donnees[14],
                    ];
                }
                $i++;
            }
            fclose($file);
            $session = intval(preg_replace('/[^0-9]/', '', $csvData[1]['admission']));

        

            

            if ($session === 2){
                foreach($csvData as $record){
                    $resultat = floatval($record['resultat']);
                    if ($resultat >= 0 && $resultat <= 20) {
                    $etudiant = $em->getRepository(Etudiant::class)->findOneBy(["numetd" => intval($record['numetd'])]);
                    if (!$etudiant) {
                        // Gérer le cas où l'étudiant n'existe pas dans la base de données
                        $this->logger->error('L\'étudiant avec le numéro {numero} n\'existe pas dans la base de données.', [
                            'numero' => intval($record['numetd']),
                        ]);
                        continue;
                    }
                    $element = $em->getRepository(Element::class)->findOneBy(["codeelt" => $record['epreuve']]);

                    if (!$element) {
                        $element = new Element();
                        $element->setCodeelt($record['epreuve']);
                        $em->persist($element);
                    }

                    $annee = $em->getRepository(AnneeUniversitaire::class)->findOneBy(["annee" => $anneeuniversitaire]);

                    $existingNote = $em->getRepository(Note::class)->findOneBy([
                        'anneeuniversitaire' => $annee,
                        'etudiant' => $etudiant,
                        'element' => $element
                    ]);

                    if ($existingNote) {
                        // Mettre à jour la note existante si nécessaire
                        $existingNote->setNote(floatval($record['resultat']));
                        $em->persist($existingNote);
                    } else {
                        $note = new Note();
                        $note->setNote(floatval($record['resultat']));
                        $note->setEtudiant($etudiant);
                        $note->setElement($element);
                        $note->setAnneeuniversitaire($annee);

                        $errors = $validator->validate($note);
                        if (count($errors) > 0) {
                            // Gérer les erreurs de validation pour l'entité
                            foreach ($errors as $error) {
                                echo $error->getMessage() . "\n";
                            }
                        } else {
                            $em->persist($note);
                        }
                    }

                    $em->flush();

                    $epreuve = $element->getEpreuve();
                    if ($epreuve !== null) {
                        $matiere = $epreuve->getMatiere();
                        if ($matiere) {
                                $unite = $matiere->getUnite();

                                if ($unite) {
                                    $noteUnite = $em->getRepository(Note::class)->findOneBy([
                                        'anneeuniversitaire' => $annee,
                                        'etudiant' => $etudiant,
                                        'element' => $unite->getElement(),
                                    ]);

                                    if ($noteUnite->getNote() < $record['resultat']){
                                        $noteUnite->setNote($record['resultat']);
                                        $em->flush();

                                        $bloc = $unite->getBloc();

                                        if ($bloc) {
                                            $unites = $em->getRepository(Unite::class)->findBy([
                                                'bloc' => $bloc,
                                            ]);

                                            $totalPercentage = 0;
                                            $Score = 0;

                                            foreach ($unites as $uni) {
                                                $noteUnite = $em->getRepository(Note::class)->findOneBy([
                                                    'etudiant' => $etudiant,
                                                    'element' => $uni->getElement(),
                                                ]);

                                                if ($noteUnite) {
                                                    $percentage = $uni->getCoeficient();
                                                    $totalPercentage += $percentage;
                                                    $Score += $noteUnite->getNote() * $percentage;
                                                }
                                            }

                                            if ($totalPercentage > 0) {
                                                $finalNote = $Score / $totalPercentage;

                                                // Vérifier si une note existe déjà pour le bloc
                                                $existingNote = $em->getRepository(Note::class)->findOneBy([
                                                    'anneeuniversitaire' => $annee,
                                                    'etudiant' => $etudiant,
                                                    'element' => $bloc->getElement(),
                                                ]);

                                                if ($existingNote) {
                                                    // Mettre à jour la note existante
                                                    $existingNote->setNote($finalNote);
                                                    $em->persist($existingNote);
                                                } else {
                                                    // Créer une nouvelle note
                                                    $note = new Note();
                                                    $note->setNote($finalNote);
                                                    $note->setEtudiant($etudiant);
                                                    $note->setElement($bloc->getElement());
                                                    $note->setAnneeuniversitaire($annee);
                                                    $em->persist($note);
                                                }

                                                $em->flush();

                                                $filiere = $bloc->getFiliere();

                                                if ($filiere) {
                                                    $blocs = $em->getRepository(Bloc::class)->findBy([
                                                        'filiere' => $filiere,
                                                    ]);

                                                    $totalPercentage = 0;
                                                    $Score = 0;

                                                    foreach ($blocs as $blc) {
                                                        $noteBloc = $em->getRepository(Note::class)->findOneBy([
                                                            'etudiant' => $etudiant,
                                                            'element' => $blc->getElement(),
                                                        ]);

                                                        if ($noteBloc) {
                                                            $bloc_unites = $blc->getUnites();
                                                            $sommeCoefficients = 0;

                                                            foreach ($bloc_unites as $un) {
                                                                $sommeCoefficients += $un->getCoeficient();
                                                            }
                                                            $percentage = $sommeCoefficients;
                                                            $totalPercentage += $percentage;
                                                            $Score += $noteBloc->getNote() * $percentage;
                                                        }
                                                    }

                                                    if ($totalPercentage > 0) {
                                                        $finalNote = $Score / $totalPercentage;

                                                        // Vérifier si une note existe déjà pour la filière
                                                        $existingNote = $em->getRepository(Note::class)->findOneBy([
                                                            'anneeuniversitaire' => $annee,
                                                            'etudiant' => $etudiant,
                                                            'element' => $filiere->getElement(),
                                                        ]);

                                                        if ($existingNote) {
                                                            // Mettre à jour la note existante
                                                            $existingNote->setNote($finalNote);
                                                            $em->persist($existingNote);
                                                        } else {
                                                            // Créer une nouvelle note
                                                            $note = new Note();
                                                            $note->setNote($finalNote);
                                                            $note->setEtudiant($etudiant);
                                                            $note->setElement($filiere->getElement());
                                                            $note->setAnneeuniversitaire($annee);
                                                            $em->persist($note);
                                                        }

                                                        $em->flush();
                                                    }
                                                }
                                            }
                                        }
                                    }
     
                                    
                                }
                        
                        }
                    } else {
                        continue;
                    }
                    
                }else {
                    // C'est un ABI (valeur non valide entre 0 et 20)
                    // Sortir de la boucle et continuer vers l'élément suivant
                    break;
                }
            }
            } else {
                foreach ($csvData as $record) {
                    $resultat = floatval($record['resultat']);
                    if ($resultat >= 0 && $resultat <= 20) {
                    $etudiant = $em->getRepository(Etudiant::class)->findOneBy(["numetd" => intval($record['numetd'])]);
                    if (!$etudiant) {
                        // Gérer le cas où l'étudiant n'existe pas dans la base de données
                        $this->logger->error('L\'étudiant avec le numéro {numero} n\'existe pas dans la base de données.', [
                            'numero' => intval($record['numetd']),
                        ]);
                        continue;
                    }
                    $element = $em->getRepository(Element::class)->findOneBy(["codeelt" => $record['epreuve']]);
    
                    if (!$element) {
                        $element = new Element();
                        $element->setCodeelt($record['epreuve']);
                        $em->persist($element);
                    }
    
                    $annee = $em->getRepository(AnneeUniversitaire::class)->findOneBy(["annee" => $anneeuniversitaire]);
    
                    $existingNote = $em->getRepository(Note::class)->findOneBy([
                        'anneeuniversitaire' => $annee,
                        'etudiant' => $etudiant,
                        'element' => $element
                    ]);
    
                    if ($existingNote) {
                        // Mettre à jour la note existante si nécessaire
                        $existingNote->setNote(floatval($record['resultat']));
                        $em->persist($existingNote);
                    } else {
                        $note = new Note();
                        $note->setNote(floatval($record['resultat']));
                        $note->setEtudiant($etudiant);
                        $note->setElement($element);
                        $note->setAnneeuniversitaire($annee);
    
                        $errors = $validator->validate($note);
                        if (count($errors) > 0) {
                            // Gérer les erreurs de validation pour l'entité
                            foreach ($errors as $error) {
                                echo $error->getMessage() . "\n";
                            }
                        } else {
                            $em->persist($note);
                        }
                    }
    
                    $em->flush();
                    //if ($session === 1) {
                        $epreuve = $element->getEpreuve();
                        if ($epreuve !== null) {
                            $matiere = $epreuve->getMatiere();
                            if ($matiere) {
                                $epreuves = $em->getRepository(Epreuve::class)->findBy([
                                    'matiere' => $matiere,
                                    'numchance' => 1,
                                ]);
    
                                $totalPercentage = 0;
                                $Score = 0;
    
                                foreach ($epreuves as $epreuve) {
                                    $noteEpreuve = $em->getRepository(Note::class)->findOneBy([
                                        'etudiant' => $etudiant,
                                        'element' => $epreuve->getElement(),
                                    ]);
    
                                    if ($noteEpreuve) {
                                        $percentage = $epreuve->getPourcentage();
                                        $totalPercentage += $percentage;
                                        $Score += $noteEpreuve->getNote() * $percentage;
                                    }
                                }
    
                                if ($totalPercentage > 0) {
                                    $finalNote = $Score / $totalPercentage;
    
                                    // Vérifier si une note existe déjà pour la matière
                                    $existingNote = $em->getRepository(Note::class)->findOneBy([
                                        'anneeuniversitaire' => $annee,
                                        'etudiant' => $etudiant,
                                        'element' => $matiere->getElement(),
                                    ]);
    
                                    if ($existingNote) {
                                        // Mettre à jour la note existante
                                        $existingNote->setNote($finalNote);
                                        $em->persist($existingNote);
                                    } else {
                                        // Créer une nouvelle note
                                        $note = new Note();
                                        $note->setNote($finalNote);
                                        $note->setEtudiant($etudiant);
                                        $note->setElement($matiere->getElement());
                                        $note->setAnneeuniversitaire($annee);
                                        $em->persist($note);
                                    }
    
                                    $em->flush();
    
                                    $unite = $matiere->getUnite();
    
                                    if ($unite) {
                                        $matieres = $em->getRepository(Matiere::class)->findBy([
                                            'unite' => $unite,
                                        ]);
    
                                        $totalPercentage = 0;
                                        $Score = 0;
    
                                        foreach ($matieres as $mat) {
                                            $noteMatier = $em->getRepository(Note::class)->findOneBy([
                                                'etudiant' => $etudiant,
                                                'element' => $mat->getElement(),
                                            ]);
    
                                            if ($noteMatier) {
                                                $percentage = 50;
                                                $totalPercentage += $percentage;
                                                $Score += $noteMatier->getNote() * $percentage;
                                            }
                                        }
    
                                        if ($totalPercentage > 0) {
                                            $finalNote = $Score / $totalPercentage;
    
                                            // Vérifier si une note existe déjà pour l'unité
                                            $existingNote = $em->getRepository(Note::class)->findOneBy([
                                                'anneeuniversitaire' => $annee,
                                                'etudiant' => $etudiant,
                                                'element' => $unite->getElement(),
                                            ]);
    
                                            if ($existingNote) {
                                                // Mettre à jour la note existante
                                                $existingNote->setNote($finalNote);
                                                $em->persist($existingNote);
                                            } else {
                                                // Créer une nouvelle note
                                                $note = new Note();
                                                $note->setNote($finalNote);
                                                $note->setEtudiant($etudiant);
                                                $note->setElement($unite->getElement());
                                                $note->setAnneeuniversitaire($annee);
                                                $em->persist($note);
                                            }
    
                                            $em->flush();
    
                                            $bloc = $unite->getBloc();
    
                                            if ($bloc) {
                                                $unites = $em->getRepository(Unite::class)->findBy([
                                                    'bloc' => $bloc,
                                                ]);
    
                                                $totalPercentage = 0;
                                                $Score = 0;
    
                                                foreach ($unites as $uni) {
                                                    $noteUnite = $em->getRepository(Note::class)->findOneBy([
                                                        'etudiant' => $etudiant,
                                                        'element' => $uni->getElement(),
                                                    ]);
    
                                                    if ($noteUnite) {
                                                        $percentage = $uni->getCoeficient();
                                                        $totalPercentage += $percentage;
                                                        $Score += $noteUnite->getNote() * $percentage;
                                                    }
                                                }
    
                                                if ($totalPercentage > 0) {
                                                    $finalNote = $Score / $totalPercentage;
    
                                                    // Vérifier si une note existe déjà pour le bloc
                                                    $existingNote = $em->getRepository(Note::class)->findOneBy([
                                                        'anneeuniversitaire' => $annee,
                                                        'etudiant' => $etudiant,
                                                        'element' => $bloc->getElement(),
                                                    ]);
    
                                                    if ($existingNote) {
                                                        // Mettre à jour la note existante
                                                        $existingNote->setNote($finalNote);
                                                        $em->persist($existingNote);
                                                    } else {
                                                        // Créer une nouvelle note
                                                        $note = new Note();
                                                        $note->setNote($finalNote);
                                                        $note->setEtudiant($etudiant);
                                                        $note->setElement($bloc->getElement());
                                                        $note->setAnneeuniversitaire($annee);
                                                        $em->persist($note);
                                                    }
    
                                                    $em->flush();
    
                                                    $filiere = $bloc->getFiliere();
    
                                                    if ($filiere) {
                                                        $blocs = $em->getRepository(Bloc::class)->findBy([
                                                            'filiere' => $filiere,
                                                        ]);
    
                                                        $totalPercentage = 0;
                                                        $Score = 0;
    
                                                        foreach ($blocs as $blc) {
                                                            $noteBloc = $em->getRepository(Note::class)->findOneBy([
                                                                'etudiant' => $etudiant,
                                                                'element' => $blc->getElement(),
                                                            ]);
    
                                                            if ($noteBloc) {
                                                                $bloc_unites = $blc->getUnites();
                                                                $sommeCoefficients = 0;
    
                                                                foreach ($bloc_unites as $un) {
                                                                    $sommeCoefficients += $un->getCoeficient();
                                                                }
                                                                $percentage = $sommeCoefficients;
                                                                $totalPercentage += $percentage;
                                                                $Score += $noteBloc->getNote() * $percentage;
                                                            }
                                                        }
    
                                                        if ($totalPercentage > 0) {
                                                            $finalNote = $Score / $totalPercentage;
    
                                                            // Vérifier si une note existe déjà pour la filière
                                                            $existingNote = $em->getRepository(Note::class)->findOneBy([
                                                                'anneeuniversitaire' => $annee,
                                                                'etudiant' => $etudiant,
                                                                'element' => $filiere->getElement(),
                                                            ]);
    
                                                            if ($existingNote) {
                                                                // Mettre à jour la note existante
                                                                $existingNote->setNote($finalNote);
                                                                $em->persist($existingNote);
                                                            } else {
                                                                // Créer une nouvelle note
                                                                $note = new Note();
                                                                $note->setNote($finalNote);
                                                                $note->setEtudiant($etudiant);
                                                                $note->setElement($filiere->getElement());
                                                                $note->setAnneeuniversitaire($annee);
                                                                $em->persist($note);
                                                            }
    
                                                            $em->flush();
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            continue;
                        }
                
                }else {
                    // C'est un ABI (valeur non valide entre 0 et 20)
                    // Sortir de la boucle et continuer vers l'élément suivant
                    break;
                }
            }
            }

            
        }

        return $this->render('etudiant/file.html.twig', [
            'form' => $form->createView(),
            'filieres' => $filieres,
        ]);
    }


    #[Route('etudiant/note', name: 'etudiant_note')]
    public function etudiant_note(Request $request, EntityManagerInterface $em): Response
    {
        $filieres = $em->getRepository(Filiere::class)->findAll();
        
        return $this->render('etudiant/note.html.twig', [
            'filieres'=>$filieres
        ]);
    }

    #[Route('etudiant/visualisation', name: 'etudiant_visualisation')]
    public function etudiant_visualisation(managerRegistry $doctrine, Request $request, EntityManagerInterface $em): Response
    {
        $entityManager = $doctrine->getManager();
        $filieres = $em->getRepository(Filiere::class)->findAll();
        $etudiants = $entityManager->getRepository(Etudiant::class)->findAll();

        return $this->render('etudiant/visualisation.html.twig', [
            'etudiants' => $etudiants,
            'filieres' => $filieres,
        ]);
    }
    #[Route('etudiant/visualisationNote', name: 'note_visualisation')]
    public function note_visualisation(Request $request, managerRegistry $doctrine, EntityManagerInterface $em): Response
    {
        $numetd = $request->get('numetd');
        $entityManager = $doctrine->getManager();
        $epreuve = $entityManager->getRepository(Epreuve::class)->findOneBy(["codeepreuve" => $this->elt]);
        $type = $epreuve->getTypeepreuve();
        $nommat = $epreuve->getMatiere()->getNommat();
        $notes = $epreuve->getElement()->getNotes();
        $long = $notes->count();
        $somme = 0;
        $comptmoyen = 0;
        $comptvalid = 0;
        foreach ($notes as $note) {
            $somme = $somme + $note->getNote();
        }
        $moyen = $somme / $long;
        foreach ($notes as $note) {
            if ($note->getNote() >= $moyen) {
                $comptmoyen = $comptmoyen + 1;
            }
            if ($note->getNote() >= 10) {
                $comptvalid = $comptvalid + 1;
            }
        }



        return $this->render('etudiant/visualisationNote.html.twig', [
            'type' => $type,
            'notes' => $notes,
            'nommat' => $nommat,
            'long' => $long,
            'moyen' => $moyen,
            'compteurmoy' => $comptmoyen,
            'compteurval' => $comptvalid,
        ]);
    }

    #[Route('etudiant/visualisation_etudiant/{numetd}', name: 'etudiant_visual')]
    public function affichage_result_etudiant($numetd, Request $request, EntityManagerInterface $em, NoteRepository $noteRepository)
    {
        $codefiliere = $request->get('codefiliere');
        $numetd = $request->get("numetd");
        $etudiant = $em->getRepository(Etudiant::class)->findOneBy(["numetd" => $numetd]);
        if (!$etudiant) {
            throw new \Exception("Étudiant non trouvé");
        }
        $annee = $noteRepository->findDistinctYearsForEtudiant($etudiant);
        $filieres = $em->getRepository(Filiere::class)->findAll();

        $etd_filiere_details = [];

        foreach ($filieres as $filiere) {
            $element = $filiere->getElement();

            $note_element = $em->getRepository(Note::class)->findOneBy([
                "element" => $element,
                "etudiant" => $etudiant,
            ]);

            if ($note_element) {

                $filiere_table = [
                    ["Nom" => $filiere->getNomfiliere(), "Note" => $note_element->getNote(), "Blocs" => []]
                ];

                $blocs = $filiere->getBlocs();
                foreach ($blocs as $bloc) {
                    $note_bloc = $em->getRepository(Note::class)->findOneBy([
                        "element" => $bloc->getElement(),
                        "etudiant" => $etudiant,
                    ]);

                    $bloc_data = [
                        "Nom" => $bloc->getNombloc(),
                        "Note" => $note_bloc ? $note_bloc->getNote() : "-",
                        "Unites" => []
                    ];

                    $unites = $bloc->getUnites();
                    foreach ($unites as $unite) {
                        $note_unite = $em->getRepository(Note::class)->findOneBy([
                            "element" => $unite->getElement(),
                            "etudiant" => $etudiant,
                        ]);

                        $unite_data = [
                            "Nom" => $unite->getNomunite(),
                            "Note" => $note_unite ? $note_unite->getNote() : "-",
                            "Matieres" => []
                        ];

                        $matieres = $unite->getMatieres();
                        foreach ($matieres as $matiere) {
                            $note_matiere = $em->getRepository(Note::class)->findOneBy([
                                "element" => $matiere->getElement(),
                                "etudiant" => $etudiant,
                            ]);

                            $unite_data["Matieres"][] = [
                                "Nom" => $matiere->getNommat() . ' ' . $matiere->getPeriode(),
                                "Note" => $note_matiere ? $note_matiere->getNote() : "-"
                            ];
                        }

                        $bloc_data["Unites"][] = $unite_data;
                    }

                    $filiere_table[0]["Blocs"][] = $bloc_data;
                }

                $etd_filiere_details[] = $filiere_table;
            }
        }
        //dd($etd_filiere_details);
        $tab_unites_names = [];
        $tab_unites_notes = [];

        foreach ($etd_filiere_details[0][0]["Blocs"] as $bloc) {
            foreach ($bloc["Unites"] as $unite) {
                $tab_unites_names[] = $unite["Nom"];
                $tab_unites_notes[] = $unite["Note"];
            }
        }

        // Remplacer les tirets par des zéros dans le tableau de notes
        $tab_unites_notes = array_map(function ($note) {
            return $note === '-' ? 0 : $note;
        }, $tab_unites_notes);

        // Afficher les tableaux de noms et de notes

        return $this->render('etudiant/visualisation_etudiant.html.twig', [
            //'filieres' => $filiere,
            'etudiant' => $etudiant,
            'annees' => $annee, // Ajout de la variable 'annees'
            'edt_filiere' => $etd_filiere_details,
            'tab_unites_name' => $tab_unites_names,
            'tab_unites_note' => $tab_unites_notes,
            'filieres' => $filieres,
        ]);
    }
}
