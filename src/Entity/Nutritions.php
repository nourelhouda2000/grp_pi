<?php

namespace App\Entity;

use App\Repository\NutritionsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use App\Entity\Recette;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: NutritionsRepository::class)]
#[Broadcast]
class Nutritions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
 
    #[Assert\NotBlank(message: "calories not selected")]
    private ?string $calories = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "proteines not selected")]
    private ?string $proteines = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "glucides not selected")]
    
    #[Assert\Length(
        min: 2,
        minMessage: "glucides must have at least {{ limit }} characters"
    )]
    
    private ?string $glucides = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "lipides not selected")]

    private ?string $lipides = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "fibres not selected")]

    private ?string $fibres = null;

    #[ORM\OneToMany(targetEntity: Recette::class, mappedBy: 'nutrition_id')]
    #[Assert\NotBlank(message: "recettes not selected")]

    private Collection $recettes;

    public function __construct()
    {
        $this->recettes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCalories(): ?string
    {
        return $this->calories;
    }

    public function setCalories(?string $calories): static
    {
        $this->calories = $calories;

        return $this;
    }

    public function getProteines(): ?string
    {
        return $this->proteines;
    }

    public function setProteines(?string $proteines): static
    {
        $this->proteines = $proteines;

        return $this;
    }

    public function getGlucides(): ?string
    {
        return $this->glucides;
    }

    public function setGlucides(?string $glucides): static
    {
        $this->glucides = $glucides;

        return $this;
    }

    public function getLipides(): ?string
    {
        return $this->lipides;
    }

    public function setLipides(?string $lipides): static
    {
        $this->lipides = $lipides;

        return $this;
    }

    public function getFibres(): ?string
    {
        return $this->fibres;
    }

    public function setFibres(?string $fibres): static
    {
        $this->fibres = $fibres;

        return $this;
    }

    /**
     * @return Collection<int, Recettes>
     */
    public function getRecettes(): Collection
    {
        return $this->recettes;
    }

    public function addRecette(Recettes $recette): static
    {
        if (!$this->recettes->contains($recette)) {
            $this->recettes->add($recette);
            $recette->setYes($this);
        }

        return $this;
    }

    public function removeRecette(Recettes $recette): static
    {
        if ($this->recettes->removeElement($recette)) {
            // set the owning side to null (unless already changed)
            if ($recette->getYes() === $this) {
                $recette->setYes(null);
            }
        }

        return $this;
    }
    public function __tostring()
    {

        return $this->id;
    }
}
