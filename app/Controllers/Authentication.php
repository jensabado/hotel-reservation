<?php

namespace App\Controllers;

use App\Models\Account;
use App\Libraries\Hash;
use App\Libraries\CIAuth;

class Authentication extends BaseController
{
    public function index(): string
    {
        $data = ['page_title' => 'Login'];
        return view('login', $data);
    }

    public function register()
    {
        $data = ['page_title' => 'Register'];
        return view('register', $data);
    }

    public function register_submit()
    {
        if ($this->request->isAJAX()) {
            $validation = \Config\Services::validation();

            $rules = [
                'username' => 'required',
                'email' => 'required|valid_email',
                'password' => 'required|min_length[8]',
            ];

            // Set custom error messages if needed
            $validation->setRules($rules, [
                'username' => [
                    'required' => 'Username is required.',
                ],
                'email' => [
                    'required' => 'Email is required.',
                    'valid_email' => 'Please enter a valid email address.',
                ],
                'password' => [
                    'required' => 'Password is required.',
                    'min_length' => 'Password must be at least 8 characters long.',
                ],
            ]);

            if ($validation->withRequest($this->request)->run()) {
                $username = $this->request->getPost('username');
                $email = $this->request->getPost('email');
                $password = $this->request->getPost('password');

                $account = new Account();
                $user_exist = $account->username_is_exist($username);
                $email_exist = $account->email_is_exist($email);

                if (!$email_exist && !$user_exist) {
                    $type = 'user';

                    $data = [
                        'username' => $username,
                        'email' => $email,
                        'password' => Hash::hash($password),
                        'type' => $type,
                    ];

                    $account->insert($data);

                    $result = ['status' => 'success', 'message' => 'Account created successfully. You can now login.'];
                } else {
                    $errors = [];

                    if ($user_exist) {
                        $errors['username'] = 'Username already exists.';
                    }

                    if ($email_exist) {
                        $errors['email'] = 'Email already exists.';
                    }

                    $result = ['status' => 'error', 'message' => $errors];
                }
            } else {
                $errors = $validation->getErrors();
                $result = ['status' => 'error', 'message' => $errors];
            }

            return $this->response->setJSON($result);
        }
    }

    public function login_submit()
    {
        if ($this->request->isAJAX()) {
            $validation = \Config\Services::validation();

            $rules = [
                'login_id' => 'required',
                'password' => 'required|min_length[8]',
            ];

            $validation->setRules($rules, [
                'login_id' => [
                    'required' => 'Username or Email is required.',
                ],
                'password' => [
                    'required' => 'Password is required.',
                    'min_length' => 'Password must be at least 8 characters long.',
                ],
            ]);

            if ($validation->withRequest($this->request)->run()) {
                $login_id = $this->request->getPost('login_id');
                $password = $this->request->getPost('password');
                $account = new Account();

                if (filter_var($login_id, FILTER_VALIDATE_EMAIL)) {
                    $field = 'email';
                } else {
                    $field = 'username';
                }

                $user = $account->where($field, $login_id)
                    ->where('type', 'user')
                    ->where('is_deleted', 0)
                    ->first();

                if (is_array($user) && Hash::check($password, $user['password'])) {

                    CIAuth::setCIAuth($user);
                    $result = ['status' => 'success'];
                } else {
                    $errors['login_id'] = 'Invalid credentials.';
                    $result = ['status' => 'error', 'message' => $errors];
                }
            } else {
                $errors = $validation->getErrors();
                $result = ['status' => 'error', 'message' => $errors];
            }

            return $this->response->setJSON($result);
        }
    }

    public function logout()
    {
        CIAuth::forget();
        return redirect()->route('home');
    }
}
