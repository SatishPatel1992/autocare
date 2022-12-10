<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SettingModel extends CI_Model {
    
    public function getsettings() {
        $result = array();
        $result['setting'] = array();
        $result['make_list'] = array();
        
        $this->db->select('*');
        $this->db->from('tbl_garage');
        $this->db->where('garage_id', $this->session->userdata['setting']->garage_id);
        $result['setting'] = $this->db->get()->row_array();

        $this->db->select('*');
        $this->db->from('tbl_payment_type');
        $result['payment_methods'] = $this->db->get()->result_array();
        
        $this->db->select('*');
        $this->db->from('tbl_make');
        $result['makes'] = $this->db->get()->result_array();
        
        // get service makes.
        $this->db->select('*');
        $this->db->from('tbl_users');
        $this->db->where('garage_id',$this->session->userdata['setting']->garage_id);
		$result['users'] = $this->db->get()->result_array();
        foreach ($result['make_list'] as $ml) {
            $result['make_id_selected'][] = $ml['make_id'];
        }
        // get state.
		$this->load->model('CountryStateCityModel');
		$result['states'] = $this->CountryStateCityModel->getState('101');
			
		// get state.
		$this->load->model('CountryStateCityModel');
		$result['city'] = $this->CountryStateCityModel->getCity($result['setting']['state']);
		
		$this->db->select("*");
		$this->db->from("tbl_template");
		$this->db->where("order_no",1);
		$this->db->where("tbl_template.garage_id",$_SESSION['setting']->garage_id);
		$result['template'] = $this->db->get()->row_array();

        return $result;
    }
    public function getIndianCurrencyInWords($number)
	{
		$decimal = round($number - ($no = floor($number)), 2) * 100;
		$hundred = null;
		$digits_length = strlen($no);
		$i = 0;
		$str = array();
		$words = array(0 => '', 1 => 'One', 2 => 'Two',
			3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
			7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
			10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
			13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
			16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
			19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
			40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
			70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
		$digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
		while( $i < $digits_length ) {
			$divider = ($i == 2) ? 10 : 100;
			$number = floor($no % $divider);
			$no = floor($no / $divider);
			$i += $divider == 10 ? 1 : 2;
			if ($number) {
				$plural = (($counter = count($str)) && $number > 9) ? 's' : null;
				$hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
				$str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
			} else $str[] = null;
		}
		$Rupees = implode('', array_reverse($str));
		return $Rupees;
	}
}
