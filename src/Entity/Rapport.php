<?php

namespace App\Entity;
use App\Entity\Rendezvous;
use App\Repository\RendezvousRepository;
use App\Repository\RapportRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\User;
#[ORM\Entity(repositoryClass: RapportRepository::class)]
class Rapport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idRapport = null;

    #[ORM\Column(length: 1500)]
    /**
    
     * @Assert\NotBlank(message="Le champ Rapport ne peut pas être vide")
  
     
     
     */
    private ?string $Rapport = null;
    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name:'idR',referencedColumnName:'id_r', onDelete: 'CASCADE')]
   
    /**
    
     * @Assert\NotBlank(message="L'ID du rendez-vous ne peut pas être vide")
  
     
     
     */
   

private ?Rendezvous $idR = null;

   
    private ?User $user = null;
    public function getIdRapport(): ?int
    {
        return $this->idRapport;
    }

    public function getRapport(): ?string
    {
        return $this->Rapport;
    }

    public function setRapport(string $Rapport): static
    {
        $this->Rapport = $Rapport;

        return $this;
    }

    public function getIdR(): ?Rendezvous
    {
        return $this->idR;
    }

    public function setIdR(?Rendezvous $idR): static
    {
        $this->idR = $idR;

        return $this;
    }

    public function getNomUser(): ?string
    {
        return $this->user ;
    }
    public function getPrenomUser(): ?string
    {
        return $this->user ;
    }



public function __tostring()
{

    return $this->idRapport;
}







}
