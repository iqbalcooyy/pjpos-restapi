<?php

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class report extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('report_model', 'report');
    }

    // Get Report by Period
    public function index_post()
    {
        if ($this->post()) {
            $startDate = $this->post('start_date'); // format:'YYYY-MM-DD'
            $endDate = $this->post('end_date');

            if (trim($startDate) == "" || trim($endDate) == "") {
                $this->response([
                    'status' => false,
                    'message' => 'Harap lengkapi parameter!'
                ], REST_Controller::HTTP_OK);
            } else {
                $getSales = $this->report->getSalesTrx($startDate, $endDate);
                $getPurchase = $this->report->getPurchaseTrx($startDate, $endDate);

                if ($getSales && $getPurchase) {
                    $this->response([
                        'status' => true,
                        'message' => 'Load Report Successfully',
                        'rownum_sales' => $getSales->num_rows(),
                        'result_sales' => $getSales->result_array(),
                        'rownum_purchase' => $getPurchase->num_rows(),
                        'result_purchase' => $getPurchase->result_array()
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Report not found!'
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