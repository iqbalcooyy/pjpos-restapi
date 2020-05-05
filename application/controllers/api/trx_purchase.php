<?php

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class trx_purchase extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('purchase_model', 'purchase');
    }

    // INSERT
    public function index_post()
    {
        if ($this->post()) {
            $data = $this->post();
            $purchaseId = 'P'.$this->purchase->getPurchaseId(); //P is Purchase

            $insert = $this->purchase->createPurchase($purchaseId, $data);
    
            if ($insert > 0) {
                $this->response([
                    'status' => true,
                    'id' => $purchaseId,
                    'message' => 'Purchase transaction '.$purchaseId.' has been created!'
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