<?php

namespace App\Entity;

use App\Repository\FormationIntRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FormationIntRepository::class)]
class FormationInt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'formationInts')]
    #[ORM\JoinColumn(nullable: false, name:"etudiant", referencedColumnName:"numetd")]
    private ?Etudiant $etudiant = null;

    #[ORM\ManyToOne(inversedBy: 'formationInts')]
    #[ORM\JoinColumn(nullable: false, name:"filiere", referencedColumnName:"codefiliere")]
    private ?Filiere $filiere = null;

    #[ORM\ManyToOne(inversedBy: 'formationInts')]
    #[ORM\JoinColumn(referencedColumnName:"annee")]
    private ?AnneeUniversitaire $annee = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtudiant(): ?Etudiant
    {
        return $this->etudiant;
    }

    public function setEtudiant(?Etudiant $etudiant): static
    {
        $this->etudiant = $etudiant;

        return $this;
    }

    public function getFiliere(): ?Filiere
    {
        return $this->filiere;
    }

    public function setFiliere(?Filiere $filiere): static
    {
        $this->filiere = $filiere;

        return $this;
    }

    public function getAnnee(): ?AnneeUniversitaire
    {
        return $this->annee;
    }

    public function setAnnee(?AnneeUniversitaire $annee): static
    {
        $this->annee = $annee;

        return $this;
    }
}
