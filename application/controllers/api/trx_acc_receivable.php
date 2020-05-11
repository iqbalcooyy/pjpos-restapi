<?php

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class trx_acc_receivable extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('acc_receivable_model', 'ar_model');
    }

    public function index_get()
    {
        $receivable = $this->ar_model->getAr();

        if ($receivable) {
            $this->response([
                'status' => true,
                'message' => 'Load Account Receivable Successfully',
                'rownum' => $receivable->num_rows(),
                'result' => $receivable->result_array()
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Account Receivable not found!'
            ], REST_Controller::HTTP_OK);
        }
    }

    //INSERT
    public function index_post()
    {
        if ($this->post()) {
            $data = $this->post();
            if(trim($data['sale_id']) == "" || trim($data['cust_id']) == "" || trim($data['ar_total']) == "") {
                $this->response([
                    'status' => false,
                    'message' => 'Mohon, lengkapi data!'
                ], REST_Controller::HTTP_OK);
            } else {
                $arId = $this->ar_model->getArId();
                $arStatus = "OPEN";

                $insert = $this->ar_model->createAr($arId, $arStatus, $data);

                if ($insert > 0) {
                    $this->response([
                        'status' => true,
                        'id' => $arId,
                        'ar_status' => $arStatus,
                        'message' => 'Account receivable has been created!'
                    ], REST_Controller::HTTP_CREATED);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Failed to created new data!'
                    ], REST_Controller::HTTP_OK);
                }
            }

        } else {
            $this->response([
                'status' => false,
                'message' => 'Data value cannot be null!'
            ], REST_Controller::HTTP_OK);
        }
    }

    //UPDATE
    public function index_put()
    {
        if ($this->put()) {
            $data = $this->put();

            if(trim($data['ar_id']) == "" || trim($data['ar_paid']) == "") {
                $this->response([
                    'status' => false,
                    'message' => 'Mohon, lengkapi data!'
                ], REST_Controller::HTTP_OK);
            } else {
                $update = $this->ar_model->updateAr($data);
                $getAr = $this->ar_model->getArWithId($data['ar_id']);

                foreach ($getAr->result() as $row)
                {
                    $arStatus = $row->ar_status;
                }

                if ($update > 0) {
                    $this->response([
                        'status' => true,
                        'id' => $data['ar_id'],
                        'ar_status' => $arStatus,
                        'message' => 'Piutang '.$data['ar_id'].' has been modified!'
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Failed to modify the data!'
                    ], REST_Controller::HTTP_OK);
                }
            }

        } else {
            $this->response([
                'status' => false,
                'message' => 'Data value cannot be null!'
            ], REST_Controller::HTTP_OK);
        }
    }
}
