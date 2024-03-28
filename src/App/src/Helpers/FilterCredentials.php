<?php

declare(strict_types=1);

namespace App\Helpers;

/**
 * Class FilterCredentials.
 * Предоставляет методы для ...
 *
 * @package Helpers
 */
class FilterCredentials
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
    public function checkPassword($password): bool
    {
        // Password should be at least 8 characters long,
        //contain at least one uppercase letter, 
        //one lowercase letter, one digit, and one special character
        $pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W\_])[A-Za-z\d\W\_]{8,}$/';
    
        if (preg_match($pattern, $password)) {
            // Password format is correct
            return true;
        }
        return false;
    }

    public function checkLogin($login): bool
    {
         // Login should contain only alphanumeric characters and underscores,
         //and should be between 3 and 16 characters long
        $pattern = '/^[a-zA-Z0-9_]{3,16}$/';
    
        if (preg_match($pattern, $login)) {
            return true;
        } 
        return false;
    }

    public function validatePriceRequirement($price): bool
    {
        if (!is_numeric($price) || $price <= 0) {
            return false;
        }
        return true;
    }

    public function validateMaxLength($param, $maxLength = 300): bool
    {
        if (strlen($param) > $maxLength) {
            return false;
        }
        return true;
    }
}
