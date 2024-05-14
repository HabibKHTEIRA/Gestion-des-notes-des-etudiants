<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use App\Repository\AnneeUniversitaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity(fields:'annee', message:'This value is already used.')]
#[ORM\Entity(repositoryClass: AnneeUniversitaireRepository::class)]
class AnneeUniversitaire
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $annee = null;

    #[ORM\OneToMany(mappedBy: 'anneeuniversitaire', targetEntity: Note::class, orphanRemoval: true)]
    private Collection $notes;

    /**
     * @var Collection<int, FormationInt>
     */
    #[ORM\OneToMany(targetEntity: FormationInt::class, mappedBy: 'annee')]
    private Collection $formationInts;

    public function __construct()
    {
        $this->notes = new ArrayCollection();
        $this->formationInts = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getAnnee();
    }
    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    /**
     * @return Collection<int, Note>
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes->add($note);
            $note->setAnneeuniversitaire($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getAnneeuniversitaire() === $this) {
                $note->setAnneeuniversitaire(null);
            }
        }

        return $this;
    }


    /**
     * Set the value of annee
     *
     * @return  self
     */ 
    public function setAnnee($annee)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * @return Collection<int, FormationInt>
     */
    public function getFormationInts(): Collection
    {
        return $this->formationInts;
    }

    public function addFormationInt(FormationInt $formationInt): static
    {
        if (!$this->formationInts->contains($formationInt)) {
            $this->formationInts->add($formationInt);
            $formationInt->setAnnee($this);
        }

        return $this;
    }

    public function removeFormationInt(FormationInt $formationInt): static
    {
        if ($this->formationInts->removeElement($formationInt)) {
            // set the owning side to null (unless already changed)
            if ($formationInt->getAnnee() === $this) {
                $formationInt->setAnnee(null);
            }
        }

        return $this;
    }
  
}
