<?php

class stock_model extends CI_Model
{
    public function getItemId()
    {
        $q = $this->db->query("SELECT MAX(RIGHT(item_id, 3)) AS kd_max FROM stock");
        $kd = "";
        if ($q->num_rows()>0) {
            foreach($q->result() as $k){
                $tmp = ((int)$k->kd_max)+1;
                $kd = sprintf("%03s", $tmp);
            }
        }else {
            $kd = "001";
        }
        return "PJ".$kd;
    }

    public function getStock()
    {
        return $this->db->get('stock');
    }

    public function createNewItem($itemId, $data)
    {
        $this->db->insert('stock', [
            'item_id' => $itemId, 
            'item_name' => $data['item_name'], 
            'item_qty' => $data['item_qty'],
            'uom' => $data['uom'],
            'selling_price' => $data['selling_price'],
            'purchase_price' => $data['purchase_price']
            ]);

        return $this->db->affected_rows();
    }

    public function updateProduct($data)
    {
        $this->db->update('stock', 
        [
            'item_name' => $data['item_name'], 
            'item_qty' => $data['item_qty'],
            'uom' => $data['uom'],
            'selling_price' => $data['selling_price'],
            'purchase_price' => $data['purchase_price']
        ],
        [
            'item_id' => $data['item_id']
        ]);

        return $this->db->affected_rows();
    }

    public function deleteProduct($itemId)
    {
        $this->db->delete('stock', ['item_id' => $itemId]);

        return $this->db->affected_rows();
    }
}