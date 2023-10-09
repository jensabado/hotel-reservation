<?php

namespace App\Validation;

class ReservedDateValidation
{
    public function valid_reserved_date($date): bool
    {
        $selected_date = strtotime($date);
        $date_tomorrow = strtotime('+1 day', strtotime('Y-m-d'));

        return $selected_date >= $date_tomorrow;
    }
}
