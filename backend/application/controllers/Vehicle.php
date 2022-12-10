<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vehicle extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $GLOBALS['title_right'] = "Vehicle";
        if ($this->session->mobile_no == "") {
            redirect('');
        }
        $this->load->model('VehicleModel');
    }
    public function index() {
        $this->data = $this->VehicleModel->getVehicleData();
        $this->render('vehicle');
    }
}
