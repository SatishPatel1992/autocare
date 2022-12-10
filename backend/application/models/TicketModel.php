<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TicketModel extends CI_Model {
    
    public function getTicketDetail() {
        $result = array();
        
        $this->db->select('*');
        $this->db->from('tbl_ticket');
        $this->db->where('ticket_id',$_REQUEST['ticket_id']);
        $result['ticketData'] = $this->db->get()->row_array();

        return $result;
    }
    public function deleteTicket() {
        $this->db->where('ticket_id',$_REQUEST['ticket_id']);
        $this->db->delete('tbl_ticket');
    }
}
