<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MysqlModel extends CI_Model {
    
    public function getData($tableName,$conditionArray,$fieldName,$joinArray,$noofrecord) {
        $this->db->select($fieldName);
        $this->db->from($tableName);
        if(!empty($joinArray)) {
            foreach ($joinArray as $join) {
                $this->db->join($join['join_table'],$join['join_on'],$join['join_type']);
            }
        }
        if(!empty($conditionArray)) {
            foreach ($conditionArray as $col_name => $col_value) {
                $this->db->where($col_name,$col_value);
            }
        }
        if($noofrecord == 1) {
            $this->db->get()->row_array(); 
        } else {
            $this->db->get()->result_array();
        }
        echo $this->db->last_query();exit;
    }
}
