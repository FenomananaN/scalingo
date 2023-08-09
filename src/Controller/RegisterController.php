<?php

namespace App\Controller;

use OpenApi\Attributes as OA;
use App\Entity\User;
use App\Entity\Affiliated;
use App\Service\RandomMVXId;
use App\Entity\AffiliatedLevel;
use App\Entity\UserVerifiedInfo;
use App\Repository\UserRepository;
use App\Repository\AffiliatedRepository;
use App\Repository\RPManagerRepository;
use App\Service\CheckMultiple;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    private $em;
    private $userRepository;
    private $affiliatedRepository;
    private $randomMVXId;
    private $checkMultiple;
    private $rpManagerRepository;

    public function __construct(EntityManagerInterface $em, UserRepository $userRepository, AffiliatedRepository $affiliatedRepository, RandomMVXId $randomMVXId, CheckMultiple $checkMultiple, RPManagerRepository $rpManagerRepository)
    {
        $this->em = $em;
        $this->affiliatedRepository = $affiliatedRepository;
        $this->userRepository = $userRepository;
        $this->randomMVXId = $randomMVXId;
        $this->checkMultiple = $checkMultiple;
        $this->rpManagerRepository=$rpManagerRepository;
    }
    //, description: 'register a new user', tags: 'Register'
    #[Route('/register', name: 'app_register', methods: 'POST')]
    #[OA\Post(path: "/register", description: 'register a new user', tags: ['Register'])]
    #[OA\RequestBody(
        request: 'create new user',
        required: true,
        content: [
            'application/json' => new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    required: ['email', 'password', 'username'],
                    properties: [
                        'email' => new OA\Property(property: 'email', type: 'string', format: 'email'),
                        'password' => new OA\Property(property: 'password', type: 'string', format: 'password'),
                        'username' => new OA\Property(property: 'username', type: 'string'),
                        'parrainerId' => new OA\Property(property: 'parrainerId', type: 'string')
                    ]
                )
            )
        ]
    )]
    #[OA\Response(
        response: 200,
        description: 'data received when in success',
        content: [
            'application/json' => new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    properties: [
                        'user_id' => new OA\Property(property: 'user_id', type: 'integer'),
                        'email' => new OA\Property(property: 'email', type: 'string', format: 'email'),
                        'username' => new OA\Property(property: 'username', type: 'string'),
                        'parrainageId' => new OA\Property(property: 'parrainageId', type: 'string'),
                    ]
                )
            )
        ]
    )]
    public function register(Request $request, UserPasswordHasherInterface $passwordhasher): JsonResponse
    {
        $email = $request->request->get('email');
        $plainPassword = $request->request->get('password');
        $username = $request->request->get('username');

        $parrainerId = $request->request->get("parrainerId");

        $phoneNumber = $request->request->get('phonenumber');
        
        
        //check if user has already registred
        $allEmails = $this->userRepository->findAllEmail();
        $allEmailArray = array();
        foreach ($allEmails as $key => $emailcheck) {
            $allEmailArray[$key] = $emailcheck['email'];
        }
        if ($this->checkMultiple->checkMultiple($email, $allEmailArray)) {
            //verifie si le user est deja un user verifier
            if ($this->userRepository->findOneByEmail($email)->isVerifiedStatus()) {
                $message = 'Le compte existe déjà';
            } else {
                $message = 'Le compte exite déjà et veuillez verifier votre compte';
            }
            return $this->json([
                'message' => $message,
            ],401);
        }
        $user = new User();
        $user->setEmail($email);

        $user->setUsername($username);

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


        $user->setRoles(['ROLE_USER']);

        //hashing the password
        $hashedPassword = $passwordhasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);
        $user->setVerifiedStatus(false);
        $user->setVerificationFailed(false);
        $user->setCreatedAt(new \DateTime());

        //set initial value to RP
        $rp=$this->rpManagerRepository->findOneById(1);
        $user->setCurrentRP($rp->getRPInitial());
        //
        //

        //creating parrainage for user
        //  $user=$this->userRepository->findOneByEmail('fenomanana.nomenjanahary@gmail.com');

        $affiliated = new Affiliated();
        $affiliated->setUsers($user);
        $affiliated->setCommision(0);

        //creating the generated parrainageId
        $mvxIds = $this->affiliatedRepository->findAllMvxId();
        $mvxId = array();
        foreach ($mvxIds as $key => $parrainage) {
            $mvxId[$key] = $parrainage['mvxId'];
        }

        $newMvxId = $this->randomMVXId->getNewMVXId($mvxId);
        $affiliated->setMvxId($newMvxId);
        //dd($affiliated);
        
//hasian message flash
        //jerena raha nisi nanetana izy
        $affiliater = $this->affiliatedRepository->findOneBy(['mvxId' => $parrainerId]); //MVX56779 98575

        if ($affiliater) {
            $affiliatedLevel = new AffiliatedLevel();
            $affiliatedLevel->setAffiliated($affiliater);
            $affiliatedLevel->setUsers($user);
        }
       // dump($affiliatedLevel);
       // dd($user);

        //methode hi enregistrena azy amn ni base
        $this->em->getConnection()->beginTransaction();
        try {

            $this->em->persist($user);
            $this->em->persist($affiliated);


            if ($affiliater) {
                $this->em->persist($affiliatedLevel);
            }

            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }

        return $this->json([
            'user_id' => $user->getId(),
            'email' => $email,
            'username' => $username,
            'mvxId' => $newMvxId
        ]);
    }
    //, tags: 'Register'
    #[OA\Post(path: '/registerverification', description: 'to make the user verified', tags: ['Register'])]
    #[OA\RequestBody(
        request: 'register verification',
        description: 'data to enter for making the user verified',
        required: true,
        content: [
            'multipart/form-data' => new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    type: 'object',
                    required: ['rectophoto', 'versoPhoto', 'selfieAvecCIN'],
                    properties: [
                        'rectophoto' => new OA\Property(property: 'rectophoto', type: 'file', format: 'binary'),
                        'versoPhoto' => new OA\Property(property: 'versoPhoto', type: 'file', format: 'binary'),
                        'selfieAvecCIN' => new OA\Property(property: 'selfieAvecCIN', type: 'file', format: 'binary'),
                    ]
                )
            ),
            'application/json' => new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    required: ['numeroCIN', 'fullname'],
                    properties: [
                        'numeroCIN' => new OA\Property(property: 'numeroCIN', type: 'string'),
                        'email' => new OA\Property(property: 'email', type: 'string', format: 'email'),
                        'fullname' => new OA\Property(property: 'fullname', type: 'string'),
                    ]
                )
            )
        ]
    )]
    #[OA\Response(response: '201', description: 'Created')]

    //validation after registration// user non connected
    #[Route('/registerverificationn', name: 'app_register_verificationm', methods: 'POST')]
    public function verificationRegister(Request $request): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            $user = $this->userRepository->findOneByEmail($request->request->get('email'));
        }
        $userVerifiedInfo = new UserVerifiedInfo();
        $userVerifiedInfo->setUsers($user);

        $user->setFullname($request->request->get('fullname'));
        $userVerifiedInfo->setNumeroCIN('' . $request->request->get('numeroCIN'));


        $path = $this->getParameter('kernel.project_dir') . '/public/image/verification';
        //get rectoPhotoCIN
        $file = $request->files->get('rectoPhoto');
        
        $filename = md5(uniqid()) . '.' . $file->guessClientExtension();
        $file->move($path, $filename);
        $userVerifiedInfo->setRectoPhoto($filename);

        //set VersoPhotoCIN
        $file = $request->files->get('versoPhoto');
        $filename = md5(uniqid()) . '.' . $file->guessClientExtension();
        $file->move($path, $filename);
        $userVerifiedInfo->setVersoPhoto($filename);

        //set SelfieAvecCIN
        $file = $request->files->get('selfieAvecCIN');
        $filename = md5(uniqid()) . '.' . $file->guessClientExtension();
        $file->move($path, $filename);
        $userVerifiedInfo->setSelfieAvecCIN($filename);

        //set the time of verification
        $userVerifiedInfo->setVerifiedAt(new \DateTime());

        //set VerifiedStatus to true
        $user->setVerificationPending(true);

        //methode hi enregistrena azy amn ni base
        $this->em->getConnection()->beginTransaction();
        try {

            $this->em->persist($user);
            $this->em->persist($userVerifiedInfo);

            $this->em->flush();

            $this->em->commit();
            //

        } catch (\Exception $e) {
            $this->em->rollback();

            throw $e;
        }

        return $this->json([
            'message' => 'success',
        ]);
    }

    //validation user connected
    #[Route('/validate', name: 'app_register_validate', methods: 'POST')]
    public function validate(Request $request): JsonResponse
    {
        $user = $this->getUser();
        if (!$user) {
            $user = $this->userRepository->findOneByEmail($request->request->get('email'));
        }

        $userVerifiedInfo = new UserVerifiedInfo();
        $userVerifiedInfo->setUsers($user);

        $user->setFullname($request->request->get('fullname'));
        $userVerifiedInfo->setNumeroCIN('' . $request->request->get('numeroCIN'));
        
        $path = $this->getParameter('kernel.project_dir') . '/public/image/verification';
        //get rectoPhotoCIN
        $file = $request->files->get('rectoPhoto');
        //dump($file);
        $filename = md5(uniqid()) . '.' . $file->guessClientExtension();
        $file->move($path, $filename);
        $userVerifiedInfo->setRectoPhoto($filename);

        //set VersoPhotoCIN
        $file = $request->files->get('versoPhoto');
        $filename = md5(uniqid()) . '.' . $file->guessClientExtension();
        $file->move($path, $filename);
        $userVerifiedInfo->setVersoPhoto($filename);

        //set SelfieAvecCIN
        $file = $request->files->get('selfieAvecCIN');
        $filename = md5(uniqid()) . '.' . $file->guessClientExtension();
        $file->move($path, $filename);
        $userVerifiedInfo->setSelfieAvecCIN($filename);

        //set the time of verification
        $userVerifiedInfo->setVerifiedAt(new \DateTime());
        
        //set VerifiedStatus to true
        $user->setVerificationPending(true);

        //methode hi enregistrena azy amn ni base
        $this->em->getConnection()->beginTransaction();
        try {

            $this->em->persist($user);
            $this->em->persist($userVerifiedInfo);

            $this->em->flush();

            $this->em->commit();
            

        } catch (\Exception $e) {
            $this->em->rollback();

            throw $e;
        }


        return $this->json(['mety']);
    }
}
