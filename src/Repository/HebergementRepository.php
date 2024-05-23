<?php

namespace App\Repository;

use App\Entity\HostService;
use App\Entity\Hebergement;
use App\Entity\TypeHebergement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Hebergement>
 *
 * @method Hebergement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Hebergement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Hebergement[]    findAll()
 * @method Hebergement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HebergementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Hebergement::class);
    }

  /*  public function findByTypeHebergementAndHostServiceAndPriceRange(
        TypeHebergement $typeHebergement = null,
        HostService     $HostService = null,
        ?float          $prixMin = null,
        ?float          $prixMax = null
    ): array {
        $queryBuilder = $this->createQueryBuilder('h');

        if ($typeHebergement !== null) {
            $queryBuilder
                ->andWhere('h.TypeHebergement = :typeHebergement')
                ->setParameter('typeHebergement', $typeHebergement);
        }

        if ($HostService !== null) {
            $queryBuilder
                ->andWhere('h.HostService = :HostService')
                ->setParameter('HostService', $HostService);
        }

        if ($prixMin !== null) {
            $queryBuilder
                ->andWhere('h.prixAdul >= :prixMin AND h.prixEnf >= :prixMin')
                ->setParameter('prixMin', $prixMin);
        }

        if ($prixMax !== null) {
            $queryBuilder
                ->andWhere('h.prixAdul <= :prixMax AND h.prixEnf <= :prixMax')
                ->setParameter('prixMax', $prixMax);
        }

        return $queryBuilder->getQuery()->getResult();
    }*/
    public function findRelatedHebergements(?Hebergement $hebergement): array
    {
        if ($hebergement === null) {
            return [];
        }

        return $this->createQueryBuilder('h')
            ->where('h.id != :id')
            ->andWhere('h.TypeHebergement = :typeHebergement')
            ->setParameter('id', $hebergement->getId())
            ->setParameter('typeHebergement', $hebergement->getTypeHebergement())
            ->getQuery()
            ->getResult();
    }








//    /**
//     * @return Hebergement[] Returns an array of Hebergement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Hebergement
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
