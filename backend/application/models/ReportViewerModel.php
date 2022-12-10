<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReportViewerModel extends CI_Model {
	
	public function getCustomerLedgerData() {
		$this->db->select('*');
		$this->db->from('tbl_customer');
		$this->db->where('garage_id',$this->session->userdata['setting']->garage_id);
		return $this->db->get()->result_array();
	}
}