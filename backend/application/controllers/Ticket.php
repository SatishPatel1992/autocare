<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ticket extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('TicketModel');
    }
    public function index() {
        $this->data = $this->TicketModel->getSettings();
        $this->render('ticket',NULL);
    }
    public function getTicketDetail() {
        $this->data = $this->TicketModel->getTicketDetail();
        $this->render('','json');
    }
    public function deleteTicket() {
        $this->data = $this->TicketModel->deleteTicket();
        $this->render('','json');
    }
}
