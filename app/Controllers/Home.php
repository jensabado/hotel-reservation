<?php

namespace App\Controllers;

use App\Libraries\CIAuth;
use App\Models\Reservation;
use App\Models\RoomType;

class Home extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

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

        if (!$room_data) {
            return redirect()->route('home');
        } else {
            return view('book-now', $data);
        }
    }

    public function book_submit()
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'firstname' => 'required',
            'lastname' => 'required',
            'address' => 'required',
            'contact' => 'required|is_contact_num[contact]',
            'reserved_date' => 'required|valid_reserved_date[reserved_date]',
        ], [
            'firstname' => [
                'required' => 'First Name is required.',
            ],
            'lastname' => [
                'required' => 'Last Name is required.',
            ],
            'address' => [
                'required' => 'Address is required.',
            ],
            'contact' => [
                'required' => 'Contact number is required.',
                'is_contact_num' => 'Invalid contact number format',
            ],
            'reserved_date' => [
                'required' => 'Reserved date is required.',
                'valid_reserved_date' => 'Invalid selected date.',
            ],
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $result = ['status' => 'error', 'message' => $validation->getErrors()];
        } else {
            $user_data = CIAuth::user();
            $reservation = new Reservation();
            $room_type = new RoomType();
            $room_type_data = $room_type->getDataFromId($this->request->getPost('id'));

            if ($reservation->checkIfHasBookingAlready($user_data->id)) {
                $result = ['status' => 'error_alert', 'message' => 'You already have a pending booking.'];
            } else {
                $data = [
                    'user_id' => $user_data->id,
                    'email' => $user_data->email,
                    'firstname' => $this->request->getPost('firstname'),
                    'middlename' => $this->request->getPost('middlename'),
                    'lastname' => $this->request->getPost('lastname'),
                    'contact' => $this->request->getPost('contact'),
                    'address' => $this->request->getPost('address'),
                    'room_type_id' => $this->request->getPost('id'),
                    'room_type' => $room_type_data['name'],
                    'reserved_date' => $this->request->getPost('reserved_date'),
                    'bill' => $room_type_data['price'],
                ];

                $insert = $this->db->table('reservation')
                    ->insert($data);
                
                    if($insert) {
                        helper('CIMail_helper');
                        $mail_data = array(
                            'user' => $user_data
                        );

                        $view = \Config\Services::renderer();
                        $mail_body = $view->setVar('mail_data', $mail_data)->render('email-templates/reservation-email-template');

                        $mail_config = array(
                            'mail_from_email' => 'untamedandromeda@gmail.com',
                            'mail_from_name' => 'EasyStay Reservation',
                            'mail_recipient_email' => $user_data->email,
                            'mail_recipient_name' => $this->request->getPost('firstname') . ' ' . $this->request->getPost('lastname'),
                            'mail_subject' => 'Reservation Verification',
                            'mail_body' => $mail_body,
                        );

                        if(send_email($mail_config)) {
                            $result = ['status' => 'success', 'message' => 'Please visit our EasyStay Hotel for reservation verification. Thank you!'];
                        }
                    }
            }
        }

        return $this->response->setJSON($result);
    }
}
