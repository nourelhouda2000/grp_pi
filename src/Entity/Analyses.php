<?php

namespace App\Entity;

use App\Repository\AnalysesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AnalysesRepository::class)]
class Analyses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Poids est requis")]
    #[Assert\Range(
            min : 40,
            max : 300,
          minMessage : "Le poids ne peut pas être inférieur à 40 kg",
         maxMessage : "Le poids ne peut pas être supérieur à 300 kg"
         )]
    #[Assert\Regex(
           pattern:"/^\d+$/",
         message:"Ce champ ne peut contenir que des chiffres."
        )]
    
    private ?int $poids = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Taille est requis")]
    #[Assert\Regex(
        pattern:"/^\d+$/",
      message:"Ce champ ne peut contenir que des chiffres."
     )]
     #[Assert\Range(
        min : 20,
        max : 200,
      minMessage : "La taille ne peut pas être inférieur à 20 cm",
     maxMessage : "La taille ne peut pas être supérieur à 200 cm"
     )]

    private ?int $taille = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Poids Ideal est requis")]
    #[Assert\Regex(
        pattern:"/^\d+$/",
      message:"Ce champ ne peut contenir que des chiffres."
     )]
     #[Assert\Range(
        min : 40,
        max : 100,
      minMessage : "Le poids ne peut pas être inférieur à 40 kg",
     maxMessage : "Le poids ideal ne peut pas être supérieur à 100 kg"
     )]
    private ?int $poidsideal = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"IMC est requis")]
    #[Assert\Regex(
        pattern:"/^\d+$/",
      message:"Ce champ ne peut contenir que des chiffres."
     )]
     #[Assert\Range(
        min : 10,
        max : 40,
      minMessage : "L'IMC ne peut pas être inférieur à 10",
     maxMessage : "L'IMC ne peut pas être supérieur à 40"
     )]
    private ?int $IMC = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Taux est requis")]
    #[Assert\Regex(
        pattern:"/^\d+$/",
      message:"Ce champ ne peut contenir que des chiffres."
     )]
     #[Assert\Range(
        min : 0,
        max : 100,
      minMessage : "Le taux de graisse ne peut pas être inférieur à 0 % ",
     maxMessage : "Le taux de graisse ne peut pas être supérieur à 100%"
     )]
    private ?int $taux = null;

    #[ORM\ManyToOne(inversedBy: 'Analyses')]
    #[ORM\JoinColumn(name:'santeid_id',referencedColumnName:'id')]
    private ?Sante $Sante = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPoids(): ?int
    {
        return $this->poids;
    }

    public function setPoids(int $poids): static
    {
        $this->poids = $poids;

        return $this;
    }

    public function getTaille(): ?int
    {
        return $this->taille;
    }

    public function setTaille(int $taille): static
    {
        $this->taille = $taille;

        return $this;
    }

    public function getPoidsideal(): ?int
    {
        return $this->poidsideal;
    }

    public function setPoidsideal(int $poidsideal): static
    {
        $this->poidsideal = $poidsideal;

        return $this;
    }

    public function getIMC(): ?int
    {  
        return $this->IMC;
    }

    public function setIMC(int $IMC): static
    {   
       
        $this->IMC = $IMC;

        return $this;
    }

    public function getTaux(): ?int
    {
        return $this->taux;
    }

    public function setTaux(int $taux): static
    {
        $this->taux = $taux;

        return $this;
    }

    public function getSante(): ?Sante
    {
        return $this->Sante;
    }

    public function setSante(?Sante $Sante): static
    {
        $this->Sante = $Sante;

        return $this;
    }
}
