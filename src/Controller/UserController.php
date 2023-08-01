<?php

namespace App\Controller;

use DateTime;
use OpenApi\Attributes as OA;
use App\Repository\UserRepository;
use App\Repository\DepotRepository;
use App\Repository\AffiliatedRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AffiliatedLevelRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserVerifiedInfoRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{  
    private $em;
    private $userRepository;
    private $affiliatedRepository;
    private $affiliatedLevelRepository;
    private $userVerifiedInfoRepository;
    //private $depotRepository;

    public  function __construct(UserRepository $userRepository/*, DepotRepository $depotRepository*/,EntityManagerInterface $em, AffiliatedRepository $affiliatedRepository, AffiliatedLevelRepository $affiliatedLevelRepository, UserVerifiedInfoRepository $userVerifiedInfoRepository)
    {
        $this->userRepository = $userRepository;
      //  $this->depotRepository=$depotRepository;
        $this->affiliatedRepository=$affiliatedRepository;
        $this->affiliatedLevelRepository=$affiliatedLevelRepository;
        $this->userVerifiedInfoRepository=$userVerifiedInfoRepository;
        $this->em = $em;
    }

    #[OA\Get(path: "/user", description: 'get user logged info', tags: ['User'])]
    #[OA\Response(
        response: 200,
        description: 'data received when in success',
        content: [
            'application/json' => new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    properties: [
                        'id' => new OA\Property(property: 'id', type: 'integer'),
                        'email' => new OA\Property(property: 'email', type: 'string', format: 'email'),
                        'username' => new OA\Property(property: 'username', type: 'string'),
                        'fullname' => new OA\Property(property: 'fullname', type: 'string'),
                        'telma' => new OA\Property(property: 'telma', type: 'string'),
                        'orange' => new OA\Property(property: 'orange', type: 'string'),
                        'airtel' => new OA\Property(property: 'airtel', type: 'string'),
                        'createdAt' => new OA\Property(property: 'createdAt', type: 'string', format: 'date-time'),
                        'isVerified' => new OA\Property(property: 'isVerified', type: 'boolean'),
                        'parrainageId' => new OA\Property(property: 'parrainageId', type: 'string')
                    ]
                )
            )
        ]
    )]
    #[Route('/api/user', name: 'app_user')]
    public function getCurrentuser(): JsonResponse
    {
        $user = $this->getUser();
        
        /*if (!$user) {
            return $this->json(['message' => 'not connected'], 401);
        }*/
        //misi erreur ni vs code
        //$user = $this->userRepository->findOneById(1);


        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'username' => $user->getUsername(),
            'fullname' => $user->getFullname(),
            'telma' => $user->getTelma(),
            'orange' => $user->getOrange(),
            'airtel' => $user->getAirtel(),
            'createdAt' => $user->getCreatedAt(),
            'isVerified' => $user->isVerifiedStatus(),
            'currentRP' => $user->getCurrentRP(),
            'parrainageId' => $user->getAffiliated()->getParrainageId(),
            'roles' => $user->getRoles()
        ]);
    }

    #[Route('/api/userdetailled', name: 'app_dashboard', methods:'GET')]
    public function getDashboard(): JsonResponse
    {

        //misi erreur ni vs code
        $user = $this->getUser();
        $affiliated=$this->affiliatedRepository->findOneByUser($user);

        return $this->json([
            'id' => $user->getId(),
          //  'email' => $user->getEmail(),
           // 'username' => $user->getUsername(),
           // 'fullname' => $user->getFullname(),
            'telma' => $user->getTelma(),
            'orange' => $user->getOrange(),
            'airtel' => $user->getAirtel(),
            'createdAt' => $user->getCreatedAt(), 
            'currentRP' => $user->getCurrentRP(),
           //'fidelityPt' => $user->getFidelityPt(),
            'isVerified' => $user->isVerifiedStatus(),
            'isVerificationPending'=>$user->isVerificationPending(),
            'parrainageId' => $affiliated->getParrainageId(),
            'mvx'=>$affiliated->getMvx()
        ]);
    }


    #[Route('/api/edituser', name: 'app_edit_user', methods:'POST')]
    public function editCurrentUser(Request $request): JsonResponse
    {
        $user = $this->getUser();

        $user->setEmail($request->request->get('email',$user->getEmail()));
        $user->setUsername($request->request->get('username',$user->getUsername()));
       // $user->setTelma($request->request->get('telma',$user->getTelma()));
       // $user->setOrange($request->request->get('orange',$user->getOrange()));
       // $user->setAirtel($request->request->get('airtel',$user->getAirtel()));
        /*
        'email' => $user->getEmail(),
        'username' => $user->getUsername(),
        'fullname' => $user->getFullname(),
        'telma' => $user->getTelma(),
        'orange' => $user->getOrange(),
        'airtel' => $user->getAirtel(),*/

        /*if (!$user) {
            return $this->json(['message' => 'not connected'], 401);
        }*/
        //misi erreur ni vs code
        //$user = $this->userRepository->findOneById(1);

        $this->em->getConnection()->beginTransaction();
        try {
            $this->em->persist($user);
            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }

        return $this->json([
            'email' => $user->getEmail(),
            'username' => $user->getUsername(),
           // 'telma' => $user->getTelma(),
           // 'orange' => $user->getOrange(),
           // 'airtel' => $user->getAirtel(),
        ]);
    }

    #[Route('api/editnumber', name: 'app_edit_number', methods:'POST')]
    public function editCurrentUserNumberOrAdd(Request $request): JsonResponse
    {
        $user = $this->getUser();
        $phoneNumber=$request->request->get('number');
        
       // return $this->json($user->getUsername(),403);
        //adding phone number
        if(str_contains(substr($phoneNumber,0,3),'034')){
            $user->setTelma($phoneNumber);
        }
        else if(str_contains(substr($phoneNumber,0,3),'038')){
            $user->setTelma($phoneNumber);
        }
        else if(str_contains(substr($phoneNumber,0,3),'033')){
            $user->setAirtel($phoneNumber);
        }
        else if(str_contains(substr($phoneNumber,0,3),'032')){
            $user->setOrange($phoneNumber);
        }
        else {
            return $this->json(['erreur: le numero de telephone est invalide'],401);
        }

        

        $this->em->getConnection()->beginTransaction();
        try {
            $this->em->persist($user);
            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }

        return $this->json([
            'status' => 'successed'
           // 'telma' => $user->getTelma(),
           // 'orange' => $user->getOrange(),
           // 'airtel' => $user->getAirtel(),
        ]);
    }
   /* #[Route('/api/getRecentTransac', name: 'app_get_recent_transac')]
    public function getRecentTransac(): JsonResponse
    {
        $transac=$this->depotRepository->findAllTransacByUserId($this->getUser()->getId());
        dd($transac);
        return $this->json(['hgh']);
    }*/

    
    #[Route('/api/setting', name: 'app_setting', methods:'GET')]
    public function getSetting(): JsonResponse
    {

        //misi erreur ni vs code
        $user = $this->getUser();

        $affiliated=$this->affiliatedRepository->findOneByUser($user);

        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'username' => $user->getUsername(),
            'fullname' => $user->getFullname(),
            'telma' => $user->getTelma(),
            'orange' => $user->getOrange(),
            'airtel' => $user->getAirtel(),
            'createdAt' => $user->getCreatedAt(),
            'isVerified' => $user->isVerifiedStatus(),
            'isVerificationPending'=>$user->isVerificationPending(),
            'parrainageId' => $affiliated->getParrainageId(),
            'mvx'=>$affiliated->getMvx()
        ]);
    }

    //user
    #[Route('/api/commission', name: 'app_commission', methods:'GET')]
    public function getCommission(): JsonResponse
    {
        $_commission=$this->affiliatedLevelRepository->findByAffiliated($this->getUser()->getAffiliated());
        

        $commission = array();

        foreach($_commission as $key => $com){
            $commission[$key]['manavolaId']=$com->getUser()->getAffiliated()->getParrainageId();
            $commission[$key]['username']=$com->getUser()->getUsername();
        }
        return $this->json($commission);
    }

    //admin
    #[Route('/commission/{id}', name: 'app_commission_admin', methods:'GET')]
    public function getCommissionUser($id): JsonResponse
    {
        $user = $this->userRepository->findOneById($id);

        $_commission=$this->affiliatedLevelRepository->findByAffiliated($user->getAffiliated());

        $commission = array();

        foreach($_commission as $key => $com){
            $commission[$key]['manavolaId']=$com->getUser()->getAffiliated()->getParrainageId();
            $commission[$key]['username']=$com->getUser()->getUsername();
        }
        return $this->json($commission);
    }

    //admin
    #[Route('/getAllUser', name: 'app_findAllUser', methods:'GET')]
    public function getAllUser(): JsonResponse
    {
        $_users=$this->userRepository->findAll();
    
        $users= array();

        foreach($_users as $key=>$user){
            $users[$key]['id']=$user->getId();
            $users[$key]['mvxId']=$user->getAffiliated()->getParrainageId();
            $users[$key]['email']=$user->getEmail();
            $users[$key]['username']=$user->getUsername();
            $users[$key]['verificationPending']=$user->isverificationPending();
            $users[$key]['verificationFailed']=$user->isverificationFailed();
            $users[$key]['verifiedStatus']=$user->isverifiedStatus();
        }
        return $this->json($users);
    }

    //admin
    #[Route('/getAllpendingUser', name: 'app_findAllpendingUser', methods:'GET')]
    public function getAllpendingUser(): JsonResponse
    {
        $_users=$this->userRepository->findAllpendingUser();
    
        $users= array();

        foreach($_users as $key=>$user){
            $users[$key]['id']=$user->getId();
            $users[$key]['mvxId']=$user->getAffiliated()->getParrainageId();
            $users[$key]['email']=$user->getEmail();
            $users[$key]['username']=$user->getUsername();
        }
        return $this->json($users);
    }

    //admin
    #[Route('/getAllConfirmedUser', name: 'app_findAllConfirmedgUser', methods:'GET')]
    public function getAllConfirmedUser(): JsonResponse
    {
        $_users=$this->userRepository->findAllConfirmedUser();
    
        $users= array();

        foreach($_users as $key=>$user){
            $users[$key]['id']=$user->getId();
            $users[$key]['mvxId']=$user->getAffiliated()->getParrainageId();
            $users[$key]['email']=$user->getEmail();
            $users[$key]['username']=$user->getUsername();
        }
        return $this->json($users);
    }

    //admin
    #[Route('/getAllRejectedUser', name: 'app_findAllRejectedgUser', methods:'GET')]
    public function getAllRejectedUser(): JsonResponse
    {
        $_users=$this->userRepository->findAllRejectedUser();
    
        $users= array();

        foreach($_users as $key=>$user){
            $users[$key]['id']=$user->getId();
            $users[$key]['mvxId']=$user->getAffiliated()->getParrainageId();
            $users[$key]['email']=$user->getEmail();
            $users[$key]['username']=$user->getUsername();
        }
        return $this->json($users);
    }

    //admin
    #[Route('/userVerficationInfo/{id}', name: 'app_userVerficationInfo', methods:'GET')]
    public function getUserVerficationInfo($id): JsonResponse
    {
        $user=$this->userRepository->findOneById($id);
        //$userVerificationInfo=$this->userVerifiedInfoRepository->findOneById($id);

        if(!$user){
            return $this->json(['error'=>'not found for userId='.$id],404);
        }

        $userVerificationInfo= $this->userVerifiedInfoRepository->findOneByUser($user);
        //dd($user);

        if(!$userVerificationInfo){
            return $this->json(['error'=>'didn t send verification info yet for userId='.$id],401);
        }
        //rectophoto versoPhoto selfieAvecCIN numeroCIN
        $users= array();
            $users['id']=$user->getId();
            $users['mvxId']=$user->getAffiliated()->getParrainageId();
            $users['email']=$user->getEmail();
            $users['username']=$user->getUsername();
            $users['fullname']=$user->getFullname();
            $users['rectophoto']=$userVerificationInfo->getRectophoto();
            $users['versoPhoto']=$userVerificationInfo->getVersoPhoto();
            $users['selfieAvecCIN']=$userVerificationInfo->getSelfieAvecCIN();
            $users['numeroCIN']=$userVerificationInfo->getNumeroCIN();
        
        return $this->json($users);
    }

    //admin
    
    #[Route('/comfirmUserVerification/{id}', name: 'app_comfirm_user_verification', methods:['POST','PATCH','PUT'])]
    public function comfirmUserVerification($id): JsonResponse
    {
        $user = $this->userRepository->findOneById($id);

        if(!$user){
            return $this->json('error no user found for userId='+$id);
        }

        $user->setVerifiedStatus(true);
  //      $userInfo= $this->userVerifiedInfoRepository->findOneByUser($user);

//TODO: time problem to be solved
       // $date= new DateTime();
       // $userInfo->setVerifiedAt($date->format('U = Y-m-d H:i:s'));
      // $userInfo->setVerifiedAt($date);

        $this->em->getConnection()->beginTransaction();
        try {
            $this->em->persist($user);
         //   $this->em->persist($userInfo);
            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }

        return $this->json('sucess');
    }

    //admin
    
    #[Route('/rejectUserVerification/{id}', name: 'app_reject_user_verification', methods:['POST','PATCH','PUT'])]
    public function rejectUserVerification($id): JsonResponse
    {
        $user = $this->userRepository->findOneById($id);

        if(!$user){
            return $this->json('error no user found for userId='+$id);
        }

        $user->setVerificationFailed(true);

        $this->em->getConnection()->beginTransaction();
        try {
            $this->em->persist($user);
            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }

        return $this->json('sucess');
    }

    //admin 
    #[Route('/userdetail/{id}', name: 'app_user_detail')]
    public function getUserDetail($id): JsonResponse
    {
        $user = $this->userRepository->findOneById($id);

        /*if (!$user) {
            return $this->json(['message' => 'not connected'], 401);
        }*/
        //misi erreur ni vs code
        //$user = $this->userRepository->findOneById(1);


        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'username' => $user->getUsername(),
            'fullname' => $user->getFullname(),
            'telma' => $user->getTelma(),
            'orange' => $user->getOrange(),
            'airtel' => $user->getAirtel(),
            'createdAt' => $user->getCreatedAt(),
            'isVerified' => $user->isVerifiedStatus(),
            'parrainageId' => $user->getAffiliated()->getParrainageId(),
            'roles' => $user->getRoles()
        ]);
    }
}