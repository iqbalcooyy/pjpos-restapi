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
                'rownum' => $stock->num_rows(),
                'result' => $stock->result()
            ], REST_Controller::HTTP_OK);
        } else {
            $this->response([
                'status' => false,
                'message' => 'Stock not available!'
            ], REST_Controller::HTTP_OK);
        }
    }

    //INSERT
    public function index_post()
    {
        if ($this->post()) {
            $data = $this->post();
            if(
                trim($data['item_name']) == "" || 
                trim($data['item_qty']) == "" || 
                trim($data['uom']) == "" ||
                trim($data['selling_price']) == "" ||
                trim($data['purchase_price']) == ""
                ) {
                    $this->response([
                        'status' => false,
                        'message' => 'Mohon, lengkapi data!'
                    ], REST_Controller::HTTP_OK);
            } else {
                $itemId = $this->stock->getItemId();

                $insert = $this->stock->createNewItem($itemId, $data);

                if ($insert > 0) {
                    $this->response([
                        'status' => true,
                        'id' => $itemId,
                        'message' => 'New item '.$itemId.' has been created!'
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
            $itemId = $data['item_id'];
            
            if(
                trim($data['item_id']) == "" ||
                trim($data['item_name']) == "" || 
                trim($data['item_qty']) == "" || 
                trim($data['uom']) == "" ||
                trim($data['selling_price']) == "" ||
                trim($data['purchase_price']) == ""
                ) {
                    $this->response([
                        'status' => false,
                        'message' => 'Mohon, lengkapi data!'
                    ], REST_Controller::HTTP_OK);
            } else {
                $update = $this->stock->updateProduct($data);

                if ($update > 0) {
                    $this->response([
                        'status' => true,
                        'id' => $itemId,
                        'message' => 'Product '.$itemId.' has been modified!'
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
            $itemId = $this->delete('item_id');

            if(trim($itemId) == "") {
                $this->response([
                    'status' => false,
                    'message' => 'Provide an Item ID!'
                ], REST_Controller::HTTP_OK);
            } else {
                $delete = $this->stock->deleteProduct($itemId);

                if ($delete > 0) {
                    $this->response([
                        'status' => true,
                        'id' => $itemId,
                        'message' => 'Product '.$itemId.' has been deleted!'
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
                'message' => 'Data value cannot be null!'
            ], REST_Controller::HTTP_OK);
        }
    }
}