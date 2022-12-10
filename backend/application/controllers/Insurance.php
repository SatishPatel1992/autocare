<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Insurance extends MY_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->mobile_no == "") {
            echo '<script>window.location = "../"</script>';
        }
        $this->load->model('InsuranceModel');
    }
    public function index() {
        $GLOBALS['title_right'] = 'Insurance Master';
	    $this->data = $this->InsuranceModel->getInsuranceData();
        $this->render('insurance');
    }
    public function addInsurance() {
        $GLOBALS['title_right'] = 'Add Insurance Details';
	    $this->data = $this->InsuranceModel->getInsuranceByID();
        $this->render('add_insurance');
    }
}
