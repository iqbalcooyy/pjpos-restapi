<?php

class acc_receivable_model extends CI_Model
{
    public function getArId()
    {
        $q = $this->db->query("SELECT MAX(RIGHT(ar_id, 4)) AS kd_max FROM trx_account_receivable WHERE DATE(ar_date)=CURDATE()");
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
        return "AR".date('dmy').$kd;
    }

    public function getAr()
    {
        return $this->db->get('trx_account_receivable');
    }

    public function createAr($arId, $arStatus, $data)
    {
        $this->db->insert('trx_account_receivable', [
            'ar_id' => $arId, 
            'sale_id' => $data['sale_id'], 
            'cust_id' => $data['cust_id'],
            'ar_total' => $data['ar_total'],
            'ar_status' => $arStatus
            ]);

        return $this->db->affected_rows();
    }
}
