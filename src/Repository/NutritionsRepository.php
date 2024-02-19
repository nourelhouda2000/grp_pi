<?php

namespace App\Repository;

use App\Entity\Nutritions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Nutritions>
 *
 * @method Nutritions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Nutritions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Nutritions[]    findAll()
 * @method Nutritions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NutritionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Nutritions::class);
    }

//    /**
//     * @return Nutritions[] Returns an array of Nutritions objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Nutritions
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
