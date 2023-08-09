<?php

namespace App\Repository;

use App\Entity\RetraitCours;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RetraitCours>
 *
 * @method RetraitCours|null find($id, $lockMode = null, $lockVersion = null)
 * @method RetraitCours|null findOneBy(array $criteria, array $orderBy = null)
 * @method RetraitCours[]    findAll()
 * @method RetraitCours[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RetraitCoursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RetraitCours::class);
    }
    
    public function findCoursByWalletId(int $id)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.wallet = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }


//    /**
//     * @return RetraitCours[] Returns an array of RetraitCours objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RetraitCours
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
