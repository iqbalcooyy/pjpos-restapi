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
                'rownum' => $customers->num_rows(),
                'result' => $customers->result_array()
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Customers not found!'
            ], REST_Controller::HTTP_OK);
        }
    }

    //INSERT
    public function index_post()
    {
        if ($this->post()) {
            $data = $this->post();
            if($data['cust_name'] == "" || $data['cust_address'] == "" || $data['cust_telp'] == "") {
                $this->response([
                    'status' => false,
                    'message' => 'Mohon, lengkapi data!'
                ], REST_Controller::HTTP_OK);
            } else {
                $custId = $this->cust->getCustId();

                $insert = $this->cust->createNewCust($custId, $data);

                if ($insert > 0) {
                    $this->response([
                        'status' => true,
                        'id' => $custId,
                        'message' => 'New customer '.$custId.' has been created!'
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
            $custId = $data['cust_id'];
            if(trim($data['cust_id']) == "" || trim($data['cust_name']) == "" || trim($data['cust_address']) == "" || trim($data['cust_telp']) == "") {
                $this->response([
                    'status' => false,
                    'message' => 'Mohon, lengkapi data!'
                ], REST_Controller::HTTP_OK);
            } else {
                $update = $this->cust->updateCustomer($data);

                if ($update > 0) {
                    $this->response([
                        'status' => true,
                        'id' => $custId,
                        'message' => 'Customer '.$custId.' has been modified!'
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

    //DELETE
    public function index_delete()
    {
        if ($this->delete()) {
            $custId = $this->delete('cust_id');

            if (trim($custId) == "") {
                $this->response([
                    'status' => false,
                    'message' => 'Provide a Customer ID!'
                ], REST_Controller::HTTP_OK);
            } else {
                $delete = $this->cust->deleteCustomer($custId);

                if ($delete > 0) {
                    $this->response([
                        'status' => true,
                        'id' => $custId,
                        'message' => 'Customer '.$custId.' has been deleted!'
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'status' => false,
                        'message' => 'Failed to delete the data!'
                    ], REST_Controller::HTTP_OK);
                }
            }
        } else {
            $this->response([
                'status' => false,
                'message' => 'Provide an Supplier ID!'
            ], REST_Controller::HTTP_OK);
        }
    }
}
