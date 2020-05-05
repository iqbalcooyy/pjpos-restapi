<?php

class customer_model extends CI_Model
{
    public function getCustId()
    {
        $q = $this->db->query("SELECT MAX(RIGHT(cust_id, 3)) AS kd_max FROM customer");
        $kd = "";
        if ($q->num_rows()>0) {
            foreach($q->result() as $k){
                $tmp = ((int)$k->kd_max)+1;
                $kd = sprintf("%03s", $tmp);
            }
        }else {
            $kd = "001";
        }
        return "CUST".$kd;
    }

    public function getCustomers()
    {
        return $this->db->get('customer');
    }

    public function createNewCust($custId, $data)
    {
        $this->db->insert('customer', [
            'cust_id' => $custId, 
            'cust_name' => $data['cust_name'], 
            'cust_address' => $data['cust_address'],
            'cust_telp' => $data['cust_telp']
            ]);

        return $this->db->affected_rows();
    }

    public function updateCustomer($data)
    {
        $this->db->update('customer', 
        [
            'cust_name' => $data['cust_name'], 
            'cust_address' => $data['cust_address'],
            'cust_telp' => $data['cust_telp']
        ],
        [
            'cust_id' =>  $data['cust_id']
        ]);

        return $this->db->affected_rows();
    }

    public function deleteCustomer($custId)
    {
        $this->db->delete('customer', ['cust_id' => $custId]);

        return $this->db->affected_rows();
    }
}
