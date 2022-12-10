<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VehicleModel extends CI_Model {

    public function getVehicleData() {
		$result = array();

		$this->db->select('*');
		$this->db->from('tbl_make');
		$this->db->where('garage_id = '.$_SESSION['setting']->garage_id.' or garage_id = 0');
		$this->db->order_by('name','asc');
		$result['makeNames'] = $this->db->get()->result_array();

		$this->db->select('tbl_model.*,tbl_make.name as make_name');
		$this->db->from('tbl_model');
		$this->db->join('tbl_make','tbl_make.make_id = tbl_model.make_id','left');
		$this->db->where('tbl_model.garage_id = '.$_SESSION['setting']->garage_id.' or tbl_model.garage_id = 0');
		$this->db->order_by('tbl_make.name','asc');
		$result['modelNames'] = $this->db->get()->result_array();

		$this->db->select('tbl_model.name as model_name,tbl_make.name as make_name,tbl_variant.*');
		$this->db->from('tbl_variant');
		$this->db->join('tbl_model','tbl_model.model_id = tbl_variant.model_id','left');
		$this->db->join('tbl_make','tbl_make.make_id = tbl_model.make_id','left');
		$this->db->where('tbl_model.garage_id = '.$_SESSION['setting']->garage_id.' or tbl_model.garage_id = 0');
		$this->db->order_by('tbl_make.name,tbl_model.name','asc');
		$result['varientNames'] = $this->db->get()->result_array();

		return $result;
	}
}
