<?php

namespace App\Repository;

use App\Entity\Affiliated;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Affiliated>
 *
 * @method Affiliated|null find($id, $lockMode = null, $lockVersion = null)
 * @method Affiliated|null findOneBy(array $criteria, array $orderBy = null)
 * @method Affiliated[]    findAll()
 * @method Affiliated[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AffiliatedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Affiliated::class);
    }

    public function findAllParrainageId()
    {
        $qb = $this->createQueryBuilder('a');
        $qb
            ->select('a.parrainageId');

        return $qb->getQuery()->getResult();
    }


    public function save(Affiliated $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Affiliated $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Affiliated[] Returns an array of Affiliated objects
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

    //    public function findOneBySomeField($value): ?Affiliated
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
