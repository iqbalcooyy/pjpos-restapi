<?php

class user_model extends CI_Model 
{
    public function getUser($username, $password) 
    {
        return $this->db->get_where('users', ['username' => $username, 'password' => $password])->result_array();
    }
}