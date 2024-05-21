<?php

namespace App\Controller;

use App\Entity\AnneeUniversitaire;
use App\Entity\Element;
use App\Entity\Etudiant;
use App\Entity\Filiere;
use App\Entity\Note;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class EtudiantParticulierController extends AbstractController
{
    #[Route('/etudiant/particulier/{codefiliere}', name: 'app_etudiant_particulier')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $filieres = $em->getRepository(Filiere::class)->findAll();
        $codefiliere = $request->get('codefiliere');
        $filiere = $em->getRepository(Filiere::class)->findOneBy(["codefiliere" => $codefiliere]);
        $etudiant_tableau = [];
        $qb = $em->createQueryBuilder();
        $qb->select('DISTINCT a.annee')
            ->from(Note::class, 'n')
            ->join('n.element', 'e')
            ->join('n.anneeuniversitaire', 'a')
            ->where('e.codeelt = :codefiliere')
            ->setParameter('codefiliere', $codefiliere);

        $query = $qb->getQuery();
        $annees = $query->getResult();
        foreach ($annees as $year) {
            $etudiant_tableau[$year["annee"]] = [];
            $anneeuniversitaire = $em->getRepository(AnneeUniversitaire::class)->findOneBy(["annee" => $year["annee"]]);
            $numero_etudiant = $em->getRepository(Note::class)->findBy(["anneeuniversitaire" => $anneeuniversitaire]);
            $element = $em->getRepository(Element::class)->findOneBy(["codeelt" => $codefiliere]);
            foreach ($numero_etudiant as $note) {
                $etudiant = $note->getEtudiant();
                $noteObject = $em->getRepository(Note::class)->findOneBy(["etudiant" => $etudiant, "element" => $element]);
                if ($noteObject) {
                    $note = $noteObject->getNote();
                    $numetd = intval($etudiant->getNumetd());
                    $nom = $etudiant->getNom();
                    $prenom = $etudiant->getPrenom();
                    $etudiant_tableau[$year["annee"]][$numetd] = [$nom, $prenom, $note];
                }
            }
        }
        return $this->render('etudiant_particulier/index.html.twig', [
            'filiere' => $filiere,
            'codefiliere' => $codefiliere,
            'etudiant_tableau' => $etudiant_tableau,
            'filieres' => $filieres
        ]);
    }
}
