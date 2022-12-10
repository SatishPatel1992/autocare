<?php
        $from = $this->config->item('smtp_user');
        //Load email library
        $this->load->library('email');
        $this->email->from($from, 'Make My Repair');

    $templates = array();
    $this->db->select('*');
    $this->db->from('tbl_email_sms_buffer');
    $this->db->where('sent_status','pending');
    $templates = $this->db->get()->result_array();
        
    if($templates && !empty($templates)) {
        foreach($templates as $k => $v) {
                if($v['email_id'] != "") {
                    $this->email->to($v['email_id']);
                    $this->email->subject($v['email_subject']);
                    $this->email->message($v['email_body']);
                    //Send mail
                    if($this->email->send()) {
                        $this->db->where('buffer_id',$v['buffer_id']);
                        $this->db->update('tbl_email_sms_buffer', array('sent_status' => 'sent'));
                    }                        
           }
        }
    }
?>