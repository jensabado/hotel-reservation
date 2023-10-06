<?php

namespace App\Validation;

class IsPasswordStrong
{
    public function strong_password($password): bool
    {
        $password = trim($password);

        if(!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%#*?&])[A-Za-z\d@$!%*?&#]{8,}$/', $password)) {
            return false;
        }

        return true;
    }
}
