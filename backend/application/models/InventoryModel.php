<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class InventoryModel extends CI_Model {
    public function getAllParts() { 
		$result = array();
		$this->db->select('tbl_items.*');
		$this->db->from('tbl_items');
		$this->db->where('tbl_items.garage_id',$_SESSION['setting']->garage_id);
		$result['items'] = $this->db->get()->result_array();

		return $result;
	}
	public function getInventortData() {
		$result['in_stocks'] = array();
		$result['orders'] = array();
		
		$this->db->select('tbl_items.*,sum(tbl_inventory.qty) as current_stock');
		$this->db->from('tbl_items');
		$this->db->join('tbl_inventory','tbl_inventory.product_id=tbl_items.item_id','left');
		$this->db->where('tbl_items.garage_id',$_SESSION['setting']->garage_id);
		$this->db->group_by('tbl_items.item_id');
		$result['items'] = $this->db->get()->result_array();

		$this->db->select('tbl_vendor_bills.*,tbl_vendor.company_name');
		$this->db->from('tbl_vendor_bills');
		$this->db->join('tbl_vendor','tbl_vendor.vendor_id=tbl_vendor_bills.vendor_id','left');
		$this->db->where('tbl_vendor_bills.garage_id',$_SESSION['setting']->garage_id);
		// $this->db->group_by('tbl_items.item_id');
		$result['orders'] = $this->db->get()->result_array();

		$this->db->select('*');
        $this->db->from('tbl_vendor');
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
		$result['vendor'] = $this->db->get()->result_array();

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
		$colspan = 4;
        if($_SESSION['setting']->show_discount_column == 'Y') {
            $colspan++; 
            $is_disc_applicable = 'Y';
        }
        $is_tax_applicable = 'N';
        if($_SESSION['setting']->gst_applicable == 'Y') {
            $colspan++; 
        	$is_tax_applicable = 'Y';
        }

		$result['colConfig'] = array('colspan' => $colspan,'is_tax_applicable' => $is_tax_applicable,'is_disc_applicable' => $is_disc_applicable);

		return $result;
	}
	public function getsavedData() {
		$result = array();
		
		$this->db->select('*');
		$this->db->from('tbl_vendor');
		$this->db->where('garage_id',$_SESSION['setting']->garage_id);
		$result['vendor'] = $this->db->get()->result_array();
		
		$this->db->select('*');
		$this->db->from('tbl_tax_rate');
		$this->db->where('garage_id',$_SESSION['setting']->garage_id);
		$this->db->where('is_active',1);
		$result['taxes'] = $this->db->get()->result_array();
				
		$item_id = isset($_REQUEST['id']) ? base64_decode($_REQUEST['id']) : '';
		
		if($item_id != "") {
			$this->db->select('tbl_items.*,tbl_tax_rate.HSN,tbl_tax_rate.rate');
			$this->db->from('tbl_items');
			$this->db->join('tbl_tax_rate','tbl_tax_rate.tax_id = tbl_items.tax_id','left');
			$this->db->where('item_id',$item_id);
			$result['part'] = $this->db->get()->row_array();

			if($result['part']['applicable_to'] == 'spec') {
				$this->db->select('model_id');
				$this->db->from('tbl_items_applicable_to');
				$this->db->where('item_id',$item_id);
				$parts_applicable = $this->db->get()->result_array();

				foreach ($parts_applicable as $key => $value) {
					$result['parts_applicable'][] = $value['model_id'];
				}
			}
			$current_stock = $this->getStockQty($item_id);

			if(!empty($current_stock['stocks_inventory'])) {
				$result['part']['current_stock'] = $current_stock['stocks_inventory']['current_qty'];
			}
		}

		$this->db->select('tbl_make.make_id,tbl_make.name as make_name,tbl_model.name as model_name,tbl_model.model_id');
		$this->db->from('tbl_make');
		$this->db->join('tbl_model','tbl_model.make_id=tbl_make.make_id','left');
		$this->db->where('tbl_make.garage_id', $_SESSION['setting']->garage_id);
		$make_model = $this->db->get()->result_array();

		foreach($make_model as $k => $v) {
			$result['make_model'][$v['make_id']][] = $v;
		}
		
		return $result;
		}
        public function getserviceData() {
            $result = array();
            $this->db->select('*');
            $this->db->from('tbl_category');
            $this->db->where('garage_id',$_SESSION['setting']->garage_id);
            $result['category'] = $this->db->get()->result_array();
		
            $this->db->select('*');
            $this->db->from('tbl_tax_rate');
			$this->db->where('garage_id',$_SESSION['setting']->garage_id);
			$this->db->where('is_active',1);
            $result['taxes'] = $this->db->get()->result_array();
		
            // $this->db->select('*');
            // $this->db->from('tbl_discount');
            // $this->db->where('garage_id',$_SESSION['setting']->garage_id);
            // $result['discount'] = $this->db->get()->result_array();
            
            $service_id = isset($_REQUEST['id']) ? base64_decode($_REQUEST['id']) : '';
            $this->db->select('*');
            $this->db->from('tbl_services');
            $this->db->where('service_id',$service_id);
            $result['service'] = $this->db->get()->row_array();
            
            return $result;
        }
        public function getStockQty($item_id="") {
			$result = array();

			$this->db->select('sum(qty) as current_qty');
			$this->db->from('tbl_inventory');
			$this->db->where('product_id',$item_id);
			$this->db->where('garage_id',$_SESSION['setting']->garage_id);
			$result['stocks_inventory'] = $this->db->get()->row_array();

			return $result;
		}
		public function getVendorOrderDetail() {
			$result = array();
			$this->db->select('tbl_vendor_bills.status as po_status,tbl_items.tax_type as part_tax_type,tbl_items.margin_type as part_margin_type,tbl_items.margin_value as part_margin_value,tbl_tax_rate.rate,,tbl_vendor.vendor_id,tbl_vendor_bills.status,tbl_vendor.payment_term,DATE_FORMAT(tbl_vendor_bills.order_date,"%d-%m-%Y") as order_date,tbl_vendor_bills.vendor_bill_no,tbl_vendor_bill_item.*');
			$this->db->from('tbl_vendor_bill_item');
			$this->db->join('tbl_vendor_bills','tbl_vendor_bills.po_id=tbl_vendor_bill_item.po_id','left');
			$this->db->join('tbl_items','tbl_items.item_id=tbl_vendor_bill_item.item_id','left');
			$this->db->join('tbl_tax_rate','tbl_tax_rate.tax_id=tbl_items.tax_id','left');
			$this->db->join('tbl_vendor','tbl_vendor.vendor_id=tbl_vendor_bills.vendor_id','left');
			$this->db->where('tbl_vendor_bill_item.po_id',$_REQUEST['po_id']);
			$this->db->order_by('tbl_vendor_bill_item.po_id');
			$result['po_details'] = $this->db->get()->result_array();

			$result['common_data'] = array();
			if($result['po_details'] && count($result['po_details']) > 0) {
				$po_status = $result['po_details'][0]['po_status'];
				foreach($result['po_details'] as $k => $v) {
					if($po_status == 'close') {
						$result['common_data'][$v['po_item_id']]['margin_type'] = $v['margin_type'];
						$result['common_data'][$v['po_item_id']]['margin_value'] = $v['margin_value'];
						$result['common_data'][$v['po_item_id']]['tax_type'] = $v['tax_type'];
						$result['common_data'][$v['po_item_id']]['tax_rate'] = $v['tax_rate'];
					} else {
						$result['common_data'][$v['po_item_id']]['margin_type'] = $v['part_margin_type'];
						$result['common_data'][$v['po_item_id']]['margin_value'] = $v['part_margin_value'];
						$result['common_data'][$v['po_item_id']]['tax_type'] = $v['part_tax_type'];
						$result['common_data'][$v['po_item_id']]['tax_rate'] = $v['rate'];
					}
				}
			}


			$result['payment_terms'] = array();

			if ($result['po_details'] && !empty($result['po_details'])) {
				if($result['po_details'][0]['payment_term'] != "" && $result['po_details'][0]['payment_term'] != 0) {
					$result['payment_terms'] = $result['po_details'][0]['payment_term'];
				} else {
					$result['payment_terms'] = '';
				}
			}

			$this->db->select('*');
			$this->db->from('tbl_payment_type');
			$result['payment_type'] = $this->db->get()->result_array();
			return $result;
		}
}
