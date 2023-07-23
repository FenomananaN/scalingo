<?php

namespace App\Controller;

use App\Entity\Retrait;
use App\Service\RandomMVXId;
use App\Repository\UserRepository;
use App\Repository\WalletRepository;
use App\Repository\RetraitRepository;
use App\Repository\GasyWalletRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\GlobalWalletRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RetraitController extends AbstractController
{
    private $em;
    private $walletRepository;
    private $userRepository;
    private $globalWalletRepository;
    private $gasyWalletRepository;
    private $retraitRepository;
    private $randomMVXId;

    public function __construct(EntityManagerInterface $em, GlobalWalletRepository $globalWalletRepository, WalletRepository $walletRepository, UserRepository $userRepository, GasyWalletRepository $gasyWalletRepository, RetraitRepository $retraitRepository, RandomMVXId $randomMVXId)
    {
        $this->em = $em;
        $this->walletRepository = $walletRepository;
        $this->globalWalletRepository = $globalWalletRepository;
        $this->userRepository = $userRepository;
        $this->gasyWalletRepository = $gasyWalletRepository;
        $this->retraitRepository = $retraitRepository;
        $this->randomMVXId = $randomMVXId;
    }
    /*
    #[Route('/retrait', name: 'app_retrait', methods:'POST')]
    public function retrait(Request $request): JsonResponse
    {//id,user,gasyWallet,globalWallet,soldeDemmande,totalToReceive,cours,policyAgreement,beingProcessed,verified,transacDone:

        $retrait = new Retrait();

        $user=$this->getUser();
        if (!$user) {
            $user = $this->userRepository->findOneByEmail($request->request->get('numero'));
            if (!$user) {
                return $this->json(['message' => 'Veuillez entrer un numero valide'], 401);
            }
        }

        $retrait->setUser($user);

        //get wallet type
        $globalWallet = $this->globalWalletRepository->findOneById($request->request->get('globalWallet_id'));

        $retrait->setGlobalWallet($globalWallet);

        //get gasyWallet
        $gasyWallet=$this->gasyWalletRepository->findOneById($request->request->get('mobile'));
        $retrait->setGasyWallet($gasyWallet);

        $retrait->setSoldeDemmande($request->request->get('solde_demande'));
        $retrait->setTotalToReceive($request->request->get('total_to_receive'));
        $retrait->setCours($request->request->get('cours'));
        $retrait->setPolicyAgreement($request->request->get('check_agreement'));
        $retrait->setDate(new \DateTime());
        $retrait->setVerified(false);
        $retrait->setBeingProcessed(false);
        $retrait->setTransacDone(false);

        //creating the generated parrainageId
        $_referenceManavola = $this->retraitRepository->findAllReferenceManavolaId();
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
            'solde_demande' => $retrait->getSoldeDemmande(),
   //         'numero_compte' => $retrait->getNumeroCompte(),
            'total_to_receive' => $retrait->getTotalToReceive(),
            'referenceManavola' => $retrait->getReferenceManavola(),
            'cours' => $retrait->getCours(),
            'date' => $retrait->getDate(),
            'mobile'=>$retrait->getGasyWallet()->getGasyWalletName(),
            'mobileLogo'=>$retrait->getGasyWallet()->getLogo(),
            'mainWallet' => $retrait->getGlobalWallet()->getMainWallet()->getMainWalletName(),
            'mainWalletLogo' => $retrait->getGlobalWallet()->getMainWallet()->getLogo(),
            'walletLogo' => $retrait->getGlobalWallet()->getWallet()->getLogo(),
            'wallet' => $retrait->getGlobalWallet()->getWallet()->getWalletName(),
            'currency' => $retrait->getGlobalWallet()->getWallet()->getCurrency(),
        ]);
    }
    #[Route('/retrait/addtransactionId', name: 'app_retrait_transac', methods:'POST')]
    public function retraitTransac(Request $request): JsonResponse
    {//id,user,gasyWallet,globalWallet,soldeDemmande,totalToReceive,cours,policyAgreement,beingProcessed,verified,transacDone:
        $retrait=$this->retraitRepository->findOneById($request->request->get('retrait_id'));

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
    }*/
}
