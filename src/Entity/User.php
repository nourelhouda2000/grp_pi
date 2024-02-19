<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $iduser = null;

    #[ORM\OneToMany(targetEntity: Reclamations::class, mappedBy: 'iduser')]
    private Collection $reclamations;

 

    public function __construct()
    {
        $this->reclamations = new ArrayCollection();
    }

    public function getIduser(): ?int
    {
        return $this->iduser;
    }

    /**
     * @return Collection<int, Reclamations>
     */
    public function getReclamations(): Collection
    {
        return $this->reclamations;
    }

    public function addReclamation(Reclamations $reclamation): static
    {
        if (!$this->reclamations->contains($reclamation)) {
            $this->reclamations->add($reclamation);
            $reclamation->setIduser($this);
        }

        return $this;
    }

    public function removeReclamation(Reclamations $reclamation): static
    {
        if ($this->reclamations->removeElement($reclamation)) {
            // set the owning side to null (unless already changed)
            if ($reclamation->getIduser() === $this) {
                $reclamation->setIduser(null);
            }
        }

        return $this;
    }
    public function __tostring()
    {

        return $this->iduser;
    }

}
