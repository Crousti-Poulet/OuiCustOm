<?php

namespace App\Repository;

use App\Entity\Conversation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Conversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversation[]    findAll()
 * @method Conversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Conversation::class);
    }

    public function findAllByUser($user): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere(':user MEMBER OF c.users')
            ->setParameter('user', $user)
            ->orderBy('c.creationDate', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findAllByUser2($user): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT c, m
        FROM App\Entity\Conversation c
        JOIN App\Entity\Message m
        WHERE m.sender = :user OR m.recipient = :user
        ORDER BY c.creationDate DESC'
        )->setParameter('user', $user);

        // returns an array of Conversation objects
        return $query->execute();
    }

//    /**
//     * @return Conversation[] Returns an array of Conversation objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Conversation
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
