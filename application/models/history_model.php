<?php

class history_model extends CI_Model
{
    public function getSalesHistory()
    {
        $query = "SELECT A.sale_id, A.cust_id, B.cust_name, A.to_be_paid, A.discount, A.paid, A.status, A.created_by, DATE_FORMAT(A.sale_date, '%d-%m-%Y') 'sale_date'
                FROM trx_sales_header A
                JOIN customer B
                ON A.cust_id = B.cust_id";

        return $this->db->query($query);
    }

    public function salesHistoryDetail($saleId)
    {
        $query = "SELECT A.sale_id, A.item_id, B.item_name, A.sale_qty, B.uom
                FROM trx_sales_detail A
                JOIN stock B
                ON A.item_id = B.item_id
                WHERE A.sale_id = '$saleId'";

        return $this->db->query($query);
    }

    public function getPurchaseHistory()
    {
        $query = "SELECT A.purchase_id, A.supplier_id, B.supplier_name, A.purchase_price_total, A.created_by, DATE_FORMAT(A.purchase_date, '%d-%m-%Y') 'purchase_date'
                FROM trx_purchase_header A
                JOIN supplier B
                ON A.supplier_id = B.supplier_id";

        return $this->db->query($query);
    }

    public function purchaseHistoryDetail($purchaseId)
    {
        $query = "SELECT A.item_id, B.item_name, A.purchase_qty, A.purchase_uom, A.purchase_price
                FROM trx_purchase_detail A
                JOIN stock B
                ON A.item_id = B.item_id
                WHERE A.purchase_id = '$purchaseId'";

        return $this->db->query($query);
    }
}