<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Discount extends MY_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->mobile_no == "") {
            echo '<script>window.location = "../"</script>';
        }
        $this->load->model('DiscountModel');
    }
    public function index() {
		$this->data = $this->DiscountModel->getAllDiscount();
        $this->render('discount');
    }
	public function add() {
		$this->data = $this->DiscountModel->getSavedData();
		$this->render('add_discount');
	}
}
