<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->mobile_no == "") {
            echo '<script>window.location = "../"</script>';
        }
        $this->load->model('UsersModel');
    }
    public function index() {
        $GLOBALS['title_right'] = "Users";
        $this->data = $this->UsersModel->getAllUser();
        $this->render('users');
    }
    public function AddUser() {
        $GLOBALS['title_right'] = "Add Users";
        $this->data = $this->UsersModel->getUserByUserId();
        $this->render('add_users');
    }
    public function getDefaultMenuRights() {
        $this->data = $this->UsersModel->getMenuRights($_REQUEST['role_id']);
        $this->render('json');
    }
}
