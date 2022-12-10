<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TaxModel extends CI_Model {

    public function getAllTaxes() {
		$this->db->select('*');
		$this->db->from('tbl_tax_rate');
		$this->db->where('garage_id',$_SESSION['setting']->garage_id);
		$this->db->where('is_active',1);
		$result['taxes'] = $this->db->get()->result_array();
		return $result;
	}
}
