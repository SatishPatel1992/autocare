<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $GLOBALS['title_right'] = "Menu";
        if ($this->session->mobile_no == "") {
            echo '<script>window.location = "../"</script>';
        }
        $this->load->model('MenuModel');
    }
    public function index() {
        $this->data = $this->MenuModel->get_menu(1,0,'');
        $this->render('menu');
    }
}
