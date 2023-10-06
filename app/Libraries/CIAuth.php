<?php
namespace App\Libraries;

class CIAuth
{
    public static function setCIAuth($result)
    {
        $session = session();
        $array = ['user_is_logged_in' => true];
        $user_data = $result;
        $session->set('user_data', $user_data);
        $session->set($array);
    }

    public static function setCIAuthAdmin($result)
    {
        $session = session();
        $array = ['admin_is_logged_in' => true];
        $admin_data = $result;
        $session->set('admin_data', $admin_data);
        $session->set($array);
    }

    public static function id()
    {
        $session = session();
        if ($session->has('user_is_logged_in')) {
            if ($session->has('user_data')) {
                return $session->get('user_data')['user_id'];
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public static function check()
    {
        $session = session();
        return $session->has('user_is_logged_in');
    }

    public static function check_admin()
    {
        $session = session();
        return $session->has('admin_is_logged_in');
    }

    public static function forget()
    {
        $session = session();
        $session->remove('user_is_logged_in');
        $session->remove('user_data');
    }

    public static function forgetAdmin()
    {
        $session = session();
        $session->remove('admin_is_logged_in');
        $session->remove('admin_data');
    }

    public static function user()
    {
        $session = session();
        if ($session->has('user_is_logged_in')) {
            if ($session->has('user_data')) {
                return $session->get('user_data');
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    public static function admin()
    {
        $session = session();
        if ($session->has('admin_is_logged_in')) {
            if ($session->has('admin_data')) {
                return $session->get('admin_data');
            } else {
                return null;
            }
        } else {
            return null;
        }
    }
}
