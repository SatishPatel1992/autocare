<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends MY_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->mobile_no == "") {
            echo '<script>window.location = "../"</script>';
        }
        $this->load->model('ReportsModel');
    }
    public function account_payable() {
        $GLOBALS['title_right'] = 'Account Payable';
        $this->data = $this->ReportsModel->getAccountPayable();
        $this->render('account_payable');
    }
    public function account_receivable() {
        $GLOBALS['title_right'] = 'Account Receivable';
        $this->data = $this->ReportsModel->getAccountReceivable();
        $this->render('account_receivable');
    }
    public function account_statement() { 
        $GLOBALS['title_right'] = 'Account Statement';
        $this->data = $this->ReportsModel->getAccountStatement();
        $this->render('account_statement');
    }
    public function parts_purchase() { 
        $GLOBALS['title_right'] = 'Parts Purchase';
        $this->data = $this->ReportsModel->parts_purchase();
        $this->render('parts_purchase');
    }
    public function jobcard_reports() { 
        $GLOBALS['title_right'] = 'Jobcards';
        $this->data = $this->ReportsModel->jobcard_reports();
        $this->render('jobcard_reports');
    }
    public function invoice_reports() { 
        $GLOBALS['title_right'] = 'Customer Invoices';
        $this->data = $this->ReportsModel->invoice_reports();
        $this->render('invoice_reports');
    }
    public function transactions() {
        $this->data = $this->ReportsModel->transactions();
        $this->render('transaction_list');
    }
    public function sales_summary() {
        $GLOBALS['title_right'] = 'Sales Summary';
        $this->data = $this->ReportsModel->sales_summary();
        $this->render('sales_summary');
    }
    public function ledger() {
        $GLOBALS['title_right'] = 'Ledger';
        $this->data = $this->ReportsModel->ledger();
        $this->render('ledger');
    }
    public function daybook() {
        $GLOBALS['title_right'] = 'Daybook';
        $this->data = $this->ReportsModel->daybook();
        $this->render('daybook');
    }
    public function outstanding() {
        $GLOBALS['title_right'] = 'Outstanding';
        $this->data = $this->ReportsModel->outstanding();
        $this->render('outstanding');
    }
    public function gstr_1() {
        $GLOBALS['title_right'] = 'GSTR-1 (Sales)';
        $this->data = $this->ReportsModel->gstr_1();
        $this->render('gstr_1');
    }
    public function gstr_2() {
        $GLOBALS['title_right'] = 'GSTR-2 (Purchase)';
        $this->data = $this->ReportsModel->gstr_2();
        $this->render('gstr_2');
    }
    public function bill_wise_profit() {
        $GLOBALS['title_right'] = 'Bill Wise Profit';
        $this->data = $this->ReportsModel->bill_wise_profit();
        $this->render('bill_wise_profit');
    }
    public function customer_statement() {
        $GLOBALS['title_right'] = 'Customer Statement';
        $this->data = $this->ReportsModel->customer_statement();
        $this->render('customer_statement');
    }
    public function profit_loss() {
        $GLOBALS['title_right'] = 'Profit And Loss';
        $this->data = $this->ReportsModel->getProfitLoss();
        $this->render('profit_loss');
    }
    public function stock_reports() {
        $GLOBALS['title_right'] = 'Stock Reports';
        $this->data = $this->ReportsModel->getStockReports();
        $this->render('stock_reports');
    }
}
