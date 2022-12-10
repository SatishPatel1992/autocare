<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DiscountModel extends CI_Model {
	
    public function getAllDiscount() {
		$result = array();
		$this->db->select('*');
		$this->db->from('tbl_discount');
		$this->db->where('garage_id',$_SESSION['setting']->garage_id);
		$result['discount'] = $this->db->get()->result_array();
		return $result;
	}
	public function getSavedData() {
		$result = array();
		$discount_id = isset($_REQUEST['id']) ? base64_decode($_REQUEST['id']) : '';
		$this->db->select('*');
		$this->db->from('tbl_discount');
		$this->db->where('discount_id', $discount_id);
		$result['discount'] = $this->db->get()->row_array();
		return $result;
	}
}
