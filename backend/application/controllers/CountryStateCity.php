<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CountryStateCity extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('CountryStateCityModel');
    }
    public function getCountry() {
		$id = isset($_REQUEST['country_id']) ? $_REQUEST['country_id'] : ''; 
        $cntryData = $this->CountryStateCityModel->getCountry($id);
		$this->render('','json');
    }
	public function getStateByCountry() {
		$id = isset($_REQUEST['country_id']) ? $_REQUEST['country_id'] : ''; 
        $stateData = $this->CountryStateCityModel->getState($id);
		$this->render('','json');
    }
	public function getCityByState() {
		$id = isset($_REQUEST['state_id']) ? $_REQUEST['state_id'] : ''; 
        $this->data = $this->CountryStateCityModel->getCity($id);
		$this->render('','json');
    }
}
