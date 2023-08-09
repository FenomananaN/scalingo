<?php

namespace App\Repository;

use App\Entity\AffiliatedLevel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AffiliatedLevel>
 *
 * @method AffiliatedLevel|null find($id, $lockMode = null, $lockVersion = null)
 * @method AffiliatedLevel|null findOneBy(array $criteria, array $orderBy = null)
 * @method AffiliatedLevel[]    findAll()
 * @method AffiliatedLevel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AffiliatedLevelRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AffiliatedLevel::class);
    }

//    /**
//     * @return AffiliatedLevel[] Returns an array of AffiliatedLevel objects
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

//    public function findOneBySomeField($value): ?AffiliatedLevel
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
