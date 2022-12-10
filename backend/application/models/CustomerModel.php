<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CustomerModel extends CI_Model {
	
	public function getAllCustomer() {
        $this->db->select('tbl_customer.*,SUM(IF(transaction_type = "customer_invoice", amount, 0)) AS "invoiced",SUM(IF(transaction_type = "customer_payment", amount, 0)) AS "payment"');
		$this->db->from('tbl_customer');
		$this->db->join('tbl_transaction','tbl_transaction.customer_id=tbl_customer.customer_id','left');
		$this->db->where('tbl_customer.garage_id',$this->session->userdata['setting']->garage_id);
		$this->db->group_by('tbl_customer.customer_id');
		return $this->db->get()->result_array();
	}
	public function getCustomerData($customer_id) {
		$result = array();
		$result['customer'] = array();
		$result['autos'] = array();
	
		$this->db->select('*');
		$this->db->from('tbl_payment_type');
		$result['payment_type'] = $this->db->get()->result_array();

		if($customer_id != '') {
			$this->db->select('*');
			$this->db->from('tbl_customer');
			$this->db->where('customer_id',$customer_id);
			$result['customer'] = $this->db->get()->row_array();
			
			// customer vehicle .
			$this->db->select('*');
			$this->db->from('tbl_customer_vehicle');
			$this->db->where('customer_id',$customer_id);
			$this->db->where('is_active','1');
			$result['autos'] = $this->db->get()->result_array();
			
			foreach($result['autos'] as $au => $vs) {
                $this->db->select('*');
                $this->db->from('tbl_model');
                $this->db->where('make_id',$vs['make_id']);
                $result['autoload']['model_'.($au+1)] = $this->db->get()->result_array();
                            
                $this->db->select('*');
                $this->db->from('tbl_variant');
                $this->db->where('model_id',$vs['model_id']);
            	$result['autoload']['variant_'.($au+1)] = $this->db->get()->result_array();
			}

		}

		
		$this->db->select('*');
		$this->db->from('tbl_make');
		$this->db->where('is_active',1);
		$result['make'] = $this->db->get()->result_array();
		
		return $result;
	}
	public function getModelByMake($make_id) {
		$this->db->select('*');
		$this->db->from('tbl_model');
		$this->db->where('make_id',$make_id);
		return $this->db->get()->result_array();
	}
	public function getVariantByModel($model_id) {
		$this->db->select('*');
		$this->db->from('tbl_variant');
		$this->db->where('model_id',$model_id);
		return $this->db->get()->result_array();
	}
}
