<?php

namespace App\Entity;

use App\Repository\FiliereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity('codefiliere')]
#[ORM\Entity(repositoryClass: FiliereRepository::class)]
class Filiere
{
    #[ORM\Id]
    #[ORM\Column(length: 20)]
    private ?string $codefiliere = null;

    #[ORM\Column(length: 30)]
    private ?string $nomfiliere = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $respfiliere = null;

    #[ORM\OneToMany(mappedBy: 'filiere', targetEntity: Bloc::class)]
    private Collection $blocs;

    #[ORM\OneToOne(inversedBy: 'filiere', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name: "element", referencedColumnName: "codeelt")]
    private ?Element $element = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $nivLMD = null;

    /**
     * @var Collection<int, FormationInt>
     */
    #[ORM\OneToMany(targetEntity: FormationInt::class, mappedBy: 'filiere', orphanRemoval: true)]
    private Collection $formationInts;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $codeTrouve = null;

    public function __construct()
    {
        $this->blocs = new ArrayCollection();
        $this->formationInts = new ArrayCollection();
    }



    public function getCodefiliere(): ?string
    {
        return $this->codefiliere;
    }

    public function getNomfiliere(): ?string
    {
        return $this->nomfiliere;
    }

    public function setNomfiliere(string $nomfiliere): self
    {
        $this->nomfiliere = $nomfiliere;

        return $this;
    }

    public function getRespfiliere(): ?string
    {
        return $this->respfiliere;
    }

    public function setRespfiliere(?string $respfiliere): self
    {
        $this->respfiliere = $respfiliere;

        return $this;
    }

    /**
     * @return Collection<int, Bloc>
     */
    public function getBlocs(): Collection
    {
        return $this->blocs;
    }

    public function addBloc(Bloc $bloc): self
    {
        if (!$this->blocs->contains($bloc)) {
            $this->blocs->add($bloc);
            $bloc->setFiliere($this);
        }

        return $this;
    }

    public function removeBloc(Bloc $bloc): self
    {
        if ($this->blocs->removeElement($bloc)) {
            // set the owning side to null (unless already changed)
            if ($bloc->getFiliere() === $this) {
                $bloc->setFiliere(null);
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
     * Set the value of codefiliere
     *
     * @return  self
     */
    public function setCodefiliere($codefiliere)
    {
        $this->codefiliere = $codefiliere;

        return $this;
    }
    public function __toString()
    {
        return $this->getNomfiliere();
    }

    public function getNivLMD(): ?string
    {
        return $this->nivLMD;
    }

    public function setNivLMD(?string $nivLMD): static
    {
        $this->nivLMD = $nivLMD;

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
            $formationInt->setFiliere($this);
        }

        return $this;
    }

    public function removeFormationInt(FormationInt $formationInt): static
    {
        if ($this->formationInts->removeElement($formationInt)) {
            // set the owning side to null (unless already changed)
            if ($formationInt->getFiliere() === $this) {
                $formationInt->setFiliere(null);
            }
        }

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
