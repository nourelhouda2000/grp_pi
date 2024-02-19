<?php

namespace App\Entity;

use App\Repository\ActiviteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Dompdf\Dompdf;

#[ORM\Entity(repositoryClass: ActiviteRepository::class)]
class Activite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"nom est requis")]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z]+$/',
        message: 'Le nom ne peut contenir que des lettres'
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"description est requis")]
     #[Assert\Length(
    min: 3,
    max: 50,
    minMessage: 'La description doit comporter au moins {{ limit }} caractères',
    maxMessage: 'La description ne peut pas dépasser {{ limit }} caractères'
      )]
    private ?string $description = null;


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"categorie est requis")]
    #[Assert\Length(
        min: 4,
        max: 15,
        minMessage: 'La catégorie doit comporter au moins {{ limit }} caractères',
        maxMessage: 'La catégorie ne peut pas dépasser {{ limit }} caractères'
    )]
    #[Assert\Regex(
        pattern: '/^[A-Z][a-zA-Z\s]+$/',
        message: 'La catégorie doit commencer par une majuscule'
    )]
    private ?string $categorie = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le niveau est requis')]
    #[Assert\Choice(choices: ['débutant', 'intermédiaire', 'avancé'], message: 'Le niveau doit être débutant, intermédiaire ou avancé')]
    private ?string $niveau = null;

    public const DEBUTANT = 'débutant';
    public const INTERMEDIAIRE = 'intermédiaire';
    public const AVANCE = 'avancé';

    #[ORM\OneToMany(targetEntity: Exercice::class, mappedBy: 'activite')]
    private Collection $exercice;

    public function __construct()
    {
        $this->exercice = new ArrayCollection();
    }

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

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): static
    {
        $this->categorie = $categorie;

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

    /**
     * @return Collection<int, Exercice>
     */
    public function getExercice(): Collection
    {
        return $this->exercice;
    }

    public function addExercice(Exercice $exercice): static
    {
        if (!$this->exercice->contains($exercice)) {
            $this->exercice->add($exercice);
            $exercice->setActivite($this);
        }

        return $this;
    }

    public function removeExercice(Exercice $exercice): static
    {
        if ($this->exercice->removeElement($exercice)) {
            // set the owning side to null (unless already changed)
            if ($exercice->getActivite() === $this) {
                $exercice->setActivite(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }
}
