<?php

namespace App\Repository;

use App\Entity\ProductCat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductCat>
 *
 * @method ProductCat|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductCat|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductCat[]    findAll()
 * @method ProductCat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductCatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductCat::class);
    }

//    /**
//     * @return ProductCat[] Returns an array of ProductCat objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ProductCat
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
