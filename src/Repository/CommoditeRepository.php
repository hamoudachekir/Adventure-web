<?php

namespace App\Repository;

use App\Entity\Commodite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commodite>
 *
 * @method Commodite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commodite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commodite[]    findAll()
 * @method Commodite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommoditeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commodite::class);
    }

//    /**
//     * @return Commodite[] Returns an array of Commodite objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Commodite
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
