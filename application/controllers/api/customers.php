<?php

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class customers extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('customer_model', 'cust');
    }

    public function index_get()
    {
        $customers = $this->cust->getCustomers();

        if ($customers) {
            $this->response([
                'status' => true,
                'message' => 'Load Customers Successfully',
                'result' => $customers
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Customers not found!'
            ], REST_Controller::HTTP_OK);
        }
    }
}
