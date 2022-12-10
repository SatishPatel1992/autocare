<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('AuthModel');
    }

    public function index() {
        if (!empty($_POST)) {
            $email = $this->input->post('email');
            $pass = $this->input->post('password');
            $data = $this->AuthModel->check_login($email, $pass);
            if ($data['status'] == 0) {
                $this->session->set_userdata('isloggedin', true);
                $this->session->set_userdata('data', $data['data']);
                $this->session->set_userdata('setting', $data['setting']);
                $this->session->mobile_no = $data['data']->email;
                redirect('dashboard');
            } else {
                $data['email'] = $email;
                $data['password'] = $pass;
                $this->session->set_flashdata('SUCCESSMSG', 'Email OR Password is Wrong');
                $this->data = $data;
                $this->render('login', null);
            }
        } else {
            $this->render('login', null);
        }
    }
    public function logout() {
        $this->session->sess_destroy();
        echo '<script>window.location = "../"</script>';
    }

}
