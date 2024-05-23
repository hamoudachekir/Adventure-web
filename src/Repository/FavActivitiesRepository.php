<?php

namespace App\Repository;

use App\Entity\FavActivities;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FavActivities>
 *
 * @method FavActivities|null find($id, $lockMode = null, $lockVersion = null)
 * @method FavActivities|null findOneBy(array $criteria, array $orderBy = null)
 * @method FavActivities[]    findAll()
 * @method FavActivities[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavActivitiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FavActivities::class);
    }

//    /**
//     * @return FavActivities[] Returns an array of FavActivities objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FavActivities
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
