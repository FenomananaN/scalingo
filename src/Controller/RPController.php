<?php

namespace App\Controller;

use App\Entity\CashOutRP;
use App\Entity\RPManager;
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
    private $rPUtils;

    public function __construct(EntityManagerInterface $em, UserRepository $userRepository, RPManagerRepository $rpManagerRepository, RPUtils $rPUtils)
    {
        $this->em = $em;
        $this->userRepository = $userRepository;
        $this->rpManagerRepository=$rpManagerRepository;
        $this->rPUtils=$rPUtils;

    }
    //admin 
    #[Route('/newrpmanager', name: 'app_new_rpmanager', methods: 'POST')]
    public function newrpmanager(): JsonResponse
    {
        $rpManager = new RPManager();

        $rpManager->setRPInitial(1000);
        $rpManager->setRRAriary(25000);
        $rpManager->setPObtenu(100);

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
            'PObtenu' => $rpManager->getPObtenu(),
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
        $rpManager->setPObtenu($request->request->get('PObtenu',$rpManager->getPObtenu()));
        $rpManager->setRPRAte($request->request->get('RPRAte',$rpManager->getRPRAte()));


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

    #[Route('/rpcashout', name: 'app_r_p')]
    public function index(Request $request): JsonResponse
    {
        $cashOUtRP= new CashOutRP();

        $user=$this->getUser();
        $cashOUtRP->setUser($user);

        //new CurrentRP for User
        $user->setCurrentRP($user->getCurrentRP()-$request->request->get('RP'));

        $cashOUtRP->setRP($request->request->get('RP'));
        $cashOUtRP->setRPRate($request->request->get('RPRate'));
        $cashOUtRP->setMGAValue($request->request->get('MGAValue'));
        
        $cashOUtRP->setPhoneNumber($request->request->get('phoneNumber'));
        $cashOUtRP->setDate(new \DateTime());
        
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
}
