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

    public function checkIfRoomTypeExist($name, $id)
    {
        return $this->where('is_deleted', 0)
            ->where('name', $name)
            ->where('id !=', $id)
            ->countAllResults() > 0 ? true : false;
    }

    public function checkIfIdExist($id)
    {
        return $this->where('id', $id)
            ->where('is_deleted', 0)
            ->countAllResults() > 0 ? true : false;
    }

    public function getDataFromId($id)
    {
        return $this->where('id', $id)
            ->where('is_deleted', 0)
            ->get()->getRowArray();
    }

    public function getDataForHomepage()
    {
        return $this->where('is_deleted', 0)
        ->orderBy('price', 'asc')
        ->limit(4)
        ->get()->getResultArray();
    }

    public function getDataForAccomodation()
    {
        return $this->where('is_deleted', 0)
        ->orderBy('price', 'asc')
        ->get()->getResultArray();
    }
}
