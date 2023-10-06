<?php

namespace App\Controllers;

use App\Models\Room;

class Home extends BaseController
{
    public function index(): string
    {
        $room = new Room();
        $data = ['page_title' => 'Homepage', 'room_data' => $room->getDataForHomepage()];
        return view('home', $data);
    }

    public function accomodation(): string
    {
        $room = new Room();
        $data = ['page_title' => 'Accomodation', 'room_data' => $room->getDataForAccomodation()];
        return view('accomodation', $data);
    }

    public function register(): string
    {
        return view('register');
    }

    public function login(): string
    {
        return view('login');
    }
}
