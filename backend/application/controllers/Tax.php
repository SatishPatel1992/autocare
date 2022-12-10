<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tax extends MY_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->mobile_no == "") {
            echo '<script>window.location = "../"</script>';
        }
        $GLOBALS['title_right'] = "Tax Master";
        $this->load->model('TaxModel');
    }
    public function index() {
       $data = $this->TaxModel->getAllTaxes();
	   $this->data = $data;
	   $this->render('tax');
    }
}
