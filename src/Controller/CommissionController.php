<?php

namespace App\Controller;

use App\Entity\ComissionCashOut;
use App\Entity\ComissionManager;
use App\Repository\ComissionCashOutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ComissionManagerRepository;
use App\Repository\ComissionRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommissionController extends AbstractController
{
    private $comissionManagerRepository;
    private $commissionRepository;
    private $commissionCashOutRepository;
    private $em;
    public function __construct(ComissionManagerRepository $comissionManagerRepository,ComissionRepository $commissionRepository,ComissionCashOutRepository $commissionCashOutRepository,EntityManagerInterface $em)
    {
        $this->comissionManagerRepository=$comissionManagerRepository;
        $this->commissionRepository=$commissionRepository;
        $this->commissionCashOutRepository=$commissionCashOutRepository;
        $this->em = $em;
    }
    #[Route('/unitecommission', name: 'app_unite_commission', methods:'POST')]
    public function uniteCommission(Request $request): JsonResponse
    {
        $comissionManager = new ComissionManager();

        $comissionManager->setUniteComissionAzo($request->request->get('UCA'));
        $comissionManager->setUniteTransactionMahazoCommission($request->request->get('UTMC'));

        $this->em->getConnection()->beginTransaction();
        try {
            $this->em->persist($comissionManager);
            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }

        return $this->json([
            'message' => 'OK'
        ]);
    }

    #[Route('/api/getRecentcommission/{id}', name: 'app_get_recent_commission', methods:'GET')]
    public function getRecentcommission($id): JsonResponse
    {
        $_recentCommission = $this->commissionRepository->findRecentCommissionByUser($id);

        $recentCommission= array();

        //dd($_recentCommission);
        foreach ($_recentCommission as $key =>$commission){
            $recentCommission[$key]['username'] = $commission->getComissionFrom()->getUsername();
            $recentCommission[$key]['mvxId'] = $commission->getComissionFrom()->getAffiliated()->getMvxId();
            $recentCommission[$key]['commission'] = $commission->getComission();
            $recentCommission[$key]['commissionAt'] = $commission->getComissionAt();
            //getComissionAt,getComission
        }

        return $this->json($recentCommission);
    }

    #[Route('/api/commissioncashout', name: 'app_cashout_commission', methods:'POST')]
    public function commission(Request $request): JsonResponse
    {
        $user=$this->getUser();
        $commissionCashOut = new ComissionCashOut();

        $commissionCashOut->setUser($user);
        $commissionCashOut->setMGAValue($request->request->get('commission'));
        $commissionCashOut->setCashOutAt(new \DateTime());
        $commissionCashOut->setNumero($request->request->get('phoneNumber'));
        $commissionCashOut->setBeingProcessed(false);
        $commissionCashOut->setVerified(false);
        $commissionCashOut->setSuccess(false);
        $commissionCashOut->setFailed(false);

        $this->em->getConnection()->beginTransaction();
        try {
            $this->em->persist($commissionCashOut);
            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }

        return $this->json(['message'=>'ok']);
    }

    #[Route('/api/getRecentcommissioncashout', name: 'app_get_recent_commission_cash_out', methods:'GET')]
    public function getRecentcommissionCashOut(): JsonResponse
    {
        $_recentCommissionCashOut = $this->commissionCashOutRepository->findRecentCommissionByUser($this->getUser()->getId());

        $recentCommissionCashOut= array();

        //dd($_recentCommission);
        foreach ($_recentCommissionCashOut as $key =>$commissionCashOut){
            $recentCommissionCashOut[$key]['commission'] = $commissionCashOut->getMGAValue();
            $recentCommissionCashOut[$key]['phoneNumber'] = $commissionCashOut->getNumero();
           // $recentCommissionCashOut[$key]['commission'] = $commissionCashOut->getComission();
            //$recentCommissionCashOut[$key]['commissionAt'] = $commissionCashOut->getComissionAt();
            //getComissionAt,getComission
        }
        //getMGAValue getNumero getCashOutAt isBeingProcessed isVerified isSuccess isFailed

        return $this->json($recentCommissionCashOut);

    }
}
