<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Upload extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('UploadModel');
    }

    public function uploadEditorImage() {
        $garage_id = $_SESSION['setting']->garage_id;
        $this->data = $this->UploadModel->fileupload('EditorImages', 'garage_' . $garage_id);
        $this->render('','json');
    }
}
?>
