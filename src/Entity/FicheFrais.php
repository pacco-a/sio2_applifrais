<?php

namespace App\Entity;

use App\Repository\FicheFraisRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FicheFraisRepository::class)
 */
class FicheFrais
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $month;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ficheFrais")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idVisisteur;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbProofs;

    /**
     * @ORM\Column(type="float")
     */
    private $validAmount;

    /**
     * @ORM\Column(type="date")
     */
    private $updateDate;

    /**
     * @ORM\OneToMany(targetEntity=EntreeFraisForfait::class, mappedBy="ficheFrais", orphanRemoval=true)
     */
    private $entreeFraisForfaits;

    /**
     * @ORM\ManyToOne(targetEntity=Etat::class, inversedBy="ficheFrais")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idEtat;

    /**
     * @ORM\OneToMany(targetEntity=EntreeFraisHorsForfait::class, mappedBy="ficheFrais", orphanRemoval=true)
     */
    private $entreeFraisHorsForfaits;

    /**
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ficheFraisValidee")
     */
    // EPREUVE E4 : ajout du champ validateur
    private $validateur;

    public function __construct()
    {
        $this->entreeFraisForfaits = new ArrayCollection();
        $this->entreeFraisHorsForfaits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMonth(): ?int
    {
        return $this->month;
    }

    public function setMonth(int $month): self
    {
        $this->month = $month;

        return $this;
    }

    public function getIdVisisteur(): ?User
    {
        return $this->idVisisteur;
    }

    public function setIdVisisteur(?User $idVisisteur): self
    {
        $this->idVisisteur = $idVisisteur;

        return $this;
    }

    public function getNbProofs(): ?int
    {
        return $this->nbProofs;
    }

    public function setNbProofs(int $nbProofs): self
    {
        $this->nbProofs = $nbProofs;

        return $this;
    }

    public function getValidAmount(): ?float
    {
        return $this->validAmount;
    }

    public function setValidAmount(float $validAmount): self
    {
        $this->validAmount = $validAmount;

        return $this;
    }

    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->updateDate;
    }

    public function setUpdateDate(\DateTimeInterface $updateDate): self
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * @return Collection|EntreeFraisForfait[]
     */
    public function getEntreeFraisForfaits(): Collection
    {
        return $this->entreeFraisForfaits;
    }

    public function addEntreeFraisForfait(EntreeFraisForfait $entreeFraisForfait): self
    {
        if (!$this->entreeFraisForfaits->contains($entreeFraisForfait)) {
            $this->entreeFraisForfaits[] = $entreeFraisForfait;
            $entreeFraisForfait->setFicheFrais($this);
        }

        return $this;
    }

    public function removeEntreeFraisForfait(EntreeFraisForfait $entreeFraisForfait): self
    {
        if ($this->entreeFraisForfaits->contains($entreeFraisForfait)) {
            $this->entreeFraisForfaits->removeElement($entreeFraisForfait);
            // set the owning side to null (unless already changed)
            if ($entreeFraisForfait->getFicheFrais() === $this) {
                $entreeFraisForfait->setFicheFrais(null);
            }
        }

        return $this;
    }

    public function getIdEtat(): ?Etat
    {
        return $this->idEtat;
    }

    public function setIdEtat(?Etat $idEtat): self
    {
        $this->idEtat = $idEtat;

        return $this;
    }

    /**
     * @return Collection|EntreeFraisHorsForfait[]
     */
    public function getEntreeFraisHorsForfaits(): Collection
    {
        return $this->entreeFraisHorsForfaits;
    }

    public function addEntreeFraisHorsForfait(EntreeFraisHorsForfait $entreeFraisHorsForfait): self
    {
        if (!$this->entreeFraisHorsForfaits->contains($entreeFraisHorsForfait)) {
            $this->entreeFraisHorsForfaits[] = $entreeFraisHorsForfait;
            $entreeFraisHorsForfait->setFicheFrais($this);
        }

        return $this;
    }

    public function removeEntreeFraisHorsForfait(EntreeFraisHorsForfait $entreeFraisHorsForfait): self
    {
        if ($this->entreeFraisHorsForfaits->contains($entreeFraisHorsForfait)) {
            $this->entreeFraisHorsForfaits->removeElement($entreeFraisHorsForfait);
            // set the owning side to null (unless already changed)
            if ($entreeFraisHorsForfait->getFicheFrais() === $this) {
                $entreeFraisHorsForfait->setFicheFrais(null);
            }
        }

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getValidateur(): ?User
    {
        return $this->validateur;
    }

    public function setValidateur(?User $validateur): self
    {
        $this->validateur = $validateur;

        return $this;
    }
}
