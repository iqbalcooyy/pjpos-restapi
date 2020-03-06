<?php

class stock_model extends CI_Model
{
    public function getStock()
    {
        return $this->db->get('stock')->result_array();
    }
}