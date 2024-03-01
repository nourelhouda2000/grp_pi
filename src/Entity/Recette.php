<?php

namespace App\Entity;

use App\Repository\RecetteRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use App\Entity\Nutritions;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RecetteRepository::class)]
#[Broadcast]
class Recette
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "nom not selected")]
   
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "ingredient not selected")]
    private ?string $ingredient = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "category not selected")]
    private ?string $category = null;

    #[ORM\ManyToOne(targetEntity: Nutritions::class, inversedBy: 'recettes')]
    #[ORM\JoinColumn(name: 'nutrition_id', referencedColumnName: 'id')]
    #[Assert\NotBlank(message: "nutrition not selected")]
    private ?Nutritions $nutrition;
       /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
   /*  private $imageFileName; */
    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(?string $id): static
    {
        $this->id = $id;

        return $this;
    }
    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getIngredient(): ?string
    {
        return $this->ingredient;
    }

    public function setIngredient(?string $ingredient): static
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getNutrition(): ?Nutritions
    {
        return $this->nutrition;
    }

    public function setNutrition(?Nutritions $nutrition): static
    {
        $this->nutrition = $nutrition;

        return $this;
    }
/*     public function getImageFileName(): ?string
    {
        return $this->imageFileName;
    }

    public function setImageFileName(?string $imageFileName): self
    {
        $this->imageFileName = $imageFileName;
    
        return $this; 
    } */
    
}
