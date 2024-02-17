<?php

namespace App\Repository;

use App\Entity\Sante;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sante>
 *
 * @method Sante|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sante|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sante[]    findAll()
 * @method Sante[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SanteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sante::class);
    }

//    /**
//     * @return Sante[] Returns an array of Sante objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sante
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
