<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Packages extends MY_Controller {
    public function __construct() {
        parent::__construct();
        if ($this->session->mobile_no == "") {
            echo '<script>window.location = "../"</script>';
        }
        $this->load->model('PackageModel');
    }
    public function index() {
        $GLOBALS['title_right'] = "Packages";
        $this->data =  $this->PackageModel->getPackages();
        $this->render('packages');
    }
    public function addPackage() {
        $GLOBALS['title_right'] = "Add Package";
        $this->data =  $this->PackageModel->getPackageDetail();
        $this->render('add_package');
    }
    public function savePackage() {
        $this->data =  $this->PackageModel->savePackage();
    }
}
?>
