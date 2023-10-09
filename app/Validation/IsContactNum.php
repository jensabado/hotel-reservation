<?php

namespace App\Validation;

class IsContactNum
{
    public function is_contact_num($contact): bool
    {
        $contact = trim($contact);

        if (!preg_match('/^09\d{9}$/', $contact)) {
            return false;
        }

        return true;
    }
}
