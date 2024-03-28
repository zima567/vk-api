<?php

declare(strict_types=1);

namespace App\Helpers;

/**
 * Class FilterCredentials.
 * Предоставляет методы для ...
 *
 * @package App\Helpers
 */
class Checkers
{
    /**
     * constructor.
     */
    /*public function __construct()
    {}*/

    /**
     * TTTTTTTTTT
     * @param int
     * @param array
     */
    public function checkRequiredParams($requiredParams, $dataReq): bool
    {
        //get array keys
        $keys = array_keys($dataReq);
        $requiredParamsLength = count($requiredParams);
        $counter = 0;
        for ($i=0; $i < count($keys); $i++) { 
            for ($j=0; $j < $requiredParamsLength; $j++) { 
                if($requiredParams[$j] === $keys[$i]) {
                    $counter++;
                    break;
                }
            }
        }
        if($requiredParamsLength === $counter) {
            return true;
        }
        return false;
    }
}
