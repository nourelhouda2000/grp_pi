<?php

namespace App\Entity;

use App\Repository\RendezvousRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RendezvousRepository::class)]
class Rendezvous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idR = null;

    #[ORM\Column(length: 255)]
   /**
 * @Assert\NotBlank(message="La date ne doit pas être vide")
 * @Assert\Regex(
 *     pattern="/^\d{4}-\d{2}-\d{2}$/",
 *     message="La date doit être au format YYYY-MM-DD"
 * )
 
 */


   
    private ?string $dateR = null;

    #[ORM\Column(length: 255)]

 /**
    
     * @Assert\NotBlank(message="L'heure ne doit pas être vide")
     * @Assert\Regex(
     *     pattern="/^([01]\d|2[0-3]):00$/",
     *     message="L'heure doit être au format HH:00"
     * )
     * @Assert\GreaterThanOrEqual("08:00", message="L'heure doit être postérieure ou égale à 08:00")
     * @Assert\LessThanOrEqual("17:00", message="L'heure doit être antérieure ou égale à 17:00")
     
     
     */

    private ?string $heur = null;
 
    #[ORM\ManyToOne(inversedBy: 'rendezvouses')]
    #[ORM\JoinColumn(name:'IdUser',referencedColumnName:'id_user')]
    
    private ?User $idUser = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(name:'idRapport',referencedColumnName:'id_rapport')]
   
    private ?Rapport $idRapport = null;

 
    public function getIdR(): ?int
    {
        return $this->idR;
    }

    public function getDateR(): ?string
    {
        return $this->dateR;
    }

    public function setDateR(?string $dateR): static
    {
        $this->dateR = $dateR;

        return $this;
    }

    public function getHeur(): ?string
    {
        return $this->heur;
    }

    public function setHeur(?string $heur): static
    {
        $this->heur = $heur;

        return $this;
    }

    public function __construct()
    {
        // Définit la date par défaut sur la date d'aujourd'hui lors de la création de l'entité
        $this->dateR = date('Y-m-d');
        $this->heur = date('H:i');
    }
  

    

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): static
    {
        $this->idUser = $idUser;

        return $this;
    }


    public function getNomUser(): ?string
    {
        return $this->idUser ? $this->idUser->getNomuser() : null;
    }

    public function getPrenomuser(): ?string
    {
        return $this->idUser ? $this->idUser->getPrenomuser() : null;
    }
    

    public function getIdRapport(): ?Rapport
    {
        return $this->idRapport;
    }

    public function setIdRapport(?Rapport $idRapport): static
    {
        $this->idRapport = $idRapport;

        return $this;
    }
    
    


    

    public function __tostring()
    {

        return $this->idR;
    }
   

    

    public function getRapport(): ?string
    {
        return $this->idRapport ? $this->idRapport->getRapport() : null;
    }

  
 /**
     * @Assert\Callback
     */
    public function validateDateR(ExecutionContextInterface $context, $payload)
    {
        // Obtenez la date et l'heure actuelles
        $now = new \DateTime();

        // Créez une DateTime à partir de la dateR et heur du rendez-vous
        $dateTimeRendezvous = new \DateTime($this->dateR . ' ' . $this->heur);

        // Vérifiez si la date et l'heure du rendez-vous sont antérieures à la date et l'heure actuelles
        if ($dateTimeRendezvous < $now) {
            // Ajoutez une violation de contrainte
            $context->buildViolation('La date ou l\'heure du rendez-vous ne peuvent pas être antérieures à la date et l\'heure actuelles')
                ->atPath('dateR')
                ->atPath('heur')
                ->addViolation();
        }
    }
    



}
