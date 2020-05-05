<?php

class supplier_model extends CI_Model
{
    public function getSuppId()
    {
        $q = $this->db->query("SELECT MAX(RIGHT(supplier_id, 3)) AS kd_max FROM supplier");
        $kd = "";
        if ($q->num_rows()>0) {
            foreach($q->result() as $k){
                $tmp = ((int)$k->kd_max)+1;
                $kd = sprintf("%03s", $tmp);
            }
        }else {
            $kd = "001";
        }
        return "SUP".$kd;
    }

    public function getSuppliers()
    {
        return $this->db->get('supplier');
    }

    public function createNewSupp($suppId, $data)
    {
        $this->db->insert('supplier', [
            'supplier_id' => $suppId, 
            'supplier_name' => $data['supplier_name'], 
            'supplier_address' => $data['supplier_address'],
            'supplier_telp' => $data['supplier_telp']
            ]);

        return $this->db->affected_rows();
    }

    public function updateSupplier($data)
    {
        $this->db->update('supplier', 
        [
            'supplier_name' => $data['supplier_name'], 
            'supplier_address' => $data['supplier_address'],
            'supplier_telp' => $data['supplier_telp']
        ],
        [
            'supplier_id' => $data['supplier_id']
        ]);

        return $this->db->affected_rows();
    }

    public function deleteSupplier($suppId)
    {
        $this->db->delete('supplier', ['supplier_id' => $suppId]);

        return $this->db->affected_rows();
    }
}