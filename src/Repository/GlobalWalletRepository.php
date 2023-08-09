<?php

namespace App\Repository;

use App\Entity\GlobalWallet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GlobalWallet>
 *
 * @method GlobalWallet|null find($id, $lockMode = null, $lockVersion = null)
 * @method GlobalWallet|null findOneBy(array $criteria, array $orderBy = null)
 * @method GlobalWallet[]    findAll()
 * @method GlobalWallet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GlobalWalletRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GlobalWallet::class);
    }
    public function findAllWalletByMainWalletId(int $mainWalletId)
    {
        return $this->createQueryBuilder('g')
            ->select('w.id, g.id as globalWalletId, w.walletName,w.currency,w.logo, g.reserve, g.link, g.fraisDepotCharged, g.fraisDepot, g.fraisWallet, g.fraisBlockchain, g.fraisRetrait')
            ->innerJoin('g.wallet', 'w', 'WITH', 'w.id = g.wallet')
            ->where('g.mainWallet = :mainWallet')
            ->setParameter('mainWallet', $mainWalletId)
            ->getQuery()
            ->getResult();
    }

    public function findAllWalletByMainWalletIdForRetrait(int $mainWalletId)
    {
        return $this->createQueryBuilder('g')
            ->select('w.id, g.id as globalWalletId, w.walletName,w.currency,w.logo, g.link')
            ->innerJoin('g.wallet', 'w', 'WITH', 'w.id = g.wallet')
            ->where('g.mainWallet = :mainWallet')
            ->setParameter('mainWallet', $mainWalletId)
            ->getQuery()
            ->getResult();
    }
   

//    /**
//     * @return GlobalWallet[] Returns an array of GlobalWallet objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GlobalWallet
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
