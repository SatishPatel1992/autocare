<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class InsuranceModel extends CI_Model {
    public function getInsuranceData() { 
		$result = array();

		$this->db->select('*');
		$this->db->from('tbl_insurance');
		$this->db->where('garage_id', $_SESSION['setting']->garage_id);
		$result['insurance'] = $this->db->get()->result_array();

		return $result;
	}
	public function getInsuranceByID() {
        $insurance_id = isset($_REQUEST['id']) ? base64_decode($_REQUEST['id']) : '';
        $result = array();

		$this->db->select('*');
		$this->db->from('tbl_insurance');
		$this->db->where('garage_id', $_SESSION['setting']->garage_id);
		$this->db->where('insurance_id', $insurance_id);
		$result['insurance'] = $this->db->get()->row_array();

		return $result;
	}
}
