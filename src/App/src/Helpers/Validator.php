<?php

declare(strict_types=1);

namespace App\Helpers;

/**
 * Class Validator.
 * This class contain methods to verify
 * requirements of different incoming request
 * parameters
 *
 * @package Helpers
 */
class Validator
{
    /**
     * check password requirements
     * @param bool
     * @param string $password
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

    /**
     * check login requirements
     * @param bool
     * @param string $login
     */
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

    /**
     * check price requirements
     * @param bool
     * @param string $price
     */
    public function validatePriceRequirement($price): bool
    {
        if (!is_numeric($price) || $price <= 0) {
            return false;
        }
        return true;
    }

    /**
     * check description requirements
     * @param bool
     * @param string $description
     */
    public function validateMaxLengthDesc($description, $maxLength = 300): bool
    {
        if (strlen($description) > $maxLength) {
            return false;
        }
        return true;
    }

    /**
     * check product title requirements
     * @param bool
     * @param string $title
     */
    public function validateMaxLengthTitle($description, $maxLength = 30): bool
    {
        if (strlen($description) > $maxLength) {
            return false;
        }
        return true;
    }
}
