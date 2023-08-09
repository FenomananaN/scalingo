<?php

namespace App\Controller;

use App\Entity\Wallet;
use App\Entity\DepotCours;
use App\Entity\GasyWallet;
use App\Entity\MainWallet;
use App\Entity\GlobalWallet;
use App\Entity\RetraitCours;
use OpenApi\Attributes as OA;
use App\Repository\WalletRepository;
use App\Repository\DepotCoursRepository;
use App\Repository\GasyWalletRepository;
//use App\Repository\RetraitCoursRepository;
use App\Repository\MainWalletRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\GlobalWalletRepository;
use App\Repository\RetraitCoursRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class WalletController extends AbstractController
{
    private $em;
    private $walletRepository;
    private $mainWalletRepository;
    private $globalWalletRepository;
    private $retraitCoursRepository;
    private $gasyWalletRepository;
    private $depotCoursRepository;

    public function __construct(EntityManagerInterface $em, WalletRepository $walletRepository, MainWalletRepository $mainWalletRepository, GlobalWalletRepository $globalWalletRepository, RetraitCoursRepository $retraitCoursRepository, GasyWalletRepository $gasyWalletRepository, DepotCoursRepository $depotCoursRepository)
    {
        $this->em = $em;
        $this->walletRepository = $walletRepository;
        $this->mainWalletRepository = $mainWalletRepository;
        $this->globalWalletRepository = $globalWalletRepository;
        $this->depotCoursRepository = $depotCoursRepository;
        $this->retraitCoursRepository = $retraitCoursRepository;
        $this->gasyWalletRepository = $gasyWalletRepository;
    }




    #[Route('/newMainWallet', name: 'app_new_main_wallet', methods: 'POST')]
    public function newMainWallet(Request $request): JsonResponse
    {
        $mainWallet = new MainWallet();
        $mainWallet->setMainWalletName($request->request->get('mainWalletName'));
        $file = $request->files->get('logo');
        $path = $this->getParameter('kernel.project_dir') . '/public/image/logo';
        $filename = md5(uniqid()) . '.' . $file->guessClientExtension();
        $file->move($path, $filename);

        $mainWallet->setLogo($filename);


        $this->em->getConnection()->beginTransaction();
        try {

            $this->em->persist($mainWallet);
            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }
        return $this->json([
            'mainWallet_id' => $mainWallet->getId(),
            'mainWallet_name' => $mainWallet->getMainWalletName(),
            'mainWallet_logo' => $mainWallet->getLogo()
        ]);
    }

    #[OA\Post(path: '/newWallet', description: 'to create a new wallet', tags: ['Wallet'])]
    #[OA\RequestBody(
        request: 'new Wallet',
        description: 'to create a new Wallet',
        required: true,
        content: [
            'multipart/form-data' => new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    type: 'object',
                    required: ['logo'],
                    properties: [
                        'logo' => new OA\Property(property: 'logo', type: 'file', format: 'binary'),
                    ]
                )
            ),
            'application/json' => new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    required: ['walletName', 'currency'],
                    properties: [
                        'walletName' => new OA\Property(property: 'walletName', type: 'string'),
                        'reserve' => new OA\Property(property: 'reserve', type: 'string'),
                        'currency' => new OA\Property(property: 'currency', type: 'string'),
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
                        'wallet_id' => new OA\Property(property: 'wallet_id', type: 'integer'),
                        'wallet_name' => new OA\Property(property: 'wallet_name', type: 'string'),
                        'currency' => new OA\Property(property: 'currency', type: 'string'),
                        'wallet_logo' => new OA\Property(property: 'wallet_logo', type: 'string'),
                    ]
                )
            )
        ]
    )]
    #[Route('/newWallet', name: 'app_new_wallet', methods: 'POST')]
    public function newWallet(Request $request): JsonResponse
    {
        $wallet = new Wallet();
        $wallet->setWalletName($request->request->get("walletName", null));
        $wallet->setCurrency($request->request->get('currency'));
        //    $wallet->setCoursDepot($request->request->get('coursdepot'));


        //for main logo
        $file = $request->files->get('logoMain');

        $path = $this->getParameter('kernel.project_dir') . '/public/image/logo';
        $filename = md5(uniqid()) . '.' . $file->guessClientExtension();
        $file->move($path, $filename);

        $wallet->setLogoMain($filename);

        //for sublogo
        $file = $request->files->get('logo');

        $path = $this->getParameter('kernel.project_dir') . '/public/image/logo';
        $filename = md5(uniqid()) . '.' . $file->guessClientExtension();
        $file->move($path, $filename);

        $wallet->setLogo($filename);

        $this->em->getConnection()->beginTransaction();
        try {

            $this->em->persist($wallet);
            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }
        return $this->json([
            'wallet_id' => $wallet->getId(),
            'wallet_name' => $wallet->getWalletName(),
            'currency' => $wallet->getCurrency(),
            'wallet_logoMain' => $wallet->getLogoMain(),
            'wallet_logo' => $wallet->getLogo()
        ]);
    }

    #[Route('/matchWallet', name: 'app_match_wallet', methods: 'POST')]
    public function matchWallet(Request $request): JsonResponse
    {


        $mainWallet = $request->request->get('mainWallet_id');
        $wallet = $request->request->get('wallet_id');
        $reserve = $request->request->get('reserve', 0);
        $link = $request->request->get('link');
        $fraisDepotCharged = $request->request->get('fraisDepotCharged', false);
        $fraisDepot = $request->request->get('fraisDepot');
        
        

        $mainWallet = $this->mainWalletRepository->findOneById($mainWallet);
        $wallet = $this->walletRepository->findOneById($wallet);

        $globalWallet = new GlobalWallet();
        $globalWallet->setMainWallet($mainWallet);
        $globalWallet->setWallet($wallet);
        $globalWallet->setReserve($reserve);
        $globalWallet->setLink($link);
        $globalWallet->setFraisDepotCharged($fraisDepotCharged);
        $globalWallet->setFraisDepot($fraisDepot);
        $globalWallet->setFraisWallet($request->request->get('fraisWallet'));
        $globalWallet->setFraisBlockchain($request->request->get('fraisBlockchain'));

        $this->em->getConnection()->beginTransaction();
        try {

            $this->em->persist($globalWallet);
            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;

            return new JsonResponse([
                'message' => $e->getMessage(),
            ], 500);
        }
        return $this->json([
            'globalWallet_id' => $globalWallet->getId(),
            'mainWallet_name' => $globalWallet->getMainWallet()->getMainWalletName(),
            'wallet_name' => $globalWallet->getWallet()->getWalletName(),
            'reserve' => $globalWallet->getReserve()
        ]);
    }


    #[OA\Post(path: '/newGasyWallet', description: 'to create a new gasy wallet', tags: ['Wallet'])]
    #[OA\RequestBody(
        request: 'new gasy Wallet',
        description: 'to create a new gasy Wallet',
        required: true,
        content: [
            'multipart/form-data' => new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    type: 'object',
                    required: ['logo'],
                    properties: [
                        'logo' => new OA\Property(property: 'logo', type: 'file', format: 'binary'),
                    ]
                )
            ),
            'application/json' => new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    required: ['gasyWalletName'],
                    properties: [
                        'gasyWalletName' => new OA\Property(property: 'gasyWalletName', type: 'string'),
                        'reserve' => new OA\Property(property: 'reserve', type: 'string'),
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
                        'gasyWallet_id' => new OA\Property(property: 'gasyWallet_id', type: 'integer'),
                        'gasyWallet_name' => new OA\Property(property: 'gasyWallet_name', type: 'string'),
                        'gasyWallet_logo' => new OA\Property(property: 'gasyWallet_logo', type: 'string'),
                    ]
                )
            )
        ]
    )]
    #[Route('/newGasyWallet', name: 'app_new_gasywallet', methods: 'POST')]
    public function newGasyWallet(Request $request): JsonResponse
    {
        $gasyWallet = new GasyWallet();
        $gasyWallet->setGasyWalletName($request->request->get("gasyWalletName", null));
        $gasyWallet->setReserve($request->request->get("reserve", 0));

        $file = $request->files->get('logo');
        $path = $this->getParameter('kernel.project_dir') . '/public/image/logo';

        $filename = md5(uniqid()) . '.' . $file->guessClientExtension();
        $file->move($path, $filename);
        $gasyWallet->setLogo($filename);

        $file = $request->files->get('logoMain');
        $path = $this->getParameter('kernel.project_dir') . '/public/image/logo';

        $filename = md5(uniqid()) . '.' . $file->guessClientExtension();
        $file->move($path, $filename);
        $gasyWallet->setLogoMain($filename);

        $this->em->getConnection()->beginTransaction();
        try {

            $this->em->persist($gasyWallet);
            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
            return new JsonResponse([
                'message' => $e->getMessage(),
            ], 500);
        }
        return $this->json([
            'gasyWallet_id' => $gasyWallet->getId(),
            'gasyWallet_name' => $gasyWallet->getGasyWalletName(),
            'gasyWallet_logo_main' => $gasyWallet->getLogoMain(),
            'gasyWallet_logo' => $gasyWallet->getLogo(),
        ]);
    }

    //    
    #[OA\Post(path: '/modifyWallet', description: 'to modify the current', tags: ['Wallet'])]
    #[OA\RequestBody(
        request: 'modify the current Wallet',
        description: 'to modify the current a new Wallet',
        required: false,
        content: [
            'multipart/form-data' => new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    type: 'object',
                    properties: [
                        'logo' => new OA\Property(property: 'logo', type: 'file', format: 'binary'),
                    ]
                )
            ),
            'application/json' => new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    required: ['id'],
                    properties: [
                        'id' => new OA\Property(property: 'id', type: 'integer'),
                        'walletName' => new OA\Property(property: 'walletName', type: 'string'),
                        'reserve' => new OA\Property(property: 'reserve', type: 'string'),
                        'currency' => new OA\Property(property: 'currency', type: 'string'),
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
                        'wallet_id' => new OA\Property(property: 'wallet_id', type: 'integer'),
                        'wallet_name' => new OA\Property(property: 'wallet_name', type: 'string'),
                        'currency' => new OA\Property(property: 'currency', type: 'string'),
                        'wallet_logo' => new OA\Property(property: 'wallet_logo', type: 'string'),
                    ]
                )
            )
        ]
    )]
    #[Route('/modifyWallet', name: 'app_modify_wallet', methods: 'POST')]
    public function modifyWallet(Request $request): JsonResponse
    {
        $wallet = $this->walletRepository->findOneById($request->request->get('id'));
        $wallet->setWalletName($request->request->get("walletName", $wallet->getWalletName()));
        $wallet->setCurrency($request->request->get("currency", $wallet->getCurrency()));

        $file = $request->files->get('logo');
        if ($file) {
            $path = $this->getParameter('kernel.project_dir') . '/public/image/logo';
            $filename = md5(uniqid()) . '.' . $file->guessClientExtension();
            $file->move($path, $filename);
            $wallet->setLogo($filename);
        }

        $this->em->getConnection()->beginTransaction();
        try {

            $this->em->persist($wallet);
            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }
        return $this->json([
            'wallet_id' => $wallet->getId(),
            'wallet_name' => $wallet->getWalletName(),
            'currency' => $wallet->getCurrency(),
            'wallet_logo' => $wallet->getLogo()
        ]);
    }

    #[Route('/modifyMatchWallet', name: 'app_modify_match_wallet', methods: 'POST')]
    public function modifyMatchWallet(Request $request): JsonResponse
    {
        $globalWallet = $this->globalWalletRepository->findOneById($request->request->get('id'));
        $globalWallet->setReserve($request->request->get("reserve", $globalWallet->getReserve()));
        $globalWallet->setLink($request->request->get("link", $globalWallet->getLink()));
        $globalWallet->setFraisDepot($request->request->get("fraisDepot", $globalWallet->getFraisDepot()));
        $globalWallet->setFraisDepotCharged($request->request->get("fraisDepotCharged", $globalWallet->isFraisDepotCharged()));
        //dd($globalWallet);

        $this->em->getConnection()->beginTransaction();
        try {

            $this->em->persist($globalWallet);
            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }
        return $this->json([
            'message' => 'done',
        ]);
    }

    //for cours
    #[Route('/addDepotCours/{id}', name: 'app_add_depot_cours', methods: 'POST')]
    public function addDepotCours(Request $request, $id): JsonResponse
    {
        $wallet = $this->walletRepository->findOneById($id);

        $depotCours = new DepotCours();

        $depotCours->setWallet($wallet);
        $depotCours->setCoursMax($request->request->get('coursMax'));
        $depotCours->setCoursMin($request->request->get('coursMin'));
        $depotCours->setMontantMRMax($request->request->get('MontantMRMax'));

        $this->em->getConnection()->beginTransaction();
        try {

            $this->em->persist($depotCours);
            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }
        return $this->json([
            'wallet_name' => $wallet->getWalletName(),
            'coursMax' => $depotCours->getCoursMax(),
            'coursMin' => $depotCours->getCoursMin(),
            'MontantMRMax' => $depotCours->getMontantMRMax()
        ]);
    }

    #[Route('/addRetraitCours/{id}', name: 'app_add_retrait_cours', methods: 'POST')]
    public function addRetraitCours(Request $request, $id): JsonResponse
    {
        $wallet = $this->walletRepository->findOneById($id);

        $retraitCours = new RetraitCours();

        $retraitCours->setWallet($wallet);
        $retraitCours->setCoursMax($request->request->get('coursMax'));
        $retraitCours->setCoursMin($request->request->get('coursMin'));
        $retraitCours->setMontantMRMax($request->request->get('MontantMRMax'));

        $this->em->getConnection()->beginTransaction();
        try {

            $this->em->persist($retraitCours);
            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }
        return $this->json([
            'wallet_name' => $wallet->getWalletName(),
            'coursMax' => $retraitCours->getCoursMax(),
            'coursMin' => $retraitCours->getCoursMin(),
            'MontantMRMax' => $retraitCours->getMontantMRMax()
        ]);
    }


    /*
    #[OA\Get(path: '/getAllWallet', description: 'get all wallet', tags: ['Wallet'])]
    #[OA\Response(
        response: 200,
        description: 'data received when in success',
        content: [
            'application/json' => new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    type: 'object',
                    properties: [
                        'wallet' => new OA\Property(
                            property: 'wallet',
                            type: 'array',
                            items: new OA\Items(
                                new OA\Schema(
                                    schema: 'h',
                                    type: 'object',
                                    properties: [
                                        'id' => new OA\Property(property: 'id', type: 'integer'),
                                        'walletName' => new OA\Property(property: 'walletName', type: 'string'),
                                        'logo' => new OA\Property(property: 'logo', type: 'string'),
                                        'reserve' => new OA\Property(property: 'reserve', type: 'string'),
                                        'currency' => new OA\Property(property: 'currency', type: 'string'),
                                    ]
                                )
                            ),
                        ),
                    ]
                )
            )
        ]
    )]
    #[Route('/getAllWallet', name: 'app_get_all_wallet', methods: 'GET')]
    public function getAllWallet(): JsonResponse
    {
        $_wallet = $this->walletRepository->findAll();
        $wallet = array();
        foreach ($_wallet as $key => $walletC) {
            $wallet[$key]['id'] = $walletC->getId();
            $wallet[$key]['walletName'] = $walletC->getWalletName();
            $wallet[$key]['logo'] = $walletC->getLogo();
            //            $wallet[$key]['reserve'] = $walletC->getReserve();
            $wallet[$key]['currency'] = $walletC->getCurrency();

            //get the ombony cours
            $wallet[$key]['cours'] = $this->coursDepotRepository->findCoursBywallet($walletC->getId())[0]->getCours();
        }
        return $this->json([
            'wallet' => $wallet
        ]);
    }
*/

    #[Route('/getAllMainWallet', name: 'app_get_all_main_wallet', methods: 'GET')]
    public function getAllMainWallet(): JsonResponse
    {
        $_mainWallets = $this->mainWalletRepository->findAll();
        $mainWallets = array();
        foreach ($_mainWallets as $key => $mainWallet) {
            $mainWallets[$key]['id'] = $mainWallet->getId();
            $mainWallets[$key]['mainWalletName'] = $mainWallet->getMainWalletName();
            $mainWallets[$key]['logo'] = $mainWallet->getLogo();
        }
        return $this->json(
            $mainWallets
        );
    }

    #[Route('/getAllGasyWallet', name: 'app_get_all_gasy_wallet', methods: 'GET')]
    public function getAllGasyWallet(): JsonResponse
    {
        $_gasyWallets = $this->gasyWalletRepository->findAll();
        $gasyWallets = array();
        foreach ($_gasyWallets as $key => $gasyWallet) {
            $gasyWallets[$key]['id'] = $gasyWallet->getId();
            $gasyWallets[$key]['gasyWalletName'] = $gasyWallet->getGasyWalletName();
            $gasyWallets[$key]['reserve'] = $gasyWallet->getReserve();
            $gasyWallets[$key]['logo'] = $gasyWallet->getLogo();
        }
        return $this->json(
            $gasyWallets
        );
    }

    #[Route('/getAllWallet/{id}', name: 'app_get_all_wallet', methods: 'GET')]
    public function getAllWallet($id): JsonResponse
    {
        //$mainWallet = $this->mainWalletRepository->findOneById($id);
        //$globalWallet = $this->globalWalletRepository->findBy(['mainWallet' => $mainWallet]);
        $wallets = $this->globalWalletRepository->findAllWalletByMainWalletId($id);

        //dd($wallets);
        foreach ($wallets as $key => $wallet) {
            //dd($wallet['id']);
            $cours = $this->depotCoursRepository->findCoursByWalletId($wallet['id']);
            //dd($cours);
            if ($cours) {
                $wallets[$key]['coursMax'] = $cours->getCoursMax();
                $wallets[$key]['coursMin'] = $cours->getCoursMin();
                $wallets[$key]['MontantMRMax'] = $cours->getMontantMRMax();
            } else
                $wallets[$key]['cours'] = 'Pas Cours';
        }

        return $this->json(
            $wallets
        );
    }

    //admin
    #[Route('/getAllWalletAndCoursForDepot', name: 'app_get_all_wallet_and_cours_for_depot', methods: 'GET')]
    public function getAllWalletAndCours(): JsonResponse
    {
        //$mainWallet = $this->mainWalletRepository->findOneById($id);
        //$globalWallet = $this->globalWalletRepository->findBy(['mainWallet' => $mainWallet]);
        $_wallets = $this->walletRepository->findAll();
        //dd($_wallets);
        $wallets=array();

        foreach ($_wallets as $key => $wallet) {
            //dd($wallet['id']);
            $cours = $this->depotCoursRepository->findCoursByWalletId($wallet->getId());
            //dd($cours);
            if ($cours) {
                $wallets[$key]['walletName'] = $wallet->getWalletName();
                $wallets[$key]['logo']=$wallet->getLogo();
                $wallets[$key]['id'] = $cours->getId();
                $wallets[$key]['coursMax'] = $cours->getCoursMax();
                $wallets[$key]['coursMin'] = $cours->getCoursMin();
                $wallets[$key]['MontantMRMax'] = $cours->getMontantMRMax();
            } else
                $wallets[$key]['cours'] = 'Pas Cours';
        }

        return $this->json(
            $wallets
        );
    }

    //admin
    #[Route('/getAllWalletAndCoursForRetrait', name: 'app_get_all_wallet_and_cours_for_retrait', methods: 'GET')]
    public function getAllWalletAndCoursForREtrait(): JsonResponse
    {
        //$mainWallet = $this->mainWalletRepository->findOneById($id);
        //$globalWallet = $this->globalWalletRepository->findBy(['mainWallet' => $mainWallet]);
        $_wallets = $this->walletRepository->findAll();
        //dd($_wallets);
        $wallets=array();

        foreach ($_wallets as $key => $wallet) {
            //dd($wallet['id']);
            $cours = $this->retraitCoursRepository->findCoursByWalletId($wallet->getId());
            //dd($cours);
            if ($cours) {
                $wallets[$key]['walletName'] = $wallet->getWalletName();
                $wallets[$key]['logo']=$wallet->getLogo();
                $wallets[$key]['id'] = $cours->getId();
                $wallets[$key]['coursMax'] = $cours->getCoursMax();
                $wallets[$key]['coursMin'] = $cours->getCoursMin();
                $wallets[$key]['MontantMRMax'] = $cours->getMontantMRMax();
            } else
                $wallets[$key]['cours'] = 'Pas Cours';
        }

        return $this->json(
            $wallets
        );
    }

    #[Route('/getAllWalletForRetrait/{id}', name: 'app_get_all_wallet_ForRetrait', methods: 'GET')]
    public function getAllWalletForRetrait($id): JsonResponse
    {
        //$mainWallet = $this->mainWalletRepository->findOneById($id);
        //$globalWallet = $this->globalWalletRepository->findBy(['mainWallet' => $mainWallet]);
        $wallets = $this->globalWalletRepository->findAllWalletByMainWalletId($id);

        foreach ($wallets as $key => $wallet) {
            $cours = $this->retraitCoursRepository->findCoursBywalletId($wallet['id']);

            if ($cours) {
                $wallets[$key]['coursMax'] = $cours->getCoursMax();
                $wallets[$key]['coursMin'] = $cours->getCoursMin();
                $wallets[$key]['MontantMRMax'] = $cours->getMontantMRMax();
            } else
                $wallets[$key]['cours'] = 'Pas Cours';
        }

        return $this->json(
            $wallets
        );
    }

    /* #[OA\Get(path: "/getWallet/{id}", description: 'get single wallet info', tags: ['Wallet'])]
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
                        'walletName' => new OA\Property(property: 'walletName', type: 'string'),
                        'reserve' => new OA\Property(property: 'reserve', type: 'string'),
                        'currency' => new OA\Property(property: 'currency', type: 'string'),
                    ]
                )
            )
        ]
    )]
    #[Route('/gettWallet/{id}', name: 'app_get_wallet', methods: 'GET')]
    public function getWallet(Request $request, $id): JsonResponse
    {
        $wallet = $this->walletRepository->findOneById($id);
        // feild=cours,walletName,logo,reserve,currency,fraisDepotCharged,fraisDepot
        return $this->json([
            'id' => $wallet->getId(),
            'walletName' => $wallet->getWalletName(),
            'reserve' => $wallet->getReserve(),
            'currency' => $wallet->getCurrency(),
            'cours' => $wallet->getCoursDepot()
        ]);
    }*/

    #[Route('/getGlobalWallet/{id}', name: 'app_get_global_wallet', methods: 'GET')]
    public function getGlobalWallet(Request $request, $id): JsonResponse
    {
        $globalWallet = $this->globalWalletRepository->findOneById($id);
        $cours = $this->depotCoursRepository->findCoursByWalletId($globalWallet->getWallet()->getId());
        //dd($cours);

        /*        $cours = $this->coursDepotRepository->findCoursMainByWallet($globalWallet->getWallet()->getId());

       if ($cours) {
            $cours = $cours->getCours();
        } else {
            $cours = 'Pas cours';
        }*/

        // field=cours,walletName,logo,reserve,currency,fraisDepotCharged,fraisDepot
        return $this->json([
            'walletName' => $globalWallet->getWallet()->getWalletName(),
            'wallet_id' => $globalWallet->getWallet()->getId(),
            'reserve' => $globalWallet->getReserve(),
            'currency' => $globalWallet->getWallet()->getCurrency(),
            'logo' => $globalWallet->getWallet()->getLogo(),
            'fraisDepotCharged' => $globalWallet->isFraisDepotCharged(),
            'fraisDepot' => $globalWallet->getFraisDepot(),
            'fraisRetrait' => $globalWallet->getFraisRetrait(),
            'fraisWallet' => $globalWallet->getFraisWallet(),
            'fraisBlockchain' => $globalWallet->getFraisBlockchain(),
            'coursMax' => $cours->getCoursMax(),
            'coursMin' => $cours->getCoursMin(),
            'MontantMRMax' => $cours->getMontantMRMax()
        ]);
    }

    //admin

    #[Route('/getAllGlobalWallet', name: 'app_get_all_global_wallet', methods: 'GET')]
    public function getAllGlobalWallet(): JsonResponse
    {
        $_globalWallet = $this->globalWalletRepository->findAll();

        $globalWallet=array();

        foreach($_globalWallet as $key=>$gWallet){
            $globalWallet[$key]['mainWalletName'] = $gWallet->getMainWallet()->getMainWalletName();
            $globalWallet[$key]['walletName'] = $gWallet->getWallet()->getWalletName();
            $globalWallet[$key]['id'] = $gWallet->getId();
            $globalWallet[$key]['reserve'] = $gWallet->getReserve();
            $globalWallet[$key]['currency'] = $gWallet->getWallet()->getCurrency();
            $globalWallet[$key]['walletLogo'] = $gWallet->getWallet()->getLogo();
            $globalWallet[$key]['mainWalletLogo'] = $gWallet->getMainWallet()->getLogo();
            $globalWallet[$key]['fraisDepotCharged'] = $gWallet->isFraisDepotCharged();
            $globalWallet[$key]['fraisDepot'] = $gWallet->getFraisDepot();
            $globalWallet[$key]['fraisRetrait'] = $gWallet->getFraisRetrait();
            $globalWallet[$key]['fraisWallet'] = $gWallet->getFraisWallet();
            $globalWallet[$key]['fraisBlockchain'] = $gWallet->getFraisBlockchain();
        }
        //dd($cours);

        /*        $cours = $this->coursDepotRepository->findCoursMainByWallet($globalWallet->getWallet()->getId());

       if ($cours) {
            $cours = $cours->getCours();
        } else {
            $cours = 'Pas cours';
        }*/

        // field=cours,walletName,logo,reserve,currency,fraisDepotCharged,fraisDepot
        return $this->json($globalWallet);
    }


    #[Route('/getGlobalWalletForRetrait/{id}', name: 'app_get_global_wallet_for_retrait', methods: 'GET')]
    public function getGlobalWalletForRetrait(Request $request, $id): JsonResponse
    {
        $globalWallet = $this->globalWalletRepository->findOneById($id);
        $cours = $this->retraitCoursRepository->findCoursByWalletId($globalWallet->getWallet()->getId());
        //dd($cours);

        /*        $cours = $this->coursDepotRepository->findCoursMainByWallet($globalWallet->getWallet()->getId());

       if ($cours) {
            $cours = $cours->getCours();
        } else {
            $cours = 'Pas cours';
        }*/

        // field=cours,walletName,logo,reserve,currency,fraisDepotCharged,fraisDepot
        return $this->json([
            'walletName' => $globalWallet->getWallet()->getWalletName(),
            'mainWalletName' => $globalWallet->getMainWallet()->getMainWalletName(),
            'wallet_id' => $globalWallet->getWallet()->getId(),
            'reserve' => $globalWallet->getReserve(),
            'currency' => $globalWallet->getWallet()->getCurrency(),
            'logoMain' => $globalWallet->getMainWallet()->getLogo(),
            'logo' => $globalWallet->getWallet()->getLogo(),
            'fraisDepotCharged' => $globalWallet->isFraisDepotCharged(),
            'fraisDepot' => $globalWallet->getFraisDepot(),
            'fraisRetrait' => $globalWallet->getFraisRetrait(),
            'fraisWallet' => $globalWallet->getFraisWallet(),
            'fraisBlockchain' => $globalWallet->getFraisBlockchain(),
            'coursMax' => $cours->getCoursMax(),
            'coursMin' => $cours->getCoursMin(),
            'MontantMRMax' => $cours->getMontantMRMax()
        ]);
    }

    //admin edit
    #[Route('/editCoursDepot/{id}', name: 'app_edit_cours_depot', methods: ['POST','PUT', 'PATCH'])]
    public function editCoursDepot($id, Request $request): JsonResponse
    {
        $depotCours = $this->depotCoursRepository->findOneById($id);
        if(!$depotCours){
            return $this->json(['error'=>'NO depot cours found for id='.$id],404);
        }
        $depotCours->setCoursMax($request->request->get('coursMax'),$depotCours->getCoursMax());
        $depotCours->setCoursMin($request->request->get('coursMin'),$depotCours->getCoursMin());
        $depotCours->setMontantMRMax($request->request->get('MontantMRMax'),$depotCours->getMontantMRMax());

        $this->em->getConnection()->beginTransaction();
        try {

            $this->em->persist($depotCours);
            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }

        return $this->json(
            ['message'=>'success']
        );
    }


    
    //admin edit
    #[Route('/editCoursRetrait/{id}', name: 'app_edit_cours_retrait', methods: ['POST','PUT', 'PATCH'])]
    public function editCoursRetrait($id, Request $request): JsonResponse
    {
        $retraitCours = $this->retraitCoursRepository->findOneById($id);
        if(!$retraitCours){
            return $this->json(['error'=>'NO retrait cours found for id='.$id],404);
        }
        
        $retraitCours->setCoursMax($request->request->get('coursMax'),$retraitCours->getCoursMax());
        $retraitCours->setCoursMin($request->request->get('coursMin'),$retraitCours->getCoursMin());
        $retraitCours->setMontantMRMax($request->request->get('MontantMRMax'),$retraitCours->getMontantMRMax());

        $this->em->getConnection()->beginTransaction();
        try {

            $this->em->persist($retraitCours);
            $this->em->flush();
            $this->em->commit();
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }

        return $this->json(
            ['message'=>'success']
        );
    }

//admin edit 
#[Route('/editReserve/{id}', name: 'app_edit_reserve', methods: ['POST','PUT', 'PATCH'])]
public function editReserve($id, Request $request): JsonResponse
{
    $globalWallet = $this->globalWalletRepository->findOneById($id);
    if(!$globalWallet){
        return $this->json(['error'=>'NO retrait cours found for id='.$id],404);
    }
//dd();
    $globalWallet->setReserve($request->request->get('reserve'));
    
    $globalWallet->setFraisDepotCharged($request->request->get('fraisDepotCharged'));
    if($request->request->get('fraisDepotCharged')=='false'){
        $globalWallet->setFraisDepotCharged(false);
    }
    $globalWallet->setFraisDepot($request->request->get('fraisDepot'));
    $globalWallet->setFraisRetrait($request->request->get('fraisRetrait'));
    $globalWallet->setFraisWallet(floatval($request->request->get('fraisWallet')));
    $globalWallet->setFraisBlockchain(floatval($request->request->get('fraisBlockchain')));

   // return $this->json('failed',500);

    $this->em->getConnection()->beginTransaction();
    try {

        $this->em->persist($globalWallet);
        $this->em->flush();
        $this->em->commit();
    } catch (\Exception $e) {
        $this->em->rollback();
        throw $e;
    }

    return $this->json(
        ['message'=>'success']
    );
}
    
   /* reserve
    fraisDepotCharged
    fraisDepot
    fraisRetrait
    fraisWallet
    fraisBlockchain*/

    //findAllWalletByMainWalletIdForRetrait
    //for retrait 

    /*  #[Route('/addCours/{id}', name: 'app_add_cours', methods: 'POST')]
    public function addCours(Request $request, $id): JsonResponse
    {
        //id subWallet
        $wallet = $this->walletRepository->findOneById($id);
        $cours = new RetraitCours();
        $cours->setWallet($wallet);
        $cours->setCours($request->request->get('cours'));
        $cours->setPlageA($request->request->get('plageA'));
        $cours->setPlageB($request->request->get('plageB'));

        $this->em->getConnection()->beginTransaction();
        try {

            $this->em->persist($cours);
            $this->em->flush();
            $this->em->commit();
            $message = 'success';
        } catch (\Exception $e) {
            $this->em->rollback();
            $message = 'failed';
            throw $e;
        }

        return $this->json([
            'message' => $message,
            'wallet_id' => $id,
            'wallet_mame' => $wallet->getWalletName(),
            'cours_id' => $cours->getId(),
            'cours' => $cours->getCours(),
            'plageA' => $cours->getPlageA(),
            'plageB' => $cours->getPlageB()
        ]);
    }
*/

    /*
    #[Route('/getRetraitCours/{id}', name: 'app_get_cours_retrait', methods: 'GET')]
    public function getRetraitCours($id): JsonResponse
    {
        $_cours = $this->retraitCoursRepository->findAllCoursBywallet($id);
        
        $cours = array();

        foreach ($_cours as $key => $coursC) {
            $cours[$key]['cours'] = $coursC->getCours();
            $cours[$key]['plageA'] = $coursC->getPlageA();
            $cours[$key]['plageB'] = $coursC->getPlageB();
        }

        return $this->json([
            'message' => 'success',
            'walletName' => $_cours[0]->getWallet()->getWalletName(),
            'cours' => $cours
        ]);

        return $this->json(
            $cours
        );
    }
*/
    /*#[Route('/changeCours/{id}', name: 'app_change_cours', methods: 'POST')]
    public function changeCours(Request $request, $id): JsonResponse
    {
        $cours = $this->coursDepotRepository->findOneById($id);
        $cours->setCours($request->request->get('cours'));
        $cours->setPlageA($request->request->get('plageA'));
        $cours->setPlageB($request->request->get('plageB'));

        $this->em->getConnection()->beginTransaction();
        try {

            $this->em->persist($cours);
            $this->em->flush();
            $this->em->commit();
            $message = 'success';
        } catch (\Exception $e) {
            $this->em->rollback();
            $message = 'failed';
            throw $e;
        }

        return $this->json([
            'message' => $message,
            'wallet_id' => $id,
            'wallet_mame' => $cours->getWallet()->getWalletName(),
            'cours_id' => $cours->getId(),
            'cours' => $cours->getCours(),
            'plageA' => $cours->getPlageA(),
            'plageB' => $cours->getPlageB()
        ]);
    }*/


    //fixer image wallet
    #[Route('/fixMainWallet', name: 'app_fixMain_wallet', methods: 'POST')]
    public function fixMainWallet(Request $request): JsonResponse
    {

        $mainWallet = $this->mainWalletRepository->findOneBy(['mainWalletName' => $request->request->get('mainWalletName')]);


        $filename = $mainWallet->getLogo();

        $file = $request->files->get('logo');

        $path = $this->getParameter('kernel.project_dir') . '/public/image/logo';

        $file->move($path, $filename);

        return $this->json(['done']);
    }

    //fixer image wallet
    #[Route('/fixWallet', name: 'app_fix_wallet', methods: 'POST')]
    public function fixWallet(Request $request): JsonResponse
    {

        $wallet = $this->walletRepository->findOneBy(['walletName' => $request->request->get('walletName')]);

        //for main logo
        $filename = $wallet->getLogoMain();

        $file = $request->files->get('logoMain');

        $path = $this->getParameter('kernel.project_dir') . '/public/image/logo';

        $file->move($path, $filename);

        //for sub logo
        $filename = $wallet->getLogo();

        $file = $request->files->get('logo');

        $path = $this->getParameter('kernel.project_dir') . '/public/image/logo';

        $file->move($path, $filename);

        return $this->json(['done']);
    }

    //fixer image wallet
    #[Route('/fixGasyWallet', name: 'app_fix_gasy_wallet', methods: 'POST')]
    public function fixGasyWallet(Request $request): JsonResponse
    {

        $gasyWallet = $this->gasyWalletRepository->findOneBy(['gasyWalletName' => $request->request->get('gasyWalletName')]);

        //for main logo
        $filename = $gasyWallet->getLogoMain();

        $file = $request->files->get('logoMain');

        $path = $this->getParameter('kernel.project_dir') . '/public/image/logo';

        $file->move($path, $filename);

        //for sub logo
        $filename = $gasyWallet->getLogo();

        $file = $request->files->get('logo');

        $path = $this->getParameter('kernel.project_dir') . '/public/image/logo';

        $file->move($path, $filename);

        return $this->json(['done']);
    }
}
