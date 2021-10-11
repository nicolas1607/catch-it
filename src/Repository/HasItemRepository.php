<?php

namespace App\Repository;

use App\Entity\HasItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HasItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method HasItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method HasItem[]    findAll()
 * @method HasItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HasItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HasItem::class);
    }

    // /**
    //  * @return HasItem[] Returns an array of HasItem objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HasItem
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
