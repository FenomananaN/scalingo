<?php

namespace App\Service;

class RandomMVXId
{
    private $customRandom;

    public function __construct(CustomRandom $customRandom)
    {
        $this->customRandom = $customRandom;
    }

    public function getNewMVXId(array $parrainageId)
    {
        return $this->customRandom->getRandom('MVX', $parrainageId, 99999, 10000);
    }
    public function getNewTXMVXId(array $refManavola)
    {
        return $this->customRandom->getRandom('TXDMVX', $refManavola, 99999999, 10000000);
    }
    public function getNewTXRMVXId(array $refManavola)
    {
        return $this->customRandom->getRandom('TXRMVX', $refManavola, 99999999, 10000000);
    }
}
