<?php

namespace App\Repository;

use App\Entity\Santé;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Santé>
 *
 * @method Santé|null find($id, $lockMode = null, $lockVersion = null)
 * @method Santé|null findOneBy(array $criteria, array $orderBy = null)
 * @method Santé[]    findAll()
 * @method Santé[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SantéRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Santé::class);
    }

//    /**
//     * @return Santé[] Returns an array of Santé objects
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

//    public function findOneBySomeField($value): ?Santé
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
