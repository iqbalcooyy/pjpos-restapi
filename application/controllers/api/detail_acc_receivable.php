<?php

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class detail_acc_receivable extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('acc_receivable_model', 'ar_model');
    }

    public function index_get()
    {
        if ($this->get()) {
            $data = $this->get();

            if (trim($data['ar_id']) == "") {
                $this->response([
                    'status' => false,
                    'message' => 'Parameter cannot be null!'
                ], REST_Controller::HTTP_OK);
            } else {
                $arDetail = $this->ar_model->getArDetailWithId($data['ar_id']);

                if ($arDetail) {
                    $this->response([
                        'status' => true,
                        'message' => 'Load Account Receivable Detail Success',
                        'rownum' => $arDetail->num_rows(),
                        'result' => $arDetail->result_array()
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Account Receivable not found!'
                    ], REST_Controller::HTTP_OK);
                }
            }

        } else {
            $this->response([
                'status' => false,
                'message' => 'Parameter cannot be null!'
            ], REST_Controller::HTTP_OK);
        }
    }
}