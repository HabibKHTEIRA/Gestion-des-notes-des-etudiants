<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BlocRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity(fields:'codebloc', message:'This value is already used.')]
#[ORM\Entity(repositoryClass: BlocRepository::class)]
class Bloc
{
    #[ORM\Id]
    #[ORM\Column(length: 20)]
    private ?string $codebloc = null;

    #[ORM\Column(length: 60)]
    private ?string $nombloc = null;

    #[ORM\Column(nullable: true)]
    private ?int $noteplancher = null;

    #[ORM\ManyToOne(inversedBy: 'blocs')]
    #[ORM\JoinColumn(name:"filiere", referencedColumnName:"codefiliere")]
    private ?Filiere $filiere = null;

    #[ORM\OneToMany(mappedBy: 'bloc', targetEntity: Unite::class)]
    private Collection $unites;

    #[ORM\OneToOne(inversedBy: 'bloc', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name:"element", referencedColumnName:"codeelt")]
    private ?Element $element = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $codeTrouve = null;

    public function __construct()
    {
        $this->unites = new ArrayCollection();
    }

    
    public function __toString()
    {
        return $this->getCodebloc();
    }

    public function getCodebloc(): ?string
    {
        return $this->codebloc;
    }

    public function getNombloc(): ?string
    {
        return $this->nombloc;
    }

    public function setNombloc(string $nombloc): self
    {
        $this->nombloc = $nombloc;

        return $this;
    }

    public function getNoteplancher(): ?int
    {
        return $this->noteplancher;
    }

    public function setNoteplancher(int $noteplancher): self
    {
        $this->noteplancher = $noteplancher;

        return $this;
    }

    public function getFiliere(): ?Filiere
    {
        return $this->filiere;
    }

    public function setFiliere(?Filiere $filiere): self
    {
        $this->filiere = $filiere;

        return $this;
    }

    /**
     * @return Collection<int, Unite>
     */
    public function getUnites(): Collection
    {
        return $this->unites;
    }

    public function addUnite(Unite $unite): self
    {
        if (!$this->unites->contains($unite)) {
            $this->unites->add($unite);
            $unite->setBloc($this);
        }

        return $this;
    }

    public function removeUnite(Unite $unite): self
    {
        if ($this->unites->removeElement($unite)) {
            // set the owning side to null (unless already changed)
            if ($unite->getBloc() === $this) {
                $unite->setBloc(null);
            }
        }

        return $this;
    }

    public function getElement(): ?Element
    {
        return $this->element;
    }

    public function setElement(?Element $element): self
    {
        $this->element = $element;

        return $this;
    }


    /**
     * Set the value of codebloc
     *
     * @return  self
     */ 
    public function setCodebloc($codebloc)
    {
        $this->codebloc = $codebloc;

        return $this;
    }

    public function getCodeTrouve(): ?string
    {
        return $this->codeTrouve;
    }

    public function setCodeTrouve(?string $codeTrouve): static
    {
        $this->codeTrouve = $codeTrouve;

        return $this;
    }
}
