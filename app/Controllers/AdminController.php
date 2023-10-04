<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class AdminController extends BaseController
{
    public function index()
    {
        $data = ['page_title' => 'Home'];
        return view('admin/home', $data);
    }
}
