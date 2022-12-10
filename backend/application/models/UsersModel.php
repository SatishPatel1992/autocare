<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UsersModel extends CI_Model {
    
    public function getAllUser() {
        $result = array();
        $this->db->select('*');
        $this->db->from('tbl_users');
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
        $result['users'] = $this->db->get()->result_array();
        return $result;
    }
    public function getUserByUserId() {
        $user_id = isset($_REQUEST['id']) ? base64_decode($_REQUEST['id']) : '';
        $result = array();
        
        //Menu..
        $this->db->select('*,CASE WHEN parent_id = 0 THEN menu_id ELSE parent_id END AS Sort',false);
        $this->db->from('tbl_menu');
        $this->db->where('is_active',1);
        $this->db->order_by('Sort,menu_id');
        $result['menus'] = $this->db->get()->result_array();
        
        //user Role.
        $this->db->select('*');
        $this->db->from('tbl_roles');
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
        $result['roles'] = $this->db->get()->result_array();
        
        $this->db->select('*');
        $this->db->from('tbl_users');
        $this->db->where('user_id',$user_id);
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
        $result['user'] = $this->db->get()->row_array();
        
        $this->db->select('max(employee_no) as employee_no');
        $this->db->from('tbl_users');
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
        $maxEmpNo = $this->db->get()->row_array();
        
        if($maxEmpNo['employee_no'] == NULL) {
           $result['emp_no'] = array('default_emp_no' => 1001);
        } else {
           $result['emp_no'] = array('default_emp_no' => $maxEmpNo['employee_no'] + 1);
        }
        
        $this->db->select('*');
        $this->db->from('tbl_menu_rights');
        $this->db->where('user_id',$user_id);
        $this->db->where('is_active',1);
        $menu_rights = $this->db->get()->result_array();
        
        foreach ($menu_rights as $k => $v) {
            $result['menu_rights'][$v['menu_id']] = $v;
        }
        
        return $result;
    }
    public function getMenuRights($role_id) {
        $userRole = array();
        $result = array();
        
        $this->db->select('*');
        $this->db->from('tbl_users');
        $this->db->where('role_id',$role_id);
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
        $this->db->where('is_default_rights','Y');
        $userRole = $this->db->get()->row_array();
            
        if(isset($userRole['is_default_rights']) && $userRole['is_default_rights'] == 'Y') {
            $this->db->select('*');
            $this->db->from('tbl_menu_rights');
            $this->db->where('user_id',$userRole['user_id']);
            $this->db->where('is_active',1);
            $result['default_menu_rights'] = $this->db->get()->result_array();
        }
        
        return $result;
    }
}
