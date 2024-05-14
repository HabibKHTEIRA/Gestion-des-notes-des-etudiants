<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EtudiantRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Cascade;

#[ORM\Entity(repositoryClass: EtudiantRepository::class)]
//#[UniqueEntity(fields:['email','numetd'], message:'This value is already used.')]
class Etudiant
{
    #[ORM\Id]
    #[Assert\NotBlank()]
    #[ORM\Column(length: 8)]
    private ?string $numetd = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    private ?string $prenom = null;


    #[Assert\Email()]
    #[ORM\Column(type:"string",length: 60,unique:true)]
    private ?string $email = null;

    #[ORM\Column(length: 1)]
    private ?string $sexe = null;

    #[ORM\Column(length: 70,nullable:true)]
    private ?string $adresse = null;

    #[ORM\Column(nullable:true, length: 40)]
    private ?string $tel = null;

    #[ORM\Column(nullable:true, length: 40)]
    private ?string $filiere = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable:true)]
    private ?\DateTimeInterface $datnaiss = null;

    #[ORM\Column(length: 40, nullable: true)]
    private ?string $depnaiss = null;

    #[ORM\Column(length: 40, nullable: true)]
    private ?string $villnaiss = null;

    #[ORM\Column(length: 40, nullable: true)]
    private ?string $paysnaiss = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $nationalite = null;

    #[ORM\Column(length: 80, nullable: true)]
    private ?string $sports = null;

    #[ORM\Column(length: 80, nullable: true)]
    private ?string $handicape = null;

    #[ORM\Column(nullable:true,length: 50)]
    private ?string $derdiplome = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable:true)]
    private ?\DateTimeInterface $dateinsc = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $registre = null;

    #[ORM\Column(length: 30,nullable: true)]
    private ?string $statut = null;

    #[ORM\ManyToOne(inversedBy: 'etudiants')]
    #[ORM\JoinColumn(name:"codegrp", referencedColumnName:"codegrp",onDelete:"SET NULL")]
    private ?Groupe $groupe = null;

    #[ORM\OneToMany(mappedBy: 'etudiant', targetEntity: Choix::class, orphanRemoval: true)]
    private Collection $choixes;

    #[ORM\OneToOne(mappedBy: 'etudiant', cascade: ['persist', 'remove'])]
    private ?Resultatbac $resultatbac = null;

    #[ORM\OneToMany(mappedBy: 'etudiant', targetEntity: Etudsup::class, orphanRemoval: true)]
    private Collection $etudsups;

    #[ORM\OneToMany(mappedBy: 'etudiant', targetEntity: Note::class, orphanRemoval: true)]
    private Collection $notes;

    #[ORM\Column(nullable:true,type: 'boolean',options:['default' => false])]
    private ?bool $hide = false;

    /**
     * @var Collection<int, FormationInt>
     */
    #[ORM\OneToMany(targetEntity: FormationInt::class, mappedBy: 'etudiant', orphanRemoval: true)]
    private Collection $formationInts;

    public function __construct()
    {
        $this->choixes = new ArrayCollection();
        $this->etudsups = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->formationInts = new ArrayCollection();
    }

    

    public function getNumetd(): ?string
    {
        return $this->numetd;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getDatnaiss(): ?\DateTimeInterface
    {
        return $this->datnaiss;
    }

    public function setDatnaiss(\DateTimeInterface $datnaiss): self
    {
        $this->datnaiss = $datnaiss;

        return $this;
    }

    public function getDepnaiss(): ?string
    {
        return $this->depnaiss;
    }

    public function setDepnaiss(?string $depnaiss): self
    {
        $this->depnaiss = $depnaiss;

        return $this;
    }

    public function getVillnaiss(): ?string
    {
        return $this->villnaiss;
    }

    public function setVillnaiss(?string $villnaiss): self
    {
        $this->villnaiss = $villnaiss;

        return $this;
    }

    public function getPaysnaiss(): ?string
    {
        return $this->paysnaiss;
    }

    public function setPaysnaiss(?string $paysnaiss): self
    {
        $this->paysnaiss = $paysnaiss;

        return $this;
    }

    public function getNationalite(): ?string
    {
        return $this->nationalite;
    }

    public function setNationalite(?string $nationalite): self
    {
        $this->nationalite = $nationalite;

        return $this;
    }

    public function getSports(): ?string
    {
        return $this->sports;
    }

    public function setSports(?string $sports): self
    {
        $this->sports = $sports;

        return $this;
    }

    public function getHandicape(): ?string
    {
        return $this->handicape;
    }

    public function setHandicape(?string $handicape): self
    {
        $this->handicape = $handicape;

        return $this;
    }

    public function getDerdiplome(): ?string
    {
        return $this->derdiplome;
    }

    public function setDerdiplome(string $derdiplome): self
    {
        $this->derdiplome = $derdiplome;

        return $this;
    }

    public function getDateinsc(): ?\DateTimeInterface
    {
        return $this->dateinsc;
    }

    public function setDateinsc(\DateTimeInterface $dateinsc): self
    {
        $this->dateinsc = $dateinsc;

        return $this;
    }

    public function getRegistre(): ?string
    {
        return $this->registre;
    }

    public function setRegistre(?string $registre): self
    {
        $this->registre = $registre;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }


    public function getGroupe(): ?Groupe
    {
        return $this->groupe;
    }

    public function setGroupe(?Groupe $groupe): self
    {
        $this->groupe = $groupe;

        return $this;
    }


    /**
     * @return Collection<int, Choix>
     */
    public function getChoixes(): Collection
    {
        return $this->choixes;
    }

    public function addChoix(Choix $choix): self
    {
        if (!$this->choixes->contains($choix)) {
            $this->choixes->add($choix);
            $choix->setEtudiant($this);
        }

        return $this;
    }

    public function removeChoix(Choix $choix): self
    {
        if ($this->choixes->removeElement($choix)) {
            // set the owning side to null (unless already changed)
            if ($choix->getEtudiant() === $this) {
                $choix->setEtudiant(null);
            }
        }

        return $this;
    }

    public function getResultatbac(): ?Resultatbac
    {
        return $this->resultatbac;
    }

    public function setResultatbac(Resultatbac $resultatbac): self
    {
        // set the owning side of the relation if necessary
        if ($resultatbac->getEtudiant() !== $this) {
            $resultatbac->setEtudiant($this);
        }

        $this->resultatbac = $resultatbac;

        return $this;
    }

    /**
     * @return Collection<int, Etudsup>
     */
    public function getEtudsups(): Collection
    {
        return $this->etudsups;
    }

    public function addEtudsup(Etudsup $etudsup): self
    {
        if (!$this->etudsups->contains($etudsup)) {
            $this->etudsups->add($etudsup);
            $etudsup->setEtudiant($this);
        }

        return $this;
    }

    public function removeEtudsup(Etudsup $etudsup): self
    {
        if ($this->etudsups->removeElement($etudsup)) {
            // set the owning side to null (unless already changed)
            if ($etudsup->getEtudiant() === $this) {
                $etudsup->setEtudiant(null);
            }
        }

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
            $note->setEtudiant($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getEtudiant() === $this) {
                $note->setEtudiant(null);
            }
        }

        return $this;
    }



    /**
     * Set the value of numetd
     *
     * @return  self
     */ 
    public function setNumetd($numetd)
    {
        $this->numetd = $numetd;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getNumetd().' '.$this->getNom().' '.$this->getPrenom().' '.$this->getEmail().' '.$this->getSexe();
    }

    public function isHide(): ?bool
    {
        return $this->hide;
    }

    public function setHide(?bool $hide): self
    {
        $this->hide = $hide;

        return $this;
    }


   
    

    /**
    * @return string
    */
    public function getFiliere(): string {
    	return $this->filiere;
    }

    /**
    * @param string $filiere
    */
    public function setFiliere(string $filiere): void {
    	$this->filiere = $filiere;
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
            $formationInt->setEtudiant($this);
        }

        return $this;
    }

    public function removeFormationInt(FormationInt $formationInt): static
    {
        if ($this->formationInts->removeElement($formationInt)) {
            // set the owning side to null (unless already changed)
            if ($formationInt->getEtudiant() === $this) {
                $formationInt->setEtudiant(null);
            }
        }

        return $this;
    }
}
