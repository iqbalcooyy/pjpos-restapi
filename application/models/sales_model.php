<?php

class sales_model extends CI_Model
{
    public function getSalesId(){
        $q = $this->db->query("SELECT MAX(RIGHT(sale_id, 4)) AS kd_max FROM trx_sales_header WHERE DATE(sale_date)=CURDATE()");
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

    public function createSales($salesId, $data, $statusSale)
    {
        $details = array();
        $size = count($data['item_id']);

        for($i=0; $i < $size; $i++){
            $details[] = array(
                'sale_id' => $salesId, 
                'item_id' => $data['item_id'][$i],
                'sale_qty' => $data['sale_qty'][$i]
            );
        }

        $this->db->insert('trx_sales_header', [
            'sale_id' => $salesId, 
            'cust_id' => $data['cust_id'], 
            'to_be_paid' => $data['to_be_paid'], 
            'discount' => $data['discount'],
            'paid' => $data['paid'],
            'status' => $statusSale,
            'created_by' => $data['user']
            ]);
        $this->db->insert_batch('trx_sales_detail', $details);

        //Update Stock after Sales
        for($i=0; $i<$size; $i++){
            $item_id = $data['item_id'][$i];
            $sale_qty = $data['sale_qty'][$i];

            $query = "UPDATE stock
                SET item_qty = item_qty - $sale_qty
                WHERE item_id = '$item_id'";

            $this->db->query($query);
        }

        return $this->db->affected_rows();
    }
}