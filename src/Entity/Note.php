<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\NoteRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity(fields:['anneeuniversitaire','etudiant','element'], message:'This value is already used.')]
#[ORM\Entity(repositoryClass: NoteRepository::class)]
class Note
{
    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'notes')]
    #[ORM\JoinColumn(nullable: false,name:"anneeuniversitaire", referencedColumnName:"annee")]
    private ?AnneeUniversitaire $anneeuniversitaire = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'notes')]
    #[ORM\JoinColumn(nullable: false, name:"etudiant", referencedColumnName:"numetd")]
    private ?Etudiant $etudiant = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'notes')]
    #[ORM\JoinColumn(nullable: false, name:"element", referencedColumnName:"codeelt")]
    private ?Element $element = null;
   

    #[ORM\Column(nullable:true)]
    private ?float $note = null;


    public function getAnneeuniversitaire(): ?AnneeUniversitaire
    {
        return $this->anneeuniversitaire;
    }

    public function getEtudiant(): ?Etudiant
    {
        return $this->etudiant;
    }

    public function getElement(): ?Element
    {
        return $this->element;
    }


    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(float $note): self
    {
        $this->note = $note;

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
     * Set the value of element
     *
     * @return  self
     */ 
    public function setElement($element)
    {
        $this->element = $element;

        return $this;
    }

    /**
     * Set the value of anneeuniversitaire
     *
     * @return  self
     */ 
    public function setAnneeuniversitaire($anneeuniversitaire)
    {
        $this->anneeuniversitaire = $anneeuniversitaire;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getEtudiant()->getNumetd().' '.$this->getEtudiant()->getNom().' '.$this->getEtudiant()->getPrenom().' '.$this->getNote();
    }

    
}
