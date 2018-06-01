<?php

namespace App\Repository;

use App\Entity\AdminContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AdminContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method AdminContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method AdminContact[]    findAll()
 * @method AdminContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdminContactRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AdminContact::class);
    }

//    /**
//     * @return AdminContact[] Returns an array of AdminContact objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AdminContact
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
