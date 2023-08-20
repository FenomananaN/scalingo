<?php

namespace App\Repository;

use App\Entity\CashOutRP;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CashOutRP>
 *
 * @method CashOutRP|null find($id, $lockMode = null, $lockVersion = null)
 * @method CashOutRP|null findOneBy(array $criteria, array $orderBy = null)
 * @method CashOutRP[]    findAll()
 * @method CashOutRP[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CashOutRPRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CashOutRP::class);
    }

    public function findRecentCashOutByUser(int $user)
    {
        $qb = $this->createQueryBuilder('c');
        $qb
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('c.users', ':user'),
                    $qb->expr()->eq('c.cashoutSuccessed','true'),
                ))
            ->setParameter('user', $user)
            ->orderBy('c.cashoutAt', 'DESC')
            ->setMaxResults(3);

        return $qb->getQuery()->getResult();
    }

    public function findAllPendingCashOut(){
        $qb = $this->createQueryBuilder('c');
        $qb
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('c.cashoutSuccessed', 'false'),
                    $qb->expr()->eq('c.cashoutFailed','false'),
                ))
            ->orderBy('c.cashoutAt', 'DESC')
            ;
        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return CashOutRP[] Returns an array of CashOutRP objects
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

//    public function findOneBySomeField($value): ?CashOutRP
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
