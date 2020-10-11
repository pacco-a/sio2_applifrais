<?php

namespace App\Entity;

use App\Repository\FraisForfaitRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FraisForfaitRepository::class)
 */
class FraisForfait
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $libelle;

    /**
     * @ORM\Column(type="float")
     */
    private $montant;

    /**
     * @ORM\OneToMany(targetEntity=EntreeFraisForfait::class, mappedBy="fraisForfait", orphanRemoval=true)
     */
    private $entreeFraisForfaits;

    public function __construct()
    {
        $this->entreeFraisForfaits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

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
            $entreeFraisForfait->setFraisForfait($this);
        }

        return $this;
    }

    public function removeEntreeFraisForfait(EntreeFraisForfait $entreeFraisForfait): self
    {
        if ($this->entreeFraisForfaits->contains($entreeFraisForfait)) {
            $this->entreeFraisForfaits->removeElement($entreeFraisForfait);
            // set the owning side to null (unless already changed)
            if ($entreeFraisForfait->getFraisForfait() === $this) {
                $entreeFraisForfait->setFraisForfait(null);
            }
        }

        return $this;
    }
}
