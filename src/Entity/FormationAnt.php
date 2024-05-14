<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\FormationAntRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity(fields:'codef', message:'This value is already used.')]
#[ORM\Entity(repositoryClass: FormationAntRepository::class)]
class FormationAnt
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(length: 20)]
    private ?string $codef = null;

    #[ORM\Column(length: 50)]
    private ?string $nomf = null;

    #[ORM\Column(length: 80, nullable: true)]
    private ?string $etablissement = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $diplome = null;


    public function getCodef(): ?string
    {
        return $this->codef;
    }

    public function setCodef(string $codef): self
    {
        $this->codef = $codef;

        return $this;
    }

    public function getNomf(): ?string
    {
        return $this->nomf;
    }

    public function setNomf(string $nomf): self
    {
        $this->nomf = $nomf;

        return $this;
    }

    public function getEtablissement(): ?string
    {
        return $this->etablissement;
    }

    public function setEtablissement(?string $etablissement): self
    {
        $this->etablissement = $etablissement;

        return $this;
    }

    public function getDiplome(): ?string
    {
        return $this->diplome;
    }

    public function setDiplome(?string $diplome): self
    {
        $this->diplome = $diplome;

        return $this;
    }

    
}
