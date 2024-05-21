#[Route('etudiant/note/insertion_file', name: 'insertion_file')]
    public function importCSV(Request $request, EntityManagerInterface $em)
    {
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
                        $anneeuniversitaire = 2022;
                    }
                } else {
                    $csvData[] = $donnees;
                }
                $i++;
            }
            fclose($file);
            $session = intval(preg_replace('/[^0-9]/', '', $csvData[1][1]));

            foreach ($csvData as $record) {
                $etudiant = $em->getRepository(Etudiant::class)->findOneBy(["numetd" => intval($record[7])]);
                if (!$etudiant) {
                    // Gérer le cas où l'étudiant n'existe pas dans la base de données
                    $this->logger->error('L\'étudiant avec le numéro {numero} n\'existe pas dans la base de données.', [
                        'numero' => intval($record[7]),
                    ]);
                    continue;
                }
                $element = $em->getRepository(Element::class)->findOneBy(["codeelt" => $record[4]]);

                if (!$element) {
                    $element = new Element();
                    $element->setCodeelt($record[4]);
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
                    $existingNote->setNote(floatval($record[14]));
                    $em->persist($existingNote);
                } else {
                    $note = new Note();
                    $note->setNote(floatval($record[14]));
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
                if ($session === 1) {
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
                } else {
                    $matiere = $element->getEpreuve()->getMatiere();
                    $noteMat = $em->getRepository(Note::class)->findOneBy([
                        "etudiant" => $etudiant,
                        "element" => $matiere->getElement()
                    ]);

                    if ($noteMat && ($noteMat->getNote() < floatval($record[14]))) {
                        $noteMat->setNote(floatval($record[14]));
                        $em->flush();
                    }
                }
            }
        }

        return $this->render('etudiant/file.html.twig', [
            'form' => $form->createView()
        ]);
    }
