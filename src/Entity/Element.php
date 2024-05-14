<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ElementRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity(fields:'codeelt', message:'This value is already used.')]
#[ORM\Entity(repositoryClass: ElementRepository::class)]
class Element
{
    #[ORM\Id]
    //#[ORM\GeneratedValue] ??
    #[ORM\Column(length: 20)]
    private ?string $codeelt = null;

    #[ORM\OneToOne(mappedBy: 'element', cascade: ['persist', 'remove'])]
    private ?Matiere $matiere = null;

    #[ORM\OneToOne(mappedBy: 'element', cascade: ['persist', 'remove'])]
    private ?Unite $unite = null;

    #[ORM\OneToOne(mappedBy: 'element', cascade: ['persist', 'remove'])]
    private ?Bloc $bloc = null;

    #[ORM\OneToOne(mappedBy: 'element', cascade: ['persist', 'remove'])]
    private ?Filiere $filiere = null;

    #[ORM\OneToMany(mappedBy: 'element', targetEntity: Note::class, orphanRemoval: true)]
    private Collection $notes;

    #[ORM\OneToOne(mappedBy: 'element', cascade: ['persist', 'remove'])]
    private ?Epreuve $epreuve = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;
    public function __construct()
    {
        $this->notes = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getCodeelt();
    }
    public function getCodeelt(): ?string
    {
        return $this->codeelt;
    }

    public function getMatiere(): ?Matiere
    {
        return $this->matiere;
    }

    public function setMatiere(?Matiere $matiere): self
    {
        // unset the owning side of the relation if necessary
        if ($matiere === null && $this->matiere !== null) {
            $this->matiere->setElement(null);
        }

        // set the owning side of the relation if necessary
        if ($matiere !== null && $matiere->getElement() !== $this) {
            $matiere->setElement($this);
        }

        $this->matiere = $matiere;

        return $this;
    }

    public function getUnite(): ?Unite
    {
        return $this->unite;
    }

    public function setUnite(?Unite $unite): self
    {
        // unset the owning side of the relation if necessary
        if ($unite === null && $this->unite !== null) {
            $this->unite->setElement(null);
        }

        // set the owning side of the relation if necessary
        if ($unite !== null && $unite->getElement() !== $this) {
            $unite->setElement($this);
        }

        $this->unite = $unite;

        return $this;
    }

    public function getBloc(): ?Bloc
    {
        return $this->bloc;
    }

    public function setBloc(?Bloc $bloc): self
    {
        // unset the owning side of the relation if necessary
        if ($bloc === null && $this->bloc !== null) {
            $this->bloc->setElement(null);
        }

        // set the owning side of the relation if necessary
        if ($bloc !== null && $bloc->getElement() !== $this) {
            $bloc->setElement($this);
        }

        $this->bloc = $bloc;

        return $this;
    }

    public function getFiliere(): ?Filiere
    {
        return $this->filiere;
    }

    public function setFiliere(?Filiere $filiere): self
    {
        // unset the owning side of the relation if necessary
        if ($filiere === null && $this->filiere !== null) {
            $this->filiere->setElement(null);
        }

        // set the owning side of the relation if necessary
        if ($filiere !== null && $filiere->getElement() !== $this) {
            $filiere->setElement($this);
        }

        $this->filiere = $filiere;

        return $this;
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
            $note->setElement($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getElement() === $this) {
                $note->setElement(null);
            }
        }

        return $this;
    }


    /**
     * Set the value of codeelt
     *
     * @return  self
     */ 
    public function setCodeelt($codeelt)
    {
        $this->codeelt = $codeelt;

        return $this;
    }

    public function getEpreuve(): ?Epreuve
    {
        return $this->epreuve;
    }

    public function setEpreuve(?Epreuve $epreuve): self
    {
        // unset the owning side of the relation if necessary
        if ($epreuve === null && $this->epreuve !== null) {
            $this->epreuve->setElement(null);
        }

        // set the owning side of the relation if necessary
        if ($epreuve !== null && $epreuve->getElement() !== $this) {
            $epreuve->setElement($this);
        }

        $this->epreuve = $epreuve;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }
}
