<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Review extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $GLOBALS['title_right'] = "Review";
        if ($this->session->mobile_no == "") {
            echo '<script>window.location = "../"</script>';
        }
        $this->load->model('ReviewModel');
    }
    public function index() {
        $this->data = $this->ReviewModel->getReviews();
        $this->render('review');
    }
    public function getReviewById() {
        $this->data = $this->ReviewModel->getReviewByID($_REQUEST['id']);
        $this->render('','json');
    }
}
