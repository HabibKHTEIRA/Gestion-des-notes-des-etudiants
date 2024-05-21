<?php

namespace App\Controller;

use App\Entity\Filiere;
use App\Entity\Note;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StatistiqueParticulierController extends AbstractController
{
    #[Route('/statistique/particulier/{codefiliere}', name: 'app_statistique_particulier')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $filieres = $em->getRepository(Filiere::class)->findAll();
        $codefiliere = $request->get('codefiliere');
        $filiere = $em->getRepository(Filiere::class)->findOneBy(["codefiliere" => $codefiliere]);
        $blocs = $filiere->getBlocs();
        $tab_unite_stats = [] ; 
        foreach ($blocs as $bloc){
            $unites = $bloc->getUnites(); 
            foreach ($unites as $unit){
                $notes = $em->getRepository(Note::class)->findBy(['element' => $unit->getCodeunite()]);
                $tab_notes = []; 
                foreach($notes as $nte){
                     $tab_notes [] = floatval($nte->getNote()); 
                }
                $tab_unite_stats [$unit->getNomunite()] = $tab_notes; 
            }
        }
       // dd($tab_unite_stats);
        return $this->render('statistique_particulier/index.html.twig', [
            'tab_unite_stats' => $tab_unite_stats , 
            'filiere' => $filiere,
            'filieres' => $filieres,          
        ]);
    }
}
