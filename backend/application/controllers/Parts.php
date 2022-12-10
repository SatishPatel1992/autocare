<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Parts extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $GLOBALS['title_right'] = "Item Master";
        if ($this->session->mobile_no == "") {
            echo '<script>window.location = "../"</script>';
        }
        $this->load->model('InventoryModel');
    }
    public function index() {
        $this->data = $this->InventoryModel->getAllParts(); 
        $this->render('parts');
    }
}
