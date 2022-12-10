<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template extends MY_Controller {
 
  function __construct() {
    parent::__construct();
    if($this->session->mobile_no == "") {
        echo '<script>window.location = "../"</script>';
    }
    $this->load->model('TemplateModel');
  }
  public function index() {
      $GLOBALS['title_right'] = "Template";
      $this->data = $this->TemplateModel->getTemplates();
      $this->render('template');
  }
  public function addTemplate() {
      $GLOBALS['title_right'] = "Add Template";
      $this->data = $this->TemplateModel->getTemplateById();
      $this->render('add_template');
  }
  public function imageUpload() {
      print_r($_FILES);
  }

}