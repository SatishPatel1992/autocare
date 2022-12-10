<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VendorModel extends CI_Model {
  public function getAllVendor() { 
	$result = array();
	$this->db->select('*');
	$this->db->from('tbl_vendor');
	$this->db->where('garage_id',$_SESSION['setting']->garage_id);
	$result['vendors'] = $this->db->get()->result_array();
	return $result;
  }
  public function getsaveddata() {
	    $result = array();
	  


		$this->db->select('*');
		$this->db->from('tbl_vendor');
		$this->db->where('vendor_id',base64_decode($_REQUEST['id']));
		$result['vendor'] = $this->db->get()->row_array();

		$this->db->select('*');
		$this->db->from('tbl_vendor_bills');
		$this->db->where('vendor_id',base64_decode($_REQUEST['id']));
		$this->db->order_by('po_id','desc');
		$result['purchase_orders'] = $this->db->get()->result_array();

		$this->db->select('*');
		$this->db->from('tbl_payment_type');
		$result['payment_type'] = $this->db->get()->result_array();

		$this->db->select('tbl_make.make_id,tbl_model.model_id,variant_id,concat(tbl_make.name," ",tbl_model.name," ",tbl_variant.name) as vehicle_name');
		$this->db->from('tbl_variant');
		$this->db->join('tbl_model','tbl_model.model_id = tbl_variant.model_id','left');
		$this->db->join('tbl_make','tbl_make.make_id = tbl_model.make_id','left');
		$result['vehicle_list'] = $this->db->get()->result_array();
		
		$this->load->model('CountryStateCityModel');
		$result['state'] = $this->CountryStateCityModel->getState('101');
		$state_id = $result['vendor']['state'] ? $result['vendor']['state'] : $_SESSION['setting']->default_state;
		$result['city']  =  $this->CountryStateCityModel->getCity($state_id);
		return $result;
  }
  public function getVendorPaymentDetail() {
	  $result = array();

	  $this->db->select('tbl_payment_type.name,tbl_payments.*');
	  $this->db->from('tbl_payments');
	  $this->db->join('tbl_payment_type','tbl_payment_type.payment_type = tbl_payments.payment_id','left');
	  $this->db->where('item_id',$_REQUEST['invoice_id']);
	  $this->db->where('item_type','vendor_payment');
	  $result['payments'] = $this->db->get()->result_array();

	  return $result;
  }

}
