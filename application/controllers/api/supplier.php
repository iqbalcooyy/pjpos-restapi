<?php

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class supplier extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('supplier_model', 'supplier');
    }

    public function index_get()
    {
        $suppliers = $this->supplier->getSuppliers();

        if ($suppliers) {
            $this->response([
                'status' => true,
                'message' => 'Load Suppliers Successfully',
                'rownum' => $suppliers->num_rows(),
                'result' => $suppliers->result_array()
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Suppliers not found!'
            ], REST_Controller::HTTP_OK);
        }
    }

    //INSERT
    public function index_post()
    {
        if ($this->post()) {
            $data = $this->post();
            if(trim($data['supplier_name']) == "" || trim($data['supplier_address']) == "" || trim($data['supplier_telp']) == "") {
                $this->response([
                    'status' => false,
                    'message' => 'Mohon, lengkapi data!'
                ], REST_Controller::HTTP_OK);
            } else {
                $suppId = $this->supplier->getSuppId();

                $insert = $this->supplier->createNewSupp($suppId, $data);

                if ($insert > 0) {
                    $this->response([
                        'status' => true,
                        'id' => $suppId,
                        'message' => 'New supplier '.$suppId.' has been created!'
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
            $suppId = $data['supplier_id'];
            if(trim($data['supplier_id']) == "" || trim($data['supplier_name']) == "" || trim($data['supplier_address']) == "" || trim($data['supplier_telp']) == "") {
                $this->response([
                    'status' => false,
                    'message' => 'Mohon, lengkapi data!'
                ], REST_Controller::HTTP_OK);
            } else {
                $update = $this->supplier->updateSupplier($data);

                if ($update > 0) {
                    $this->response([
                        'status' => true,
                        'id' => $suppId,
                        'message' => 'Supplier '.$suppId.' has been modified!'
                    ], REST_Controller::HTTP_CREATED);
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
            $suppId = $this->delete('supplier_id');

            if (trim($suppId) == "") {
                $this->response([
                    'status' => false,
                    'message' => 'Provide an Supplier ID!'
                ], REST_Controller::HTTP_OK);
            } else {
                $delete = $this->supplier->deleteSupplier($suppId);

                if ($delete > 0) {
                    $this->response([
                        'status' => true,
                        'id' => $suppId,
                        'message' => 'Supplier '.$suppId.' has been deleted!'
                    ], REST_Controller::HTTP_CREATED);
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
