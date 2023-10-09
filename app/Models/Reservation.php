<?php

namespace App\Models;

use CodeIgniter\Model;

class Reservation extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'reservation';
    protected $allowedFields = ['id', 'user_id', 'email', 'firstname', 'middlename', 'lastname', 'room_type_id', 'room_type', 'room_no', 'room', 'reserved_date', 'days', 'bill', 'checked_in', 'checked_out', 'status', 'created_at', 'is_deleted'];

    public function checkIfHasBookingAlready($id)
    {
        return $this->where('user_id', $id)
            ->where('is_deleted', 0)
            ->countAllResults() > 0 ? true : false;
    }
}
