<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $GLOBALS['title_right'] = "Dashboard";
        if ($this->session->mobile_no == "") {
            redirect('');
        }
        $this->load->model('DashboardModel');
    }
    public function index() {
        $this->data = $this->DashboardModel->getDashboardData();
        $this->render('dashboard');
    }
}
