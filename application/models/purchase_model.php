<?php

class purchase_model extends CI_Model
{
    public function getPurchaseId()
    {
        $q = $this->db->query("SELECT MAX(RIGHT(purchase_id, 4)) AS kd_max FROM trx_purchase_header WHERE DATE(purchase_date)=CURDATE()");
        $kd = "";
        if ($q->num_rows()>0) {
            foreach($q->result() as $k){
                $tmp = ((int)$k->kd_max)+1;
                $kd = sprintf("%04s", $tmp);
            }
        }else {
            $kd = "0001";
        }
        date_default_timezone_set('Asia/Jakarta');
        return date('dmy').$kd;
    }

    public function createPurchase($purchaseId, $data)
    {
        $details = array();
        $size = count($data['item_id']);

        for($i=0; $i < $size; $i++){
            $details[] = array(
                'purchase_id' => $purchaseId, 
                'item_id' => $data['item_id'][$i],
                'purchase_qty' => $data['purchase_qty'][$i],
                'purchase_uom' => $data['purchase_uom'][$i],
                'purchase_price' => $data['purchase_price'][$i]
            );
        }

        $this->db->insert('trx_purchase_header', [
            'purchase_id' => $purchaseId, 
            'supplier_id' => $data['supplier_id'], 
            'purchase_price_total' => $data['purchase_price_total'],
            'created_by' => $data['user']
            ]);
        $this->db->insert_batch('trx_purchase_detail', $details);

        //Update Stock after Purchase
        for($i=0; $i < $size; $i++){
            $item_id = $data['item_id'][$i];
            $purchase_qty = $data['purchase_qty'][$i];

            $query = "UPDATE stock
                SET item_qty = item_qty + $purchase_qty
                WHERE item_id = '$item_id'";

            $this->db->query($query);
        }

        return $this->db->affected_rows();
    }
}
