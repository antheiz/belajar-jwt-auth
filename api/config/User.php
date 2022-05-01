<?php


class User
{
    private $_db;

    public function __construct()
    {
        $this->_db = Database::getInstance();
    }

    public function register_user($fields = array())
    {
        if ($this->_db->insert('user', $fields))
            return true;
        else
            return false;
    }

    public function login_user($username, $password)
    {
        $data = $this->_db->get_info('user', 'username', $username);

        if (password_verify($password, $data['password'])) {
            return true;
        }
        else {
            return false;
        }

    }

    public function check_name($username) {

        $data = $this->_db->get_info('user', 'username', $username);
        
        if ( empty($data) ) return false;
        else return true;

    }

    public function get_data($username) {
        
        if ($this->check_name($username)) 
            return $this->_db->get_info('user', 'username', $username);
        else
            return "Info user tidak ditemukan";

    }
    
}