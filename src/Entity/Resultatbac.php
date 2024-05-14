<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ResultatbacRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity(fields:['bac','etudiant'], message:'This value is already used.')]
#[ORM\Entity(repositoryClass: ResultatbacRepository::class)]
class Resultatbac
{
    #[ORM\Id]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false, name:"bac", referencedColumnName:"idbac")]
    private ?Bac $bac = null;

    #[ORM\Id]
    #[ORM\OneToOne(inversedBy: 'resultatbac', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false, name:"etudiant", referencedColumnName:"numetd")]
    private ?Etudiant $etudiant = null;

    #[ORM\Column]
    private ?int $anneebac = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $mention = null;

    #[ORM\Column(nullable:true)]
    private ?float $moyennebac = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $etabbac = null;

    #[ORM\Column(length: 50,nullable:true)]
    private ?string $depbac = null;

    public function getBac(): ?Bac
    {
        return $this->bac;
    }


    public function getEtudiant(): ?Etudiant
    {
        return $this->etudiant;
    }

    public function getAnneebac(): ?int
    {
        return $this->anneebac;
    }

    public function setAnneebac(int $anneebac): self
    {
        $this->anneebac = $anneebac;

        return $this;
    }

    public function getMention(): ?string
    {
        return $this->mention;
    }

    public function setMention(?string $mention): self
    {
        $this->mention = $mention;

        return $this;
    }

    public function getMoyennebac(): ?float
    {
        return $this->moyennebac;
    }

    public function setMoyennebac(float $moyennebac): self
    {
        $this->moyennebac = $moyennebac;

        return $this;
    }
    public function getEtabbac(): ?string
    {
        return $this->etabbac;
    }

    public function setEtabbac(?string $etabbac): self
    {
        $this->etabbac = $etabbac;

        return $this;
    }
    public function getDepbac(): ?string
    {
        return $this->depbac;
    }

    public function setDepbac(string $depbac): self
    {
        $this->depbac = $depbac;

        return $this;
    }

    /**
     * Set the value of etudiant
     *
     * @return  self
     */ 
    public function setEtudiant($etudiant)
    {
        $this->etudiant = $etudiant;

        return $this;
    }

    /**
     * Set the value of bac
     *
     * @return  self
     */ 
    public function setBac($bac)
    {
        $this->bac = $bac;

        return $this;
    }


    
}
