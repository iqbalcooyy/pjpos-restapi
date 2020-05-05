<?php

use Restserver\Libraries\REST_Controller;
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class login_user extends REST_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }

    public function index_post()
    {
        $username = $this->post('username');
        $password = md5($this->post('password'));

        if ($username == null || $password == null) {
            $this->response([
                'status' => false,
                'message' => 'Username or Password Cannot be Blank !'
            ], REST_Controller::HTTP_OK);
        } else {
            $user = $this->user_model->getUser($username, $password);

            //jika tbl user ada isinya
            if($user->num_rows() > 0) {
                $this->response([
                    'status' => true,
                    'message' => 'Login Successfully',
                    'rownum' => $user->num_rows(),
                    'result' => $user->result()
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'status' => false,
                    'message' => 'Invalid Username or Password !'
                ], REST_Controller::HTTP_OK);
            }
        }
    }
}