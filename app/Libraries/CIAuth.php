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

    public static function forget()
    {
        $session = session();
        $session->remove('user_is_logged_in');
        $session->remove('user_data');
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
}
