<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DashboardModel extends CI_Model {

    public function getDashboardData() {
        //get dates..
        $this->db->select('tbl_invoices.invoice_no,tbl_invoices.item_id,sum(tbl_transaction.amount) as paid_amount,tbl_customer.name as customer_name,tbl_invoices.amount as invoice_amount,(tbl_invoices.amount - IFNULL(SUM(CASE WHEN tbl_transaction.amount IS NULL THEN 0 ELSE tbl_transaction.amount END),0)) as due_amount');
        $this->db->from('tbl_invoices');
        $this->db->join('tbl_transaction','tbl_transaction.item_id=tbl_invoices.invoice_id and tbl_transaction.transaction_type="customer_payment"','left');
        $this->db->join('tbl_customer','tbl_customer.customer_id = tbl_invoices.customer_id','left');
        $this->db->where('tbl_invoices.garage_id',$_SESSION['setting']->garage_id);
        $this->db->where('tbl_invoices.item_type',"job_invoice");
        $this->db->having('due_amount >',0);
        $this->db->group_by('tbl_invoices.invoice_id');
        $result['sales'] = $this->db->get()->result_array();

        $this->db->select('tbl_vendor_bills.po_no,tbl_vendor_bills.po_id,sum(tbl_transaction.amount) as paid_amount,tbl_vendor.company_name,tbl_vendor_bills.grand_total as po_amount,(tbl_vendor_bills.grand_total - IFNULL(SUM(CASE WHEN tbl_transaction.amount IS NULL THEN 0 ELSE tbl_transaction.amount END),0)) as due_amount');
        $this->db->from('tbl_vendor_bills');
        $this->db->join('tbl_transaction','tbl_transaction.item_id=tbl_vendor_bills.po_id and tbl_transaction.transaction_type="bill_payment"','left');
        $this->db->join('tbl_vendor','tbl_vendor.vendor_id = tbl_vendor_bills.vendor_id','left');
        $this->db->where('tbl_vendor_bills.garage_id',$_SESSION['setting']->garage_id);
        $this->db->having('due_amount >',0);
        $this->db->group_by('tbl_vendor_bills.po_id');
        $result['purchases'] = $this->db->get()->result_array();
        
        $this->db->select('*');
        $this->db->from('tbl_notification');
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
        $this->db->where('date_format(date,"%Y-%m-%d")',date('Y-m-d'));
        $this->db->order_by('date','desc');
        $result['notifications'] = $this->db->get()->result_array();

        $st_date = date('Y-m-01');
        $ed_date = date('Y-m-t');

        $this->db->select('sum(amount) as total_amount,transaction_type');
        $this->db->from('tbl_transaction');
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
        $date_where = "tbl_transaction.date between '".$st_date."' and '".$ed_date."'";
        $this->db->where($date_where);
        $this->db->group_by('transaction_type');        
        $transactions = $this->db->get()->result_array();

        $total_paid = 0;
        $total_payable = 0;
        $total_recievable = 0;
        $total_recieve = 0;
        
        foreach($transactions as $t) {
            if($t['transaction_type'] == 'bill_payment') {
                $total_paid = $t['total_amount'];
            } else if($t['transaction_type'] == 'bill') {
                $total_payable = $t['total_amount'];
            } else if($t['transaction_type'] == 'customer_invoice') {
                $total_recievable = $t['total_amount'];
            } else if($t['transaction_type'] == 'customer_payment') {
                $total_recieve += $t['total_amount'];
            } else if($t['transaction_type'] == 'other_income') {
                $total_recieve += $t['total_amount'];
            } else if($t['transaction_type'] == 'other_expense') {
                $total_paid += $t['total_amount'];
            }
        }
        $total_upcoming = $total_recievable - $total_payable;
        $total_expense = $total_paid + ($total_payable - $total_paid);
        $total_income = $total_recieve + ($total_recievable - $total_recieve);
        $total_profit =  $total_income - $total_expense;
        $result['summary'] = array('total_recieve' => $total_income,'total_recievable' => $total_recievable,'total_paid' => $total_expense,'total_payable' => $total_payable,'total_profit' => $total_profit,'total_upcoming' => $total_upcoming);
        return $result;
    }
}
