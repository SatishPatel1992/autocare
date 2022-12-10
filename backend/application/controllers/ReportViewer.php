<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReportViewer extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('ReportsModel');
    }
    public function view() {
        $GLOBALS['title_right'] = "Customers";
        $this->data = $this->ReportsModel->ledger();
        $this->render('report_viewer');
    }
}