<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ServiceReminder extends MY_Controller {
    public function __construct() {
        parent::__construct();
        $GLOBALS['title_right'] = "Service Reminders";
        if ($this->session->mobile_no == "") {
            echo '<script>window.location = "../"</script>';
        }
        $this->load->model('ServiceReminderModel');
    }
    public function index() {
        $this->data = $this->ServiceReminderModel->getReminders();
        $this->render('service_reminder');
    }
    public function filterReminder() {
        $this->data = $this->ServiceReminderModel->getReminders();
        $this->render('','json');
    }
}
?>
