<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AccountsModel extends CI_Model {
    public function getDropdownData() {
        $result = array();
        
        $this->db->select('*');
        $this->db->from('tbl_expense_types');
        $result['expense_types'] = $this->db->get()->result_array();

        $this->db->select('*');
        $this->db->from('tbl_category');
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
        $result['category'] = $this->db->get()->result_array();

        $this->db->select('*');
        $this->db->from('tbl_payment_type');
        $result['payment_type'] = $this->db->get()->result_array();
    
        $this->db->select('*');
        $this->db->from('tbl_vendor');
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
        $result['vendors'] = $this->db->get()->result_array();

        $this->db->select('tbl_customer_vehicle.vehicle_id,tbl_customer.customer_id,name,reg_no');
        $this->db->from('tbl_customer');
        $this->db->join('tbl_customer_vehicle','tbl_customer_vehicle.customer_id=tbl_customer.customer_id','left');
        $this->db->where('tbl_customer.garage_id', $_SESSION['setting']->garage_id);
        $this->db->where('tbl_customer_vehicle.is_active','1');
        $result['customers'] = $this->db->get()->result_array();

        $start_date = '';
        $end_date = date('Y-m-d');
        if(date('m') < 4) {
            $start_date = (date('Y')-1).'-04-01';
        } else {
            $start_date = date('Y').'-04-01';
        }

        if(isset($_REQUEST['d']) && $_REQUEST['d'] != "") {
            $dates = explode(" - ",$_REQUEST['d']);
            $start_date = date('Y-m-d',strtotime($dates[0]));
            $end_date = date('Y-m-d',strtotime($dates[1]));
        }

        if($_REQUEST['vd'] && $_REQUEST['vd'] != '*') {
            $result['payments'] = $this->db->query("select *,tbl_payments.notes as payment_notes,tbl_payments.date as payment_date,sum(tbl_payments.amount) as total_paid from tbl_payments left join tbl_vendor on tbl_vendor.vendor_id = tbl_payments.vendor_id left join tbl_vendor_bills on tbl_vendor_bills.po_id = tbl_payments.item_id where tbl_payments.garage_id = ".$_SESSION['setting']->garage_id." and item_type ='vendor_payment' and DATE_FORMAT(tbl_payments.date,'%Y-%m-%d') between '".$start_date."' and '".$end_date."' and tbl_payments.vendor_id = '".$_REQUEST['vd']."'  group by tbl_payments.item_id")->result_array();
            $transcationPayments = $this->db->query("select *,tbl_payments.notes as payment_notes,tbl_payments.date as payment_date,sum(tbl_payments.amount) as total_paid from tbl_payments left join tbl_vendor on tbl_vendor.vendor_id = tbl_payments.vendor_id left join tbl_transaction on tbl_transaction.transcation_id = tbl_payments.item_id where tbl_payments.garage_id = ".$_SESSION['setting']->garage_id." and item_type ='expense_payment' and DATE_FORMAT(tbl_payments.date,'%Y-%m-%d') between '".$start_date."' and '".$end_date."' and tbl_payments.vendor_id = '".$_REQUEST['vd']."' group by tbl_payments.item_id")->result_array();
            $expenses = $this->db->query("select * from tbl_vendor_bills left join tbl_vendor on tbl_vendor.vendor_id = tbl_vendor_bills.vendor_id where tbl_vendor_bills.garage_id = ".$_SESSION['setting']->garage_id." and DATE_FORMAT(tbl_vendor_bills.invoice_date,'%Y-%m-%d') between '".$start_date."' and '".$end_date."'  and tbl_vendor_bills.vendor_id = '".$_REQUEST['vd']."'")->result_array();
            $transcationExp = $this->db->query("select *,tbl_category.name as category_name from tbl_transaction left join tbl_category on tbl_category.category_id = tbl_transaction.category_id left join tbl_vendor on tbl_vendor.vendor_id = tbl_transaction.vendor_id where tbl_transaction.garage_id = ".$_SESSION['setting']->garage_id." and DATE_FORMAT(tbl_transaction.date,'%Y-%m-%d') between '".$start_date."' and '".$end_date."' and transaction_type = 'expense' and tbl_transaction.vendor_id = '".$_REQUEST['vd']."'")->result_array();
        } else {
            $result['payments'] = $this->db->query("select *,tbl_payments.notes as payment_notes,tbl_payments.date as payment_date,sum(tbl_payments.amount) as total_paid from tbl_payments left join tbl_vendor on tbl_vendor.vendor_id = tbl_payments.vendor_id left join tbl_vendor_bills on tbl_vendor_bills.po_id = tbl_payments.item_id where tbl_payments.garage_id = ".$_SESSION['setting']->garage_id." and item_type ='vendor_payment' and DATE_FORMAT(tbl_payments.date,'%Y-%m-%d') between '".$start_date."' and '".$end_date."' group by tbl_payments.item_id")->result_array();
            $transcationPayments = $this->db->query("select *,tbl_payments.notes as payment_notes,tbl_payments.date as payment_date,sum(tbl_payments.amount) as total_paid from tbl_payments left join tbl_vendor on tbl_vendor.vendor_id = tbl_payments.vendor_id left join tbl_transaction on tbl_transaction.transcation_id = tbl_payments.item_id where tbl_payments.garage_id = ".$_SESSION['setting']->garage_id." and item_type ='expense_payment' and DATE_FORMAT(tbl_payments.date,'%Y-%m-%d') between '".$start_date."' and '".$end_date."' group by tbl_payments.item_id")->result_array();
            $expenses = $this->db->query("select * from tbl_vendor_bills left join tbl_vendor on tbl_vendor.vendor_id = tbl_vendor_bills.vendor_id where tbl_vendor_bills.garage_id = ".$_SESSION['setting']->garage_id." and DATE_FORMAT(tbl_vendor_bills.invoice_date,'%Y-%m-%d') between '".$start_date."' and '".$end_date."'")->result_array();
            $transcationExp = $this->db->query("select *,tbl_category.name as category_name from tbl_transaction left join tbl_category on tbl_category.category_id = tbl_transaction.category_id left join tbl_vendor on tbl_vendor.vendor_id = tbl_transaction.vendor_id where tbl_transaction.garage_id = ".$_SESSION['setting']->garage_id." and DATE_FORMAT(tbl_transaction.date,'%Y-%m-%d') between '".$start_date."' and '".$end_date."' and transaction_type = 'expense'")->result_array();
        }
        foreach($expenses as $exp) {
            $paymentMade = $this->db->query("select sum(tbl_payments.amount) as total_paid from tbl_payments where item_id = '".$exp['po_id']."' and item_type= 'vendor_payment' and DATE_FORMAT(tbl_payments.date,'%Y-%m-%d') between '".$start_date."' and '".$end_date."' group by tbl_payments.item_id")->row_array();
            $exp['payment_made'] = $paymentMade['total_paid'];
            $result['expenses'][] = $exp;
        }
        foreach ($transcationExp as $key => $value) {
            $paymentMade = $this->db->query("select sum(tbl_payments.amount) as total_paid from tbl_payments where item_id = '".$value['transcation_id']."' and item_type= 'expense_payment' and DATE_FORMAT(tbl_payments.date,'%Y-%m-%d') between '".$start_date."' and '".$end_date."' group by tbl_payments.item_id")->row_array();
            $value['payment_made'] = $paymentMade['total_paid'];
            $result['expenses'][] = $value;
        }
        
        foreach($transcationPayments as $trasn) {
            $trasn['grand_total'] = $trasn['amount'];
            $trasn['invoice_date'] = $trasn['date'];
            $result['payments'][] = $trasn;
        }
        return $result;
    }
    public function getAccountDetailsByID() {
        $result = array();
        $this->db->select('*');
        $this->db->from('tbl_accounts');
        $this->db->where('account_id',$_REQUEST['account_id']);
        $result = $this->db->get()->row_array();
        return $result;
    }
}
