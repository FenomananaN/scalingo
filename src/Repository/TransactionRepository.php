<?php

namespace App\Repository;

use App\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transaction>
 *
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }
    public function findAllReferenceManavola()
    {
        $qb = $this->createQueryBuilder('t');
        $qb
            ->select('t.referenceManavola');

        return $qb->getQuery()->getResult();
    }

    
    public function findAllTransactionByUser(int $user)
    {
        $qb = $this->createQueryBuilder('t');
        $qb
            ->andWhere('t.users = :user')
            ->setParameter('user', $user)
            ->orderBy('t.transactionAt', 'DESC')
            ;

        return $qb->getQuery()->getResult();
    }
    public function findAllDoneTransaction(){
        $qb = $this->createQueryBuilder('t');
        $qb
            ->andWhere('t.transactionDone = true')
            ->orderBy('t.transactionAt', 'DESC')
            ;
        return $qb->getQuery()->getResult();
    }

    public function findAllPendingTransaction(){
        $qb = $this->createQueryBuilder('t');
        $qb
            ->andWhere('t.transactionDone = false')
            ->orderBy('t.transactionAt', 'DESC')
            ;
        return $qb->getQuery()->getResult();
    }

    public function findAllFailedTransaction(){
        $qb = $this->createQueryBuilder('t');
        $qb
            ->andWhere('t.failed = true')
            ->orderBy('t.transactionAt', 'DESC')
            ;
        return $qb->getQuery()->getResult();
    }
    
    public function findRecentTransactionByUser(int $user)
    {
        $qb = $this->createQueryBuilder('t');
        $qb
            ->andWhere('t.users = :user')
            ->setParameter('user', $user)
            ->orderBy('t.transactionAt', 'DESC')
            ->setMaxResults(5);

        return $qb->getQuery()->getResult();
    }

    public function findRecentSuccessedTransactionByUser(int $user)
    {
        $qb = $this->createQueryBuilder('t');
        $qb
            ->andWhere('t.users = :user')
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->eq('t.verified', 'true'),
                    $qb->expr()->eq('t.users',':user'),
                ))
            ->setParameter('user', $user)
            ->orderBy('t.transactionAt', 'DESC')
            ->setMaxResults(3);

        return $qb->getQuery()->getResult();
    }
    


//    /**
//     * @return Transaction[] Returns an array of Transaction objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Transaction
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
