<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class PackageModel extends CI_Model {
    
    public function getPackages() {
        $result = array();
        $this->db->select('*');
        $this->db->from('tbl_packages');
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
        $result['packages'] = $this->db->get()->result_array();

        return $result;
    }
    public function getPackageDetail() {
        $result = array();

        if(isset($_REQUEST['id'])) {
            $this->db->select('*');
            $this->db->from('tbl_packages');
            $this->db->where('package_id',base64_decode($_REQUEST['id']));
            $result['package'] = $this->db->get()->row_array();
        }
        $is_disc_applicable = 'N';
        $total_enable = 5;
        if(isset($result['packages']['is_disc_applicable']) && $result['packages']['is_disc_applicable'] == 'Y') {
                $total_enable++;
                $is_disc_applicable = 'Y';
        } else if(!isset($result['packages']['is_disc_applicable']) && $_SESSION['setting']->show_discount_column == 'Y') {
                $total_enable++; 
                $is_disc_applicable = 'Y';
        }
        $is_tax_applicable = 'N';
        if(isset($result['packages']['is_tax_applicable']) && $result['packages']['is_tax_applicable'] == 'Y') {
                $total_enable++;
                $is_tax_applicable = 'Y';
        } else if(!isset($result['packages']['is_tax_applicable']) && $_SESSION['setting']->gst_applicable == 'Y') {
                $total_enable++; 
                $is_tax_applicable = 'Y';
        }
        $result['total_enable'] = $total_enable;
        $result['is_tax_applicable'] = $is_tax_applicable;
        $result['is_disc_applicable'] = $is_disc_applicable;

        return $result;
    }
}
