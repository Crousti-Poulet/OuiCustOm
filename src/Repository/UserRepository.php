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

    public function findAllByZipcode($zipcode)
            {
                $qb = $this->createQueryBuilder('u')
                    ->andWhere('u.zipcode = :zipcode')
                    ->setParameter('zipcode', $zipcode)
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

    public function findAllByCategoryAndZipcode($category, $zipcode)
    {
        return $this->createQueryBuilder('u')
            ->join('u.category', 'c')
            ->andWhere('c.id = :category')
            ->setParameter('category', $category)
            ->andWhere('u.zipcode = :zipcode')
            ->setParameter('zipcode', $zipcode)
            ->getQuery()
            ->getResult()
            ;

    }

    // recherche incluant le champ de texte libre
    public function findAllByText($text, $category, $city, $zipcode)
    {
        $entityManager = $this->getEntityManager();

        if($text == null){$text='';}

        // si le champ de recherche texte libre est vide, retourne tous les artisans
        $sql ="SELECT u FROM App\Entity\User u JOIN App\Entity\Category c WHERE u.role LIKE '%s:12:\"ROLE_ARTISTE\";%' AND (u.username LIKE :text1 OR u.description LIKE :text2)";
        $param=['text1' => '%' . $text . '%', 'text2' => '%' . $text . '%'];

        if($category != null && $category != '')
        {
            $sql.= " AND u.category = :category";
            $param['category'] = $category;
        }

        if($city != null && $city != '')
        {
            $sql.= " AND u.city = :city";
            $param['city'] = $city;
        }

        if($zipcode != null && $zipcode != '')
        {
            $sql.= " AND u.zipcode = :zipcode";
            $param['zipcode'] = $zipcode;
        }

        $query = $entityManager->createQuery($sql)->setParameters($param);
//
//        dump($query);
//        dump($param);
//        die();

        // returns an array of Conversation objects
        return $query->execute();
    }

    public function findAllByCategoryAndCityAndZipcode($category, $city, $zipcode)
    {
        return $this->createQueryBuilder('u')
            ->join('u.category', 'c')
            ->andWhere('c.id = :category')
            ->setParameter('category', $category)
            ->andWhere('u.city = :city')
            ->setParameter('city', $city)
            ->andWhere('u.zipcode = :zipcode')
            ->setParameter('zipcode', $zipcode)
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
