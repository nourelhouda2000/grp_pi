<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Serializable;
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface , Serializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idUser = null;

    /**
     * @Assert\NotBlank(message="Le nom d'utilisateur ne peut pas être vide")
     */
    #[ORM\Column(length: 255)]
    private ?string $nomuser = null;

    /**
     * @Assert\NotBlank(message="L'âge de l'utilisateur ne peut pas être vide")
     */
    #[ORM\Column(length: 255)]
    private ?string $ageuser = null;

    /**
     * @Assert\NotBlank(message="Le sexe de l'utilisateur ne peut pas être vide")
     * @Assert\Choice(choices={"homme", "femme"}, message="Le sexe doit être 'homme' ou 'femme'")
     */
    #[ORM\Column(length: 255)]
    private ?string $sexe = null;

    /**
     * @Assert\NotBlank(message="L'email ne peut pas être vide")
     * @Assert\Email(message="L'email '{{ value }}' n'est pas valide.")
     */
    #[ORM\Column(length: 255)]
    private ?string $email = null;

    /**
     * @Assert\NotBlank(message="Le mot de passe ne peut pas être vide")
     * @Assert\Length(min=6, minMessage="Le mot de passe doit contenir au moins {{ limit }} caractères")
     */
    #[ORM\Column(length: 255)]
    private ?string $mdp = null;

    /**
     * @Assert\NotBlank(message="Le prenom d'utilisateur ne peut pas être vide")
     */
    #[ORM\Column(length: 255)]
    private ?string $Prenomuser = null;

    #[ORM\OneToMany(targetEntity: Rendezvous::class, mappedBy: 'idUser')]
    private Collection $rendezvouses;

    /**
     * @Assert\NotBlank(message="Le rôle de l'utilisateur ne peut pas être vide")
     * @Assert\Range(min=0, max=2, notInRangeMessage="Le rôle doit être admin ou doctor ou patient")
     */
    #[ORM\Column]
    private ?int $role = null;
    public function __construct()
    {
        $this->rendezvouses = new ArrayCollection();
    }

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function getNomuser(): ?string
    {
        return $this->nomuser;
    }

    public function setNomuser(?string $nomuser): static
    {
        $this->nomuser = $nomuser;

        return $this;
    }

    public function getAgeuser(): ?string
    {
        return $this->ageuser;
    }

    public function setAgeuser(?string $ageuser): static
    {
        $this->ageuser = $ageuser;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(?string $sexe): static
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(?string $mdp): static
    {
        $this->mdp = $mdp;

        return $this;
    }

    public function getPrenomuser(): ?string
    {
        return $this->Prenomuser;
    }

    public function setPrenomuser(?string $Prenomuser): static
    {
        $this->Prenomuser = $Prenomuser;

        return $this;
    }

    /**
     * @return Collection<int, Rendezvous>
     */
    public function getRendezvouses(): Collection
    {
        return $this->rendezvouses;
    }

    public function addRendezvouse(Rendezvous $rendezvouse): static
    {
        if (!$this->rendezvouses->contains($rendezvouse)) {
            $this->rendezvouses->add($rendezvouse);
            $rendezvouse->setIdUser($this);
        }

        return $this;
    }

    public function removeRendezvouse(Rendezvous $rendezvouse): static
    {
        if ($this->rendezvouses->removeElement($rendezvouse)) {
            if ($rendezvouse->getIdUser() === $this) {
                $rendezvouse->setIdUser(null);
            }
        }

        return $this;
    }




    public function setRole(int $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->mdp;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): ?string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
        // Implementer cette méthode si nécessaire
    }

    public function isEqualTo(UserInterface $user): bool
    {
        return $this->getId() === $user->getId();
    }











    public function getRole(): ?int
    {
        return $this->role;
    }








    public function getRoles(): array
    {
        $roles = [];

        switch ($this->role) {
            case 0:
                $roles[] = 'ROLE_ADMIN';
                break;
            case 1:
                $roles[] = 'ROLE_DOCTOR';
                break;
            case 2:
                $roles[] = 'ROLE_PATIENT';
                break;
        }

        return $roles;
    }


    

public function serialize()
{
    return serialize([
        $this->idUser,
        // ... (other fields to be serialized)
    ]);
}

public function unserialize($serialized)
{
    list(
        $this->idUser,
        // ... (other fields to be unserialized)
    ) = unserialize($serialized, ['allowed_classes' => false]);
}

public function __toString()
{
    return (string) $this->role;
}


private function handleUserRoleRedirect(User $user): Response
{
    $roles = $user->getRoles();

    foreach ($roles as $role) {
        switch ($role) {
            case 'ROLE_ADMIN':
                return $this->redirectToRoute('index');
            case 'ROLE_DOCTOR':
                return $this->redirectToRoute('index_fD');
            case 'ROLE_PATIENT':
                return $this->redirectToRoute('index_f');
            // Add other cases if needed
            default:
                return $this->redirectToRoute('index_fHome');
        }
    }
}
}
