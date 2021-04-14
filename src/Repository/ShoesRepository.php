<?php

namespace App\Repository;

use App\Entity\Shoes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Shoes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shoes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shoes[]    findAll()
 * @method Shoes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShoesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shoes::class);
    }

    // /**
    //  * @return Shoes[] Returns an array of Shoes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Shoes
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
