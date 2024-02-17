<?php

namespace App\Entity;

use App\Repository\SanteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SanteRepository::class)]
class Sante
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Maladie est requis")]
    #[Assert\Length(
        min: 3,
        max: 15,
        minMessage: 'La maladie doit comporter au moins 3 caractères',
        maxMessage: 'La maladie ne peut pas dépasser 15 caractères'
    )]
    #[Assert\Regex(
        pattern: '/^[A-Z][a-zA-Z\s]+$/',
        message: 'La maladie doit commencer par une majuscule'
    )]
    private ?string $maladie = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Medicament est requis")]
    #[Assert\Length(
        min: 3,
        max: 15,
        minMessage: 'La medicament doit comporter au moins 3 caractères',
        maxMessage: 'La medicament ne peut pas dépasser 15 caractères'
    )]
    #[Assert\Regex(
        pattern: '/^[A-Z][a-zA-Z\s]+$/',
        message: 'La medicament doit commencer par une majuscule'
    )]
    
    private ?string $medicament = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Calories est requis")]
    #[Assert\Regex(
        pattern:"/^\d+$/",
      message:"Ce champ ne peut contenir que des chiffres."
     )]
     #[Assert\Range(
        min : 1000,
        max : 3000,
      minMessage : "Les claories ne peut pas être inférieur à 1000",
     maxMessage : "Les calories ne peut pas être supérieur à 3000"
     )]
    private ?int $calories = null;

    #[ORM\OneToMany(targetEntity: Analyses::class, mappedBy: 'santeid_id')]
    private Collection $Analyses;

    public function __construct()
    {
        $this->Analyses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMaladie(): ?string
    {
        return $this->maladie;
    }

    public function setMaladie(string $maladie): static
    {
        $this->maladie = $maladie;

        return $this;
    }

    public function getMedicament(): ?string
    {
        return $this->medicament;
    }

    public function setMedicament(string $medicament): static
    {
        $this->medicament = $medicament;

        return $this;
    }

    public function getCalories(): ?int
    {
        return $this->calories;
    }

    public function setCalories(int $calories): static
    {
        $this->calories = $calories;

        return $this;
    }

    /**
     * @return Collection<int, Analyses>
     */
    public function getAnalyses(): Collection
    {
        return $this->Analyses;
    }

    public function addAnalysis(Analyses $analysis): static
    {
        if (!$this->Analyses->contains($analysis)) {
            $this->Analyses->add($analysis);
            $analysis->setSante($this);
        }

        return $this;
    }

    public function removeAnalysis(Analyses $analysis): static
    {
        if ($this->Analyses->removeElement($analysis)) {
            // set the owning side to null (unless already changed)
            if ($analysis->getSante() === $this) {
                $analysis->setSante(null);
            }
        }

        return $this;
    }
    public function __toString(){
        return $this->maladie;
    }
}
