<?php

namespace App\Controller;

use Exception;
use App\Service\RPUtils;
use App\Entity\Transaction;
use App\Service\RandomMVXId;
use App\Repository\UserRepository;
use App\Repository\WalletRepository;
use App\Repository\GasyWalletRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TransactionRepository;
use App\Repository\GlobalWalletRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TransactionController extends AbstractController
{   private $em;
    private $userRepository;
    private $globalWalletRepository;
    private $gasyWalletRepository;
    private $transactionRepository;
    private $randomMVXId;
    private $rPUtils;

    public function __construct(EntityManagerInterface $em, GlobalWalletRepository $globalWalletRepository, WalletRepository $walletRepository, UserRepository $userRepository, GasyWalletRepository $gasyWalletRepository,TransactionRepository $transactionRepository, RandomMVXId $randomMVXId, RPUtils $rPUtils)
    {
        $this->em = $em;
        $this->globalWalletRepository = $globalWalletRepository;
        $this->userRepository = $userRepository;
        $this->gasyWalletRepository = $gasyWalletRepository;
        $this->transactionRepository = $transactionRepository;
        $this->randomMVXId = $randomMVXId;
        $this->rPUtils=$rPUtils;
    }
//depot    
    #[Route('/api/depot', name: 'app_depot', methods: 'POST')]
    public function index(Request $request): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            $user = $this->userRepository->findOneByEmail($request->request->get('numero'));
            if (!$user) {
                return $this->json(['message' => 'Veuillez entrer un numero valide'], 401);
            }
        } else {    
            $user = $this->userRepository->findOneById($user->getId());
        }

        //look if the user is already verified
        if(!$user->isVerifiedStatus()){
            return $this->json(['message'=>"Votre compte n'est pas verifier"],401);
        }

        //get wallet type
        $globalWallet = $this->globalWalletRepository->findOneById($request->request->get('globalWallet_id'));

        //get malagasy
        // $gasyWallet = $this->gasyWalletRepository->findOneById($request->request->get('gasyWallet_id'));

        $depot = new Transaction();
        $depot->setUsers($user);
        $depot->setGlobalWallet($globalWallet);
        // $depot->setGasyWallet($gasyWallet);
        $depot->setSolde($request->request->get('solde_demande'));
        $depot->setTransactionType($request->request->get('transaction_type'));
        $depot->setAccountNumber($request->request->get('numero_compte'));
        $depot->setSoldeAriary($request->request->get('total_to_paid'));
        $depot->setCours($request->request->get('cours'));
        $depot->setPolicyAgreement($request->request->get('check_agreement'));
        $depot->setTransactionAt(new \DateTime());
        $depot->setBeingProcessed(false);
        $depot->setTransactionDone(false);
        $depot->setVerified(false);
        $depot->setRPObtenue('0');

     
        //creating the generated parrainageId
        $_referenceManavola = $this->transactionRepository->findAllReferenceManavola();
        $referenceManavola = array();
        foreach ($_referenceManavola as $key => $reference) {
            $referenceManavola[$key] = $reference['referenceManavola'];
        }

        $depot->setReferenceManavola($this->randomMVXId->getNewTXMVXId($referenceManavola));

        //dd($depot);

        //methode hi enregistrena azy amn ni base
        $this->em->getConnection()->beginTransaction();
        try {
            $this->em->persist($depot);
            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
            return $this->json(['message' => $e->getMessage()], 500);
        }

        return $this->json([
            'depot_id' => $depot->getId(),
            //'user_id' => $user->getId(),
            'username' => $user->getUsername(),
            'solde_demande' => $depot->getSolde(),
            'numero_compte' => $depot->getAccountNumber(),
            'total_to_paid' => $depot->getSoldeAriary(),
            'referenceManavola' => $depot->getReferenceManavola(),
            'cours' => $depot->getCours(),
            'date' => $depot->getTransactionAt(),
            'mainWallet' => $depot->getGlobalWallet()->getMainWallet()->getMainWalletName(),
            'mainWalletLogo' => $depot->getGlobalWallet()->getMainWallet()->getLogo(),
            'wallet' => $depot->getGlobalWallet()->getWallet()->getWalletName(),
            'currency' => $depot->getGlobalWallet()->getWallet()->getCurrency(),
        ]);
    }

    #[Route('/depot/manualVerification', name: 'app_depot_manualVerification', methods: 'POST')]
    public function manualVerification(Request $request): JsonResponse
    {

        $depot = $this->transactionRepository->findOneById($request->request->get('depot_id'));
        $depot->setManualVerification(true);

        $this->em->getConnection()->beginTransaction();

        try {
            $this->em->persist($depot);
            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
            return $this->json(['message' => $e->getMessage()], 500);
        }

        return $this->json(['message' => 'success']);
    }
     #[Route('/getComfirmDepotStatus/{id}', name: 'app_get_comfirm_depot_status', methods: 'GET')]
    public function getComfirmationStatus($id): JsonResponse
    {
        $depot=$this->transactionRepository->findOneById($id);

        return $this->json([
            'manualVerification' => $depot->isVerified(),
            'doneCommand'=> $depot->isTransactionDone()
        ]);

    }

//end depot

//start retrait
#[Route('/api/retrait', name: 'app_retrait', methods:'POST')]
    public function retrait(Request $request): JsonResponse
    {//id,user,gasyWallet,globalWallet,soldeDemmande,totalToReceive,cours,policyAgreement,beingProcessed,verified,transacDone:

        $retrait = new Transaction();

        $user=$this->getUser();  
        if (!$user) {
            $user = $this->userRepository->findOneByEmail($request->request->get('numero'));
            if (!$user) {
                return $this->json(['message' => 'Veuillez entrer un numero valide'], 401);
            }
        } else{ 
            $user = $this->userRepository->findOneById($user->getId());
        }

        $retrait->setUsers($user);

        //get wallet type
        $globalWallet = $this->globalWalletRepository->findOneById($request->request->get('globalWallet_id'));

        $retrait->setGlobalWallet($globalWallet);

        //get gasyWallet
        $gasyWallet=$this->gasyWalletRepository->findOneById($request->request->get('mobile'));
        $retrait->setGasyWallet($gasyWallet);

        $retrait->setTransactionType($request->request->get('transaction_type'));
        $retrait->setSolde($request->request->get('solde_demande'));
        $retrait->setSoldeAriary($request->request->get('total_to_receive'));
        $retrait->setCours($request->request->get('cours'));
        $retrait->setPolicyAgreement($request->request->get('check_agreement'));
        $retrait->setTransactionAt(new \DateTime());
        $retrait->setVerified(false);
        $retrait->setBeingProcessed(false);
        $retrait->setTransactionDone(false);
        $retrait->setAccountNumber('none');
        $retrait->setRPObtenue('0');
     
        //creating the generated parrainageId
        $_referenceManavola = $this->transactionRepository->findAllReferenceManavola();
        $referenceManavola = array();
        foreach ($_referenceManavola as $key => $reference) {
            $referenceManavola[$key] = $reference['referenceManavola'];
        }

        $retrait->setReferenceManavola($this->randomMVXId->getNewTXRMVXId($referenceManavola));

        $this->em->getConnection()->beginTransaction();
        try {
            $this->em->persist($retrait);
        
            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
            return $this->json(['message' => $e->getMessage()], 500);
        }

        return $this->json([
            'retrait_id' => $retrait->getId(),
            //'user_id' => $user->getId(),
            'username' => $user->getUsername(),
            'solde_demande' => $retrait->getSolde(),
   //         'numero_compte' => $retrait->getNumeroCompte(),
            'total_to_receive' => $retrait->getSoldeAriary(),
            'referenceManavola' => $retrait->getReferenceManavola(),
            'cours' => $retrait->getCours(),
            'date' => $retrait->getTransactionAt(),
            'mobile'=>$retrait->getGasyWallet()->getGasyWalletName(),
            'mobileLogo'=>$retrait->getGasyWallet()->getLogo(),
            'mainWallet' => $retrait->getGlobalWallet()->getMainWallet()->getMainWalletName(),
            'mainWalletLogo' => $retrait->getGlobalWallet()->getMainWallet()->getLogo(),
            'walletLogo' => $retrait->getGlobalWallet()->getWallet()->getLogo(),
            'wallet' => $retrait->getGlobalWallet()->getWallet()->getWalletName(),
            'currency' => $retrait->getGlobalWallet()->getWallet()->getCurrency(),
        ]);
    }
    #[Route('/api/retrait/addtransactionId', name: 'app_retrait_transac', methods:'POST')]
    public function retraitTransac(Request $request): JsonResponse
    {//id,user,gasyWallet,globalWallet,soldeDemmande,totalToReceive,cours,policyAgreement,beingProcessed,verified,transacDone:
        $retrait=$this->transactionRepository->findOneById($request->request->get('retrait_id'));

        if(!$retrait){
            return $this->json(['message'=>"retrait n'existe pas" ],401);
        }

        $retrait->setTransactionId($request->request->get('transacID'));

        $this->em->getConnection()->beginTransaction();
        try {
            $this->em->persist($retrait);
            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
            return $this->json(['message' => $e->getMessage()], 500);
        }
        return $this->json([
            'transacID'=> $retrait->getTransactionId()
        ]);
    }
    //end retrait

    //start get transac data and secured
    
    //user
    #[Route('/api/getRecentTransaction', name: 'app_get_recent_transaction', methods:'GET')]
    public function getRecentTransaction(Request $request): JsonResponse
    {
        //efa mis id

        $_transaction=$this->transactionRepository->findRecentTransactionByUser($this->getUser()->getId());
        
       // dd($_transaction);
        $transaction= array();
        foreach ($_transaction as $key => $transac) {
            $transaction[$key]['id'] = $transac->getId();
            $transaction[$key]['transactionType'] = $transac->getTransactionType();
            $transaction[$key]['solde'] = $transac->getSolde();
            $transaction[$key]['AccountNumber'] = $transac->getAccountNumber();
            $transaction[$key]['soldeAriary'] = $transac->getSoldeAriary();
            $transaction[$key]['cours'] = $transac->getCours();
            $transaction[$key]['mainWallet'] = $transac->getGlobalWallet()->getMainWallet()->getMainWalletName();
            $transaction[$key]['mainWalletLogo'] = $transac->getGlobalWallet()->getMainWallet()->getLogo();
            $transaction[$key]['wallet'] = $transac->getGlobalWallet()->getWallet()->getWalletName();
            $transaction[$key]['currency'] = $transac->getGlobalWallet()->getWallet()->getCurrency();
            $transaction[$key]['walletLogoMain'] = $transac->getGlobalWallet()->getWallet()->getLogoMain();
            $transaction[$key]['walletLogo'] = $transac->getGlobalWallet()->getWallet()->getLogo();
            if(!$transac->getGasyWallet()){
                $transaction[$key]['gasyWallet'] = 'null';
                $transaction[$key]['gasyWalletLogo'] = 'null';
                $transaction[$key]['gasyWalletLogoMain'] = 'null';
            } else {
                $transaction[$key]['gasyWallet'] = $transac->getGasyWallet()->getGasyWalletName();
                $transaction[$key]['gasyWalletLogo'] = $transac->getGasyWallet()->getLogo();
                $transaction[$key]['gasyWalletLogoMain'] = $transac->getGasyWallet()->getLogoMain();
            }
            $transaction[$key]['date'] = $transac->getTransactionAt();
            $transaction[$key]['referenceManavola'] = $transac->getReferenceManavola();
            $transaction[$key]['transactionId'] = $transac->getTransactionId();
            $transaction[$key]['beingProcessed'] = $transac->isBeingProcessed();
            $transaction[$key]['verified'] = $transac->isVerified();
            $transaction[$key]['transactionDone'] = $transac->isTransactionDone();
            $transaction[$key]['failed'] = $transac->isFailed();
            $transaction[$key]['rp'] = $transac->getRPObtenue();

            //soldeAriary cours globalWallet gasyWallet date referenceManavola transactionId //policyAgreement transactionDone verified beingProcessed
        }
        return $this->json($transaction);
    }

    //user
    #[Route('/api/getRecentSuccessedTransaction', name: 'app_get_recent_successed_transaction', methods:'GET')]
    public function getRecentSuccessedTransaction(): JsonResponse
    {
        //efa mis id

        $_transaction=$this->transactionRepository->findRecentSuccessedTransactionByUser($this->getUser()->getId());
        
       // dd($_transaction);
        $transaction= array();
        foreach ($_transaction as $key => $transac) {
            $transaction[$key]['id'] = $transac->getId();
            $transaction[$key]['transactionType'] = $transac->getTransactionType();
            $transaction[$key]['solde'] = $transac->getSolde();
            $transaction[$key]['AccountNumber'] = $transac->getAccountNumber();
            $transaction[$key]['soldeAriary'] = $transac->getSoldeAriary();
            $transaction[$key]['cours'] = $transac->getCours();
            $transaction[$key]['mainWallet'] = $transac->getGlobalWallet()->getMainWallet()->getMainWalletName();
            $transaction[$key]['mainWalletLogo'] = $transac->getGlobalWallet()->getMainWallet()->getLogo();
            $transaction[$key]['wallet'] = $transac->getGlobalWallet()->getWallet()->getWalletName();
            $transaction[$key]['currency'] = $transac->getGlobalWallet()->getWallet()->getCurrency();
            $transaction[$key]['walletLogoMain'] = $transac->getGlobalWallet()->getWallet()->getLogoMain();
            $transaction[$key]['walletLogo'] = $transac->getGlobalWallet()->getWallet()->getLogo();
            if(!$transac->getGasyWallet()){
                $transaction[$key]['gasyWallet'] = 'null';
                $transaction[$key]['gasyWalletLogo'] = 'null';
                $transaction[$key]['gasyWalletLogoMain'] = 'null';
            } else {
                $transaction[$key]['gasyWallet'] = $transac->getGasyWallet()->getGasyWalletName();
                $transaction[$key]['gasyWalletLogo'] = $transac->getGasyWallet()->getLogo();
                $transaction[$key]['gasyWalletLogoMain'] = $transac->getGasyWallet()->getLogoMain();
            }
            $transaction[$key]['date'] = $transac->getTransactionAt();
            $transaction[$key]['referenceManavola'] = $transac->getReferenceManavola();
            $transaction[$key]['transactionId'] = $transac->getTransactionId();
            $transaction[$key]['beingProcessed'] = $transac->isBeingProcessed();
            $transaction[$key]['verified'] = $transac->isVerified();
            $transaction[$key]['transactionDone'] = $transac->isTransactionDone();
            $transaction[$key]['failed'] = $transac->isFailed();
            $transaction[$key]['rp'] = $transac->getRPObtenue();

            //soldeAriary cours globalWallet gasyWallet date referenceManavola transactionId //policyAgreement transactionDone verified beingProcessed
        }
        return $this->json($transaction);
    }


    //user
    #[Route('/api/getAllTransaction', name: 'app_get_all_transaction', methods:'GET')]
    public function getAllTransaction(Request $request): JsonResponse
    {
       // $user=$this->getUser();

        $_transaction=$this->transactionRepository->findAllTransactionByUser($this->getUser()->getId());
        
       // dd($_transaction);
        $transaction= array();
        foreach ($_transaction as $key => $transac) {
            $transaction[$key]['id'] = $transac->getId();
            $transaction[$key]['transactionType'] = $transac->getTransactionType();
            $transaction[$key]['solde'] = $transac->getSolde();
            $transaction[$key]['AccountNumber'] = $transac->getAccountNumber();
            $transaction[$key]['soldeAriary'] = $transac->getSoldeAriary();
            $transaction[$key]['cours'] = $transac->getCours();
            $transaction[$key]['mainWallet'] = $transac->getGlobalWallet()->getMainWallet()->getMainWalletName();
            $transaction[$key]['mainWalletLogo'] = $transac->getGlobalWallet()->getMainWallet()->getLogo();
            $transaction[$key]['wallet'] = $transac->getGlobalWallet()->getWallet()->getWalletName();
            $transaction[$key]['currency'] = $transac->getGlobalWallet()->getWallet()->getCurrency();
            $transaction[$key]['walletLogoMain'] = $transac->getGlobalWallet()->getWallet()->getLogoMain();
            $transaction[$key]['walletLogo'] = $transac->getGlobalWallet()->getWallet()->getLogo();
            if(!$transac->getGasyWallet()){
                $transaction[$key]['gasyWallet'] = 'null';
                $transaction[$key]['gasyWalletLogo'] = 'null';
                $transaction[$key]['gasyWalletLogoMain'] = 'null';
            } else {
                $transaction[$key]['gasyWallet'] = $transac->getGasyWallet()->getGasyWalletName();
                $transaction[$key]['gasyWalletLogo'] = $transac->getGasyWallet()->getLogo();
                $transaction[$key]['gasyWalletLogoMain'] = $transac->getGasyWallet()->getLogoMain();
            }
            $transaction[$key]['date'] = $transac->getDate();
            $transaction[$key]['referenceManavola'] = $transac->getReferenceManavola();
            $transaction[$key]['transactionId'] = $transac->getTransactionId();
            $transaction[$key]['beingProcessed'] = $transac->isBeingProcessed();
            $transaction[$key]['verified'] = $transac->isVerified();
            $transaction[$key]['transactionDone'] = $transac->isTransactionDone();
            $transaction[$key]['failed'] = $transac->isFailed();
            $transaction[$key]['rp'] = $transac->getRPObtenu();

            //soldeAriary cours globalWallet gasyWallet date referenceManavola transactionId //policyAgreement transactionDone verified beingProcessed
        }
        return $this->json($transaction);
    }

    //Admin
    //for one by one user
    #[Route('/getRecentTransaction/{id}', name: 'app_get_recent_transaction_user', methods:'GET')]
    public function getRecentTransactionUser($id): JsonResponse
    {
       // $user=$this->userRepository->findOneById($id);

        $_transaction=$this->transactionRepository->findRecentTransactionByUser($id);
        
       // dd($_transaction);
        $transaction= array();
        foreach ($_transaction as $key => $transac) {
            $transaction[$key]['id'] = $transac->getId();
            $transaction[$key]['transactionType'] = $transac->getTransactionType();
            $transaction[$key]['solde'] = $transac->getSolde();
            $transaction[$key]['AccountNumber'] = $transac->getAccountNumber();
            $transaction[$key]['soldeAriary'] = $transac->getSoldeAriary();
            $transaction[$key]['cours'] = $transac->getCours();
            $transaction[$key]['mainWallet'] = $transac->getGlobalWallet()->getMainWallet()->getMainWalletName();
            $transaction[$key]['mainWalletLogo'] = $transac->getGlobalWallet()->getMainWallet()->getLogo();
            $transaction[$key]['wallet'] = $transac->getGlobalWallet()->getWallet()->getWalletName();
            $transaction[$key]['currency'] = $transac->getGlobalWallet()->getWallet()->getCurrency();
            $transaction[$key]['walletLogoMain'] = $transac->getGlobalWallet()->getWallet()->getLogoMain();
            $transaction[$key]['walletLogo'] = $transac->getGlobalWallet()->getWallet()->getLogo();
            if(!$transac->getGasyWallet()){
                $transaction[$key]['gasyWallet'] = 'null';
                $transaction[$key]['gasyWalletLogo'] = 'null';
                $transaction[$key]['gasyWalletLogoMain'] = 'null';
            } else {
                $transaction[$key]['gasyWallet'] = $transac->getGasyWallet()->getGasyWalletName();
                $transaction[$key]['gasyWalletLogo'] = $transac->getGasyWallet()->getLogo();
                $transaction[$key]['gasyWalletLogoMain'] = $transac->getGasyWallet()->getLogoMain();
            }
            $transaction[$key]['date'] = $transac->getTransactionAt();
            $transaction[$key]['referenceManavola'] = $transac->getReferenceManavola();
            $transaction[$key]['transactionId'] = $transac->getTransactionId();
            $transaction[$key]['beingProcessed'] = $transac->isBeingProcessed();
            $transaction[$key]['verified'] = $transac->isVerified();
            $transaction[$key]['transactionDone'] = $transac->isTransactionDone();
            $transaction[$key]['failed'] = $transac->isFailed();

            //soldeAriary cours globalWallet gasyWallet date referenceManavola transactionId //policyAgreement transactionDone verified beingProcessed
        }
        return $this->json($transaction);
    }

    //admin
    #[Route('/api/getAllTransaction/{id}', name: 'app_get_all_transaction_user', methods:'GET')]
    public function getAllTransactionUser($id): JsonResponse
    {
        $user=$this->userRepository->findOneById($id);

        $_transaction=$this->transactionRepository->findAllTransactionByUser($user);
        
       // dd($_transaction);
        $transaction= array();
        foreach ($_transaction as $key => $transac) {
            $transaction[$key]['id'] = $transac->getId();
            $transaction[$key]['transactionType'] = $transac->getTransactionType();
            $transaction[$key]['solde'] = $transac->getSolde();
            $transaction[$key]['AccountNumber'] = $transac->getAccountNumber();
            $transaction[$key]['soldeAriary'] = $transac->getSoldeAriary();
            $transaction[$key]['cours'] = $transac->getCours();
            $transaction[$key]['mainWallet'] = $transac->getGlobalWallet()->getMainWallet()->getMainWalletName();
            $transaction[$key]['mainWalletLogo'] = $transac->getGlobalWallet()->getMainWallet()->getLogo();
            $transaction[$key]['wallet'] = $transac->getGlobalWallet()->getWallet()->getWalletName();
            $transaction[$key]['currency'] = $transac->getGlobalWallet()->getWallet()->getCurrency();
            $transaction[$key]['walletLogoMain'] = $transac->getGlobalWallet()->getWallet()->getLogoMain();
            $transaction[$key]['walletLogo'] = $transac->getGlobalWallet()->getWallet()->getLogo();
            if(!$transac->getGasyWallet()){
                $transaction[$key]['gasyWallet'] = 'null';
                $transaction[$key]['gasyWalletLogo'] = 'null';
                $transaction[$key]['gasyWalletLogoMain'] = 'null';
            } else {
                $transaction[$key]['gasyWallet'] = $transac->getGasyWallet()->getGasyWalletName();
                $transaction[$key]['gasyWalletLogo'] = $transac->getGasyWallet()->getLogo();
                $transaction[$key]['gasyWalletLogoMain'] = $transac->getGasyWallet()->getLogoMain();
            }
            $transaction[$key]['date'] = $transac->getTransactionAt();
            $transaction[$key]['referenceManavola'] = $transac->getReferenceManavola();
            $transaction[$key]['transactionId'] = $transac->getTransactionId();
            $transaction[$key]['beingProcessed'] = $transac->isBeingProcessed();
            $transaction[$key]['verified'] = $transac->isVerified();
            $transaction[$key]['transactionDone'] = $transac->isTransactionDone();
            $transaction[$key]['failed'] = $transac->isFailed();

            //soldeAriary cours globalWallet gasyWallet date referenceManavola transactionId //policyAgreement transactionDone verified beingProcessed
        }
        return $this->json($transaction);
    }

    //Admin
    #[Route('/getAllPendingTransaction', name: 'app_get_all_pending_transaction', methods:'GET')]
    public function getAllPendingTransaction(): JsonResponse
    {
       // $user=$this->getUser();//

        $_transaction=$this->transactionRepository->findAllPendingTransaction();
        
       // dd($_transaction);
        $transaction= array();
        foreach ($_transaction as $key => $transac) {
            $transaction[$key]['id'] = $transac->getId();
            $transaction[$key]['transactionType'] = $transac->getTransactionType();
            $transaction[$key]['username'] = $transac->getUsers()->getUsername();
            $transaction[$key]['manavolaId'] = $transac->getUsers()->getAffiliated()->getMvxId();
            $transaction[$key]['solde'] = $transac->getSolde();
            $transaction[$key]['AccountNumber'] = $transac->getAccountNumber();
            $transaction[$key]['soldeAriary'] = $transac->getSoldeAriary();
            $transaction[$key]['cours'] = $transac->getCours();
            $transaction[$key]['mainWallet'] = $transac->getGlobalWallet()->getMainWallet()->getMainWalletName();
            $transaction[$key]['mainWalletLogo'] = $transac->getGlobalWallet()->getMainWallet()->getLogo();
            $transaction[$key]['wallet'] = $transac->getGlobalWallet()->getWallet()->getWalletName();
            $transaction[$key]['currency'] = $transac->getGlobalWallet()->getWallet()->getCurrency();
            $transaction[$key]['walletLogoMain'] = $transac->getGlobalWallet()->getWallet()->getLogoMain();
            $transaction[$key]['walletLogo'] = $transac->getGlobalWallet()->getWallet()->getLogo();
            if(!$transac->getGasyWallet()){
                $transaction[$key]['gasyWallet'] = 'null';
                $transaction[$key]['gasyWalletLogo'] = 'null';
                $transaction[$key]['gasyWalletLogoMain'] = 'null';
            } else {
                $transaction[$key]['gasyWallet'] = $transac->getGasyWallet()->getGasyWalletName();
                $transaction[$key]['gasyWalletLogo'] = $transac->getGasyWallet()->getLogo();
                $transaction[$key]['gasyWalletLogoMain'] = $transac->getGasyWallet()->getLogoMain();
            }
            $transaction[$key]['date'] = $transac->getTransactionAt();
            $transaction[$key]['referenceManavola'] = $transac->getReferenceManavola();
            $transaction[$key]['transactionId'] = $transac->getTransactionId();
            $transaction[$key]['beingProcessed'] = $transac->isBeingProcessed();
            $transaction[$key]['verified'] = $transac->isVerified();
            $transaction[$key]['transactionDone'] = $transac->isTransactionDone();
            $transaction[$key]['failed'] = $transac->isFailed();

            //soldeAriary cours globalWallet gasyWallet date referenceManavola transactionId //policyAgreement transactionDone verified beingProcessed
        }
        return $this->json($transaction);
    }

    //admin
    #[Route('/getAllDoneTransaction', name: 'app_get_all_done_transaction', methods:'GET')]
    public function getAllDoneTransaction(): JsonResponse
    {
       // $user=$this->getUser();//findAllDoneTransaction

        $_transaction=$this->transactionRepository->findAllDoneTransaction();
        
       // dd($_transaction);
        $transaction= array();
        foreach ($_transaction as $key => $transac) {
            $transaction[$key]['id'] = $transac->getId();
            $transaction[$key]['transactionType'] = $transac->getTransactionType();
            $transaction[$key]['username'] = $transac->getUsers()->getUsername();
            $transaction[$key]['manavolaId'] = $transac->getUsers()->getAffiliated()->getMvxId();
            $transaction[$key]['solde'] = $transac->getSolde();
            $transaction[$key]['AccountNumber'] = $transac->getAccountNumber();
            $transaction[$key]['soldeAriary'] = $transac->getSoldeAriary();
            $transaction[$key]['cours'] = $transac->getCours();
            $transaction[$key]['mainWallet'] = $transac->getGlobalWallet()->getMainWallet()->getMainWalletName();
            $transaction[$key]['mainWalletLogo'] = $transac->getGlobalWallet()->getMainWallet()->getLogo();
            $transaction[$key]['wallet'] = $transac->getGlobalWallet()->getWallet()->getWalletName();
            $transaction[$key]['currency'] = $transac->getGlobalWallet()->getWallet()->getCurrency();
            $transaction[$key]['walletLogoMain'] = $transac->getGlobalWallet()->getWallet()->getLogoMain();
            $transaction[$key]['walletLogo'] = $transac->getGlobalWallet()->getWallet()->getLogo();
            if(!$transac->getGasyWallet()){
                $transaction[$key]['gasyWallet'] = 'null';
                $transaction[$key]['gasyWalletLogo'] = 'null';
                $transaction[$key]['gasyWalletLogoMain'] = 'null';
            } else {
                $transaction[$key]['gasyWallet'] = $transac->getGasyWallet()->getGasyWalletName();
                $transaction[$key]['gasyWalletLogo'] = $transac->getGasyWallet()->getLogo();
                $transaction[$key]['gasyWalletLogoMain'] = $transac->getGasyWallet()->getLogoMain();
            }
            $transaction[$key]['date'] = $transac->getTransactionAt();
            $transaction[$key]['referenceManavola'] = $transac->getReferenceManavola();
            $transaction[$key]['transactionId'] = $transac->getTransactionId();
            $transaction[$key]['beingProcessed'] = $transac->isBeingProcessed();
            $transaction[$key]['verified'] = $transac->isVerified();
            $transaction[$key]['transactionDone'] = $transac->isTransactionDone();
            $transaction[$key]['failed'] = $transac->isFailed();

            //soldeAriary cours globalWallet gasyWallet date referenceManavola transactionId //policyAgreement transactionDone verified beingProcessed
        }
        return $this->json($transaction);
    }

    //admin
    #[Route('/getAllFailedTransaction', name: 'app_get_all_failed_transaction', methods:'GET')]
    public function getAllFailedTransaction(): JsonResponse
    {
       // $user=$this->getUser();//findAllDoneTransaction

        $_transaction=$this->transactionRepository->findAllFailedTransaction();
        
       // dd($_transaction);
        $transaction= array();
        foreach ($_transaction as $key => $transac) {
            $transaction[$key]['id'] = $transac->getId();
            $transaction[$key]['transactionType'] = $transac->getTransactionType();
            $transaction[$key]['username'] = $transac->getUsers()->getUsername();
            $transaction[$key]['manavolaId'] = $transac->getUsers()->getAffiliated()->getMvxId();
            $transaction[$key]['solde'] = $transac->getSolde();
            $transaction[$key]['AccountNumber'] = $transac->getAccountNumber();
            $transaction[$key]['soldeAriary'] = $transac->getSoldeAriary();
            $transaction[$key]['cours'] = $transac->getCours();
            $transaction[$key]['mainWallet'] = $transac->getGlobalWallet()->getMainWallet()->getMainWalletName();
            $transaction[$key]['mainWalletLogo'] = $transac->getGlobalWallet()->getMainWallet()->getLogo();
            $transaction[$key]['wallet'] = $transac->getGlobalWallet()->getWallet()->getWalletName();
            $transaction[$key]['currency'] = $transac->getGlobalWallet()->getWallet()->getCurrency();
            $transaction[$key]['walletLogoMain'] = $transac->getGlobalWallet()->getWallet()->getLogoMain();
            $transaction[$key]['walletLogo'] = $transac->getGlobalWallet()->getWallet()->getLogo();
            if(!$transac->getGasyWallet()){
                $transaction[$key]['gasyWallet'] = 'null';
                $transaction[$key]['gasyWalletLogo'] = 'null';
                $transaction[$key]['gasyWalletLogoMain'] = 'null';
            } else {
                $transaction[$key]['gasyWallet'] = $transac->getGasyWallet()->getGasyWalletName();
                $transaction[$key]['gasyWalletLogo'] = $transac->getGasyWallet()->getLogo();
                $transaction[$key]['gasyWalletLogoMain'] = $transac->getGasyWallet()->getLogoMain();
            }
            $transaction[$key]['date'] = $transac->getTransactionAt();
            $transaction[$key]['referenceManavola'] = $transac->getReferenceManavola();
            $transaction[$key]['transactionId'] = $transac->getTransactionId();
            $transaction[$key]['beingProcessed'] = $transac->isBeingProcessed();
            $transaction[$key]['verified'] = $transac->isVerified();
            $transaction[$key]['transactionDone'] = $transac->isTransactionDone();
            $transaction[$key]['failed'] = $transac->isFailed();

            //soldeAriary cours globalWallet gasyWallet date referenceManavola transactionId //policyAgreement transactionDone verified beingProcessed
        }
        return $this->json($transaction);
    }

    //admin
    #[Route('/setverificationstep/{id}', name: 'app_setverification_transaction', methods:['PATCH','PUT','POST'])]
    public function setVerificationStep($id): JsonResponse
    {
        $transac= $this->transactionRepository->findOneById($id);

        if(! $transac){
            return $this->json([ 'message'=> "transaction doesn't exist" ], 404);
        }

        $transac->setVerified(true);
        $transac->setBeingProcessed(true);
        
        $this->em->getConnection()->beginTransaction();
        try {
            $this->em->persist($transac);
            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
            return $this->json(['message' => $e->getMessage()], 500);
        }
        return $this->json([
            'message'=> 'done'
        ]);
    }

    //admin
    #[Route('/sendsoldestep/{id}', name: 'app_sendsolde_transaction', methods:['PATCH','PUT','POST'])]
    public function setSendSoldeStep($id): JsonResponse
    {
        $transac= $this->transactionRepository->findOneById($id);

        if(! $transac){
            return $this->json([ 'message'=> "transaction doesn't exist" ], 404);
        }

        $transac->setVerified(true);
        $transac->setBeingProcessed(true);
        
        $this->em->getConnection()->beginTransaction();
        try {
            $this->em->persist($transac);
            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
            return $this->json(['message' => $e->getMessage()], 500);
        }
        return $this->json([
            'message'=> 'done'
        ]);
    }

    //admin
    #[Route('/setTransactionDone/{id}', name: 'app_settransactiondone_transaction', methods:['PATCH','PUT','POST'])]
    public function settransactiondone($id): JsonResponse
    {
        $transac= $this->transactionRepository->findOneById($id);

        if(! $transac){
            return $this->json([ 'message'=> "transaction doesn't exist" ], 404);
        }

        $transac->setVerified(true);
        $transac->setBeingProcessed(true);
        $transac->setTransactionDone(true);

           //add RP
        $rp=$this->rPUtils->RPO($transac->getSoldeAriary());
        $transac->setRPObtenue($rp);
   
           //new CurrentRP
        $user=$transac->getUsers();
        $user->setCurrentRP($user->getCurrentRP()+$rp);
           
   
        
        $this->em->getConnection()->beginTransaction();
        try {
            $this->em->persist($transac);
            $this->em->persist($user);
            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
            return $this->json(['message' => $e->getMessage()], 500);
        }
        return $this->json([
            'message'=> 'done'
        ]);

    }

    //admin
    #[Route('/settransactionfailed/{id}', name: 'app_settransactionfailed_transaction', methods:['PATCH','PUT','POST'])]
    public function setTransactionFailed($id): JsonResponse
    {
        $transac= $this->transactionRepository->findOneById($id);

        if(! $transac){
            return $this->json([ 'message'=> "transaction doesn't exist" ], 404);
        }

        $transac->setFailed(true);
        
        $this->em->getConnection()->beginTransaction();
        try {
            $this->em->persist($transac);
            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
            return $this->json(['message' => $e->getMessage()], 500);
        }
        return $this->json([
            'message'=> 'done'
        ]);

    }
}
