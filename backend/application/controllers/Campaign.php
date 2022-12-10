<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Campaign extends MY_Controller {
    public function __construct() {
        parent::__construct();
        
        if ($this->session->mobile_no == "") {
            echo '<script>window.location = "../"</script>';
        }
        $this->load->model('CampaignModel');
    }
    public function index() {
        $GLOBALS['title_right'] = "Campaign";
        $this->render('campaign');
    }   
    public function addCampaign() {
        $GLOBALS['title_right'] = "Add Campaign";
        $this->render('add_campaign');
    }
}
?>