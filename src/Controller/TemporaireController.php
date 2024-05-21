<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TemporaireController extends AbstractController
{
    #[Route('/temporaire', name: 'app_temporaire')]
    public function index(): Response
    {
        return $this->render('temporaire/index.html.twig', [
            'controller_name' => 'TemporaireController',
        ]);
    }


    /*<p>Bac: </p> <p>{{  }}</p><br>
    <p>Annee: </p> <p>{{ etudiant.getResultatbac.anneebac }}</p><br>
    <p>Mention: </p> <p>{{ etudiant.getResultatbac.mention }}</p><br>
    <p>Moyenne: </p> <p>{{ etudiant.getResultatbac.moyennebac }}</p><br>*/

    
}
