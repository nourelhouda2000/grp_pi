<?php

namespace App\Repository;

use App\Entity\Rendezvous;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Rendezvous>
 *
 * @method Rendezvous|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rendezvous|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rendezvous[]    findAll()
 * @method Rendezvous[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RendezvousRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rendezvous::class);
    }

//    /**
//     * @return Rendezvous[] Returns an array of Rendezvous objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Rendezvous
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function searchByDateOrHeure($searchDate, $searchHeure)
{
    $qb = $this->createQueryBuilder('r');

    if ($searchDate) {
        $qb->andWhere('r.dateR = :searchDate')
           ->setParameter('searchDate', $searchDate);
    }

    if ($searchHeure) {
        $qb->andWhere('r.heur = :searchHeure')
           ->setParameter('searchHeure', $searchHeure);
    }
    if ($nom) {
        $qb->andWhere('r.nomuser = :nom')
           ->setParameter('nom', $nom);
    }

    if ($prenom) {
        $qb->andWhere('r.Prenomuser = :prenom')
           ->setParameter('prenom', $prenom);
    }

    return $qb->getQuery()->getResult();
}


public function rechercher($searchTerm)
{
    $qb = $this->createQueryBuilder('r');

    return $qb->leftJoin('r.idUser', 'u')
        ->leftJoin('r.idRapport', 'rapport') // Joindre l'entité Rapport
        ->andWhere(
            $qb->expr()->orX(
                $qb->expr()->like('u.nomuser', ':searchTerm'),
                $qb->expr()->like('u.Prenomuser', ':searchTerm'),
                $qb->expr()->like('rapport.Rapport', ':searchTerm'), // Utiliser 'rapport.description' pour faire référence au champ 'description' de l'entité Rapport
                $qb->expr()->like('r.dateR', ':searchTerm'),
                $qb->expr()->like('r.heur', ':searchTerm')
                )
        )
        ->setParameter('searchTerm', '%' . $searchTerm . '%')
        ->getQuery()
        ->getResult();
}




public function findAllSortedByDate()
{
    return $this->createQueryBuilder('r')
        ->orderBy('r.dateR', 'ASC') // Trie les réclamations par date, de la plus récente à la plus ancienne
        ->getQuery()
        ->getResult();
}
public function findAllSortedByDatedec()
{
    return $this->createQueryBuilder('r')
        ->orderBy('r.dateR', 'DESC') // Trie les réclamations par date, de la plus récente à la plus ancienne
        ->getQuery()
        ->getResult();
}

public function findRendezvousInNextFiveHours()
{
    $currentTime = new DateTime();
    $fiveHoursLater = (new DateTime())->modify('+5 hours');

    return $this->createQueryBuilder('r')
        ->andWhere('CONCAT(r.dateR, " ", r.heur) BETWEEN :current_time AND :five_hours_later')
        ->setParameter('current_time', $currentTime->format('Y-m-d H:i:s'))
        ->setParameter('five_hours_later', $fiveHoursLater->format('Y-m-d H:i:s'))
        ->getQuery()
        ->getResult();
}

}
