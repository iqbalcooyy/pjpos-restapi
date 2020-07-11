<?php

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class history_purchase extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('history_model', 'history');
    }

    public function index_get()
    {
        $getPurchaseHistory = $this->history->getPurchaseHistory();

        if ($getPurchaseHistory) {
            $this->response([
                'status' => true,
                'message' => 'Load History Successfully',
                'rownum_history' => $getPurchaseHistory->num_rows(),
                'result_history' => $getPurchaseHistory->result_array()
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'History Sales not found!'
            ], REST_Controller::HTTP_OK);
        }
    }

    //Details
    public function index_post()
    {
        if ($this->post()) {
            $purchase_id = $this->post('purchase_id');

            if (trim($purchase_id) == "") {
                $this->response([
                    'status' => false,
                    'message' => 'Parameter tidak boleh kosong!'
                ], REST_Controller::HTTP_OK);
            } else {
                $getDetails = $this->history->purchaseHistoryDetail($purchase_id);

                if ($getDetails) {
                    $this->response([
                        'status' => true,
                        'message' => 'Load History Detail Successfully',
                        'purchase_id' => $purchase_id,
                        'rownum_history_detail' => $getDetails->num_rows(),
                        'result_history_detail' => $getDetails->result_array()
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'History Detail not found!'
                    ], REST_Controller::HTTP_OK);
                }
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Parameter tidak boleh kosong!'
            ], REST_Controller::HTTP_OK);
        }
    }
}