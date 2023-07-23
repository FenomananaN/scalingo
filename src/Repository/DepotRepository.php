<?php

namespace App\Repository;

use App\Entity\Depot;
use App\Entity\Retrait;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Depot>
 *
 * @method Depot|null find($id, $lockMode = null, $lockVersion = null)
 * @method Depot|null findOneBy(array $criteria, array $orderBy = null)
 * @method Depot[]    findAll()
 * @method Depot[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepotRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Depot::class);
    }

    public function findAllReferenceManavolaId()
    {
        $qb = $this->createQueryBuilder('d');
        $qb
            ->select('d.referenceManavola');

        return $qb->getQuery()->getResult();
    }

    public function save(Depot $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Depot $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findAllTransacByUserId(int $userId)
    {
        return $this->createQueryBuilder('d')
            ->select('d','r')
            ->join('App\Entity\Retrait', 'r', 'WITH', 'd.user = r.user')
            ->where('d.user = :userId')
            ->setParameter('userId', $userId)
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }
    /*
    ->select('t1', 't2')
            ->from('App\Entity\Table1', 't1')
            ->leftJoin('App\Entity\Table2', 't2', 'WITH', 't1.someColumn = t2.someColumn')
            ->getQuery(); */

    //    /**
    //     * @return Depot[] Returns an array of Depot objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('d.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Depot
    //    {
    //        return $this->createQueryBuilder('d')
    //            ->andWhere('d.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
