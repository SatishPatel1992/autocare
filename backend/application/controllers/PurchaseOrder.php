<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PurchaseOrder extends MY_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->mobile_no == "") {
            echo '<script>window.location = "../"</script>';
        }
        $this->load->model('PurchaseModel');
    }
    public function index() {
        $this->data = $this->PurchaseModel->getAllPO();
        $this->render('','json');
    }    
}
