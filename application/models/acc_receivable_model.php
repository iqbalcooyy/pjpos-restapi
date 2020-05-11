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
        $query = "SELECT DATE(a.ar_date) ar_date, a.ar_id, a.sale_id, a.cust_id, b.cust_name, b.cust_address, b.cust_telp, a.ar_total, a.remaining_payment, a.ar_status
                FROM trx_account_receivable a
                LEFT JOIN customer b
                ON a.cust_id = b.cust_id";

        return $this->db->query($query);
    }

    public function getArWithId($arId)
    {
        return $this->db->get_where('trx_account_receivable', ['ar_id' => $arId]);
    }

    public function getArDetailWithId($arId)
    {
        $query = "SELECT ar_detail_id, ar_id, ar_paid, notes, DATE_FORMAT(ar_paid_date, '%d %M %Y') 'ar_paid_date'
                FROM trx_account_receivable_detail
                WHERE ar_id = '$arId'";

        return $this->db->query($query);
    }

    public function createAr($arId, $arStatus, $data)
    {
        $this->db->insert('trx_account_receivable', [
            'ar_id' => $arId, 
            'sale_id' => $data['sale_id'], 
            'cust_id' => $data['cust_id'],
            'ar_total' => $data['ar_total'],
            'remaining_payment' => $data['ar_total'],
            'ar_status' => $arStatus
            ]);

        return $this->db->affected_rows();
    }

    public function updateAr($data)
    {
        $arId = $data['ar_id'];
        $arPaid = $data['ar_paid'];
        $arNotes = $data['notes'];

        //Update Remaining Payment
        $this->db->query("UPDATE trx_account_receivable
                        SET remaining_payment = remaining_payment - $arPaid
                        WHERE ar_id = '$arId'"
        );

        //Insert History Pembayaran
        $this->db->insert('trx_account_receivable_detail', [
            'ar_id' => $arId,
            'ar_paid' => $arPaid,
            'notes' => $arNotes
        ]);

        //Update Status Piutang
        $getAr = $this->db->get_where('trx_account_receivable', ['ar_id' => $arId]);
        
        foreach ($getAr->result() as $row)
        {
            $remainingPay = $row->remaining_payment;
        }

        if ($remainingPay == 0) {
            $this->db->update('trx_account_receivable', 
            [
                'ar_status' => "CLOSED"
            ],
            [
                'ar_id' =>  $arId
            ]);
        }
        
        return $this->db->affected_rows();
    }
}
