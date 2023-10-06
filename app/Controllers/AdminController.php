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
        $columnNames = ['rooms.id', 'room_types.name', 'rooms.room_no', 'rooms.photo', 'rooms.price'];
        $orderColumnName = $columnNames[$orderColumnIndex] ?? $columnNames[0]; // Use a default column if index is out of bounds
        $orderDir = $order['dir'];

        $builder = $this->db->table('rooms');
        $builder->select('rooms.id, room_types.name, rooms.room_no, rooms.photo, rooms.price');
        $builder->join('room_types', 'rooms.room_type = room_types.id', 'left');
        $builder->where('rooms.is_deleted', 0);

        if (!empty($searchValue)) {
            $builder->groupStart()
                ->like('r1.id', '%' . $searchValue . '%')
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

        foreach ($rows as $row) {
            $subArray = [];
            $subArray[] = '#' . $row['id'];
            $subArray[] = ucwords($row['name']);
            $subArray[] = ucwords($row['room_no']);
            $subArray[] = $row['photo'] ? '<img style="width: 80px; height: 80px; object-fit: cover; border-radius: 0;" src="' . base_url('room-image/' . $row['photo']) . '" alt="">' : '<img style="width: 80px; height: 80px; object-fit: cover; border-radius: 0;" src="' . base_url('room-image/No-Image-Placeholder.svg.png') . '" alt="">';
            $subArray[] = ucwords($row['price']);
            $subArray[] = '<div class="btn-group" role="group">
            <a href="room/edit/' . $row['id'] . '" class="btn btn-success py-2 px-3">EDIT</a>
            <button class="btn btn-danger py-2 px-3" id="get_delete" data-id="'.$row['id'].'">DELETE</button>
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
            'add_room_image' => 'uploaded[add_room_image]|is_image[add_room_image]|max_size[add_room_image,10240]',
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
            'add_room_image' => [
                'uploaded' => 'Room image is required.',
                'is_image' => 'File type is not supported.',
                'max_size' => 'File size is too large.',
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
                $uploaded_file = $this->request->getFile('add_room_image');
                $new_name = $uploaded_file->getRandomName();
                $uploaded_file->move(ROOTPATH . 'public/room-image', $new_name);

                $data = [
                    'room_type' => $this->request->getPost('add_room_type'),
                    'room_no' => $this->request->getPost('add_room_no'),
                    'price' => $this->request->getPost('add_room_price'),
                    'photo' => $new_name,
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
            'edit_room_image' => 'is_image[edit_room_image]|max_size[edit_room_image,10240]',
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
            'edit_room_image' => [
                'is_image' => 'File type is not supported.',
                'max_size' => 'File size is too large.',
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

            if ($this->request->getFile('edit_room_image')->isValid()) {
                $get_data = $roomModel->getData($this->request->getPost('id'));

                if (!empty($get_data['photo'])) {
                    $file_path = ROOTPATH . 'public/room-image/' . $get_data['photo'];

                    if (file_exists($file_path)) {
                        unlink($file_path);
                    }
                }

                $uploaded_file = $this->request->getFile('edit_room_image');
                $new_name = $uploaded_file->getRandomName();
                $uploaded_file->move(ROOTPATH . 'public/room-image', $new_name);
                $roomData['photo'] = $new_name;
            }

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
        if(!$this->request->isAJAX()) {
            return;
        }

        $id = $this->request->getPost('id');

        $room = new Room();

        if($room->ifIdIsExist($id)) {
            $room_builder = $this->db->table('rooms');

            $update = $room_builder->where('id', $id)
            ->update([
                'is_deleted' => 1
            ]);

            if($update) {
                $result = ['status' => 'success', 'message' => 'Room deleted successfully'];
            }
        } else {
            $result = ['status' => 'error', 'message' => 'Room not found.'];
        }

        return $this->response->setJSON($result);
    }

}
