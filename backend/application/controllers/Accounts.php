<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Accounts extends MY_Controller {
    public $CI = NULL;
    public function __construct() {
        parent::__construct();
        if ($this->session->mobile_no == "") {
            echo '<script>window.location = "../"</script>';
        }
        $GLOBALS['title_right'] = 'Accounts';
        $this->load->model('AccountsModel');
        $this->CI = & get_instance();
    }
    public function index() {
        $this->data = $this->AccountsModel->getDropdownData();
        $this->render('accounts');
    }
    public function getAccountDetailsByID() {
        $this->data = $this->AccountsModel->getAccountDetailsByID();
        $this->render('accounts','json');
    }
    public function moneyFormatIndia($num1) {
        $num_array = explode('.', $num1);
        $num = $num_array[0];

        $explrestunits = "";
        if (strlen($num) > 3) {
            $lastthree = substr($num, strlen($num) - 3, strlen($num));
            $restunits = substr($num, 0, strlen($num) - 3);
            $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits;
            $expunit = str_split($restunits, 2);

            for ($i = 0; $i < sizeof($expunit); $i++) {
                if ($i == 0) {
                    if($expunit[$i] == '0-') {
                       $explrestunits .= '-'; 
                    } else {
                        $explrestunits .= (int) $expunit[$i] . ",";
                    }
                } else {
                    $explrestunits .= $expunit[$i] . ",";
                }
            }
            $thecash = $explrestunits . $lastthree;
        } else {
            $thecash = $num;
        }
        return ($thecash.'.00');
    }
}
