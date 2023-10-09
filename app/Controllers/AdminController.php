<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Room;
use App\Models\RoomType;

class AdminController extends BaseController
{
    protected $db;

    public function __construct()
    {
        // Load the database service
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data = ['page_title' => 'Home'];
        return view('admin/home', $data);
    }

    public function room_types()
    {
        $data = ['page_title' => 'Room Type | ES Admin', 'header' => 'Room Type'];

        return view('admin/room-types/index', $data);
    }

    public function room_type_datatable()
    {
        $request = service('request');
        $draw = intval($request->getPost('draw'));
        $start = intval($request->getPost('start'));
        $length = intval($request->getPost('length'));
        $searchValue = $request->getPost('search')['value'];
        $order = $request->getPost('order')[0];
        $orderColumnIndex = intval($order['column']);
        $columnNames = ['id', 'thumbnail', 'name', 'price'];
        $orderColumnName = $columnNames[$orderColumnIndex] ?? $columnNames[0]; // Use a default column if index is out of bounds
        $orderDir = $order['dir'];

        $builder = $this->db->table('room_types');
        $builder->where('is_deleted', 0);

        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('id', '%' . $searchValue . '%')
                ->orLike('name', '%' . $searchValue . '%')
                ->orLike('price', '%' . $searchValue . '%')
                ->groupEnd();
        }

        $totalRecords = $builder->countAllResults(false);

        $builder->orderBy($orderColumnName, $orderDir);

        if ($length != -1) {
            $builder->limit($length, $start);
        }

        $rows = $builder->get()->getResultArray();

        $data = [];

        $count = ($start / $length) * $length + 1;

        foreach ($rows as $row) {
            $subArray = [];
            $subArray[] = $count++;
            $subArray[] = $row['thumbnail'] ? '<img style="width: 80px; height: 80px; object-fit: cover; border-radius: 0;" src="' . base_url('room-type-image/' . $row['thumbnail']) . '" alt="">' : '<img style="width: 80px; height: 80px; object-fit: cover; border-radius: 0;" src="' . base_url('room-type-image/No-Image-Placeholder.svg.png') . '" alt="">';
            $subArray[] = ucwords($row['name']);
            $subArray[] = $row['price'];
            $subArray[] = '<div class="btn-group" role="group">
            <a href="room-types/edit/' . $row['id'] . '" class="btn btn-success py-2 px-3">EDIT</a>
            <button class="btn btn-danger py-2 px-3" id="get_delete" data-id="' . $row['id'] . '">DELETE</button>
          </div>';
            $data[] = $subArray;
        }

        $output = [
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data,
        ];

        return $this->response->setJSON($output);

    }

    public function room()
    {
        $data = ['page_title' => 'Room | ES Admin', 'header' => 'Room'];

        return view('admin/room/index', $data);
    }

    public function room_datatable()
    {
        $request = service('request');
        $draw = intval($request->getPost('draw'));
        $start = intval($request->getPost('start'));
        $length = intval($request->getPost('length'));
        $searchValue = $request->getPost('search')['value'];
        $order = $request->getPost('order')[0];
        $orderColumnIndex = intval($order['column']);
        $columnNames = ['rooms.id', 'room_types.name', 'rooms.room_no', 'rooms.price'];
        $orderColumnName = $columnNames[$orderColumnIndex] ?? $columnNames[0]; // Use a default column if index is out of bounds
        $orderDir = $order['dir'];

        $builder = $this->db->table('rooms');
        $builder->select('rooms.id, room_types.name, rooms.room_no');
        $builder->join('room_types', 'rooms.room_type = room_types.id', 'left');
        $builder->where('rooms.is_deleted', 0);

        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('rooms.room_no', '%' . $searchValue . '%')
                ->orLike('room_types.name', '%' . $searchValue . '%')
                ->groupEnd();
        }

        $totalRecords = $builder->countAllResults(false);

        $builder->orderBy($orderColumnName, $orderDir);

        if ($length != -1) {
            $builder->limit($length, $start);
        }

        $rows = $builder->get()->getResultArray();

        $data = [];

        $count = ($start / $length) * $length + 1;

        foreach ($rows as $row) {
            $subArray = [];
            $subArray[] = $count++;
            $subArray[] = ucwords($row['name']);
            $subArray[] = ucwords($row['room_no']);
            $subArray[] = '<div class="btn-group" role="group">
            <a href="room/edit/' . $row['id'] . '" class="btn btn-success py-2 px-3">EDIT</a>
            <button class="btn btn-danger py-2 px-3" id="get_delete" data-id="' . $row['id'] . '">DELETE</button>
          </div>';
            $data[] = $subArray;
        }

        $output = [
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data,
        ];

        return $this->response->setJSON($output);

    }

    public function add_room_type()
    {
        $data = ['page_title' => 'Add Room Type', 'header' => 'Add Room Type'];

        return view('admin/room-types/add', $data);
    }

    public function add_room_type_submit()
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'add_name' => 'required',
            'add_price' => 'required|is_price[add_price]',
            'add_image' => 'uploaded[add_image]|is_image[add_image]|max_size[add_image,10240]',
        ], [
            'add_name' => [
                'required' => 'Room Type Name is required',
            ],
            'add_price' => [
                'required' => 'Price is required',
                'is_price' => 'Invalid price input.',
            ],
            'add_image' => [
                'uploaded' => 'Room type image is required.',
                'is_image' => 'File type is not supported.',
                'max_size' => 'File size is too large.',
            ],
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $result = ['status' => 'error', 'message' => $validation->getErrors()];
        } else {
            $roomTypeModel = new RoomType();
            if ($roomTypeModel->checkIfRoomTypeExist($this->request->getPost('add_name'), 0)) {
                $result = ['status' => 'error', 'message' => ['add_name' => 'Room Type already exist.']];
            } else {
                $uploaded_file = $this->request->getFile('add_image');
                $new_name = $uploaded_file->getRandomName();
                $uploaded_file->move(ROOTPATH . 'public/room-type-image', $new_name);

                $data = [
                    'name' => $this->request->getPost('add_name'),
                    'price' => $this->request->getPost('add_price'),
                    'thumbnail' => $new_name,
                ];

                $insert = $this->db->table('room_types')
                    ->insert($data);

                if ($insert) {
                    $result = ['status' => 'success', 'message' => 'Room Type added successfully'];
                }
            }
        }

        return $this->response->setJSON($result);
    }

    public function edit_room_type($id)
    {
        $roomTypeModel = new RoomType();

        if (!$roomTypeModel->checkIfIdExist($id)) {
            return redirect()->route('admin.room_types');
        } else {
            $data = ['page_title' => 'Edit Room Type', 'header' => 'Edit Room', 'get_data' => $roomTypeModel->getDataFromId($id), 'id' => $id];

            return view('admin/room-types/edit', $data);
        }
    }

    public function edit_room_type_submit()
    {
        $validation = \Config\Services::validation();

        $validation->setRules([
            'edit_name' => 'required',
            'edit_price' => 'required|is_price[edit_price]',
            'edit_image' => 'is_image[edit_image]|max_size[edit_image,10240]',
        ], [
            'edit_name' => [
                'required' => 'Room Type Name is required.',
            ],
            'edit_price' => [
                'required' => 'Price is required.',
                'is_price' => 'Invalid price input.',
            ],
            'edit_image' => [
                'is_image' => 'File type is not supported.',
                'max_size' => 'File size is too large.',
            ],
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $result = ['status' => 'error', 'message' => $validation->getErrors()];
        } else {
            $roomTypeModel = new RoomType();

            if ($roomTypeModel->checkIfRoomTypeExist($this->request->getPost('edit_name'), $this->request->getPost('id'))) {
                $result = ['status' => 'error', 'message' => ['edit_name' => 'Room Type already exist.']];
            } else {
                $data = [
                    'name' => $this->request->getPost('edit_name'),
                    'price' => $this->request->getPost('edit_price'),
                ];

                if ($this->request->getFile('edit_image')->isValid()) {
                    $get_data = $roomTypeModel->getDataFromId($this->request->getPost('id'));

                    if (!empty($get_data['thumbnail'])) {
                        $file_path = ROOTPATH . 'public/room-type-image/' . $get_data['thumbnail'];

                        if (file_exists($file_path)) {
                            unlink($file_path);
                        }
                    }

                    $uploaded_file = $this->request->getFile('edit_image');
                    $new_name = $uploaded_file->getRandomName();
                    $uploaded_file->move(ROOTPATH . 'public/room-type-image', $new_name);
                    $data['thumbnail'] = $new_name;
                }

                $update = $this->db->table('room_types')
                    ->where('id', $this->request->getPost('id'))
                    ->update($data);

                if ($update) {
                    $result = ['status' => 'success', 'message' => 'Room Type updated successfully.'];
                }
            }
        }

        return $this->response->setJSON($result);
    }

    public function delete_room_type_submit()
    {
        $roomTypeModel = new RoomType();

        if (!$roomTypeModel->checkIfIdExist($this->request->getPost('id'))) {
            $result = ['status' => 'error', 'message' => 'Room Type not found.'];
        } else {
            $update = $this->db->table('room_types')
                ->set('is_deleted', 1)
                ->where('id', $this->request->getPost('id'))
                ->update();

            if ($update) {
                $result = ['status' => 'success', 'message' => 'Room Type deleted successfully.'];
            }
        }

        return $this->response->setJSON($result);
    }

    public function add_room()
    {
        $roomTypeModel = new RoomType();
        $data = ['page_title' => 'Add Room', 'header' => 'Add Room', 'get_data' => $roomTypeModel->getData()];

        return view('admin/room/add', $data);
    }

    public function add_room_submit()
    {
        if (!$this->request->isAJAX()) {
            return;
        }

        $validation = \Config\Services::validation();

        $validation->setRules([
            'add_room_type' => 'required',
            'add_room_no' => 'required',
            'add_room_price' => 'required|is_price[add_room_price]',
        ], [
            'add_room_type' => [
                'required' => 'Room Type is required.',
            ],
            'add_room_no' => [
                'required' => 'Room Name/Number is required',
            ],
            'add_room_price' => [
                'required' => 'Price is required.',
                'is_price' => 'Invalid input price.',
            ],
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $result = ['status' => 'error', 'message' => $validation->getErrors()];
        } else {
            $roomModel = new Room();
            $if_exist = $roomModel->ifExist($this->request->getPost('add_room_type'), $this->request->getPost('add_room_no'));

            if ($if_exist) {
                $result = ['status' => 'error', 'message' => ['add_room_type' => 'Room already exist in record.']];
            } else {
                $data = [
                    'room_type' => $this->request->getPost('add_room_type'),
                    'room_no' => $this->request->getPost('add_room_no'),
                    'price' => $this->request->getPost('add_room_price'),
                ];

                $roomModel->insert($data);

                if ($roomModel) {
                    $result = ['status' => 'success', 'message' => 'Room added successfully.'];
                } else {
                    $result = ['status' => 'error', 'message' => ['add_room_type' => 'Something went wrong.']];
                }
            }
        }

        return $this->response->setJSON($result);
    }

    public function edit_room($id)
    {
        $roomModel = new Room();
        $roomTypeModel = new RoomType();

        if ($roomModel->ifIdIsExist($id)) {
            $data = ['page_title' => 'Edit Room', 'header' => 'Edit Room', 'get_data' => $roomTypeModel->getData(), 'room_data' => $roomModel->getData($id), 'id' => $id];

            return view('admin/room/edit', $data);
        } else {
            return redirect()->route('admin.room');
        }
    }

    public function edit_room_submit()
    {
        if (!$this->request->isAJAX()) {
            return;
        }

        $validation = \Config\Services::validation();
        $validationRules = [
            'edit_room_type' => 'required',
            'edit_room_no' => 'required',
            'edit_room_price' => 'required|is_price[edit_room_price]',
        ];
        $validationMessages = [
            'edit_room_type' => [
                'required' => 'Room Type is required.',
            ],
            'edit_room_no' => [
                'required' => 'Room Name/Number is required',
            ],
            'edit_room_price' => [
                'required' => 'Price is required.',
                'is_price' => 'Invalid input price.',
            ],
        ];

        if (!$validation->setRules($validationRules, $validationMessages)->withRequest($this->request)->run()) {
            $result = ['status' => 'error', 'message' => $validation->getErrors()];
        } else {
            $roomModel = new Room();
            $roomData = [
                'room_type' => $this->request->getPost('edit_room_type'),
                'room_no' => $this->request->getPost('edit_room_no'),
                'price' => $this->request->getPost('edit_room_price'),
            ];

            $builder = $this->db->table('rooms')->where('id', $this->request->getPost('id'))->update($roomData);

            if ($builder) {
                $result = ['status' => 'success', 'message' => 'Room updated successfully.'];
            } else {
                $result = ['status' => 'error', 'message' => ['edit_room_type' => 'Something went wrong.']];
            }
        }

        return $this->response->setJSON($result);
    }

    public function delete_room_submit()
    {
        if (!$this->request->isAJAX()) {
            return;
        }

        $id = $this->request->getPost('id');

        $room = new Room();

        if ($room->ifIdIsExist($id)) {
            $room_builder = $this->db->table('rooms');

            $update = $room_builder->where('id', $id)
                ->update([
                    'is_deleted' => 1,
                ]);

            if ($update) {
                $result = ['status' => 'success', 'message' => 'Room deleted successfully'];
            }
        } else {
            $result = ['status' => 'error', 'message' => 'Room not found.'];
        }

        return $this->response->setJSON($result);
    }

    public function pending_reservation()
    {
        $data = ['page_title' => 'Pending Reservation | ES Admin', 'header' => 'Pending Reservation'];

        return view('admin/reservation/pending', $data);
    }

    public function pending_reservation_datatable()
    {
        $request = service('request');
        $draw = intval($request->getPost('draw'));
        $start = intval($request->getPost('start'));
        $length = intval($request->getPost('length'));
        $searchValue = $request->getPost('search')['value'];
        $order = $request->getPost('order')[0];
        $orderColumnIndex = intval($order['column']);
        $columnNames = ['id', 'firstname', 'email', 'contact', 'room_type', 'created_at', 'reserved_date', 'bill'];
        $orderColumnName = $columnNames[$orderColumnIndex] ?? $columnNames[0]; // Use a default column if index is out of bounds
        $orderDir = $order['dir'];

        $builder = $this->db->table('reservation')
            ->select('id, firstname, middlename, lastname, contact, email, room_type, created_at, reserved_date, bill')
            ->where('is_deleted', 0)
            ->where('status', 'pending');

        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('firstname', '%' . $searchValue . '%')
                ->orLike('middlename', '%' . $searchValue . '%')
                ->orLike('lastname', '%' . $searchValue . '%')
                ->orLike('email', '%' . $searchValue . '%')
                ->orLike('contact', '%' . $searchValue . '%')
                ->orLike('room_type', '%' . $searchValue . '%')
                ->orLike('created_at', '%' . $searchValue . '%')
                ->orLike('reserved_date', '%' . $searchValue . '%')
                ->orLike('bill', '%' . $searchValue . '%')
                ->groupEnd();
        }

        $totalRecords = $builder->countAllResults(false);

        $builder->orderBy($orderColumnName, $orderDir);

        if ($length != -1) {
            $builder->limit($length, $start);
        }

        $rows = $builder->get()->getResultArray();

        $data = [];

        $count = ($start / $length) * $length + 1;

        foreach ($rows as $row) {
            $subArray = [];
            $subArray[] = $count++;
            $subArray[] = ucwords($row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname']);
            $subArray[] = $row['email'];
            $subArray[] = $row['contact'];
            $subArray[] = $row['room_type'];
            $subArray[] = $row['created_at'];
            $subArray[] = $row['reserved_date'];
            $subArray[] = $row['bill'];
            $subArray[] = '<div class="btn-group" role="group">
            <a href="room/edit/' . $row['id'] . '" class="btn btn-success py-2 px-3">EDIT</a>
            <button class="btn btn-danger py-2 px-3" id="get_delete" data-id="' . $row['id'] . '">DELETE</button>
          </div>';
            $data[] = $subArray;
        }

        $output = [
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $totalRecords,
            'data' => $data,
        ];

        return $this->response->setJSON($output);
    }
}
