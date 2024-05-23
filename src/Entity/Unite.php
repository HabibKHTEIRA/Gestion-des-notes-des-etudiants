<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UniteRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity(fields:'codeunite', message:'This value is already used.')]
#[ORM\Entity(repositoryClass: UniteRepository::class)]
class Unite
{
    #[ORM\Id]
    #[ORM\Column(length: 20)]
    private ?string $codeunite = null;

    #[ORM\Column(length: 60)]
    private ?string $nomunite = null;

    #[ORM\Column(nullable: true)]
    private ?int $coeficient = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $respunite = null;

    #[ORM\ManyToOne(inversedBy: 'unites')]
    #[ORM\JoinColumn(name:"bloc", referencedColumnName:"codebloc")]
    private ?Bloc $bloc = null;

    #[ORM\OneToMany(mappedBy: 'unite', targetEntity: Matiere::class)]
    private Collection $matieres;

    #[ORM\OneToOne(inversedBy: 'unite', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name:"element", referencedColumnName:"codeelt")]
    private ?Element $element = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $codeTrouve = null;

    public function __construct()
    {
        $this->matieres = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getNomunite();
    }

    

    public function getCodeunite(): ?string
    {
        return $this->codeunite;
    }

    public function getNomunite(): ?string
    {
        return $this->nomunite;
    }

    public function setNomunite(string $nomunite): self
    {
        $this->nomunite = $nomunite;

        return $this;
    }

    public function getCoeficient(): ?int
    {
        return $this->coeficient;
    }

    public function setCoeficient(int $coeficient): self
    {
        $this->coeficient = $coeficient;

        return $this;
    }

    public function getRespunite(): ?string
    {
        return $this->respunite;
    }

    public function setRespunite(?string $respunite): self
    {
        $this->respunite = $respunite;

        return $this;
    }

    public function getBloc(): ?Bloc
    {
        return $this->bloc;
    }

    public function setBloc(?Bloc $bloc): self
    {
        $this->bloc = $bloc;

        return $this;
    }

    /**
     * @return Collection<int, Matiere>
     */
    public function getMatieres(): Collection
    {
        return $this->matieres;
    }

    public function addMatiere(Matiere $matiere): self
    {
        if (!$this->matieres->contains($matiere)) {
            $this->matieres->add($matiere);
            $matiere->setUnite($this);
        }

        return $this;
    }

    public function removeMatiere(Matiere $matiere): self
    {
        if ($this->matieres->removeElement($matiere)) {
            // set the owning side to null (unless already changed)
            if ($matiere->getUnite() === $this) {
                $matiere->setUnite(null);
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
     * Set the value of codeunite
     *
     * @return  self
     */ 
    public function setCodeunite($codeunite)
    {
        $this->codeunite = $codeunite;

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
