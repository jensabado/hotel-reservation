<?php

namespace App\Models;

use CodeIgniter\Model;

class Account extends Model
{
    protected $table = 'accounts';
    protected $allowedFields = ['id', 'username', 'email', 'password', 'type', 'is_deleted'];

    public function username_is_exist($username)
    {
        return $this->where('username', $username)
            ->where('type', 'user')
            ->where('is_deleted', 0)
            ->countAllResults() > 0 ? true : false;
    }

    public function email_is_exist($email)
    {
        return $this->where('email', $email)
            ->where('type', 'user')
            ->where('is_deleted', 0)
            ->countAllResults() > 0 ? true : false;
    }
}
