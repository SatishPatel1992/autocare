<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PurchaseModel extends CI_Model {

    public function getAllPO() {
            $result = array();

            $this->db->select('tbl_vendor_bills.*,tbl_vendor.company_name,sum(amount) total_paid');
            $this->db->from('tbl_vendor_bills');
            $this->db->join('tbl_vendor','tbl_vendor.vendor_id=tbl_vendor_bills.vendor_id','left');
            $this->db->join('tbl_transaction','tbl_transaction.item_id=tbl_vendor_bills.po_id and tbl_transaction.transaction_type="bill_payment"','left');
            $this->db->where('tbl_vendor_bills.garage_id',$_SESSION['setting']->garage_id);
            $this->db->group_by('tbl_vendor_bills.po_id');
            $result['orders'] = $this->db->get()->result_array();
            
            return $result;
	}
    public function addPurchaseOrder() {
        $result = array();

        $this->db->select('*');
        $this->db->from('tbl_vendor');
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
        $result['vendors'] = $this->db->get()->result_array();

        if(isset($_REQUEST['id'])) {
            $this->db->select('*');
            $this->db->from('tbl_vendor_bills');
            $this->db->where('po_id',base64_decode($_REQUEST['id']));
            $result['purchase'] = $this->db->get()->row_array();
        }

        $this->db->select('*');
        $this->db->from('tbl_garage');
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
        $result['settings'] = $this->db->get()->row_array();

        $this->db->select('*');
        $this->db->from('tbl_accounts');
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
        $this->db->where_in('account_type',array('10','11','5','3'));
        $result['paid_accounts'] = $this->db->get()->result_array();

        $this->db->select('*');
        $this->db->from('tbl_accounts');
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
        $this->db->where('account_type',2);
        $result['ap_account'] = $this->db->get()->result_array();

        $is_disc_applicable = 'N';
        $total_enable = 5;
        if(isset($result['purchase']['is_disc_applicable']) && $result['purchase']['is_disc_applicable'] == 'Y') {
            $total_enable++;
            $is_disc_applicable = 'Y';
        } else if(!isset($result['purchase']['is_disc_applicable']) && $result['settings']['show_discount_column'] == 'Y') {
            $total_enable++; 
            $is_disc_applicable = 'Y';
        }
        $is_tax_applicable = 'N';
        if(isset($result['purchase']['is_tax_applicable']) && $result['purchase']['is_tax_applicable'] == 'Y') {
            $total_enable++;
            $is_tax_applicable = 'Y';
        } else if(!isset($result['purchase']['is_tax_applicable']) && $result['settings']['gst_applicable'] == 'Y') {
            $total_enable++; 
            $is_tax_applicable = 'Y';
        }
        
        $result['colConfig'] = array('colspan' => $total_enable,'is_tax_applicable' => $is_tax_applicable,'is_disc_applicable' => $is_disc_applicable);

        return $result;
    }
}