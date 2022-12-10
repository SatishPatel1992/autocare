<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Logout extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->session->sess_destroy();
        echo '<script>window.location = "../"</script>';
        //header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

}
