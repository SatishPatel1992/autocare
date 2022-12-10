<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends MY_Controller {

    public function __construct() {
        parent::__construct();

    }
    public function CommonFunc() {
        if ($this->session->mobile_no == "") {
            echo '<script>window.location = "../"</script>';
        }
        $returnData = '';
        $message = '';
        if($_REQUEST['do'] == 'delete_jobcard') {
            if(isset($_REQUEST['jobcard_id']) && $_REQUEST['jobcard_id'] !="") {
                // delete jobcard.
                $job_id = base64_decode($_REQUEST['jobcard_id']);

                // check if any invoice generated.
                $invoices = array();
                $this->db->select('invoice_id,customer_id');
                $this->db->from('tbl_invoices');
                $this->db->where('item_id',$job_id);
                $this->db->where('item_type','job_invoice');
                $invoices = $this->db->get()->row_array();
                
                $this->db->where('jobcard_id',$job_id);
                $this->db->delete('tbl_jobcard');

                $this->db->where('jobcard_id',$job_id);
                $this->db->delete('tbl_jobcard_item');

                $this->db->where('item_id',$job_id);
                $this->db->where('item_type','job_invoice');
                $this->db->delete('tbl_invoices');

                if($invoices && !empty($invoices)) {
                    $this->db->where('item_id',$invoices['invoice_id']);
                    $this->db->where('customer_id',$invoices['customer_id']);
                    $this->db->delete('tbl_transaction');
                }

                $this->db->where('job_id',$job_id);
                $this->db->delete('tbl_service_reminder');

                $message = 'Jobcard deleted successfully !';
            }
        } else if($_REQUEST['do'] == 'change_jobcard_status') {
            $cond = array('jobcard_id' => base64_decode($_REQUEST['jobcard_id']));
            $this->db->update('tbl_jobcard',array('status' => $_REQUEST['status']),$cond);
        } else if($_REQUEST['do'] == 'get_stock_details') {
            $this->db->select('product_id,sum(tbl_inventory.qty) as total_qty');
            $this->db->from('tbl_inventory');
            $this->db->where('product_id',$_REQUEST['item_id']);
            $current_stocks = $this->db->get()->row_array();
         
            $this->db->select('*');
            $this->db->from('tbl_items');
            $this->db->where('item_id',$_REQUEST['item_id']);
            $item_details = $this->db->get()->row_array();
            
            $this->db->query("set @csum := 0;");
            $this->db->select('*,date_format(date,"%d-%m-%Y") as date,(@csum := @csum + qty) as closing_balance');
            $this->db->from('tbl_inventory');
            $this->db->where('product_id',$_REQUEST['item_id']);
            $inventory = $this->db->get()->result_array();
            
            $returnData = array('inventory' => $inventory,'current_stocks' => $current_stocks,'item_details' => $item_details);
        } else if($_REQUEST['do'] == 'get_template_body') {
            $this->db->select();
            $this->db->from('tbl_template');
            $this->db->where('template_id',$_REQUEST['id']);
            $returnData = $this->db->get()->row_array();
        } else if($_REQUEST['do'] == 'send_sms_to_customer') {
            $this->db->select('*');
            $this->db->from('tbl_template');
            $this->db->where('garage_id',$_SESSION['setting']->garage_id);
            $this->db->where('order_no', $_REQUEST['order_no']);
            $data = $this->db->get()->row_array();
            
            $this->load->model('BookingModel');
            $idArray['jobcard_id'] = $_REQUEST['job_id'];
            $idArray['order_no'] = $_REQUEST['order_no'];
            $idArray['message'] = $data['sms_body'];
            $idArray['isSave'] = 'Y';
            $returnData = $this->BookingModel->convertMessage($idArray);
        } else if($_REQUEST['do'] == 'send_to_customer_whatsapp') {
            $this->db->select('*');
            $this->db->from('tbl_template');
            $this->db->where('garage_id',$_SESSION['setting']->garage_id);
            $this->db->where('order_no', $_REQUEST['order_no']);
            $data = $this->db->get()->row_array();

            $this->load->model('BookingModel');
            $idArray['jobcard_id'] = $_REQUEST['job_id'];
            $idArray['order_no'] = $_REQUEST['order_no'];
            $idArray['message'] = $data['whatsapp_body'];
            $returnData = $this->BookingModel->convertMessage($idArray);
        } else if($_REQUEST['do'] == 'send_ledger_to_whatsapp') {
            include_once('assets/Shortener.php');
            $shortener = new Shortener();
            $link = FRONT_ROOT.$shortener->urlToShortCode(BASE_URL.'reportViewer/view'.$_REQUEST['param'].'&rtype=cstrlgr');
            $returnData = "View your ledger with ".$_SESSION['setting']->name." here: ".$link;
        } else if($_REQUEST['do'] == 'get_package_detail') {
            $this->db->select('tbl_package_item.*,tbl_items.item_name,tbl_items.purchase_price,tbl_items.purchase_price_tax');
            $this->db->from('tbl_package_item');
            $this->db->join('tbl_items','tbl_items.item_id=tbl_package_item.item_id','left');
            $this->db->where('package_id',base64_decode($_REQUEST['id']));
            $returnData = $this->db->get()->result_array();
        } else if($_REQUEST['do'] == 'get_job_item') {
            $this->db->select('tbl_jobcard.customer_id,tbl_jobcard.vehicle_id,tbl_jobcard_item.*,tbl_items.item_name,tbl_items.purchase_price,tbl_items.purchase_price_tax,tbl_items.sell_price_tax,tbl_items.sell_price');
            $this->db->from('tbl_jobcard_item');
            $this->db->join('tbl_jobcard','tbl_jobcard.jobcard_id=tbl_jobcard_item.jobcard_id','left');
            $this->db->join('tbl_items','tbl_items.item_id=tbl_jobcard_item.item_id','left');
            $this->db->where('tbl_jobcard_item.jobcard_id',base64_decode($_REQUEST['id']));
            $jobItems = $this->db->get()->result_array();
            
            $customerData = array();
            if($jobItems && $jobItems[0]['customer_id']) {
                $this->db->select('tbl_make.make_id,tbl_make.name as make_name,tbl_model.model_id,tbl_model.name as model_name,tbl_customer_vehicle.*,tbl_customer.customer_id,tbl_customer.mobile_no,tbl_customer.email,tbl_customer.name,tbl_customer.billing_address,tbl_customer.gst_no,tbl_customer_vehicle.reg_no,tbl_customer_vehicle.vehicle_id,(SUM(IF(transaction_type = "customer_invoice", amount, 0)) - SUM(IF(transaction_type = "customer_payment", amount, 0))) AS "balance"');
                $this->db->from('tbl_customer_vehicle');
                $this->db->join('tbl_customer','tbl_customer.customer_id=tbl_customer_vehicle.customer_id','left');
                $this->db->join('tbl_transaction','tbl_transaction.customer_id=tbl_customer.customer_id','left');
                $this->db->join('tbl_make','tbl_customer_vehicle.make_id=tbl_make.make_id','left');
                $this->db->join('tbl_model','tbl_customer_vehicle.model_id=tbl_model.model_id','left');
                $this->db->where('tbl_customer.customer_id', $jobItems[0]['customer_id']);
                $this->db->where('tbl_customer_vehicle.vehicle_id', $jobItems[0]['vehicle_id']);
                $this->db->group_by('tbl_customer_vehicle.vehicle_id');
                $customerData = $this->db->get()->row_array();
            }
            $returnData = array('job_items' => $jobItems,'customer_data' => $customerData);
        }  else if($_REQUEST['do'] == 'get_bill_item') {
            $this->db->select('tbl_vendor_bill_item.*,tbl_vendor_bills.*,tbl_items.item_type,tbl_items.item_name');
            $this->db->from('tbl_vendor_bill_item');
            $this->db->join('tbl_vendor_bills','tbl_vendor_bills.po_id=tbl_vendor_bill_item.po_id','left');
            $this->db->join('tbl_items','tbl_items.item_id=tbl_vendor_bill_item.item_id','left');
            $this->db->where('tbl_vendor_bill_item.po_id',base64_decode($_REQUEST['id']));
            $BillItems = $this->db->get()->result_array();
            //echo $this->db->last_query();
            
            $returnData = array('bill_items' => $BillItems);
        } else if($_REQUEST['do'] == 'get_po_details') {
            $this->db->select('tbl_vendor_bills.*,tbl_vendor_bill_item.*,tbl_items.item_name');
            $this->db->from('tbl_vendor_bill_item');
            $this->db->join('tbl_vendor_bills','tbl_vendor_bills.po_id=tbl_vendor_bill_item.po_id','left');
            $this->db->join('tbl_items','tbl_items.item_id=tbl_vendor_bill_item.item_id','left');
            $this->db->where('tbl_vendor_bill_item.po_id',base64_decode($_REQUEST['po_id']));
            $poDetails = $this->db->get()->result_array();
            $this->db->select('sum(amount) as total_paid');
            $this->db->from('tbl_transaction');
            $this->db->where('item_id',base64_decode($_REQUEST['po_id']));
            $this->db->where('transaction_type','bill_payment');
            $this->db->where('vendor_id',$poDetails[0]['vendor_id']);
            $paid_details = $this->db->get()->row_array();
            $returnData = array('data' => $poDetails,'grand_total'=> $poDetails[0]['grand_total'],'paid' => $paid_details['total_paid']);
        } else if($_REQUEST['do'] == 'get_payment_details') {
            $this->db->select('*');
            $this->db->from('tbl_vendor_bills');
            $this->db->where('po_id',base64_decode($_REQUEST['po_id']));
            $vendor = $this->db->get()->row_array();

            $this->db->select('sum(amount) as total_paid');
            $this->db->from('tbl_transaction');
            $this->db->where('item_id',base64_decode($_REQUEST['po_id']));
            $this->db->where('vendor_id',$vendor['vendor_id']);
            $this->db->where('transaction_type','bill_payment');
            $payments = $this->db->get()->row_array();

            $returnData = array('payable' => $vendor['grand_total'],'paid'=> $payments['total_paid'] !=null ? $payments['total_paid'] : 0,'balance' => $vendor['grand_total'] - $payments['total_paid']);
        } else if($_REQUEST['do'] == 'get_due_reminders') {
            $this->db->select('date_format(reminder_date,"%d-%m-%Y") as date_of_reminder,date_format(service_date,"%d-%m-%Y") as date_of_service,tbl_service_reminder.*,name as cust_name,reg_no,email,mobile_no');
            $this->db->from('tbl_service_reminder');
            $this->db->join('tbl_customer','tbl_customer.customer_id=tbl_service_reminder.customer_id','left');
            $this->db->join('tbl_customer_vehicle','tbl_customer_vehicle.vehicle_id=tbl_service_reminder.vehicle_id','left');
            $this->db->where('tbl_service_reminder.garage_id',$_SESSION['setting']->garage_id);
            $where = 'reminder_date between "'.date('Y-m-d',strtotime($_REQUEST['start'])).'" and "'.date('Y-m-d',strtotime($_REQUEST['end'])).'"';
            $this->db->where($where);
            $returnData = $this->db->get()->result_array();
        } else if($_REQUEST['do'] == 'getModelNameByMakeID') {
            $this->db->select('*');
            $this->db->from('tbl_model');
            $this->db->where('make_id',$_REQUEST['make_id']);
            $returnData = $this->db->get()->result_array();
        } else if($_REQUEST['do'] == 'getVendorBillDetails') {            
            $vendor_bills = $this->db->query("select tbl_vendor_bills.*,tbl_vendor.company_name from tbl_vendor_bills left join tbl_vendor on tbl_vendor.vendor_id = tbl_vendor_bills.vendor_id  where tbl_vendor_bills.vendor_id = '".$_REQUEST['vendor_id']."' and (status = 'unpaid' or status='partial_paid')")->result_array();
            $payDetails = array();
            
            foreach($vendor_bills as $vb) {
                if($vb['status'] == 'partial_paid') {
                   $vpayment = $this->db->query("select sum(amount) as total_paid from tbl_payments where vendor_id = '".$vb['vendor_id']."' and item_id ='".$vb['po_id']."' and item_type='vendor_payment'")->row_array();
                   $vb['paid'] = $vpayment['total_paid'] != NULL ? $vpayment['total_paid'] : 0;
                   $vb['payable'] = $vb['grand_total'] - $vpayment['total_paid'];
                } else {
                   $vb['paid'] = 0;
                   $vb['payable'] = $vb['grand_total'];
                }

                $payDetails[] = $vb;
            }

            $transcation_bills = $this->db->query("select tbl_transaction.*,company_name from tbl_transaction left join tbl_vendor on tbl_vendor.vendor_id = tbl_transaction.vendor_id where tbl_transaction.vendor_id = '".$_REQUEST['vendor_id']."'")->result_array();

            foreach($transcation_bills as $tras) {
                $vpayment = $this->db->query("select sum(amount) as total_paid from tbl_payments where vendor_id = '".$tras['vendor_id']."' and item_id ='".$tras['transcation_id']."' and item_type='expense_payment'")->row_array();
                    if($vpayment['total_paid'] != NULL) {
                        $tras['paid'] =    $vpayment['total_paid'];
                        $tras['payable'] = $tras['amount'] - $vpayment['total_paid'];
                    } else {
                        $tras['paid'] = 0;
                        $tras['payable'] = $tras['amount'];
                    }
                    if($tras['payable'] != 0) {
                        $tras['grand_total'] = $tras['amount'];
                        $tras['invoice_date'] = date('d-m-Y',strtotime($tras['date']));
                        $payDetails[] = $tras;
                   }
            }

            $returnData = $payDetails;
        } else if($_REQUEST['do'] == 'get_reminder_body') {
            $this->db->select('*');
            $this->db->from('tbl_email_sms_buffer');
            $this->db->where('buffer_id',$_REQUEST['id']);
            $returnData = $this->db->get()->row_array();
        } else if($_REQUEST['do'] == 'get_reminder_sent_history') {
            $sent_start_date = date('Y-m-d',strtotime($_REQUEST['start']));
            $sent_end_date = date('Y-m-d',strtotime($_REQUEST['end']));
    
            $this->db->select('tbl_email_sms_buffer.*,name as customer_name,tbl_customer_vehicle.reg_no');
            $this->db->from('tbl_email_sms_buffer');
            $this->db->join('tbl_service_reminder','tbl_service_reminder.serv_remider_id = tbl_email_sms_buffer.reminder_id','left');
            $this->db->join('tbl_customer','tbl_customer.customer_id = tbl_service_reminder.customer_id','left');
            $this->db->join('tbl_customer_vehicle','tbl_customer_vehicle.vehicle_id = tbl_service_reminder.vehicle_id','left');
            $this->db->where('tbl_email_sms_buffer.garage_id',$_SESSION['setting']->garage_id);
            $sent_date_where = "date_format(email_sent_date,'%Y-%m-%d') between '".$sent_start_date."' and '".$sent_end_date."'";
            $this->db->where($sent_date_where);
            $sms_sent_date_where = "date_format(sms_sent_date,'%Y-%m-%d') between '".$sent_start_date."' and '".$sent_end_date."'";
            $this->db->where($sms_sent_date_where);
            $where = "email_sent_status = 'sent' OR sms_sent_status='sent'";
            $this->db->where($where);
            $returnData = $this->db->get()->result_array();
        } else if($_REQUEST['do'] == 'get_account_type') {
            if($_REQUEST['trans_type'] == 'bill') {
              $this->db->select('*');
              $this->db->from('tbl_accounts');
              $this->db->where_in('account_type',array(2));
              $this->db->get()->result_array();
            } else if($_REQUEST['trans_type'] == 'bill_payment') {

            } else if($_REQUEST['trans_type'] == 'bill_payment') { 
                
            }
        } else if($_REQUEST['do'] == 'get_account_summary') {
        $st_date = date('Y-m-d',strtotime($_REQUEST['st_date']));
        $ed_date = date('Y-m-d',strtotime($_REQUEST['ed_date']));
    
        $this->db->select('sum(amount) as total_amount,transaction_type');
        $this->db->from('tbl_transaction');
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
        $date_where = "tbl_transaction.date between '".$st_date."' and '".$ed_date."'";
        $this->db->where($date_where);
        $this->db->group_by('transaction_type');        
        $transactions = $this->db->get()->result_array();

        $total_paid = 0;
        $total_payable = 0;
        $total_recievable = 0;
        $total_recieve = 0;
        
        foreach($transactions as $t) {
            if($t['transaction_type'] == 'bill_payment') {
                $total_paid = $t['total_amount'];
            } else if($t['transaction_type'] == 'bill') {
                $total_payable = $t['total_amount'];
            } else if($t['transaction_type'] == 'customer_invoice') {
                $total_recievable = $t['total_amount'];
            } else if($t['transaction_type'] == 'customer_payment') {
                $total_recieve += $t['total_amount'];
            } else if($t['transaction_type'] == 'other_income') {
                $total_recieve += $t['total_amount'];
            } else if($t['transaction_type'] == 'other_expense') {
                $total_paid += $t['total_amount'];
            }
        }
        $total_profit =  $total_recieve - $total_paid;
        $total_upcoming = $total_recievable - $total_payable;

        $returnData = array('total_recieve' => $total_recieve,'total_recievable' => $total_recievable,'total_paid' => $total_paid,'total_payable' => $total_payable,'total_profit' => $total_profit,'total_upcoming' => $total_upcoming);
        } else if($_REQUEST['do'] == 'delete_customer') { 
            $this->db->where('customer_id',$_REQUEST['id']);
            $this->db->delete('tbl_customer');

            $this->db->where('customer_id',$_REQUEST['id']);
            $this->db->delete('tbl_customer_vehicle');
        }  else if($_REQUEST['do'] == 'delete_vendor') { 
            $this->db->where('vendor_id',$_REQUEST['id']);
            $this->db->delete('tbl_vendor');
        }
        
        echo json_encode(array('message' => $message, 'data' => $returnData));
        return false;
    }
    public function authentication() {
        if($_REQUEST && $_REQUEST['mobileNo'] != "" && $_REQUEST['password'] != "") {
            $query  = $this->db->select('*')->from('tbl_users')->where('mobile_no',$_REQUEST['mobileNo'])->get()->row();
            $settings = $this->db->select('*')->from('tbl_garage')->where('garage_id',$query->garage_id)->get()->row();
            if($query == "") {
                echo json_encode(array('status' => 1,'message' => 'Username not found.'));
            } else {
                $hashed_password = $query->password;
                $user_id  = $query->user_id;

                if(md5($_REQUEST['password']) == $hashed_password) {
                    if($query->expiry_date == "0000-00-00" || (strtotime($query->expiry_date) >= strtotime(date('Y-m-d')))) {
                        $token = crypt(substr( md5(rand()), 0, 7));
                        $this->db->where('user_id',$user_id);
                        $this->db->update('tbl_users',array('last_login'=>date('Y-m-d H:i:s'),'is_firsttime_login' => 'N'));
    
                        if($this->db->trans_status() === FALSE) {
                          echo json_encode(array('redirect'=> '','status' => 500,'message' => 'Internal server error.'));
                        } else {
                          $this->session->set_userdata('isloggedin', true);
                          $this->session->set_userdata('data', $query);
                          $this->session->set_userdata('setting', $settings);
                          $this->session->mobile_no = $query->mobile_no;
                          if($query->is_firsttime_login == 'Y') {
                              $redirect = 'backend/settings';
                          } else {
                              $redirect = 'backend/dashboard';
                          }
                          echo json_encode(array('redirect'=> $redirect,'status' => 200,'message' => 'Successfully login.','data' => $query, 'token' => $token,'setting' => $settings));
                        }
                    } else {
                          echo json_encode(array('redirect'=> '','status' => 500,'message' => 'Your account was expiry on '.date('d-m-Y',strtotime($query->expiry_date)).'. Please contact to +917359519628 for activating your account.'));
                    }
                } else {
                    echo json_encode(array('redirect'=> '','status' => 500,'message' => 'Please enter valid mobile No and Password.'));
                }
            }
        } else {
            echo json_encode(array('status'=> 500,'message' => 'Mobile no. and password are requires.', 'data' => ''));
        }
    }
    public function inquery() {
            unset($_REQUEST['PHPSESSID']);
            $_REQUEST['date'] = date('Y-m-d H:i:s');
            $this->db->insert('tbl_inquiry',$_REQUEST);
            // Account details
            $apiKey = urlencode('g7hGQO4ny20-gU7LMONSZZ7hrtN5RUf6tiivVBjptn');
	
            // Message details
            $numbers = urlencode('7359519628');
            $sender = urlencode('CARRPR');
            $message = rawurlencode('Inquiry received from '.$_REQUEST['contact_no']);
         
            // Prepare data for POST request
            $data = 'apikey=' . $apiKey . '&numbers=' . $numbers . "&sender=" . $sender . "&message=" . $message;
         
            // Send the GET request with cURL
            $ch = curl_init('https://api.textlocal.in/send/?' . $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
    }
    public function checkMobileNo() {
        if($_REQUEST['mobile_no'] && $_REQUEST['mobile_no'] != "") {
            $this->db->select('*');
            $this->db->from('tbl_users');
            $this->db->where('mobile_no',$_REQUEST['mobile_no']);
            $isExists = $this->db->get()->result_array();
            if(count($isExists) > 0) {
                echo json_encode(array('status'=> 500,'message' => 'Mobile '.$_REQUEST['mobile_no'].' is already register with us.', 'data' => ''));
            } else {
                echo json_encode(array('status'=> 200,'message' => 'No Record found.', 'data' => ''));
            }
        }
    }
    public function register() {
        if($_REQUEST && $_REQUEST['mobile_no'] != "" && $_REQUEST['password'] != "" && $_REQUEST['garage_name'] != "") {
            $end_date = date('d-m-Y',strtotime(date('Y-m-d') . ' +14 day'));
            $end_date_ymd = date('Y-m-d',strtotime(date('Y-m-d') . ' +14 day'));
            $garage_data = array(
                'name'=> $_REQUEST['garage_name'],
                'contact_no' => $_REQUEST['mobile_no'],
                'sms_balance' => 50
            );
            $this->db->insert('tbl_garage',$garage_data);
            $garage_id = $this->db->insert_id();
            
            $user_data = array(
                'garage_id' => $garage_id,
                'mobile_no'=> $_REQUEST['mobile_no'],
                'first_name'=> $_REQUEST['contact_name'],
                'password' => md5($_REQUEST['password']),
                'role_id' => 2,
                'status' => 'A',
                'expiry_date' => $end_date_ymd,
                'created_date' => date('Y-m-d H:i:s'),
                'updated_date' => date('Y-m-d H:i:s')
            );
            $this->db->insert('tbl_users',$user_data);
            $user_id = $this->db->insert_id();

            $menu_rights_data = array(
                array(
                    'garage_id' => $garage_id,
                    'user_id' => $user_id,
                    'menu_id' => 1,
                    'have_rights' => 'Y',
                    'is_active' => '1',
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => '1',
                    'updated_date' => date('Y-m-d H:i:s'),
                    'updated_by' => '1'                ),
                array(
                    'garage_id' => $garage_id,
                    'user_id' => $user_id,
                    'menu_id' => 2,
                    'have_rights' => 'Y',
                    'is_active' => '1',
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => '1',
                    'updated_date' => date('Y-m-d H:i:s'),
                    'updated_by' => '1'                ),
                array(
                    'garage_id' => $garage_id,
                    'user_id' => $user_id,
                    'menu_id' => 3,
                    'have_rights' => 'Y',
                    'is_active' => '1',
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => '1',
                    'updated_date' => date('Y-m-d H:i:s'),
                    'updated_by' => '1'
                ),
                array(
                    'garage_id' => $garage_id,
                    'user_id' => $user_id,
                    'menu_id' => 4,
                    'have_rights' => 'Y',
                    'is_active' => '1',
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => '1',
                    'updated_date' => date('Y-m-d H:i:s'),
                    'updated_by' => '1'
                ),
                array(
                    'garage_id' => $garage_id,
                    'user_id' => $user_id,
                    'menu_id' => 5,
                    'have_rights' => 'Y',
                    'is_active' => '1',
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => '1',
                    'updated_date' => date('Y-m-d H:i:s'),
                    'updated_by' => '1'
                ),
                array(
                    'garage_id' => $garage_id,
                    'user_id' => $user_id,
                    'menu_id' => 6,
                    'have_rights' => 'Y',
                    'is_active' => '1',
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => '1',
                    'updated_date' => date('Y-m-d H:i:s'),
                    'updated_by' => '1'
                ),
                array(
                    'garage_id' => $garage_id,
                    'user_id' => $user_id,
                    'menu_id' => 8,
                    'have_rights' => 'Y',
                    'is_active' => '1',
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => '1',
                    'updated_date' => date('Y-m-d H:i:s'),
                    'updated_by' => '1'
                ),
                array(
                    'garage_id' => $garage_id,
                    'user_id' => $user_id,
                    'menu_id' => 11,
                    'have_rights' => 'Y',
                    'is_active' => '1',
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => '1',
                    'updated_date' => date('Y-m-d H:i:s'),
                    'updated_by' => '1'
                ),
                array(
                    'garage_id' => $garage_id,
                    'user_id' => $user_id,
                    'menu_id' => 12,
                    'have_rights' => 'Y',
                    'is_active' => '1',
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => '1',
                    'updated_date' => date('Y-m-d H:i:s'),
                    'updated_by' => '1'
                ),
                array(
                    'garage_id' => $garage_id,
                    'user_id' => $user_id,
                    'menu_id' => 13,
                    'have_rights' => 'Y',
                    'is_active' => '1',
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => '1',
                    'updated_date' => date('Y-m-d H:i:s'),
                    'updated_by' => '1'
                ),
                array(
                    'garage_id' => $garage_id,
                    'user_id' => $user_id,
                    'menu_id' => 26,
                    'have_rights' => 'Y',
                    'is_active' => '1',
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => '1',
                    'updated_date' => date('Y-m-d H:i:s'),
                    'updated_by' => '1'
                ),
                array(
                    'garage_id' => $garage_id,
                    'user_id' => $user_id,
                    'menu_id' => 28,
                    'have_rights' => 'Y',
                    'is_active' => '1',
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => '1',
                    'updated_date' => date('Y-m-d H:i:s'),
                    'updated_by' => '1'
                ),
                array(
                    'garage_id' => $garage_id,
                    'user_id' => $user_id,
                    'menu_id' => 32,
                    'have_rights' => 'Y',
                    'is_active' => '1',
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => '1',
                    'updated_date' => date('Y-m-d H:i:s'),
                    'updated_by' => '1'
                ),
                array(
                    'garage_id' => $garage_id,
                    'user_id' => $user_id,
                    'menu_id' => 33,
                    'have_rights' => 'Y',
                    'is_active' => '1',
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => '1',
                    'updated_date' => date('Y-m-d H:i:s'),
                    'updated_by' => '1'
                ),
                array(
                    'garage_id' => $garage_id,
                    'user_id' => $user_id,
                    'menu_id' => 34,
                    'have_rights' => 'Y',
                    'is_active' => '1',
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => '1',
                    'updated_date' => date('Y-m-d H:i:s'),
                    'updated_by' => '1'
                ),
                array(
                    'garage_id' => $garage_id,
                    'user_id' => $user_id,
                    'menu_id' => 35,
                    'have_rights' => 'Y',
                    'is_active' => '1',
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => '1',
                    'updated_date' => date('Y-m-d H:i:s'),
                    'updated_by' => '1'
                ),
                array(
                    'garage_id' => $garage_id,
                    'user_id' => $user_id,
                    'menu_id' => 36,
                    'have_rights' => 'Y',
                    'is_active' => '1',
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => '1',
                    'updated_date' => date('Y-m-d H:i:s'),
                    'updated_by' => '1'
                ),
                array(
                    'garage_id' => $garage_id,
                    'user_id' => $user_id,
                    'menu_id' => 37,
                    'have_rights' => 'Y',
                    'is_active' => '1',
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => '1',
                    'updated_date' => date('Y-m-d H:i:s'),
                    'updated_by' => '1'
                ),
                array(
                    'garage_id' => $garage_id,
                    'user_id' => $user_id,
                    'menu_id' => 38,
                    'have_rights' => 'Y',
                    'is_active' => '1',
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => '1',
                    'updated_date' => date('Y-m-d H:i:s'),
                    'updated_by' => '1'
                ),
                array(
                    'garage_id' => $garage_id,
                    'user_id' => $user_id,
                    'menu_id' => 39,
                    'have_rights' => 'Y',
                    'is_active' => '1',
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => '1',
                    'updated_date' => date('Y-m-d H:i:s'),
                    'updated_by' => '1'
                ),
                array(
                    'garage_id' => $garage_id,
                    'user_id' => $user_id,
                    'menu_id' => 40,
                    'have_rights' => 'Y',
                    'is_active' => '1',
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => '1',
                    'updated_date' => date('Y-m-d H:i:s'),
                    'updated_by' => '1'
                )
            );
            //$this->db->insert_batch('tbl_menu_rights',$menu_rights_data);
     
            $category = array(
                array(
                    'garage_id' => $garage_id,
                    'head_type' => 'Expense',
                    'name' => 'Electricity bill',
                    'is_active' => '1',
                    'created_date' => date('Y-m-d H:i:s'),
                    'updated_date' => date('Y-m-d H:i:s')
                ),
                array(
                    'garage_id' => $garage_id,
                    'head_type' => 'Expense',
                    'name' => 'Food',
                    'is_active' => '1',
                    'created_date' => date('Y-m-d H:i:s'),
                    'updated_date' => date('Y-m-d H:i:s')
                ),
                array(
                    'garage_id' => $garage_id,
                    'head_type' => 'Expense',
                    'name' => 'Fuel',
                    'is_active' => '1',
                    'created_date' => date('Y-m-d H:i:s'),
                    'updated_date' => date('Y-m-d H:i:s')
                ),
                array(
                    'garage_id' => $garage_id,
                    'head_type' => 'Expense',
                    'name' => 'Interest',
                    'is_active' => '1',
                    'created_date' => date('Y-m-d H:i:s'),
                    'updated_date' => date('Y-m-d H:i:s')
                ),
                array(
                    'garage_id' => $garage_id,
                    'head_type' => 'Expense',
                    'name' => 'Internet bill',
                    'is_active' => '1',
                    'created_date' => date('Y-m-d H:i:s'),
                    'updated_date' => date('Y-m-d H:i:s')
                ),
                array(
                    'garage_id' => $garage_id,
                    'head_type' => 'Expense',
                    'name' => 'Office maintenance',
                    'is_active' => '1',
                    'created_date' => date('Y-m-d H:i:s'),
                    'updated_date' => date('Y-m-d H:i:s')
                ),
                array(
                    'garage_id' => $garage_id,
                    'head_type' => 'Expense',
                    'name' => 'Other',
                    'is_active' => '1',
                    'created_date' => date('Y-m-d H:i:s'),
                    'updated_date' => date('Y-m-d H:i:s')
                ),
                array(
                    'garage_id' => $garage_id,
                    'head_type' => 'Expense',
                    'name' => 'Rent',
                    'is_active' => '1',
                    'created_date' => date('Y-m-d H:i:s'),
                    'updated_date' => date('Y-m-d H:i:s')
                ),
                array(
                    'garage_id' => $garage_id,
                    'head_type' => 'Expense',
                    'name' => 'Salary',
                    'is_active' => '1',
                    'created_date' => date('Y-m-d H:i:s'),
                    'updated_date' => date('Y-m-d H:i:s')
                ),
                array(
                    'garage_id' => $garage_id,
                    'head_type' => 'Income',
                    'name' => 'Washing',
                    'is_active' => '1',
                    'created_date' => date('Y-m-d H:i:s'),
                    'updated_date' => date('Y-m-d H:i:s')
                ));

            $this->db->insert_batch('tbl_category',$category);

            $template_master_data = array(
               array(
                   'garage_id' => $garage_id,
                   'order_no' => '3',
                   'name' => 'Quote / Estimation',
                   'email_subject' => 'Quote from {{garage_name}}',
                   'email_body' => '<table width="100%" align="center" bgcolor="#F4F5F8" style="font-size: 1rem;"><tbody><tr><td><table width="100%" border="0" cellspacing="0" cellpadding="10" bgcolor="white" style="border:1px solid #dadbdd;border-top:0;padding-bottom:10px;max-width: 850px;margin-top:20px;" align="center"><tbody><tr bgcolor="#F4F5F8" style="color:#333333;border-bottom:1px solid #dadbdd"><td style="padding:0 25px 15px 25px;border-top:5px solid darkseagreen;margin-top:5px;"><table style="table-layout:fixed;padding-bottom:20px"><tbody><tr><td style="padding-top:10px"></td><td style="font-size:16pt;font-weight:bold;word-wrap:break-word"><p>{{garage_name}}</p></td></tr></tbody></table><table style="width:100%"><tbody><tr><td><p></p><p></p><p></p><br><p></p><table align="left" border="0" cellpadding="0" cellspacing="0" style="padding-bottom:20px;padding-right:15px"><tbody><tr><td style="border-right:1px solid #d4d7db;margin-right:0;padding-right:15px" nowrap=""><p class="m_4293929705867903007inv_headers" style="font-size: 12px; color: rgb(57, 58, 61); margin-right: 0px; margin-bottom: 5px; margin-left: 0px;"><span class="il">Estimate No</span></p><p class="m_4293929705867903007inv_val" style="font-size: 18px; margin-right: 0px; margin-bottom: 0px; margin-left: 0px;">{{estimate_no}}</p></td><td style="border-right:1px solid #d4d7db;margin-right:0;padding-right:15px;padding-left:15px" nowrap=""><p class="m_4293929705867903007inv_headers" style="font-size: 12px; color: rgb(57, 58, 61); margin-right: 0px; margin-bottom: 5px; margin-left: 0px;">Estimate Date</p><p class="m_4293929705867903007inv_val" style="font-size: 18px; color: rgb(57, 58, 61); margin-right: 0px; margin-bottom: 0px; margin-left: 0px;">{{estimate_date}}</p></td><td style="border-right:1px solid #d4d7db;margin-right:0;padding-right:15px;padding-left:15px" nowrap=""><p class="m_4293929705867903007inv_headers" style="font-size: 12px; color: rgb(57, 58, 61); margin-right: 0px; margin-bottom: 5px; margin-left: 0px;">Quotation</p><p class="m_4293929705867903007inv_val" style="font-size: 18px; color: rgb(57, 58, 61); margin-right: 0px; margin-bottom: 0px; margin-left: 0px;">Rs. {{estimate_total_amount}}</p></td></tr></tbody><tbody></tbody></table><table class="m_4293929705867903007buttonwrapper" align="center" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td><a style="vertical-align: bottom; height: 15px; padding: 15px; border-radius: 2px; text-align: center; background-color: rgb(44, 160, 28); font-family: HelveticaNeueRoman, Helvetica, Verdana, sans-serif; font-size: 16px; color: rgb(255, 255, 255); line-height: 16px; letter-spacing: 0.5px;" href="{{estimate_link}}" target="__blank">View Quotation</a>                                                                                                                                                                                            </td></tr></tbody></table></td></tr></tbody></table></td></tr><tr><td style="font-size: 12px; padding: 20px 25px 50px; font-family: HelveticaNeueRoman, Helvetica, Arial, Verdana, sans-serif;"><p><span style="font-family: " open="" sans",="" sans-serif;="" font-size:="" 14px;"="">Dear {{customer_fullname}},</span></p><p><span style="font-family: " open="" sans",="" sans-serif;="" font-size:="" 14px;"="">Thanks for giving a chance to {{garage_name}}. </span></p><p><span style="font-family: " open="" sans",="" sans-serif;="" font-size:="" 14px;"="">Please contact us once you have made your decision so we can schedule a booking at your most suitable time.</span></p><p><span style="font-family: " open="" sans",="" sans-serif;="" font-size:="" 14px;"="">Thanks for your business !!.</span></p><p><span style="font-family: " open="" sans",="" sans-serif;="" font-size:="" 14px;"="">Regards,</span></p><p><span style="font-family: " open="" sans",="" sans-serif;="" font-size:="" 14px;"="">{{sender_name}},</span></p><p><span style="font-family: " open="" sans",="" sans-serif;="" font-size:="" 14px;"="">{{sender_contact_no}}</span></p></td></tr></tbody></table></td></tr></tbody></table>',
                   'sms_body' => 'Dear {{customer_name}}, Please find the quotation from {{garage_name}}, To view your quotation:  {{estimate_link}} {{garage_name}},{{garage_contact_no}}',
                   'whatsapp_body' => 'Hi {{customer_name}} !%0A%0AQuotation (#{{estimate_no}}) for the amount of Rs. {{estimate_total_amount}} has been generated.%0A%0AView your quotation here: {{estimate_link}} %0A%0A-{{garage_name}} ,{{garage_contact_no}}',
                   'created_date' => date('Y-m-d H:i:s'),
                   'updated_date' => date('Y-m-d H:i:s'),
               ),
               array(
                'garage_id' => $garage_id,
                'order_no' => '4',
                'name' => 'Invoice',
                'email_subject' => 'Invoice {{invoice_no}} from {{garage_name}}.',
                'email_body' => '<table width="100%" align="center" bgcolor="#F4F5F8" style="font-size: 1rem;"><tbody><tr><td><table width="100%" border="0" cellspacing="0" cellpadding="10" bgcolor="white" style="border:1px solid #dadbdd;border-top:0;padding-bottom:10px;max-width: 850px;margin-top:20px;" align="center"><tbody><tr bgcolor="#F4F5F8" style="color:#333333;border-bottom:1px solid #dadbdd"><td style="padding:0 25px 15px 25px;border-top:5px solid darkseagreen;margin-top:5px;"><table style="table-layout:fixed;padding-bottom:20px"><tbody><tr><td style="padding-top:10px"></td><td style="font-size:16pt;font-weight:bold;word-wrap:break-word"><p>{{garage_name}}</p></td></tr></tbody></table><table style="width:100%"><tbody><tr><td><p></p><p></p><p></p><br><p></p><table align="left" border="0" cellpadding="0" cellspacing="0" style="padding-bottom:20px;padding-right:15px"><tbody><tr><td style="border-right:1px solid #d4d7db;margin-right:0;padding-right:15px" nowrap=""><p class="m_4293929705867903007inv_headers" style="font-size: 12px; color: rgb(57, 58, 61); margin-right: 0px; margin-bottom: 5px; margin-left: 0px;"><span class="il">INVOICE</span></p><p class="m_4293929705867903007inv_val" style="font-size: 18px; margin-right: 0px; margin-bottom: 0px; margin-left: 0px;">{{invoice_no}}</p></td><td style="border-right:1px solid #d4d7db;margin-right:0;padding-right:15px;padding-left:15px" nowrap=""><p class="m_4293929705867903007inv_headers" style="font-size: 12px; color: rgb(57, 58, 61); margin-right: 0px; margin-bottom: 5px; margin-left: 0px;">DUE DATE</p><p class="m_4293929705867903007inv_val" style="font-size: 18px; color: rgb(57, 58, 61); margin-right: 0px; margin-bottom: 0px; margin-left: 0px;">{{invoice_due_date}}</p></td><td style="padding-left:15px"><p class="m_4293929705867903007inv_headers" style="font-size: 12px; color: rgb(57, 58, 61); margin-right: 0px; margin-bottom: 3px; margin-left: 0px;">TOTAL INVOICE</p><p class="m_4293929705867903007inv_amt" style="font-size: 20px; color: rgb(57, 58, 61); margin-right: 0px; margin-bottom: 0px; margin-left: 0px;">Rs. {{invoice_total_amount}}</p></td></tr></tbody><tbody></tbody></table><table class="m_4293929705867903007buttonwrapper" align="center" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td><a style="vertical-align: bottom; height: 15px; padding: 15px; border-radius: 2px; text-align: center; background-color: rgb(44, 160, 28); font-family: HelveticaNeueRoman, Helvetica, Verdana, sans-serif; font-size: 16px; color: rgb(255, 255, 255); line-height: 16px; letter-spacing: 0.5px;" href="{{invoice_link}}" target="__blank">View invoice</a></td></tr></tbody></table></td></tr></tbody></table></td></tr><tr><td style="font-size: 12px; padding: 20px 25px 50px; font-family: HelveticaNeueRoman, Helvetica, Arial, Verdana, sans-serif;"><p>Dear {{customer_firstname}},<br><br>Here is your <span class="il">invoice</span>! We appreciate your prompt payment.<br><br>Thanks for your business!</p><p><br></p><p>Regards,<br>{{sender_name}},</p><p>{{sender_contact_no}}.</p></td></tr></tbody></table></td></tr></tbody></table>',
                'sms_body' => 'Hi {{customer_name}},Your Invoice No {{invoice_no}} of Rs. {{invoice_total_amount}} from {{garage_name}} is generated on date {{invoice_date}}. To view invoice click : {{invoice_link}}{{garage_name}},{{garage_contact_no}}',
                'whatsapp_body' => 'Hi {{customer_name}} !%0A%0AYou have an outstanding amount of Rs.{{invoice_total_due}} for the invoice-{{invoice_no}}.%0AView your invoice here: {{invoice_link}} %0A%0A-{{garage_name}} ,{{garage_contact_no}}',
                'created_date' => date('Y-m-d H:i:s'),
                'updated_date' => date('Y-m-d H:i:s'),
               ),
               array(
                'garage_id' => $garage_id,
                'order_no' => '6',
                'name' => 'Payment Due',
                'email_subject' => 'Your payment of Rs. {{invoice_total_due}} was due at {{garage_name}}',
                'email_body' => '<table width="100%" align="center" bgcolor="#F4F5F8" style="font-size: 1rem;"><tbody><tr><td><table width="100%" border="0" cellspacing="0" cellpadding="10" bgcolor="white" style="border:1px solid #dadbdd;border-top:0;padding-bottom:10px;max-width: 800px;margin-top:20px;" align="center"><tbody><tr bgcolor="#F4F5F8" style="color:#333333;border-bottom:1px solid #dadbdd"><td style="padding:0 25px 15px 25px;border-top:5px solid darkseagreen;margin-top:5px;"><table style="table-layout:fixed;padding-bottom:20px"><tbody><tr><td style="padding-top:10px"></td><td style="font-size:16pt;font-weight:bold;word-wrap:break-word"><p>{{garage_name}}</p></td></tr></tbody></table><table style="width:100%"><tbody><tr><td><p></p><p></p><p></p><br><p></p><table align="left" border="0" cellpadding="0" cellspacing="0" style="padding-bottom:20px;padding-right:15px"><tbody><tr><td style="border-right:1px solid #d4d7db;margin-right:0;padding-right:15px" nowrap=""><p class="m_4293929705867903007inv_headers" style="font-size: 12px; color: rgb(57, 58, 61); margin-right: 0px; margin-bottom: 5px; margin-left: 0px;"><span class="il">INVOICE</span></p><p class="m_4293929705867903007inv_val" style="font-size: 18px; margin-right: 0px; margin-bottom: 0px; margin-left: 0px;">{{invoice_no}}</p></td><td style="border-right:1px solid #d4d7db;margin-right:0;padding-right:15px;padding-left:15px" nowrap=""><p class="m_4293929705867903007inv_headers" style="font-size: 12px; color: rgb(57, 58, 61); margin-right: 0px; margin-bottom: 5px; margin-left: 0px;">DUE DATE</p><p class="m_4293929705867903007inv_val" style="font-size: 18px; color: rgb(57, 58, 61); margin-right: 0px; margin-bottom: 0px; margin-left: 0px;">{{invoice_due_date}}</p></td><td style="padding-left:15px"><p class="m_4293929705867903007inv_headers" style="font-size: 12px; color: rgb(57, 58, 61); margin-right: 0px; margin-bottom: 3px; margin-left: 0px;">BALANCE DUE</p><p class="m_4293929705867903007inv_amt" style="font-size: 20px; color: rgb(57, 58, 61); margin-right: 0px; margin-bottom: 0px; margin-left: 0px;">{{invoice_total_due}}</p></td></tr></tbody><tbody></tbody></table><table class="m_4293929705867903007buttonwrapper" align="center" border="0" cellpadding="0" cellspacing="0"><tbody><tr><td><a style="vertical-align: bottom; height: 15px; padding: 15px; border-radius: 2px; text-align: center; background-color: rgb(44, 160, 28); font-family: HelveticaNeueRoman, Helvetica, Verdana, sans-serif; font-size: 16px; color: rgb(255, 255, 255); line-height: 16px; letter-spacing: 0.5px;" href="{{invoice_link}}" target="__blank">View invoice</a></td></tr></tbody></table></td></tr></tbody></table></td></tr><tr><td style="font-size: 12px; padding: 20px 25px 50px; font-family: HelveticaNeueRoman, Helvetica, Arial, Verdana, sans-serif;"><p>Dear {{customer_firstname}},</p><p><br>Your payment of Rs. {{invoice_total_due}} was due on {{invoice_due_date}}. We appreciate your prompt payment.<br><br>Thanks for your business!</p><p>Regards,<br>{{sender_name}},</p><p style="line-height: 1.2;">{{sender_contact_no}}.</p></td></tr></tbody></table></td></tr></tbody></table>',
                'sms_body' => 'Dear {{customer_name}}, Your payment of Rs. {{invoice_total_due}} was due on {{invoice_due_date}}. To view your invoice: {{invoice_link}} {{sender_name}},{{garage_name}}',
                'whatsapp_body' => 'Hi {{customer_name}} !%0A%0AWe have not received your payment of Rs.{{invoice_total_due}} for the invoice-{{invoice_no}} yet. Kindly pay at the earliest. Thank you.%0A%0AView your invoice here: {{invoice_link}}.%0A%0A-{{garage_name}},{{garage_contact_no}}',
                'created_date' => date('Y-m-d H:i:s'),
                'updated_date' => date('Y-m-d H:i:s'),
               ),
               array(
                'garage_id' => $garage_id,
                'order_no' => '5',
                'name' => 'Payment received from customer',
                'email_subject' => 'Your Payment to {{garage_name}} was successful.',
                'email_body' => '<p>Dear {{customer_fullname}},</p><p>We have received a payment of Rs. {{invoice_paid}} for invoice No. {{invoice_no}}.</p><p>Details of the invoice are as follows:</p><p>Invoice No. {{invoice_no}},</p><p>Invoice Amount : {{invoice_total_amount}}.</p><p>Paid Amount : {{invoice_total_paid}}.</p><p>Balance Amount : {{invoice_total_due}}.</p><p>Thanks for your business !!.</p><p><br></p><p>Kindest Regards,</p><p>{{sender_name}}</p><p>{{sender_contact_no}}</p>',
                'sms_body' => 'Hi {{customer_name}}, payment of Rs. {{invoice_total_paid}} against invoice-{{invoice_no}} has been received. Thanks. {{garage_name}}',
                'whatsapp_body' => 'Hi {{customer_name}} !%0A%0AThanks for servicing your vehicle. we have received a payment of Rs. {{invoice_total_paid}} for the invoice-{{invoice_no}}.%0A%0A-{{garage_name}},{{garage_contact_no}}',
                'created_date' => date('Y-m-d H:i:s'),
                'updated_date' => date('Y-m-d H:i:s'),
               ),
               array(
                'garage_id' => $garage_id,
                'order_no' => '7',
                'name' => 'Service Feedback',
                'email_subject' => 'Thanks for visiting {{garage_name}} ! provide your valuable feedback.',
                'email_body' => '<p>Dear {{customer_firstname}},</p><p>Thanks for giving a chance to service your vehicle @ {{garage_name}}. To help us serve you better in the future we would love to hear about your experience.</p><p>{{feedback_link}} to give your feedback.</p><p>we appreciate by your time and we value your feedback.</p><p>Kind Regards,</p><p>{{sender_name}}&nbsp;</p><p>{{garage_name}}</p>',
                'sms_body' => 'Dear {{customer_firstname}},<span style="font-size: 1rem;">Thanks for giving a chance to service your vehicle @ {{garage_name}}. To help us serve you better in the future we would love to hear about your experience.</span><span style="font-size: 1rem;">give your feedback {{feedback_link}}</span></p>',
                'whatsapp_body' => '',
                'created_date' => date('Y-m-d H:i:s'),
                'updated_date' => date('Y-m-d H:i:s'),
               ),
               array(
                'garage_id' => $garage_id,
                'order_no' => '1',
                'name' => 'Service Reminder',
                'email_subject' => 'Service reminder for vehicle {{customer_vehicle_no}} at {{garage_name}}.',
                'email_body' => '<p>Hello {{customer_firstname}},</p><p>It looks like your vehicle {{customer_vehicle_no}} is due on service on {{service_due_date}} last service was done on {{last_service_date}} so I just wanted to reach out and see how things are doing. Are there any problems or issues with you vehicle that you’ve noticed? Please let me know if there’s anything about servicing your car that I can help you with. I’m here to help!</p><p>If you are planning some vehicle maintenance but just can’t get the time, I’d like to let you know about our Pick Up/Drop facility. We know you’re really busy, so we’ll pick up your car from your place of work, service it, and return it to you before the day is over. It’s a totally free service we offer to our awesome customers.</p><p>Give me a call at {{sender_contact_no}} to schedule a service appointment whenever is convenient, and feel free to let me know if there’s anything I can help you with.</p><p><br></p><p>Happy Driving!</p><p>{{sender_name}}</p><p>{{garage_name}}</p>',
                'sms_body' => 'Thanks for servicing your vehicle {{service_due_vehicle_no}} with the {{garage_name}}. Your vehicle is due for servicing on {{service_due_date}}<span style="font-size: 1rem;">. call on {{sender_contact_no}} to book your service.</span>',
                'whatsapp_body' => '',
                'created_date' => date('Y-m-d H:i:s'),
                'updated_date' => date('Y-m-d H:i:s'),
               ));
           $this->db->insert_batch('tbl_template',$template_master_data);

           echo json_encode(array('status' => 200 ,'message'=> 'Register successfully. Your free trial period has started and will be ended on '.$end_date.'. You can now login with Mobile No and Password !!.'));
        } else {
           echo json_encode(array('status' => 500 ,'message'=> 'Garage Name, Mobile No and Password is require to register'));
        }
    }
    public function setSession() {
        $this->session->set_userdata('isloggedin', true);
        $this->session->set_userdata('data', $data['data']);
        $this->session->set_userdata('setting', $data['setting']);
        $this->session->mobile_no = $data['data']->mobile_no; 
    }
}
