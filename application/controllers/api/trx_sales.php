<?php

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class trx_sales extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('sales_model', 'sales');
    }
    
    // INSERT
    public function index_post()
    {
        if ($this->post()) {
            $data = $this->post();
            $salesId = 'S'.$this->sales->getSalesId(); //S is Sales
            $paid = $data['paid'];
            $discount = $data['discount'];

            if($paid+$discount < $data['to_be_paid']) {
                $statusSale = 'Terhutang';
            } elseif($paid+$discount == $data['to_be_paid']) {
                $statusSale = 'Lunas';
            }

            $insert = $this->sales->createSales($salesId, $data, $statusSale);
    
            if ($insert > 0) {
                $this->response([
                    'status' => true,
                    'id' => $salesId,
                    'status_sale' => $statusSale,
                    'message' => 'Transaksi penjualan '.$salesId.' berhasil dibuat dengan status '.$statusSale.'!'
                ], REST_Controller::HTTP_CREATED);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Failed to created new data!'
                ], REST_Controller::HTTP_OK);
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Data value cannot be null!'
            ], REST_Controller::HTTP_OK);
        }
        
    }
}