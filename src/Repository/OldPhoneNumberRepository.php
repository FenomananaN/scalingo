<?php

namespace App\Repository;

use App\Entity\OldPhoneNumber;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OldPhoneNumber>
 *
 * @method OldPhoneNumber|null find($id, $lockMode = null, $lockVersion = null)
 * @method OldPhoneNumber|null findOneBy(array $criteria, array $orderBy = null)
 * @method OldPhoneNumber[]    findAll()
 * @method OldPhoneNumber[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OldPhoneNumberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OldPhoneNumber::class);
    }

//    /**
//     * @return OldPhoneNumber[] Returns an array of OldPhoneNumber objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?OldPhoneNumber
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
