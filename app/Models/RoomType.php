<?php

namespace App\Models;

use CodeIgniter\Model;

class RoomType extends Model
{
    protected $DBGroup = 'default';
    protected $table = 'room_types';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id', 'name'];

    public function getData()
    {
        return $this->select('id, name')
            ->where('is_deleted', 0)
            ->findAll();
    }
}
