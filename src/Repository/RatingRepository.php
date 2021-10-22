<?php

namespace App\Repository;

use App\Entity\Item;
use App\Entity\User;
use App\Entity\Rating;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Rating|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rating|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rating[]    findAll()
 * @method Rating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rating::class);
    }

    // /**
    //  * @return Rating[] Retournes la liste des commentaires pour un item
    //  */
    public function findRatingByItem(Item $item)
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT r FROM App:rating r
                INNER JOIN App:item i
                WITH r.item = " . $item->getId() . "
                ORDER BY r.createdAt DESC"
            )
            ->getResult();
    }

    // /**
    //  * @return Rating[] Retournes la liste des commentaires pour un utilisateur
    //  */
    public function findRatingByUser(User $user)
    {
        return $this->getEntityManager()
            ->createQuery(
                "SELECT r FROM App:rating r
                WHERE r.user = " . $user->getId() . "
                ORDER BY r.createdAt DESC"
            )
            ->getResult();
    }
}
