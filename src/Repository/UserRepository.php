<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\ORM\EntityRepository;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }







public function countDoctors(): int
{
    return $this->count(['role' => 1]); // 1 représente le rôle de docteur
}

/**
 * Récupérer le nombre total de patients
 */
public function countPatients(): int
{
    return $this->count(['role' => 2]); // 2 représente le rôle de patient
}




// ...

/**
 * Récupérer le nombre de médecins et de patients par mois
 */


/*
public function rechercherUser($searchTerm)
{
    $qb = $this->createQueryBuilder('r');

    return 
    $qb ->andWhere(
            $qb->expr()->orX(
                $qb->expr()->like('r.nomuser', ':searchTerm'),
                $qb->expr()->like('r.Prenomuser', ':searchTerm'),
                $qb->expr()->like('r.email', ':searchTerm'), 
                $qb->expr()->like('r.ageuser', ':searchTerm'),
                $qb->expr()->like('r.role', ':searchTerm')
                )
        )
        ->setParameter('searchTerm', '%' . $searchTerm . '%')
        ->getQuery()
        ->getResult();
}




public function loadUserByUsername($username): ?UserInterface
{
    return $this->createQueryBuilder('u')
        ->andWhere('u.email = :email')
        ->setParameter('email', $username)
        ->getQuery()
        ->getOneOrNullResult();
}
*/


public function findByCriteria(array $criteria): array
    {
        $qb = $this->createQueryBuilder('u');

        foreach ($criteria as $field => $value) {
            // Customize this part based on your actual entity fields
            $qb->andWhere("u.$field = :$field")->setParameter($field, $value);
        }

        return $qb->getQuery()->getResult();
    }

public function countUsers(): int
{
    return $this->createQueryBuilder('u')
        ->select('COUNT(u.idUser)')
        ->getQuery()
        ->getSingleScalarResult();
}


public function loadUserByUsername($username): ?UserInterface
{
    return $this->createQueryBuilder('u')
        ->andWhere('u.email = :email')
        ->setParameter('email', $username)
        ->getQuery()
        ->getOneOrNullResult();
}

}