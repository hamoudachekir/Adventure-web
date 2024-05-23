<?php

namespace App\Repository;

use App\Entity\ActivityImages;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ActivityImages>
 *
 * @method ActivityImages|null find($id, $lockMode = null, $lockVersion = null)
 * @method ActivityImages|null findOneBy(array $criteria, array $orderBy = null)
 * @method ActivityImages[]    findAll()
 * @method ActivityImages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActivityImagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ActivityImages::class);
    }

//    /**
//     * @return ActivityImages[] Returns an array of ActivityImages objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ActivityImages
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
