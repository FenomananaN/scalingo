<?php

namespace App\Service;

class CustomRandom
{
    public function getRandom(string $init, array $array, int $max, int $min)
    {
        $number = 'hgh';

        do {
            $number = $init . rand($min, $max);
            $status = in_array($number, $array, true);
        } while ($status);

        return $number;
    }
}
