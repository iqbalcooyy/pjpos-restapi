<?php

class report_model extends CI_Model
{
    public function getSalesTrx($startDate, $endDate)
    {
        $querySales = "SELECT * 
                    FROM trx_sales_header
                    WHERE DATE(sale_date) BETWEEN '$startDate' AND '$endDate'";

        return $this->db->query($querySales);
    }

    public function getPurchaseTrx($startDate, $endDate)
    {
        $queryPurchase = "SELECT * 
                    FROM trx_purchase_header
                    WHERE DATE(purchase_date) BETWEEN '$startDate' AND '$endDate'";
        
        return $this->db->query($queryPurchase);
    }
}
