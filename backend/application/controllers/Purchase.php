<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Purchase extends MY_Controller {
    public function __construct() {
        parent::__construct();
        if ($this->session->mobile_no == "") {
            echo '<script>window.location = "../"</script>';
        }
        $this->load->model('PurchaseModel');
    }
    public function index() {
        $GLOBALS['title_right'] = "Vendor Bills";
        $this->data =  $this->PurchaseModel->getAllPO();
        $this->render('purchase');
    }
    public function add_purchase() {
        $GLOBALS['title_right'] = "Add Purchase Order";
        $this->data =  $this->PurchaseModel->addPurchaseOrder();
        $this->render('add_purchase');
    }
    // public function savePackage() {
    //     $this->data =  $this->PackageModel->savePackage();
    // }
}
?>
