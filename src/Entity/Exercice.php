<?php

namespace App\Entity;

use App\Repository\ExerciceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ExerciceRepository::class)]
class Exercice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Nom est requis")]
    #[Assert\Length(max: 20, maxMessage: "Le nom ne peut pas dépasser {{ limit }} caractères.")]
     private string $nom;


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Description est requis")]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Niveau est requis")]
    #[Assert\Length(min: 5, minMessage: "Le niveau doit avoir au moins {{ limit }} caractères.")]
    private ?string $niveau = null;
    
    #[ORM\ManyToOne(inversedBy: 'exercice')]
    #[ORM\JoinColumn(name: 'activite_id', referencedColumnName: 'id')]
    #[Assert\NotNull]
    private ?Activite $Activite = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(message: "Nombre de répétition est requis")]
    #[Assert\Type(type: 'integer', message: "Veuillez saisir un nombre entier.")]
    private ?int $nombreRepetition = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    public function setNiveau(string $niveau): static
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getActivite(): ?Activite
    {
        return $this->Activite;
    }

    public function setActivite(?Activite $Activite): static
    {
        $this->Activite = $Activite;

        return $this;
    }

    public function getNombreRepetition(): ?int
    {
        return $this->nombreRepetition;
    }

    public function setNombreRepetition(int $nombreRepetition): static
    {
        $this->nombreRepetition = $nombreRepetition;

        return $this;
    }
}
