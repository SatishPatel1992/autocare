<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IncomeExpenseHead extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $GLOBALS['title_right'] = "Add Income & Expense Head";
        if ($this->session->mobile_no == "") {
            redirect('');
        }
        $this->load->model('IncomeExpenseModel');
    }
    public function index() {
        $this->data = $this->IncomeExpenseModel->getData();
        $this->render('income_expense_head');
    }
    public function addIncExpHead() {
        $this->data = $this->IncomeExpenseModel->getHeadByID();
        $this->render('add_income_expense_head');
    }
}
