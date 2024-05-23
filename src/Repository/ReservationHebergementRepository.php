<?php

namespace App\Repository;

use App\Entity\ReservationHebergement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReservationHebergement>
 *
 * @method ReservationHebergement|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReservationHebergement|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReservationHebergement[]    findAll()
 * @method ReservationHebergement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationHebergementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReservationHebergement::class);
    }

//    /**
//     * @return ReservationHebergement[] Returns an array of ReservationHebergement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ReservationHebergement
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
