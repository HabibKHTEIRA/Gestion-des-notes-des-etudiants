<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MatiereRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity(fields:"codemat", message:'This value is already used.')]
#[ORM\Entity(repositoryClass: MatiereRepository::class)]
class Matiere
{
    #[ORM\Id]
    #[ORM\Column(length: 20)]
    private ?string $codemat = null;

    #[ORM\Column(length: 40)]
    private ?string $nommat = null;

    #[ORM\Column(length: 3)]
    private ?string $periode = null;

    #[ORM\ManyToOne(inversedBy: 'matieres')]
    #[ORM\JoinColumn(name:"unite", referencedColumnName:"codeunite")]
    private ?Unite $unite = null;

    #[ORM\OneToMany(mappedBy: 'matiere', targetEntity: Epreuve::class)]
    private Collection $epreuves;

    #[ORM\OneToOne(inversedBy: 'matiere', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name:"element", referencedColumnName:"codeelt")]
    private ?Element $element = null;

    public function __construct()
    {
        $this->epreuves = new ArrayCollection();
    }



    public function getCodemat(): ?string
    {
        return $this->codemat;
    }


    public function getNommat(): ?string
    {
        return $this->nommat;
    }

    public function setNommat(string $nommat): self
    {
        $this->nommat = $nommat;

        return $this;
    }

    public function getPeriode(): ?string
    {
        return $this->periode;
    }

    public function setPeriode(string $periode): self
    {
        $this->periode = $periode;

        return $this;
    }

    public function getUnite(): ?Unite
    {
        return $this->unite;
    }

    public function setUnite(?Unite $unite): self
    {
        $this->unite = $unite;

        return $this;
    }

    /**
     * @return Collection<int, Epreuve>
     */
    public function getEpreuves(): Collection
    {
        return $this->epreuves;
    }

    public function addEpreufe(Epreuve $epreufe): self
    {
        if (!$this->epreuves->contains($epreufe)) {
            $this->epreuves->add($epreufe);
            $epreufe->setMatiere($this);
        }

        return $this;
    }

    public function removeEpreufe(Epreuve $epreufe): self
    {
        if ($this->epreuves->removeElement($epreufe)) {
            // set the owning side to null (unless already changed)
            if ($epreufe->getMatiere() === $this) {
                $epreufe->setMatiere(null);
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
     * Set the value of codemat
     *
     * @return  self
     */ 
    public function setCodemat($codemat)
    {
        $this->codemat = $codemat;

        return $this;
    }
}
