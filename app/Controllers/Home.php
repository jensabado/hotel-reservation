<?php

namespace App\Controllers;

use App\Models\Room;
use App\Models\RoomType;

class Home extends BaseController
{
    public function index(): string
    {
        $roomTypeModel = new RoomType();
        $data = ['page_title' => 'Homepage', 'room_data' => $roomTypeModel->getDataForHomepage()];
        return view('home', $data);
    }

    public function accomodation(): string
    {
        $roomTypeModel = new RoomType();
        $data = ['page_title' => 'Accomodation', 'room_data' => $roomTypeModel->getDataForAccomodation()];
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

    public function book_now($id)
    {
        $roomTypeModel = new RoomType();
        $room_data = $roomTypeModel->getDataFromId($id);

        $data = ['page_title' => 'Book Now', 'room_data' => $room_data];
        
        if(!$room_data)
        {
            return redirect()->route('home');
        } else {
            return view('book-now', $data);
        }
    }
}
