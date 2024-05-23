<?php

namespace App\Repository;

use App\Entity\HostService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HostService>
 *
 * @method HostService|null find($id, $lockMode = null, $lockVersion = null)
 * @method HostService|null findOneBy(array $criteria, array $orderBy = null)
 * @method HostService[]    findAll()
 * @method HostService[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HostServiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HostService::class);
    }

//    /**
//     * @return HostService[] Returns an array of HostService objects
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

//    public function findOneBySomeField($value): ?HostService
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
