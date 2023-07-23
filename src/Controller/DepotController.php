<?php

namespace App\Controller;

use App\Entity\Depot;
use App\Service\RandomMVXId;
use App\Repository\UserRepository;
use App\Repository\DepotRepository;
use App\Repository\WalletRepository;
use App\Repository\GasyWalletRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\GlobalWalletRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DepotController extends AbstractController
{
    private $em;
    private $walletRepository;
    private $userRepository;
    private $globalWalletRepository;
    private $gasyWalletRepository;
    private $depotRepository;
    private $randomMVXId;

    public function __construct(EntityManagerInterface $em, GlobalWalletRepository $globalWalletRepository, WalletRepository $walletRepository, UserRepository $userRepository, GasyWalletRepository $gasyWalletRepository, DepotRepository $depotRepository, RandomMVXId $randomMVXId)
    {
        $this->em = $em;
        $this->walletRepository = $walletRepository;
        $this->globalWalletRepository = $globalWalletRepository;
        $this->userRepository = $userRepository;
        $this->gasyWalletRepository = $gasyWalletRepository;
        $this->depotRepository = $depotRepository;
        $this->randomMVXId = $randomMVXId;
    }

    

    /* #[Route('/getComfirmDepot', name: 'app_get_comfirm_depot', methods: 'POST')]
    public function getDepot($request): JsonResponse
    {
        $depot = $this->depotRepository->findOneBy($id);

        return $this->json([
            'depot_id' => $depot->getId(),
            //'user_id' => $user->getId(),
            'username' => $depot->getUser()->getUsername(),
            'solde_demande' => $depot->getSoldeDemande(),
            'numero_compte' => $depot->getNumeroCompte(),
            'total_to_paid' => $depot->getTotalToPaid(),
            'referenceManavola' => $depot->getReferenceManavola(),
            'cours' => $depot->getCours(),
            'date' => $depot->getDate(),
            'mainWallet' => $depot->getGlobalWallet()->getMainWallet()->getMainWalletName(),
            'mainWalletLogo' => $depot->getGlobalWallet()->getMainWallet()->getLogo(),
            'wallet' => $depot->getGlobalWallet()->getWallet()->getWalletName(),
            'currency' => $depot->getGlobalWallet()->getWallet()->getCurrency(),
        ]);
    }*/
}
