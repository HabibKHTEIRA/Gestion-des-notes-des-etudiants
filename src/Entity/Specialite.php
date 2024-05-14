<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\SpecialiteRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity(fields:"codespe", message:'This value is already used.')]
#[ORM\Entity(repositoryClass: SpecialiteRepository::class)]
class Specialite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(length: 10)]
    private ?string $codespe = null;

    #[ORM\Column(length: 50)]
    private ?string $nomspe = null;


    public function getCodespe(): ?string
    {
        return $this->codespe;
    }

    public function getNomspe(): ?string
    {
        return $this->nomspe;
    }

    public function setNomSpe(string $nomspe): self
    {
        $this->nomspe = $nomspe;

        return $this;
    }

    /**
     * Set the value of codespe
     *
     * @return  self
     */ 
    public function setCodespe($codespe)
    {
        $this->codespe = $codespe;

        return $this;
    }
}
