<?php

namespace App\Service;

use App\Repository\RPManagerRepository;

class RPUtils
{
    
    private $rpManagerRepository;

    public function __construct(RPManagerRepository $rpManagerRepository){
        $this->rpManagerRepository=$rpManagerRepository;
    }

    //Point de recompense Obtenue
    public function RPO(int $volumeTransaction){

        $rpManager=$this->rpManagerRepository->findOneById(1);

        //formula 
        //RPO=VT/RR*PO
        
        return floor($volumeTransaction/$rpManager->getRRAriary()*$rpManager->getPObtenue());
    }
}