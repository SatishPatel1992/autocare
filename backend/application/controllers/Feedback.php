<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feedback extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('FeedbackModel');
    }
    public function index() {
        $this->data = $this->FeedbackModel->getQuestions();
        $this->render('feedback',NULL);
    }
}
