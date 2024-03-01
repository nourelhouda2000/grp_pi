<?php

namespace App\Repository;

use App\Entity\Recette;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recette>
 *
 * @method Recette|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recette|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recette[]    findAll()
 * @method Recette[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecetteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recette::class);
    }

//    /**
//     * @return Recette[] Returns an array of Recette objects
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

//    public function findOneBySomeField($value): ?Recette
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
public function rechercher($searchTerm)
{
    $qb = $this->createQueryBuilder('r');

    return 
       
        $qb ->andWhere(
            $qb->expr()->orX(
                $qb->expr()->like('r.nom', ':searchTerm'),
               
                $qb->expr()->like('r.ingredient', ':searchTerm'), // Utiliser 'rapport.description' pour faire référence au champ 'description' de l'entité Rapport
                $qb->expr()->like('r.category', ':searchTerm'),
           
                )
        )
        ->setParameter('searchTerm', '%' . $searchTerm . '%')
        ->getQuery()
        ->getResult();
}

public function findByFilters($filterOptions)
{
    $query = $this->createQueryBuilder('p');

    if (isset($filterOptions['nom']) && !empty($filterOptions['nom'])) {
        $query->andWhere('p.nom LIKE :nom');
        $query->setParameter('nom', '%' . $filterOptions['nom'] . '%');
    }

    if (isset($filterOptions['ingredient']) && !empty($filterOptions['ingredient'])) {
        $query->andWhere('p.ingredient LIKE :ingredient');
        $query->setParameter('ingredient', '%' . $filterOptions['ingredient'] . '%');
    }

    if (isset($filterOptions['category']) && !empty($filterOptions['category'])) {
        $query->andWhere('p.category LIKE :category');
        $query->setParameter('category', '%' . $filterOptions['category'] . '%');
    }

    return $query->getQuery()->getResult();
}

     
        }
        
        
    
    
    


