<?php

namespace App\Models;

use CodeIgniter\Model;

class Room extends Model
{
    protected $table = 'rooms';
    protected $allowedFields = ['id', 'room_type', 'room_no', 'price', 'photo'];

    public function ifExist($room_type, $room_no)
    {
        return $this->where('room_type', $room_type)
            ->where('room_no', $room_no)
            ->where('is_deleted', 0)
            ->countAllResults() > 0 ? true : false;
    }

    public function ifIdIsExist($id)
    {
        return $this->where('id', $id)
            ->where('is_deleted', 0)
            ->countAllResults() > 0 ? true : false;
    }

    public function getData($id)
    {
        return $this->where('id', $id)
            ->first();
    }

    public function ifRoomAlreadyExist($id, $room_type, $room_no)
    {
        return $this->where('room_type', $room_type)
            ->where('room_no', $room_no)
            ->where('id !=', $id)
            ->countAllResults() > 0 ? true : false;
    }

    public function getDataForHomepage()
    {
        $query = $this->select('rooms.id, room_types.name, rooms.room_no, rooms.photo, rooms.price')
            ->join('room_types', 'rooms.room_type = room_types.id', 'left')
            ->where('rooms.is_deleted', 0)
            ->groupBy('rooms.room_type')
            ->orderBy('rooms.price', 'asc')
            ->limit(4);

        $results = $query->get()->getResult();

        return $results;

    }

    public function getDataForAccomodation()
    {
        $query = $this->select('rooms.id, room_types.name, rooms.room_no, rooms.photo, rooms.price')
            ->join('room_types', 'rooms.room_type = room_types.id', 'left')
            ->where('rooms.is_deleted', 0)
            ->groupBy('rooms.room_type')
            ->orderBy('rooms.price', 'asc');

        $results = $query->get()->getResult();

        return $results;
    }
}
