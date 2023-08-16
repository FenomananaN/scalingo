<?php

namespace App\Controller;

use App\Entity\CashOutRP;
use App\Entity\RPManager;
use App\Repository\CashOutRPRepository;
use App\Repository\UserRepository;
use App\Repository\RPManagerRepository;
use App\Service\RPUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class RPController extends AbstractController
{
    private $em;
    private $userRepository;
    private $rpManagerRepository;
    private $cashOutRPRepository;
    private $rPUtils;

    public function __construct(EntityManagerInterface $em, UserRepository $userRepository, RPManagerRepository $rpManagerRepository, RPUtils $rPUtils, CashOutRPRepository $cashOutRPRepository)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->rpManagerRepository=$rpManagerRepository;
        $this->rPUtils=$rPUtils;
        $this->cashOutRPRepository=$cashOutRPRepository;

    }
    //admin 
    #[Route('/newrpmanager', name: 'app_new_rpmanager', methods: 'POST')]
    public function newrpmanager(): JsonResponse
    {
        $rpManager = new RPManager();

        $rpManager->setRPInitial(1000);
        $rpManager->setRRAriary(25000);
        $rpManager->setPObtenue(100);
        $rpManager->setRPRate(0.5);

        //methode hi enregistrena azy amn ni base
        $this->em->getConnection()->beginTransaction();
        try {

            $this->em->persist($rpManager);

            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }

        return $this->json([
            'status' => 'OK',
        ]);
    }

    //admin
    #[Route('/rpmanager', name: 'app_rpmanager', methods: 'GET')]
    public function rpmanager(Request $request): JsonResponse
    {
        $rpManager=$this->rpManagerRepository->findOneById(1);

        return $this->json([
            'RPInitial' => $rpManager->getRPInitial(),
            'RRAriary' => $rpManager->getRRAriary(),
            'PObtenue' => $rpManager->getPObtenue(),
            'RPRate' =>$rpManager->getRPRate()
        ]);
    }

    //admin
    #[Route('/editrpmanager', name: 'app_edit_rpmanager', methods: 'POST')]
    public function editrpmanager(Request $request): JsonResponse
    {
        $rpManager=$this->rpManagerRepository->findOneById(1);


        $rpManager->setRPInitial($request->request->get('RPInitial',$rpManager->getRPInitial()));
        $rpManager->setRRAriary($request->request->get('RRAriary',$rpManager->getRRAriary()));
        $rpManager->setPObtenue($request->request->get('PObtenue',$rpManager->getPObtenue()));
        $rpManager->setRPRate($request->request->get('RPRate',$rpManager->getRPRate()));


        //methode hi enregistrena azy amn ni base
        $this->em->getConnection()->beginTransaction();
        try {

            $this->em->persist($rpManager);

            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }

        return $this->json([
            'status' => 'OK',
        ]);
    }

    #[Route('/api/rpcashout', name: 'app_r_p', methods: 'POST')]
    public function index(Request $request): JsonResponse
    {
        $cashOUtRP= new CashOutRP();

        $user=$this->getUser();
        $user = $this->userRepository->findOneById($user->getId());
        
        $cashOUtRP->setUsers($user);

        //new CurrentRP for User
        $user->setCurrentRP($user->getCurrentRP()-$request->request->get('RP'));

        $cashOUtRP->setRP($request->request->get('RP'));
        $cashOUtRP->setRPRate($request->request->get('RPRate'));
        $cashOUtRP->setMGAValue($request->request->get('MGAValue'));
        
        $cashOUtRP->setPhoneNumber($request->request->get('phoneNumber'));
        $cashOUtRP->setBeingProcessed(true);
        $cashOUtRP->setVerified(false);
        $cashOUtRP->setCashoutAt(new \DateTime());
        
        //methode hi enregistrena azy amn ni base
        $this->em->getConnection()->beginTransaction();
        try {

            $this->em->persist($cashOUtRP);
            $this->em->persist($user);

            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }

        return $this->json([
            'status' => 'ok',
        ]);
    }

    //get cash out
    #[Route('/api/getrpcashoutbyuser', name: 'app_get_r_p_user', methods: 'GET')]
    public function getRpCashOutByUser(Request $request): JsonResponse
    {
        $user=$this->getUser();
        $user = $this->userRepository->findOneById($user->getId());

        $_cashOutRP=$this->cashOutRPRepository->findByUsers($user);
        
        $cashOUtRP= array();
        foreach ($_cashOutRP as $key => $cashOUt) {
            $cashOUtRP[$key]['transactionType'] = 'cashOut';
            $cashOUtRP[$key]['RP'] = $cashOUt->getRP();
            $cashOUtRP[$key]['RPRate'] = $cashOUt->getRPRate();
            $cashOUtRP[$key]['MGAValue'] = $cashOUt->getMGAValue();
            $cashOUtRP[$key]['phoneNumber'] = $cashOUt->getPhoneNumber();
            $cashOUtRP[$key]['name'] = $cashOUt->getName();
            $cashOUtRP[$key]['beingProcessed'] = $cashOUt->isBeingProcessed();
            $cashOUtRP[$key]['verified'] = $cashOUt->isVerified();
            $cashOUtRP[$key]['cashoutSuccessed'] = $cashOUt->isCashoutSuccessed();
            $cashOUtRP[$key]['cashoutFailed'] = $cashOUt->isCashoutFailed();
            $cashOUtRP[$key]['date'] = $cashOUt->getCashoutAt();
            //$cashOUtRP[$key][''] = $cashOUt->get();
        }
        return $this->json($cashOUtRP);
    }
}
