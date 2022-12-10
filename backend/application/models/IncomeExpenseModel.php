<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IncomeExpenseModel extends CI_Model {

    public function getData() {
        $result = array();
        $this->db->select('*');
        $this->db->from('tbl_category');
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
        $this->db->order_by('head_type');
        $result['category'] = $this->db->get()->result_array();

        return $result;
    }
    public function getHeadByID() {
        $result = array();
        $this->db->select('*');
        $this->db->from('tbl_category');
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
        $this->db->where('category_id',base64_decode($_REQUEST['id']));
        $this->db->order_by('head_type');
        $result['head'] = $this->db->get()->row_array();
        return $result;
    }
}
