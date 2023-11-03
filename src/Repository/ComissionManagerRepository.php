<?php

namespace App\Repository;

use App\Entity\ComissionManager;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ComissionManager>
 *
 * @method ComissionManager|null find($id, $lockMode = null, $lockVersion = null)
 * @method ComissionManager|null findOneBy(array $criteria, array $orderBy = null)
 * @method ComissionManager[]    findAll()
 * @method ComissionManager[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComissionManagerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ComissionManager::class);
    }

//    /**
//     * @return ComissionManager[] Returns an array of ComissionManager objects
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

//    public function findOneBySomeField($value): ?ComissionManager
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
