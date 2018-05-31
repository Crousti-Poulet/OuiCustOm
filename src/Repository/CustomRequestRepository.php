<?php

namespace App\Repository;

use App\Entity\CustomRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CustomRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomRequest[]    findAll()
 * @method CustomRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomRequestRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CustomRequest::class);
    }

//    /**
//     * @return CustomRequest[] Returns an array of CustomRequest objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CustomRequest
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
