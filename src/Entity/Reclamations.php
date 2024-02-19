<?php

namespace App\Entity;

use App\Repository\ReclamationsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\DBAL\Types\Types;


#[ORM\Entity(repositoryClass: ReclamationsRepository::class)]
class Reclamations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idrec = null;

    
  

   

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le champ description ne peut pas être vide")]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Priorite not selected")]
    private ?string $Priorite = null;
    
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    
    #[Assert\EqualTo("today", message: "The donation date should be today")]
  
    private ?\DateTimeInterface $daterec = null;

    #[ORM\OneToMany(targetEntity: Reponses::class, mappedBy: 'reclamations')]
    private Collection $idreponses;

    #[ORM\ManyToOne(inversedBy: 'reclamations')]
    #[ORM\JoinColumn(name:'idusers',referencedColumnName:'iduser')]
    #[Assert\NotBlank(message: "user not selected")]
    private ?User $iduser = null;

    public function __construct()
    {
        $this->idreponses = new ArrayCollection();
    }

   




  



    public function getIdrec(): ?int
    {
        return $this->idrec;
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

    public function getPriorite(): ?string
    {
        return $this->Priorite;
    }

    public function setPriorite(string $Priorite): static
    {
        $this->Priorite = $Priorite;

        return $this;
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

    /**
     * @return Collection<int, Reponses>
     */
    public function getIdreponses(): Collection
    {
        return $this->idreponses;
    }

    public function addIdreponse(Reponses $idreponse): static
    {
        if (!$this->idreponses->contains($idreponse)) {
            $this->idreponses->add($idreponse);
            $idreponse->setReclamations($this);
        }

        return $this;
    }

    public function removeIdreponse(Reponses $idreponse): static
    {
        if ($this->idreponses->removeElement($idreponse)) {
            // set the owning side to null (unless already changed)
            if ($idreponse->getReclamations() === $this) {
                $idreponse->setReclamations(null);
            }
        }

        return $this;
    }

    public function getIduser(): ?User
    {
        return $this->iduser;
    }

    public function setIduser(?User $iduser): static
    {
        $this->iduser = $iduser;

        return $this;
    }

   
  
  
    public function __tostring()
    {

        return $this->iduser;
    }


}