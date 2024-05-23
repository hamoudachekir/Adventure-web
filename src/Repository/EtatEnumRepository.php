<?php

namespace App\Repository;

use App\Entity\EtatEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EtatEnum>
 *
 * @method EtatEnum|null find($id, $lockMode = null, $lockVersion = null)
 * @method EtatEnum|null findOneBy(array $criteria, array $orderBy = null)
 * @method EtatEnum[]    findAll()
 * @method EtatEnum[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EtatEnumRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EtatEnum::class);
    }

//    /**
//     * @return EtatEnum[] Returns an array of EtatEnum objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EtatEnum
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
