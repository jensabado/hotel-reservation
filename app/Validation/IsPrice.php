<?php

namespace App\Validation;

class IsPrice
{
    public function is_price($price): bool
    {
        $price = trim($price);

        if(!preg_match('/^\d{1,7}(\.\d{1,2})?$/', $price)) {
            return false;
        }

        return true;
    }
}
