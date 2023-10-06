<?php

namespace App\Controllers;

use App\Libraries\CIAuth;
use App\Libraries\Hash;
use App\Models\Account;
use App\Models\PasswordResetToken;
use Carbon\Carbon;

class Authentication extends BaseController
{

    public function validatePassword(string $password): bool
    {
        $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&#]{8,}$/';
        return (bool) preg_match($pattern, $password);
    }

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
        if (!$this->request->isAJAX()) {
            return;
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'username' => 'required',
            'email' => 'required|valid_email',
            'password' => 'required|min_length[8]|is_password_strong[password]',
        ], [
            'username' => ['required' => 'Username is required.'],
            'email' => [
                'required' => 'Email is required.',
                'valid_email' => 'Please enter a valid email address.',
            ],
            'password' => [
                'required' => 'Password is required.',
                'min_length' => 'Password must be at least 8 characters long.',
                'is_password_strong' => 'Password must contains atleast 1 uppercase, 1 lowercase, 1 number and 1 special character',
            ],
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $result = ['status' => 'error', 'message' => $validation->getErrors()];
        } else {
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
        }

        return $this->response->setJSON($result);
    }

    public function login_submit()
    {
        if (!$this->request->isAJAX()) {
            return;
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'login_id' => 'required',
            'password' => 'required|min_length[8]',
        ], [
            'login_id' => [
                'required' => 'Username or Email is required.',
            ],
            'password' => [
                'required' => 'Password is required.',
                'min_length' => 'Password must be at least 8 characters long.',
            ],
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $result = ['status' => 'error', 'message' => $validation->getErrors()];
        } else {
            $login_id = $this->request->getPost('login_id');
            $password = $this->request->getPost('password');
            $account = new Account();

            $field = filter_var($login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

            $user = $account->asObject()->where($field, $login_id)
                ->where('type', 'user')
                ->where('is_deleted', 0)
                ->first();

            if ($user && Hash::check($password, $user->password)) {
                CIAuth::setCIAuth($user);
                $result = ['status' => 'success'];
            } else {
                $result = ['status' => 'error', 'message' => ['login_id' => 'Invalid credentials.']];
            }
        }

        return $this->response->setJSON($result);
    }

    public function logout()
    {
        CIAuth::forget();
        return redirect()->route('home');
    }

    public function forgot_password()
    {
        if (session()->getFlashdata('message') !== null) {
            $data = ['page_title' => 'Forgot Password', 'message' => session()->getFlashdata('message')];
        } else {
            $data = ['page_title' => 'Forgot Password'];
        }

        return view('forgot-password', $data);
    }

    public function forgot_password_submit()
    {
        if (!$this->request->isAJAX()) {
            return;
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'email' => 'required|valid_email',
        ], [
            'email' => [
                'required' => 'Email is required.',
                'valid_email' => 'Please enter a valid email address.',
            ],
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $result = ['status' => 'error', 'message' => $validation->getErrors()];
        } else {
            helper('CIMail_helper');
            $account = new Account();
            $user = $account->asObject()->where('email', $this->request->getPost('email'))
                ->where('type', 'user')
                ->where('is_deleted', 0)
                ->first();

            if ($user) {
                $token = bin2hex(openssl_random_pseudo_bytes(65));

                $password_reset_token = new PasswordResetToken();

                $is_old_token_exist = $password_reset_token->asObject()->where('email', $user->email)->first();

                if ($is_old_token_exist) {
                    $password_reset_token->where('email', $user->email)
                        ->set(['token' => $token, 'created_at' => Carbon::now()])
                        ->update();
                } else {
                    $password_reset_token->insert([
                        'email' => $user->email,
                        'token' => $token,
                    ]);
                }

                $action_link = base_url(route_to('user.reset-password', $token));

                $mail_data = array(
                    'action_link' => $action_link,
                    'user' => $user,
                );

                $view = \Config\Services::renderer();
                $mail_body = $view->setVar('mail_data', $mail_data)->render('email-templates/forgot-email-template');

                $mail_config = array(
                    'mail_from_email' => 'untamedandromeda@gmail.com',
                    'mail_from_name' => 'EasyStay Reservation',
                    'mail_recipient_email' => $user->email,
                    'mail_recipient_name' => $user->username,
                    'mail_subject' => 'Reset Password',
                    'mail_body' => $mail_body,
                );

                if (send_email($mail_config)) {
                    $result = ['status' => 'success', 'message' => 'We have emailed your password reset link.'];
                } else {
                    $result = ['status' => 'error', 'message' => ['email' => 'Something went wrong']];
                }
            } else {
                $result = ['status' => 'error', 'message' => ['email' => 'Email not registered.']];
            }
        }

        return $this->response->setJSON($result);
    }

    public function reset_password($token)
    {
        $password_reset_token = new PasswordResetToken();
        $validate_token = $password_reset_token->asObject()->where('token', $token)->first();

        if (!$validate_token) {
            $data_to_pass = 'Invalid token. Request another reset password link.';

            session()->setFlashdata('message', $data_to_pass);

            return redirect()->route('user.forgot-password.form');
        } else {
            $diff_mins = Carbon::createFromFormat('Y-m-d H:i:s', $validate_token->created_at)->diffInMinutes(Carbon::now());

            if ($diff_mins > 15000) {
                $data_to_pass = 'Token expired. Request another reset password link.';

                session()->setFlashdata('message', $data_to_pass);

                return redirect()->route('user.forgot-password.form');
            } else {
                $data = ['page_title' => 'Reset Password', 'token' => $token];
                return view('reset-password', $data);
            }
        }
    }

    public function reset_password_submit()
    {
        if (!$this->request->isAJAX()) {
            return;
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'new_password' => 'required|min_length[8]|strong_password[new_password]',
            'confirm_password' => 'required|matches[new_password]',
        ], [
            'new_password' => [
                'required' => 'New Password is required',
                'min_length' => 'New Password must be at least 8 characters long.',
                'strong_password' => 'New Password must contains atleast 1 uppercase, 1 lowercase, 1 number, and 1 special character.',
            ],
            'confirm_password' => [
                'required' => 'Confirm Password is required.',
                'matches' => 'Password confirmation does not match.',
            ],
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON(['status' => 'error', 'message' => $validation->getErrors()]);
        }

        $token = $this->request->getPost('token');
        $password_reset_token = new PasswordResetToken();
        $get_token = $password_reset_token->asObject()->where('token', $token)->first();

        $account = new Account();
        $user = $account->asObject()
            ->where('email', $get_token->email)
            ->where('type', 'user')
            ->where('is_deleted', 0)
            ->first();

        if (!$get_token) {
            $data_to_pass = 'Invalid token. Request another reset password link.';
            session()->setFlashdata('message', $data_to_pass);
            return redirect()->route('user.forgot-password.form');
        }

        $account->where('email', $user->email)
            ->where('type', 'user')
            ->where('is_deleted', 0)
            ->set('password', Hash::hash($this->request->getPost('new_password')))
            ->update();

        if ($account) {
            $password_reset_token->where('token', $token)->delete();
            return $this->response->setJSON(['status' => 'success', 'message' => 'Password updated successfully. You can now login.']);
        }
    }

    public function admin_login()
    {
        $data = ['page_title' => 'Admin Login'];
        return view('admin/login', $data);
    }

    public function admin_login_submit()
    {
        if (!$this->request->isAJAX()) {
            return;
        }

        $validation = \Config\Services::validation();
        $validation->setRules([
            'login_id' => 'required',
            'password' => 'required|min_length[8]',
        ], [
            'login_id' => [
                'required' => 'Username or Email is required.',
            ],
            'password' => [
                'required' => 'Password is required.',
                'min_length' => 'Password must be at least 8 characters long.',
            ],
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $result = ['status' => 'error', 'message' => $validation->getErrors()];
        } else {
            $login_id = $this->request->getPost('login_id');
            $password = $this->request->getPost('password');
            $account = new Account();

            $field = filter_var($login_id, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

            $admin = $account->asObject()->where($field, $login_id)
                ->where('type', 'admin')
                ->where('is_deleted', 0)
                ->first();

            if ($admin && Hash::check($password, $admin->password)) {
                CIAuth::setCIAuthAdmin($admin);
                $result = ['status' => 'success'];
            } else {
                $result = ['status' => 'error', 'message' => ['login_id' => 'Invalid credentials.']];
            }
        }

        return $this->response->setJSON($result);
    }

    public function admin_logout()
    {
        CIAuth::forgetAdmin();
        return redirect()->route('admin.login.form');
    }
}
