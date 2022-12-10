<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Booking extends MY_Controller {
    public function __construct() {
        parent::__construct();
        if ($this->session->mobile_no == "") {
            echo '<script>window.location = "../"</script>';
        }
        $GLOBALS['title_right'] = "Jobcards";
        $this->load->model('BookingModel');
        $this->load->model('CustomerModel');
    }
    public function index() {
        $this->data = $this->BookingModel->getJobcards();
        $this->render('booking');
    }
    public function GetBookings() {
        $this->data = $this->BookingModel->GetBookingsEvents();
    }
    public function getcustomerByRegNo() {
        $this->data = $this->BookingModel->getcustomerByRegNo($_REQUEST['term']);
        $this->render('','json');
    }
    public function BookingView() {
        $GLOBALS['title_right'] = "Jobcard";
        $this->data = $booking_view = $this->BookingModel->getBookingDetail();
        $this->render('booking_view');
    }
    public function getpackageDetail() {
        $this->data = $booking_view = $this->BookingModel->getSelectedPackageDetail();
        $this->render('','json');
    }
    public function getcustomerByMobileNo() {
        $this->data = $this->BookingModel->getcustomerByMobileNo();
        $this->render('','json');
    }
    public function FindCustomer() {
        $this->data = $this->BookingModel->FindCustomer();
	    $this->render('','json');
    }
    public function getCustomerHistoryData() {
        $this->data = $this->BookingModel->getCustomerHistoryData();
	    $this->render('','json');
    }
    public function getInventorybyName() {
        $this->data = $this->BookingModel->getInventorybyName();
    }
    public function getItemByName() {
	$this->data = $this->BookingModel->getItems();
	$this->render('','json');
    }
    public function getItemDetail() {
        $this->data = $this->BookingModel->getItemDetail();
	$this->render('','json');
    }
    public function viewInvoicePdf() {
        $this->BookingModel->generatePdf(base64_decode($_REQUEST['job_id']));
    }
    public function viewInsuranceInvoicePdf() {
        $this->BookingModel->viewInsuranceInvoicePdf(base64_decode($_REQUEST['job_id']));
    }
    public function viewCustomerInvoicePdf() {
        $this->BookingModel->viewCustomerInvoicePdf(base64_decode($_REQUEST['job_id']));
    }
    public function viewEstimatePdf() {
        $this->BookingModel->generateEstimate(base64_decode($_REQUEST['job_id']));
    }
    public function viewJobcardPdf() {
        $this->BookingModel->generateRepairOrder(base64_decode($_REQUEST['job_id']));
    }
    
    // public function viewInvoice() {
    //     $this->BookingModel->generateInvoicePdf(base64_decode($_REQUEST['bk_id']));
    // }
    public function getdetailforpo() {
        $this->data = $this->BookingModel->getdetailforpo();
        $this->render('','json');
    }
    public function viewpopdf() {
        $this->BookingModel->viewpoPdf(base64_decode($_REQUEST['po_id']));
    }
    public function gettemplate() {
        $this->data = $this->BookingModel->gettemplate();
        $this->render('','json');
    }
    public function getcustomerdetail() {
        $this->data = $this->CustomerModel->getCustomerData($_REQUEST['customer_id']);
        $this->render('','json');
    }
    public function getPODetailsByPOID() {
        $this->data = $this->BookingModel->getPODetailsByPOID($_REQUEST['po_id']);
        $this->render('','json');
    }
    public function getItemListByItemSearch() {
        $this->data = $this->BookingModel->getItems();
        $this->render('','json');
    }
    public function getVendorInvoiceDetail() {
        $this->data = $this->BookingModel->getVendorInvoiceDetail($_REQUEST['invoice_id']);
        $this->render('','json');
    }
    public function sendCommunication() {
        $this->data = $this->BookingModel->sendCommunication();
        $this->render('','json');
    }

}
