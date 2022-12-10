<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends MY_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->mobile_no == "") {
            echo '<script>window.location = "../"</script>';
        }
        $this->load->model('CustomerModel');
    }
    public function index() {
        $GLOBALS['title_right'] = "Customers";
	$data = $this->CustomerModel->getAllCustomer();
	$this->data = array('data' => $data);
	$this->render('customer');
    }
    public function add() {
        $GLOBALS['title_right'] = "Add New Customer";
        $customer_id = isset($_REQUEST['id']) ? base64_decode($_REQUEST['id']) : '';
	$this->data = $this->CustomerModel->getCustomerData($customer_id);
	$this->render('add_customer');
    }
    public function getModelByMake() {
	$make_id = $_REQUEST['make_id'];
	$this->data = $this->CustomerModel->getModelByMake($make_id);
	$this->render('','json');		
    }
    public function getVariantByModel() {
	$model_id = $_REQUEST['model_id'];
	$this->data = $this->CustomerModel->getVariantByModel($model_id);
	$this->render('','json');		
    }
}
