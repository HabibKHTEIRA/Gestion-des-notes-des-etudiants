<?php

namespace App\Controller;

use App\Entity\Filiere;
use App\Entity\Note;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class StatistiqueParticulierController extends AbstractController
{
    #[Route('/statistique/particulier/{codefiliere}', name: 'app_statistique_particulier')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $filieres = $em->getRepository(Filiere::class)->findAll();
        $codefiliere = $request->get('codefiliere');
        $filiere = $em->getRepository(Filiere::class)->findOneBy(["codefiliere" => $codefiliere]);
        $blocs = $filiere->getBlocs();
        $tab_unite_stats = [];

        foreach ($blocs as $bloc) {
            $unites = $bloc->getUnites();
            foreach ($unites as $unite) {
                $notes = $em->getRepository(Note::class)->findBy(['element' => $unite->getCodeunite()]);
                $tab_notes_count = [];

                foreach ($notes as $note) {
                    $noteValue = floatval($note->getNote());
                    if (array_key_exists($noteValue, $tab_notes_count)) {
                        $tab_notes_count[$noteValue]++;
                    } else {
                        $tab_notes_count[$noteValue] = 1;
                    }
                }

                // Tri des notes par ordre croissant
                ksort($tab_notes_count);
                $tab_unite_stats[$unite->getNomunite()] = $tab_notes_count;
            }
        }

        return $this->render('statistique_particulier/index.html.twig', [
            'tab_unite_stats' => $tab_unite_stats,
            'filiere' => $filiere,
            'filieres' => $filieres,
        ]);
    }
}
