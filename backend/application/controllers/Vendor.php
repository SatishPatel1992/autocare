<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vendor extends MY_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->mobile_no == "") {
            echo '<script>window.location = "../"</script>';
        }
        $this->load->model('VendorModel');
        $this->load->model('InventoryModel');
    }
    public function index() {
        $GLOBALS['title_right'] = "Vendors";
    	$this->data = $this->VendorModel->getAllVendor();
        $this->render('vendor');
    }
    public function add() {
        $GLOBALS['title_right'] = "Add New Vendor";
        $this->data = $this->VendorModel->getsaveddata();
        $this->render('add_vendor');
    }
    public function getVendorOrderDetail() {
        $this->data = $this->InventoryModel->getVendorOrderDetail();
        $this->render('','json');
    }
    public function getVehicleData() {
        $this->data = $this->VendorModel->getVehicleData();
        $this->render('','json');
    }
}
