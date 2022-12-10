<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReviewModel extends CI_Model {

    public function getReviews() {
        $result = array();

        $this->db->select('tbl_feedback_received.*,name,reg_no,jobcard_no,');
        $this->db->from('tbl_feedback_received');
        $this->db->join('tbl_jobcard','tbl_jobcard.jobcard_id=tbl_feedback_received.jobcard_id','left');
        $this->db->join('tbl_customer','tbl_customer.customer_id=tbl_jobcard.customer_id','left');
        $this->db->join('tbl_customer_vehicle','tbl_customer_vehicle.vehicle_id=tbl_jobcard.vehicle_id','left');
        $this->db->where('tbl_feedback_received.garage_id',$_SESSION['setting']->garage_id);
        $result['feedbacks'] = $this->db->get()->result_array();

        return $result;
    }
    public function getReviewByID($id) {
        $result = array();

        $this->db->select('*');
        $this->db->from('tbl_feedback_received');
        $this->db->join('tbl_feedback_answers','tbl_feedback_answers.received_id=tbl_feedback_received.receive_id','left');
        $this->db->join('tbl_feedback_questions','tbl_feedback_questions.question_id=tbl_feedback_answers.question_id','left');
        $this->db->where('tbl_feedback_received.receive_id',$id);
        $result['feedback'] = $this->db->get()->result_array();

        return $result;
    }
}
