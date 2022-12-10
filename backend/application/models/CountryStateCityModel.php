<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CountryStateCityModel extends CI_Model {
    
	public function getCountry($id="") {
		$this->db->select('*');
		$this->db->from('tbl_countries');
		if($id != "") {
			$this->db->where('id',$id);
		}
		return $this->db->get()->result_array();
	}
	public function getState($id="") {
		$this->db->select('*');
		$this->db->from('tbl_states');
		if($id != "") {
			$this->db->where('country_id',$id);
		}
		return $this->db->get()->result_array();
	}
	public function getCity($id="") {
		$this->db->select('*');
		$this->db->from('tbl_cities');
		$this->db->where('state_id',$id);
		return $this->data = $this->db->get()->result_array();
	}
}
