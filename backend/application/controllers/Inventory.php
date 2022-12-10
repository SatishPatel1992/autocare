<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inventory extends MY_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->mobile_no == "") {
            echo '<script>window.location = "../"</script>';
        }
        $this->load->model('InventoryModel');
    }
    public function index() {
        $GLOBALS['title_right'] = 'Items';
	    $this->data = $this->InventoryModel->getInventortData(); 
        $this->render('inventory');
    }
    public function getVendorOrderDetail() {
        $this->data = $this->InventoryModel->getVendorOrderDetail();
        $this->render('','json');
    }
}
