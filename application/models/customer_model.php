<?php

class customer_model extends CI_Model
{
    public function getCustomers()
    {
        return $this->db->get('customer')->result_array();
    }
}
