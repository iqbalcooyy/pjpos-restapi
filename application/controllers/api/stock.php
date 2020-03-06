<?php

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class stock extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('stock_model', 'stock');
    }

    public function index_get()
    {
        $stock = $this->stock->getStock();

        if ($stock) {
            $this->response([
                'status' => true,
                'message' => 'Load Stock Successfully',
                'result' => $stock
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Stock not available!'
            ], REST_Controller::HTTP_OK);
        }
    }
}