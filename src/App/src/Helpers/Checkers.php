<?php

declare(strict_types=1);

namespace App\Helpers;

/**
 * Class Checkers
 * Contain methods for simple checks
 * on array of data
 *
 * @package App\Helpers
 */
class Checkers
{
    /**
     * Check if request contain required fields
     * @param array $requiredParams
     * @param array $dataReq
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
