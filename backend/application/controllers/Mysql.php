<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mysql extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('MysqlModel');
    }
    public function index() {
        //$this->render('setting');
    }
}
