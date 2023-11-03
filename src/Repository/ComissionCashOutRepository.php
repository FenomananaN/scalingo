<?php

namespace App\Repository;

use App\Entity\ComissionCashOut;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ComissionCashOut>
 *
 * @method ComissionCashOut|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComissionCashOut|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComissionCashOut[]    findAll()
 * @method ComissionCashOut[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComissionCashOutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ComissionCashOut::class);
    }

    public function findRecentCommissionCashOutByUser($id): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.user = :id')
            ->setParameter('id', $id)
            //->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

//    /**
//     * @return ComissionCashOut[] Returns an array of ComissionCashOut objects
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

//    public function findOneBySomeField($value): ?ComissionCashOut
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
