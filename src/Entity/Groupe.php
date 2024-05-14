<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GroupeRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity(fields:'codegrp', message:'This value is already used.')]
#[ORM\Entity(repositoryClass: GroupeRepository::class)]
//#[ORM\HasLifecycleCallbacks]
class Groupe
{
    #[ORM\Id]
    #[ORM\Column(length: 50)]
    private ?string $codegrp = null;
    
    #[ORM\Column(length: 50)]
    private ?string $nomgrp = null;

    #[ORM\Column]
    private ?int $nbetds = null;

    #[ORM\Column]
    private ?int $capacite = null;

    #[ORM\OneToMany(mappedBy: 'codegrp', targetEntity: Etudiant::class)]
    private Collection $etudiants;

    public function __construct()
    {
        $this->etudiants = new ArrayCollection();
    }

    

    public function getCodegrp(): ?string
    {
        return $this->codegrp;
    }

    public function getNomgrp(): ?string
    {
        return $this->nomgrp;
    }

    public function setNomgrp(string $nomgrp): self
    {
        $this->nomgrp = $nomgrp;

        return $this;
    }

    public function getNbetds(): ?int
    {
        return $this->nbetds;
    }

    public function setNbetds(int $nbetds): self
    {
        $this->nbetds = $nbetds;

        return $this;
    }

    public function getCapacite(): ?int
    {
        return $this->capacite;
    }

    public function setCapacite(int $capacite): self
    {
        $this->capacite = $capacite;

        return $this;
    }

    /**
     * @return Collection<int, Etudiant>
     */
    public function getEtudiants(): Collection
    {
        return $this->etudiants;
    }

    public function addEtudiant(Etudiant $etudiant): self
    {
        if (!$this->etudiants->contains($etudiant)) {
            $this->etudiants->add($etudiant);
            $etudiant->setGroupe($this);
        }

        return $this;
    }

    public function removeEtudiant(Etudiant $etudiant): self
    {
        if ($this->etudiants->removeElement($etudiant)) {
            // set the owning side to null (unless already changed)
            if ($etudiant->getGroupe() === $this) {
                $etudiant->setGroupe(null);
            }
        }

        return $this;
    }

  /*  #[ORM\PreRemove]
    public function preRemove()
    {
        foreach ($this->etudiants as $etudiant) {
            $etudiant->setGroupe(null);
        }
    }*/

    /**
     * Set the value of codegrp
     *
     * @return  self
     */ 
    public function setCodegrp($codegrp)
    {
        $this->codegrp = $codegrp;

        return $this;
    }
}
