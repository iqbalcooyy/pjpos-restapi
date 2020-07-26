<?php

class report_model extends CI_Model
{
    public function getSalesTrx($startDate, $endDate)
    {
        $querySales = "SELECT A.sale_id, A.cust_id, B.cust_name, A.to_be_paid, A.discount, A.paid, A.status, A.created_by, DATE_FORMAT(A.sale_date, '%d %M %Y') 'sale_date'
                    FROM trx_sales_header A
                    JOIN customer B
                    ON A.cust_id = B.cust_id
                    WHERE DATE(sale_date) BETWEEN '$startDate' AND '$endDate'";

        return $this->db->query($querySales);
    }

    public function getPurchaseTrx($startDate, $endDate)
    {
        $queryPurchase = "SELECT A.purchase_id, A.supplier_id, B.supplier_name, A.purchase_price_total, A.created_by, DATE_FORMAT(A.purchase_date, '%d %M %Y') 'purchase_date'
                    FROM trx_purchase_header A
                    JOIN supplier B
                    ON A.supplier_id = B.supplier_id
                    WHERE DATE(purchase_date) BETWEEN '$startDate' AND '$endDate'";
        
        return $this->db->query($queryPurchase);
    }
}