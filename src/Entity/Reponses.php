<?php

namespace App\Entity;

use App\Repository\ReponsesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;
#[ORM\Entity(repositoryClass: ReponsesRepository::class)]
class Reponses
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idrep = null;
   
   
     
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le champ description ne peut pas être vide")]
    private ?string $description = null;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    
    #[Assert\EqualTo("today", message: "The donation date should be today")]
  
    private ?\DateTimeInterface $daterec = null;

   


    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "note reponse not selected")]
    private ?string $notereponse = null;

    #[ORM\ManyToOne(inversedBy: 'idreponses')]
  
    #[ORM\JoinColumn(name:'idreclamations',referencedColumnName:'idrec')]
   
    
    
  
     
     
     
    #[Assert\NotBlank(message: "reclamations not selected")]
    private ?Reclamations $reclamations = null;

  

    public function getIdrep(): ?int
    {
        return $this->idrep;
    }

    public function getDaterec(): ?\DateTimeInterface
    {
        return $this->daterec;
    }

    public function setDaterec(\DateTimeInterface $daterec): static
    {
        $this->daterec = $daterec;

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

    public function getNoteReponse(): ?string
    {
        return $this->notereponse;
    }

    public function setNoteReponse(string $notereponse): static
    {
        $this->notereponse = $notereponse;

        return $this;
    }

    public function getReclamations(): ?Reclamations
    {
        return $this->reclamations;
    }

    public function setReclamations(?Reclamations $reclamations): static
    {
        $this->reclamations = $reclamations;

        return $this;
    }

   
}
