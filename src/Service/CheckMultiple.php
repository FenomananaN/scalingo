<?php

namespace App\Service;

class CheckMultiple
{
    public function checkMultiple(string $tocheck, array $all){
        return in_array($tocheck,$all,true);
    }
}