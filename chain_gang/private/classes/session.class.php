<?php

//2:38 Define a session class
class Session
{
    private $admin_id;
    public $username;
    private $last_login;

    public const MAX_LOGIN_AGE = 60*60*24; //1DAY

    public function __construct()
    {
        session_start();
        $this->check_stored_login(); //immediate check on the login of our user on this Session class
    }

    public function login($admin)
    {
        //stored in the session variable and the property
        if ($admin) {
            // prevents session fixation attacks
            session_regenerate_id(); //regenerate on every unique login
            $this->admin_id = $_SESSION['admin_id'] = $admin->id;
            $this->admin_id = $admin->id;

            //double assignment to The SESSION and to the properties
            $this->username = $_SESSION['username'] = $admin->username;
            $this->last_login = $_SESSION['last_login'] = time();
        }
        return true;
    }

    //if the user is set and the last login is recent then the user returns true
    public function is_logged_in()
    {
        // return isset($this->admin_id);
        return isset($this->admin_id) && $this->last_login_was_recent();
    }

    //UNSET $_SESSION VALUE FOR LOGGING OUT
    public function logout()
    {
        unset($_SESSION['admin_id']);
        unset($_SESSION['username']);
        unset($_SESSION['last_login']);
        unset($this->admin_id);
        unset($this->username);
        unset($this->last_login);
        return true;
    }

    //CHECK
    private function check_stored_login()
    {
        if (isset($_SESSION['admin_id'])) {
            $this->admin_id = $_SESSION['admin_id'];
            $this->username = $_SESSION['username'];
            $this->last_login = $_SESSION['last_login'];
        }
    }

    //better than storing a token thats fs lol
    private function last_login_was_recent()
    {
        if(!isset($this->last_login)){
            return false;
        } elseif(($this->last_login + self::MAX_LOGIN_AGE) < time()) {
            return false;
        } else {
            return true;
        }
    }

    public function message($msg="")
    {
        if(!empty($msg)){
            //then this is a "set" message
            $_SESSION['message'] = $msg;
            return true;
        } else {
            //then this is a "get" message
            return $_SESSION['message'] ?? '';
        }
    }

    public function clear_message()
    {
        unset($_SESSION['message']);
    }
}

?>