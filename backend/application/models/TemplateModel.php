<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TemplateModel extends CI_Model {
    
    public function getTemplates() {
        $this->db->select('*');
        $this->db->from('tbl_template');
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
        $this->db->order_by('order_no');
        $result['templates'] = $this->db->get()->result_array();
        return $result;
    }
    public function getTemplateById() {
        $this->db->select('*');
        $this->db->from('tbl_template');
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
        $this->db->where('template_id',  base64_decode($_REQUEST['id']));
        $result['template'] = $this->db->get()->row_array();
        return $result;
    }
    
}
