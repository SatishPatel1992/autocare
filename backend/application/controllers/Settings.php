<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('session');
        if($this->session->mobile_no == "") {
            echo '<script>window.location = "../"</script>';
        }
        $GLOBALS['title_left'] = "Settings";
        $this->load->model('SettingModel');
        $this->load->model('MysqlModel');
    }
    public function index() {
        $GLOBALS['title_right'] = "Settings";
        $this->data = $this->SettingModel->getsettings();
        $this->render('setting');
    }
}
