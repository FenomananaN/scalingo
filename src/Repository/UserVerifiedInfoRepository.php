<?php

namespace App\Repository;

use App\Entity\UserVerifiedInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserVerifiedInfo>
 *
 * @method UserVerifiedInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserVerifiedInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserVerifiedInfo[]    findAll()
 * @method UserVerifiedInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserVerifiedInfoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserVerifiedInfo::class);
    }

    public function save(UserVerifiedInfo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(UserVerifiedInfo $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return UserVerifiedInfo[] Returns an array of UserVerifiedInfo objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserVerifiedInfo
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
