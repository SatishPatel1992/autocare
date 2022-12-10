<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FeedbackModel extends CI_Model {
    
    public function getQuestions() {
        $result = array();

        $this->db->select('tbl_jobcard.*,tbl_garage.name as garage_name,tbl_feedback_questions.*');
        $this->db->from('tbl_feedback_questions');
        $this->db->join('tbl_jobcard','tbl_jobcard.garage_id=tbl_feedback_questions.garage_id','left');
        $this->db->join('tbl_garage','tbl_garage.garage_id=tbl_feedback_questions.garage_id','left');
        $this->db->where('tbl_jobcard.jobcard_id',base64_decode($_REQUEST['id']));
        $result['questions'] = $this->db->get()->result_array();
        
        return $result;
    }
}
