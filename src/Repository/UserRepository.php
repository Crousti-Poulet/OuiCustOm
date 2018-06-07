<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }


    public function findAllByCategory($category)
            {
                return $this->createQueryBuilder('u')
                    ->join('u.category', 'c')
                    ->andWhere('c.id = :category')
                    ->setParameter('category', $category)
                    ->getQuery()
                    ->getResult()
                    ;
            }

    public function findAllByCity($city)
            {
                $qb = $this->createQueryBuilder('u')
                    ->andWhere('u.city = :city')
                    ->setParameter('city', $city)
                    ->getQuery();

                return $qb->execute();
            }

    public function findAllByCategoryAndCity($category, $city)
            {
                return $this->createQueryBuilder('u')
                    ->join('u.category', 'c')
                    ->andWhere('c.id = :category')
                    ->setParameter('category', $category)
                    ->andWhere('u.city = :city')
                    ->setParameter('city', $city)
                    ->getQuery()
                    ->getResult()
                    ;
                    
            }
    // /**
    //  * @return User[] Returns an array of User objects
    //  */

    // public function findByCategoryField($category): array
    // {
    //     $qb = $this->createQueryBuilder('c')
    //         ->andWhere('c.category_id = :category_id')
    //         ->setParameter('category_id', $category)
    //         ->getQuery();

    //         return $qb->execute();

           
    // }

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
