<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Transcation extends MY_Controller {

    function __construct() {
        parent::__construct();
        if($_REQUEST && $_REQUEST['table_name'] != 'tbl_feedback') {
            if ($this->session->mobile_no == "") {
                echo '<script>window.location = "../"</script>';
            }
        }
        $this->load->model('BookingModel');
        $this->load->model('UploadModel');
        $this->load->model('ServiceReminderModel');
    }

    public function InsertOperation() {
        if (isset($_REQUEST) && !empty($_REQUEST) && $_REQUEST['table_name'] != '') {
            if(isset($_REQUEST['PHPSESSID'])) {
                unset($_REQUEST['PHPSESSID']);
            }
            if ($_REQUEST['table_name'] == 'tbl_garage') {
                $garage_id = $_SESSION['setting']->garage_id;
                if (!isset($garage_id) && $garage_id == 0) {
                    echo json_encode(array('status' => 400, 'message' => "Your session has expiry ! you will be logged"));
                    return false;
                }
                $Condition = array('user_id' => $_SESSION['data']->user_id);
                $this->UpdateData('tbl_users', array('is_setting_saved' => 'Y'), $Condition);
                
                $notification = array();
                $notification['garage_id'] = $_SESSION['setting']->garage_id;
                $notification['item_type'] = 'settings';
                $notification['item_id'] = $garage_id;
                $notification['description'] = 'Garage setting updated.';
                $notification['date'] = date('Y-m-d H:i:s');
                $this->notification($notification);
                unset($_REQUEST['table_name']);
                $cond = array('garage_id' => $garage_id);
                $this->UpdateData('tbl_garage', $_REQUEST, $cond);
                
                if ($_FILES['filename']['name'] != "") {
                    if ($_FILES['filename']['size'] > 2000000) {
                        echo json_encode(array('status' => 500, 'message' => 'File size should not be greater than 2MB.', 'data' => ''));
                        exit;
                    }
                    $fileName = $this->UploadModel->fileupload('logos', 'garage_' . $garage_id, $_FILES['logo']);
                    $this->UpdateData('tbl_garage', array('logo_path' => $fileName['file_name']), $cond);
                }
            } else if ($_REQUEST['table_name'] == 'tbl_customer') {
                $customer = array();
                $vehicle = array();
                parse_str($_REQUEST['customer_detail'], $customer);
                parse_str($_REQUEST['vehicle_detail'], $vehicle);
                $cust_id = isset($_REQUEST['cust_id']) ? $_REQUEST['cust_id'] : '';
                unset($_REQUEST['cust_id']);
                
                $vehicle_id = '';
                if ($cust_id == '') {
                    $customer['garage_id'] = $_SESSION['setting']->garage_id;
                    $customer['is_active'] = 1;
                    $customer['created_date'] = date('Y-m-d H:i:s');
                    $customer['updated_date'] = date('Y-m-d H:i:s');
                    $customer_id = $this->InsertData('tbl_customer', $customer);
                    $message = "Customer Added Successfully.";

                    foreach($vehicle['vehicle'] as $key => $value) {
                        if($value['make_id'] != "") {
                            $value['customer_id'] = $customer_id;
                            $value['garage_id'] = $_SESSION['setting']->garage_id;
                            $value['is_active'] = 1;
                            $vehicle_id = $this->InsertData('tbl_customer_vehicle', $value);
                        }
                    }

                    $notification['garage_id'] = $_SESSION['setting']->garage_id;
                    $notification['item_type'] = 'customer';
                    $notification['item_id'] = $customer_id;
                    $notification['description'] = 'Customer '.$customer['name'].' is added.';
                    $notification['date'] = date('Y-m-d H:i:s');
                    $this->notification($notification);
                } else {
                    $customer['updated_date'] = date('Y-m-d H:i:s');
                    $Condition = array('customer_id' => $cust_id);
                    $this->UpdateData('tbl_customer', $customer, $Condition);
                    $message = "Customer Updated Successfully.";

                    $this->db->where('customer_id', $cust_id);
                    $this->db->update('tbl_customer_vehicle', array('is_active' => '0'));    

                    foreach($vehicle['vehicle'] as $key => $value) {
                        if($value['make_id'] != "") {
                            $value['customer_id'] = $cust_id;
                            $value['garage_id'] = $_SESSION['setting']->garage_id;
                            $value['is_active'] = 1;
                            $vehicle_id = $this->InsertData('tbl_customer_vehicle', $value);
                        }
                    }
                    
                    $notification['garage_id'] = $_SESSION['setting']->garage_id;
                    $notification['item_type'] = 'customer';
                    $notification['item_id'] = $cust_id;
                    $notification['description'] = 'Customer '.$customer['name'].' is updated.';
                    $notification['date'] = date('Y-m-d H:i:s');
                    $this->notification($notification);
                }
 
                $returnData = "";
                if (isset($_REQUEST['vehicle_id'])) {
                    $returnData = $this->BookingModel->getcustomerByRegNo('', $vehicle_id);
                }
                $status_code = 200;
            } else if ($_REQUEST['table_name'] == 'tbl_vendor') {
                $vendorData = array();
                parse_str($_REQUEST['data'], $vendorData);
                $vendor_id = isset($vendorData['vendor_id']) && $vendorData['vendor_id'] != "" ? $vendorData['vendor_id'] : '';
                unset($vendorData['vendor_id']);
                $notification = array();
                if (isset($vendor_id) && $vendor_id != "") {
                    $Condition = array('vendor_id' => $vendor_id);
                    $vendorData['updated_date'] = date('Y-m-d H:i:s');
                    $this->UpdateData('tbl_vendor', $vendorData, $Condition);
                    $message = "Vendor updated successfully";

                    $notification['garage_id'] = $_SESSION['setting']->garage_id;
                    $notification['item_type'] = 'vendor';
                    $notification['item_id'] = $vendor_id;
                    $notification['description'] = 'Vendor '.$vendorData['company_name'].' is updated.';
                    $notification['date'] = date('Y-m-d H:i:s');
                    $this->notification($notification);
                } else {
                    $vendorData['garage_id'] = $_SESSION['setting']->garage_id;
                    $vendorData['created_date'] = date('Y-m-d H:i:s');
                    $vendorData['updated_date'] = date('Y-m-d H:i:s');
                    $vendor_id = $this->InsertData($_REQUEST['table_name'], $vendorData);
                    $message = "Vendor saved successfully";

                    $notification['garage_id'] = $_SESSION['setting']->garage_id;
                    $notification['item_type'] = 'vendor';
                    $notification['item_id'] = $vendor_id;
                    $notification['description'] = 'Vendor '.$vendorData['company_name'].' is created.';
                    $notification['date'] = date('Y-m-d H:i:s');
                    $this->notification($notification);
                }


                $status_code = 200;
                $returnData = "";
            } else if($_REQUEST['table_name'] == 'tbl_vendor_ajax') {
                $vendor_details = array();
                $vendor_contacts = array();
                parse_str($_REQUEST['vendor_detail'], $vendor_details);
                parse_str($_REQUEST['vendor_contacts'], $vendor_contacts);
                $vendor_details['is_active'] = 1;
                $vendor_details['garage_id'] = $_SESSION['setting']->garage_id;
                $vendor_details['created_date'] = date('Y-m-d H:i:s');
                $vendor_details['updated_date'] = date('Y-m-d H:i:s');
                $vendor_id = $this->InsertData('tbl_vendor', $vendor_details);
                
                foreach($vendor_contacts['vendor_contact'] as $key => $value) {
                    if($value['person_name'] != "") {
                        $value['vendor_id'] = $vendor_id;
                        $value['is_active'] = 1;
                        $value['created_date'] = date('Y-m-d H:i:s');
                        $value['updated_date'] = date('Y-m-d H:i:s');
                        $this->InsertData('tbl_vendor_contacts', $value);
                    }
                }
                $status_code = 200;
                $returnData = json_encode(array('vendor_id' =>$vendor_id,'company_name' =>$vendor_details['company_name']));
            } else if($_REQUEST['table_name'] == 'tbl_customer_ajax') {
                $customer = array();
                $vehicle = array();
                parse_str($_REQUEST['customer_detail'], $customer);
                parse_str($_REQUEST['vehicle_detail'], $vehicle);
                
                $customer['garage_id'] = $_SESSION['setting']->garage_id;
                $customer['is_active'] = 1;
                $customer['created_date'] = date('Y-m-d H:i:s');
                $customer['updated_date'] = date('Y-m-d H:i:s');
                $customer_id = $this->InsertData('tbl_customer', $customer);
                $message = "Customer Added Successfully.";

                $vehicle_id = 0;
                $reg_no = 0;
                foreach($vehicle['vehicle'] as $key => $value) {
                    if($value['make_id'] != "") {
                        $value['customer_id'] = $customer_id;
                        $value['garage_id'] = $_SESSION['setting']->garage_id;
                        $value['is_active'] = 1;
                        $vehicle_id = $this->InsertData('tbl_customer_vehicle', $value);
                        $reg_no = $value['reg_no'];
                    }
                }
                $status_code = 200;
                $returnData = json_encode(array('id' => $customer_id.'_'.$vehicle_id,'customer' => $customer['name'].'-'.$reg_no));
            } else if ($_REQUEST['table_name'] == 'tbl_items') {
                $items = array();
                parse_str($_REQUEST['data'], $items);
                if($_REQUEST['item_id']==0) {
                    $opening_stock = $items['opening_stock'];
                    $date = $items['date'];
                    unset($items['opening_stock'],$items['date']);
                    $items['garage_id'] = $_SESSION['setting']->garage_id;
                    $items['item_type'] = $_REQUEST['item_type'];
                    $items['created_date'] = date('Y-m-d H:i:s');
                    $items['updated_date'] = date('Y-m-d H:i:s');
                    $items['low_stock_warning'] = $items['low_stock_unit'] != "" ? 'Y':'N';
                    $item_id = $this->InsertData('tbl_items', $items);    

                    $inventory = array();
                    $inventory['garage_id'] = $_SESSION['setting']->garage_id;
                    $inventory['item_id'] = $item_id;
                    $inventory['item_type'] = 'new_item';
                    $inventory['product_id'] = $item_id;
                    $inventory['description'] = 'Opening stock';
                    $inventory['qty'] = $opening_stock;
                    $inventory['date'] = $date != "" ? date('Y-m-d',strtotime($date)) : '0000-00-00';
                    $inventory['created_date'] = date('Y-m-d H:i:s');
                    $this->InsertData('tbl_inventory', $inventory);
                    $items['item_id'] = $item_id;
                    $returnData = $items;
                } else {
                    unset($items['opening_stock'],$items['date']);
                    $items['garage_id'] = $_SESSION['setting']->garage_id;
                    $items['updated_date'] = date('Y-m-d H:i:s');
                    $items['low_stock_warning'] = $items['low_stock_unit'] != "" ? 'Y':'N';
                    $Condition = array('item_id' => $_REQUEST['item_id']);
                    $this->UpdateData('tbl_items', $items, $Condition);

                    $items['item_id'] = $item_id;
                    $returnData = $items;
                }
            } else if($_REQUEST['table_name'] == 'tbl_inventory') {
                $inventory = array();
                $inventory['garage_id'] = $_SESSION['setting']->garage_id;
                $inventory['item_id'] = $_REQUEST['item_id'];
                $inventory['item_type'] = 'adjust';
                $inventory['product_id'] = $_REQUEST['item_id'];
                $inventory['qty'] = $_REQUEST['qty'];
                $inventory['date'] = date('Y-m-d');
                $inventory['description'] = $_REQUEST['qty'] < 0 ? 'Reduce stock' : 'Add stock';
                $inventory['created_date'] = date('Y-m-d H:i:s');
                $this->InsertData('tbl_inventory', $inventory);
            } else if ($_REQUEST['table_name'] == 'tbl_transaction') {
                $transcation = array();
                parse_str($_REQUEST['data'], $transcation);
                
                $transcation['date'] = date('Y-m-d',strtotime($transcation['date']));
                if($transcation['due_date'] != "") {
                    $transcation['due_date'] = date('Y-m-d',strtotime($transcation['due_date']));
                }
                $transcation['garage_id'] = $_SESSION['setting']->garage_id;
                $transcation_id = $this->InsertData('tbl_transaction', $transcation);

                $notification = array();
                $notification['garage_id'] = $_SESSION['setting']->garage_id;
                $notification['item_type'] = 'transcation';
                $notification['item_id'] = $transcation_id;
                $trans_type = str_replace('_', ' ', $transcation['transaction_type']);
                $notification['description'] =  'Transcation '.$trans_type.' of Rs. '.$transcation['amount'].' is created.';
                $notification['date'] = date('Y-m-d H:i:s');
                $this->notification($notification);
                $status_code = 200;
            } else if ($_REQUEST['table_name'] == 'tbl_users') {
                $users = array();
                parse_str($_REQUEST['add_user'], $users);
                $user_id = isset($users['user_id']) && $users['user_id'] != "" ? base64_decode($users['user_id']) : '';
                $users['garage_id'] = $_SESSION['setting']->garage_id;
                if (isset($user_id) && $user_id != "") {
                    $users['dob'] = $users['dob'] != "" ? date('Y-m-d', strtotime($users['dob'])) : '0000-00-00';
                    $users['joining_date'] = $users['joining_date'] != "" ? date('Y-m-d', strtotime($users['joining_date'])) : '0000-00-00';
                    $users['leaving_date'] = $users['leaving_date'] != "" ? date('Y-m-d', strtotime($users['leaving_date'])) : '0000-00-00';
                    if($users['password'] != "") {
                        $users['password'] = md5($users['password']);
                    } else {
                        unset($users['password']);
                    }
                    $users['updated_date'] = date('Y-m-d H:i:s');
                    $Condition = array('user_id' => $user_id);
                    $this->UpdateData('tbl_users', $users, $Condition);

                    $notification = array();
                    $notification['garage_id'] = $_SESSION['setting']->garage_id;
                    $notification['item_type'] = 'user';
                    $notification['item_id'] = $user_id;
                    $notification['description'] = 'User '.$users['first_name'].' is updated.';
                    $notification['date'] = date('Y-m-d H:i:s');
                    $this->notification($notification);
                    $message = "user updated successfully !!";
                } else {
                    $users['parent_id'] = $_SESSION['data']->user_id;
                    $users['dob'] = $users['dob'] != "" ? date('Y-m-d', strtotime($users['dob'])) : '0000-00-00';
                    $users['joining_date'] = $users['joining_date'] != "" ? date('Y-m-d', strtotime($users['joining_date'])) : '0000-00-00';
                    $users['leaving_date'] = $users['leaving_date'] != "" ? date('Y-m-d', strtotime($users['leaving_date'])) : '0000-00-00';
                    $users['password'] = md5($users['password']);
                    $users['created_date'] = date('Y-m-d H:i:s');
                    $users['updated_date'] = date('Y-m-d H:i:s');
                    $user_id = $this->InsertData($_REQUEST['table_name'], $users);

                    $notification = array();
                    $notification['garage_id'] = $_SESSION['setting']->garage_id;
                    $notification['item_type'] = 'user';
                    $notification['item_id'] = $user_id;
                    $notification['description'] = 'User '.$users['first_name'].' is created.';
                    $notification['date'] = date('Y-m-d H:i:s');
                    $this->notification($notification);
                    $message = "User created successfully, Now assing menu rights !!";
                }
                $status_code = 200;
                $returnData = array('user_id' => base64_encode($user_id));
            } else if($_REQUEST['table_name'] == 'tbl_profile') {
                $userprofile = array();
                $fileName = array();
                if ($_FILES['filename']['name'] != "") {
                    if ($_FILES['filename']['size'] > 2000000) {
                        echo json_encode(array('status' => 500, 'message' => 'Profile photo size should not be greater than 2MB.', 'data' => ''));
                        exit;
                    }
                    $fileName = $this->UploadModel->fileupload('profilePhoto','',$_FILES['filename']);
                }
                $userprofile['profile_photo'] = !empty($fileName) ? $fileName['file_name'] : $_REQUEST['photo_path'];
                $userprofile['first_name'] = $_REQUEST['first_name'];
                $userprofile['last_name'] = $_REQUEST['last_name'];
                $userprofile['email'] = $_REQUEST['email'];
                $userprofile['mobile_no'] = $_REQUEST['mobile_no'];
                $userprofile['updated_date'] = date('Y-m-d H:i:s');

                $this->db->where('user_id', $this->session->userdata['data']->user_id);
                $this->db->update('tbl_users', $userprofile);

                $query = $this->db->select('*')->from('tbl_users')->where('user_id',$this->session->userdata['data']->user_id)->get()->row();
                $this->session->set_userdata('data', $query);
            } else if($_REQUEST['table_name'] == 'tbl_reset_password') {
                $new_password =  md5($_REQUEST['password']);
                $this->db->where('user_id', $this->session->userdata['data']->user_id);
                $this->db->update('tbl_users', array('password' => $new_password));
            } else if($_REQUEST['table_name'] == 'tbl_menu_rights') {
                $menu_rights = array();
                parse_str($_REQUEST['menu_right'], $menu_rights);
                $user_id = isset($_REQUEST['user_id']) && $_REQUEST['user_id'] != "" ? base64_decode($_REQUEST['user_id']) : '';
                if (isset($user_id) && $user_id != "") {
                    $this->db->where('user_id', $user_id);
                    $this->db->update('tbl_menu_rights', array('is_active' => 0, 'updated_date' => date('Y-m-d H:i:s'), 'updated_by' => $this->session->userdata['data']->user_id));

                    // Menu Rights..
                    $menuRightInsert = array();
                    foreach ($menu_rights as $mname => $access) {
                        $menuRightInsert['garage_id'] = $_SESSION['setting']->garage_id;
                        $menuRightInsert['user_id'] = $user_id;
                        $menuRightInsert['menu_id'] = $access['menu_id'];
                        $menuRightInsert['have_rights'] = isset($access['have_rights']) && $access['have_rights'] == 'Y' ? 'Y' : 'N';
                        $menuRightInsert['is_active'] = 1;
                        $menuRightInsert['created_by'] = $_SESSION['data']->user_id;
                        $menuRightInsert['created_date'] = date('Y-m-d H:i:s');
                        $menuRightInsert['updated_date'] = date('Y-m-d H:i:s');
                        $menuRightInsert['updated_by'] = $_SESSION['data']->user_id;
                        $this->InsertData('tbl_menu_rights', $menuRightInsert);
                    }

                    $notification = array();
                    $notification['garage_id'] = $_SESSION['setting']->garage_id;
                    $notification['item_type'] = 'menurights';
                    $notification['item_id'] = $user_id;
                    $notification['description'] = 'User '.$users['first_name'].' menu rights updated.';
                    $notification['date'] = date('Y-m-d H:i:s');
                    $this->notification($notification);
                    $message = 'Menu rights updated successfully !!';
                } 
            } else if ($_REQUEST['table_name'] == 'tbl_payment') {
                if ($_REQUEST['action'] == 'invoice') {
                $this->db->select("max(jobcard_no) as max_jobcard_no");
                $this->db->from("tbl_jobcard");
                $this->db->where("garage_id",$_SESSION['setting']->garage_id);
                $next_job_no = $this->db->get()->row_array();

                if($next_job_no['max_jobcard_no'] == NULL || $next_job_no['max_jobcard_no'] == '') {
                    $next_jobcard_no = 1001;
                } else {
                    $next_jobcard_no = $next_job_no['max_jobcard_no'] + 1;
                }
                $inv_status = '';
                if($_REQUEST['data']['invoice_status'] == 'paid') {
                    $_REQUEST['mainItem']['status'] = 'close';
                    $inv_status = 'paid';
                } else {
                    $_REQUEST['mainItem']['status'] = $_REQUEST['data']['invoice_status'];
                    $inv_status = 'payment_due';
                }

                $_REQUEST['mainItem']['date'] = $_REQUEST['mainItem']['date'] != '' ? date('Y-m-d', strtotime($_REQUEST['mainItem']['date'])) : '';
                $_REQUEST['mainItem']['expt_delivery_date'] = $_REQUEST['mainItem']['expt_delivery_date'] != '' ? date('Y-m-d', strtotime($_REQUEST['mainItem']['expt_delivery_date'])) : '';
                if (isset($_REQUEST['mainItem']) && !empty($_REQUEST['mainItem']) && $_REQUEST['data']['jobcard_id'] == '') {
                    $_REQUEST['mainItem']['garage_id'] = $this->session->userdata['setting']->garage_id;
                    $_REQUEST['mainItem']['created_date'] = date('Y-m-d H:i:s');
                    $_REQUEST['mainItem']['updated_date'] = date('Y-m-d H:i:s');
                    $_REQUEST['mainItem']['is_disc_applicable'] = $_SESSION['setting']->show_discount_column;
                    $_REQUEST['mainItem']['is_tax_applicable'] = $_SESSION['setting']->gst_applicable;
                    $_REQUEST['mainItem']['jobcard_no'] = $next_jobcard_no; 
                    $jobcard_id = $this->InsertData('tbl_jobcard', $_REQUEST['mainItem']);
                } else {
                    $_REQUEST['mainItem']['updated_date'] = date('Y-m-d H:i:s');
                    $jobcard_id = base64_decode($_REQUEST['data']['jobcard_id']);
                    $Condition = array('jobcard_id' => $jobcard_id);
                    $this->UpdateData('tbl_jobcard', $_REQUEST['mainItem'], $Condition);

                    $this->db->select('jobcard_no');
                    $this->db->from('tbl_jobcard');
                    $this->db->where('jobcard_id',$jobcard_id);
                    $job_no = $this->db->get()->row_array();
                }

                $Condition = array('invoice_id' => base64_decode($_REQUEST['data']['invoice_id']));
                $this->UpdateData('tbl_invoices', array('status' => $inv_status), $Condition);
                
                if ($_REQUEST['rowItem'] && !empty($_REQUEST['rowItem']) && $jobcard_id != '') {
                    $this->db->where('jobcard_id', $jobcard_id);
                    $this->db->delete('tbl_jobcard_item');
        		    $this->db->where('item_id', $jobcard_id);
                    $this->db->where('item_type', 'job');
                    $this->db->delete('tbl_inventory');
                    
                    foreach ($_REQUEST['rowItem'] as $r => $t) {
                        $t['jobcard_id'] = $jobcard_id;
                        $this->InsertData('tbl_jobcard_item', $t);
                        if($t['item_type'] == 'P') {
                            // update inventory...
                            $inventory = array();
                            $inventory['garage_id'] = $_SESSION['setting']->garage_id;
                            $inventory['item_id'] = $jobcard_id;
                            $inventory['item_type'] = 'job';
                            $inventory['product_id'] = $t['item_id'];
                            $inventory['description'] = 'Sales record';
                            $inventory['qty'] = -$t['qty'];
                            $inventory['date'] = date('Y-m-d');
                            $inventory['created_date'] = date('Y-m-d H:i:s');
                            $this->InsertData('tbl_inventory', $inventory);
                        }
                    }
                }
                
                $this->db->select("max(invoice_no) as max_invoice_no");
                $this->db->from("tbl_invoices");
                $this->db->where("garage_id",$_SESSION['setting']->garage_id);
                $this->db->where("item_type","job_invoice");
                $next_inv_no = $this->db->get()->row_array();

                if(($next_inv_no['max_invoice_no'] == NULL || $next_inv_no['max_invoice_no'] == '') && $_SESSION['setting']->invoice_no_start != "") {
                  $next_invoice_no = $_SESSION['setting']->invoice_no_start;
                } else if($next_inv_no['max_invoice_no'] == NULL || $next_inv_no['max_invoice_no'] == '') {
                  $next_invoice_no = 1001;
                } else {
                  $next_invoice_no = $next_inv_no['max_invoice_no'] + 1;
                }
                
                $this->db->select('credit_period');
                $this->db->from('tbl_customer');
                $this->db->where('customer_id',$_REQUEST['mainItem']['customer_id']);
                $customer_credit = $this->db->get()->row_array();

                $invoice['item_id'] = $jobcard_id;
                $invoice['item_type'] = 'job_invoice';
                $invoice['payment_term'] = '';
                $invoice['garage_id'] = $_SESSION['setting']->garage_id;
                $invoice['customer_id'] = $_REQUEST['mainItem']['customer_id'];
                $invoice['invoice_no'] = $next_invoice_no;
                $invoice['date'] = date('Y-m-d');
                $invoice['due_date'] = date('Y-m-d',strtotime(date('Y-m-d').' + '.$customer_credit['credit_period'].' days'));
                $invoice['amount'] = $_REQUEST['mainItem']['grand_total'];
                $invoice['status'] = $_REQUEST['data']['invoice_status'];
                $invoice['notes'] = $_REQUEST['data']['notes'];
                $invoice['created_date'] = date("Y-m-d H:i:s");
                $inv_id = $this->InsertData('tbl_invoices', $invoice);
                
                // ar receivable entry.
                $dataArray['garage_id'] = $_SESSION['setting']->garage_id;
                $dataArray['item_id'] = $inv_id;
                $dataArray['transaction_type'] = 'customer_invoice';
                $dataArray['category_id'] = 0;
                $dataArray['payment_type'] = 0;
                $dataArray['customer_id'] = $_REQUEST['data']['customer_id'];
                $dataArray['date'] = date('Y-m-d H:i:s');
                $dataArray['due_date'] = date('Y-m-d H:i:s');
                $dataArray['amount'] = $_REQUEST['mainItem']['grand_total'];
                $dataArray['description'] = 'Invoice-'.$next_invoice_no;
                $this->InsertData('tbl_transaction', $dataArray);
                
                //send email/sms communication for invoice.

                $invoice_template = array();
                $this->db->select('template_id');
                $this->db->from('tbl_template');
                $this->db->where('garage_id',$_SESSION['setting']->garage_id);
                $this->db->where('order_no',4);
                $invoice_template =  $this->db->get()->row_array();

                if((!empty($invoice_template)) && ($_REQUEST['data']['invoice_payment_sms'] == 'Y' || $_REQUEST['data']['invoice_payment_email'] == 'Y')) {
                    $templateForm['item_id'] = base64_encode($jobcard_id);
                    $templateForm['item_type'] = 'jobcard';
                    $templateForm['template_id'] = $invoice_template['template_id'];
                    $templateForm['email_chk'] = $_REQUEST['data']['invoice_payment_email'];
                    $templateForm['sms_chk'] = $_REQUEST['data']['invoice_payment_sms'];
                    $this->replacePlaceholder($templateForm);
                }

                $notification = array();
                $notification['garage_id'] = $_SESSION['setting']->garage_id;
                $notification['item_type'] = 'invoice';
                $notification['item_id'] = $inv_id;
                $notification['description'] = 'Invoice '.$next_invoice_no.' is created.';
                $notification['date'] = date('Y-m-d H:i:s');
                $this->notification($notification);
                
                if($_REQUEST['data']['invoice_status'] != 'due') {
                    $dataArray['garage_id'] = $_SESSION['setting']->garage_id;
                    $dataArray['item_id'] = $inv_id;
                    $dataArray['transaction_type'] = 'customer_payment';
                    $dataArray['category_id'] = 0;
                    $dataArray['payment_type'] = $_REQUEST['data']['payment_type_id'];
                    $dataArray['customer_id'] = $_REQUEST['data']['customer_id'];
                    $dataArray['vendor_id'] = 0;
                    $dataArray['date'] = date('Y-m-d H:i:s');
                    $dataArray['due_date'] = date('Y-m-d H:i:s');
                    $dataArray['amount'] = $_REQUEST['data']['paid_amount'];
                    $dataArray['description'] = 'Invoice-'.$next_invoice_no;
                    $this->InsertData('tbl_transaction', $dataArray);
                    $message = 'Invoice generated successfully !';
                
                    $this->db->select('sum(tbl_transaction.amount) as total_paid,tbl_invoices.amount as invoice_amount');
                    $this->db->from('tbl_transaction');
                    $this->db->join('tbl_invoices','tbl_invoices.invoice_id=tbl_transaction.item_id','left');
                    $this->db->where('tbl_transaction.item_id',$inv_id);
                    $this->db->where('transaction_type','customer_payment');
                    $this->db->where('tbl_transaction.customer_id',$_REQUEST['data']['customer_id']);
                    $paid_details = $this->db->get()->row_array();
                    
                    $total_due = $paid_details['invoice_amount'] - $paid_details['total_paid'];
    
                    // if($total_due == $paid_details['invoice_amount']) {
                    //     $status = 'payment_due';
                    // } else if($total_due == 0) {
                    //     $status = 'close';
                    // } else if($total_due != $paid_details['invoice_amount']) {
                    //     $status = 'partial_paid';
                    // }
    
                    // $cond = array('jobcard_id' => $jobcard_id);
                    // $this->UpdateData('tbl_jobcard', array('status' => $status), $cond);
                    
                    $Condition = array('invoice_id' => base64_decode($_REQUEST['data']['invoice_id']));
                    $this->UpdateData('tbl_invoices', array('status' => $status), $Condition);
                    //send email/sms communication for payment.

                    $payment_template = array();
                    $this->db->select('template_id');
                    $this->db->from('tbl_template');
                    $this->db->where('garage_id',$_SESSION['setting']->garage_id);
                    $this->db->where('order_no',5);
                    $payment_template =  $this->db->get()->row_array();

                    if((!empty($payment_template)) && ($_REQUEST['data']['invoice_payment_sms'] == 'Y' || $_REQUEST['data']['invoice_payment_email'] == 'Y')) {
                        $templateForm['item_id'] = base64_encode($jobcard_id);
                        $templateForm['item_type'] = 'jobcard';
                        $templateForm['template_id'] = $payment_template['template_id'];
                        $templateForm['email_chk'] = $_REQUEST['data']['invoice_payment_email'];
                        $templateForm['sms_chk'] = $_REQUEST['data']['invoice_payment_sms'];
                        $this->replacePlaceholder($templateForm);
                    }
                    
                    $notification = array();
                    $notification['garage_id'] = $_SESSION['setting']->garage_id;
                    $notification['item_type'] = 'payment';
                    $notification['item_id'] = $inv_id;
                    $notification['description'] = 'Payment of '.$_REQUEST['data']['paid_amount'].' for invoice '.$next_invoice_no.' is received.';
                    $notification['date'] = date('Y-m-d H:i:s');
                    $this->notification($notification);
                }
                

                if($_REQUEST['data']['next_service_date'] != "") {
                    // save service reminder data.
                    $reminder = array();
                    $reminder['garage_id'] =  $_SESSION['setting']->garage_id;
                    $reminder['job_id'] = $jobcard_id;
                    $reminder['customer_id'] =  $_REQUEST['data']['customer_id'];
                    $reminder['vehicle_id'] =  $_REQUEST['data']['vehicle_id'];
                    $reminder['service_date'] = $_REQUEST['mainItem']['date'] != '' ? date('Y-m-d', strtotime($_REQUEST['mainItem']['date'])) : '';
                    $reminder['reminder_date'] = date('Y-m-d',strtotime($_REQUEST['data']['next_service_date']));
                    $reminder['odometer'] = $_REQUEST['mainItem']['odometer'];
                    $reminder['created_date'] = date('Y-m-d H:i:s');
                    $reminder['updated_date'] = date('Y-m-d H:i:s');
                    $this->InsertData('tbl_service_reminder', $reminder);
                    
                }

                $status_code = 200;
                $message = "Invoice proceeds successfully !!.";
                $returnData = array('jobcard_id' => base64_encode($jobcard_id));
            } else {
                $this->db->select('sum(tbl_transaction.amount) as total_paid,tbl_invoices.invoice_no,tbl_invoices.amount as invoice_amount,tbl_invoices.item_id as jobcard_id');
                $this->db->from('tbl_transaction');
                $this->db->join('tbl_invoices','tbl_invoices.invoice_id=tbl_transaction.item_id','left');
                $this->db->where('tbl_transaction.item_id',base64_decode($_REQUEST['data']['invoice_id']));
                $this->db->where('transaction_type','customer_payment');
                $this->db->where('tbl_transaction.customer_id',$_REQUEST['data']['customer_id']);
                $paid_details = $this->db->get()->row_array();

                $dataArray['garage_id'] = $_SESSION['setting']->garage_id;
                $dataArray['item_id'] = base64_decode($_REQUEST['data']['invoice_id']);
                $dataArray['transaction_type'] = 'customer_payment';
                $dataArray['category_id'] = 0;
                $dataArray['customer_id'] = $_REQUEST['data']['customer_id'];
                $dataArray['vendor_id'] = 0;
                $dataArray['date'] = date('Y-m-d H:i:s');
                $dataArray['due_date'] = date('Y-m-d H:i:s');
                $dataArray['amount'] = $_REQUEST['data']['paid_amount'];
                $dataArray['description'] = 'Invoice-'.$paid_details['invoice_no'];

                $this->InsertData('tbl_transaction', $dataArray);
                $message = 'Invoice generated successfully !';
                
                $total_due = $paid_details['invoice_amount'] - $paid_details['total_paid'];

                $inv_status = '';
                $jobStatus = '';
                if($_REQUEST['data']['invoice_status'] == 'paid') {
                    $jobStatus = 'close';
                    $inv_status = 'paid';
                } else {
                    $jobStatus = 'payment_due';
                    $inv_status = 'payment_due';
                }

                // $invoice_status = '';
                // if($total_due == $paid_details['invoice_amount']) {
                //     $status = 'payment_due';
                // } else if($total_due == 0) {
                //     $status = 'close';
                // } else if($total_due != $paid_details['invoice_amount']) {
                //     $status = 'partial_paid';
                // }
                
                $cond = array('jobcard_id' => $paid_details['jobcard_id']);
                $this->UpdateData('tbl_jobcard', array('status' => $jobStatus), $cond);
                
                $Condition = array('invoice_id' => base64_decode($_REQUEST['data']['invoice_id']));
                $this->UpdateData('tbl_invoices', array('status' => $inv_status), $Condition);
                
                //send email/sms communication for payment.

                $payment_template = array();
                $this->db->select('template_id');
                $this->db->from('tbl_template');
                $this->db->where('garage_id',$_SESSION['setting']->garage_id);
                $this->db->where('order_no',5);
                $payment_template =  $this->db->get()->row_array();

                if((!empty($payment_template)) && ($_REQUEST['data']['payment_sms'] == 'Y' || $_REQUEST['data']['payment_email'] == 'Y')) {
                    $templateForm['item_id'] = $_REQUEST['data']['jobcard_id'];
                    $templateForm['item_type'] = 'jobcard';
                    $templateForm['template_id'] = $payment_template['template_id'];
                    $templateForm['email_chk'] = $_REQUEST['data']['payment_email'];
                    $templateForm['sms_chk'] = $_REQUEST['data']['payment_sms'];
                    $this->replacePlaceholder($templateForm);
                }

                $status_code = 200;
                $message = 'Payment added successfully !';
            }
            } else if($_REQUEST['table_name'] == 'tbl_customer_payment') {
                    $data = array();
                    parse_str($_REQUEST['payment_form'], $data);
                    
                    $payment = array();
                    $payment['garage_id'] = $_SESSION['setting']->garage_id;
                    $payment['item_id'] = $data['invoice_id'];
                    $payment['item_type'] = 'job_payment';
                    $payment['customer_id'] = $data['payment_vendor_id'];
                    $payment['date'] = date('Y-m-d',strtotime($data['vendor_date']));
                    $payment['payment_type_id'] = $data['vendor_payment_type'];
                    $payment['amount'] = $data['amount_to_be_paid'];
                    $payment['reference_no'] = $data['vendor_reference_no'];
                    $payment['cheque_no'] = '';  
                    $payment['created_date'] = date('Y-m-d H:i:s');
                    $payment['created_by'] = $_SESSION['data']->user_id;
                    $this->InsertData('tbl_payments', $payment);

                    $this->db->select('invoice_no');
                    $this->db->from('tbl_invoices');
                    $this->db->where('invoice_id',$data['invoice_id']);
                    $invoice_no = $this->db->get()->row_array();

                    $notification = array();
                    $notification['garage_id'] = $_SESSION['setting']->garage_id;
                    $notification['item_type'] = 'payment';
                    $notification['item_id'] = $data['invoice_id'];
                    $notification['description'] = 'Payment of '.$data['amount_to_be_paid'].' for invoice '.$invoice_no['invoice_no'].' is received.';
                    $notification['date'] = date('Y-m-d H:i:s');
                    $this->notification($notification);

            } else if ($_REQUEST['table_name'] == 'tbl_template') {
                $_REQUEST['garage_id'] = $_SESSION['setting']->garage_id;
                $notification = array();
                if ($_REQUEST['template_id'] != "") {
                    $_REQUEST['updated_date'] = date('Y-m-d H:i:s');
                    $Condition = array('template_id' => base64_decode($_REQUEST['template_id']));
                    unset($_REQUEST['template_id']);
                    $this->UpdateData($_REQUEST['table_name'], $_REQUEST, $Condition);
                    $message = 'Template Updated Successfully.';
                    
                    $notification['garage_id'] = $_SESSION['setting']->garage_id;
                    $notification['item_type'] = 'template';
                    $notification['item_id'] = base64_decode($_REQUEST['template_id']);
                    $notification['description'] = 'Template '.$_REQUEST['name'].' is updated.';
                    $notification['date'] = date('Y-m-d H:i:s');
                    $this->notification($notification);

                } else {
                    $_REQUEST['created_date'] = date('Y-m-d H:i:s');
                    $_REQUEST['updated_date'] = date('Y-m-d H:i:s');
                    $template_id = $this->InsertData($_REQUEST['table_name'], $_REQUEST);
                    $message = 'Template Created Successfully.';

                    $notification['garage_id'] = $_SESSION['setting']->garage_id;
                    $notification['item_type'] = 'template';
                    $notification['item_id'] = $template_id;
                    $notification['description'] = 'Template '.$_REQUEST['name'].' is created.';
                    $notification['date'] = date('Y-m-d H:i:s');
                    $this->notification($notification);
                }
                $status_code = '200';
            } else if ($_REQUEST['table_name'] == 'tbl_vendor_bills') {
                if($_REQUEST['po_id'] == "" || $_REQUEST['po_id'] == '0') {
                // get po no.
                $this->db->select('max(po_no) as max_po');
                $this->db->from('tbl_vendor_bills');
                $this->db->where('garage_id', $_SESSION['setting']->garage_id);
                $po_row = $this->db->get()->row_array();

                if ($po_row['max_po'] == NULL) {
                    $po_no = '1001';
                } else {
                    $po_no = $po_row['max_po'] + 1;
                }
                $po = array();
                $po = $_REQUEST['mainItem'];
                $po['garage_id'] = $_SESSION['setting']->garage_id;
                $po['jobcard_id'] = isset($po['jobcard_id']) && $po['jobcard_id'] != '' ? base64_decode($po['jobcard_id']) : 0;
                $po['po_no'] = $po_no;
                $po['order_date'] = $po['order_date'] != '' ? date('Y-m-d', strtotime($po['order_date'])) : '';
                $po['due_date'] = $po['due_date'] != '' ? date('Y-m-d', strtotime($po['due_date'])) : '';
                $po['status'] = 'unpaid';
                $po['notes'] = '';
                $po['created_date'] = date('Y-m-d H:i:s');
                $po['updated_date'] = date('Y-m-d H:i:s');
                $po_id = $this->InsertData($_REQUEST['table_name'], $po);

                $this->db->select('company_name');
                $this->db->from('tbl_vendor');
                $this->db->where('vendor_id',$po['vendor_id']);
                $vendor_name = $this->db->get()->row_array();
                

                $notification = array();
                $notification['garage_id'] = $_SESSION['setting']->garage_id;
                $notification['item_type'] = 'purchase_order';
                $notification['item_id'] = $po_id;
                $notification['description'] = 'Purchase order '.$po_no.' to '.$vendor_name['company_name'].' is created.';
                $notification['date'] = date('Y-m-d H:i:s');
                $this->notification($notification);

                foreach ($_REQUEST['rowItem'] as $k => $v) {
                    $v['po_id'] = $po_id;
                    $this->InsertData('tbl_vendor_bill_item', $v);

                    // update inventory...
                    $inventory = array();
                    $inventory['garage_id'] = $_SESSION['setting']->garage_id;
                    $inventory['item_id'] = $po_id;
                    $inventory['item_type'] = 'po';
                    $inventory['product_id'] = $v['item_id'];
                    $inventory['description'] = 'Purchase record';
                    $inventory['qty'] = $v['qty'];
                    $inventory['date'] = $po['order_date'] != '' ? date('Y-m-d', strtotime($po['order_date'])) : '';
                    $inventory['created_date'] = date('Y-m-d H:i:s');
                    $this->InsertData('tbl_inventory', $inventory);
                }

                $transaction = array();
                $transaction['garage_id'] = $_SESSION['setting']->garage_id;
                $transaction['item_id'] = $po_id;
                $transaction['transaction_type'] = 'bill';
                $transaction['ap_account'] = $_REQUEST['ap_account'];
                $transaction['due_date'] = $po['due_date'] != '' ? date('Y-m-d', strtotime($po['due_date'])) : '';
                $transaction['date'] = $po['order_date'] != '' ? date('Y-m-d', strtotime($po['order_date'])) : '';
                $transaction['amount'] = $po['grand_total'];
                $transaction['vendor_id'] = $po['vendor_id'];
                $transaction['description'] = 'Po.No -'.$po_no;
                $this->InsertData('tbl_transaction', $transaction);

                $status_code = 200;
                $message = "Purchase Order ceated successfully.";
                $returnData = "";

                } else { // update po
                $po = array();
                $po = $_REQUEST['mainItem'];
                $po['garage_id'] = $_SESSION['setting']->garage_id;
                $po['jobcard_id'] = isset($po['jobcard_id']) && $po['jobcard_id'] != '' ? base64_decode($po['jobcard_id']) : 0;
                $po['order_date'] = $po['order_date'] != '' ? date('Y-m-d', strtotime($po['order_date'])) : '';
                $po['due_date'] = $po['due_date'] != '' ? date('Y-m-d', strtotime($po['due_date'])) : '';
                // $po['status'] = 'unpaid';
                $po['notes'] = $po['notes'];
                $po['updated_date'] = date('Y-m-d H:i:s');

                $po_id = base64_decode($_REQUEST['po_id']);
                $cond = array('po_id' => $po_id);
                $this->UpdateData($_REQUEST['table_name'], $po, $cond);

                $this->db->select('po_no');
                $this->db->from('tbl_vendor_bills');
                $this->db->where('po_id',$po_id);
                $poDetail = $this->db->get()->row_array();
            
                $this->db->where('po_id',$po_id);
                $this->db->delete('tbl_vendor_bill_item');

                $this->db->where('item_id',$po_id);
                $this->db->where('item_type','po');
                $this->db->delete('tbl_inventory');

                $this->db->where('vendor_id',$po['vendor_id']);
                $this->db->where('item_id',$po_id);
                $this->db->where('transaction_type','bill');
                $this->db->delete('tbl_transaction');

                foreach ($_REQUEST['rowItem'] as $k => $v) {
                    $v['po_id'] = $po_id;
                    $this->InsertData('tbl_vendor_bill_item', $v);

                    // update inventory...
                    $inventory = array();
                    $inventory['garage_id'] = $_SESSION['setting']->garage_id;
                    $inventory['item_id'] = $po_id;
                    $inventory['item_type'] = 'po';
                    $inventory['product_id'] = $v['item_id'];
                    $inventory['description'] = 'Purchase record';
                    $inventory['qty'] = $v['qty'];
                    $inventory['date'] = $po['order_date'] != '' ? date('Y-m-d', strtotime($po['order_date'])) : '';
                    $inventory['created_date'] = date('Y-m-d H:i:s');
                    $this->InsertData('tbl_inventory', $inventory);
                }
                
                $transaction = array();
                $transaction['garage_id'] = $_SESSION['setting']->garage_id;
                $transaction['item_id'] = $po_id;
                $transaction['transaction_type'] = 'bill';
                $transaction['ap_account'] = $po['ap_account'];
                $transaction['due_date'] = $po['due_date'] != '' ? date('Y-m-d', strtotime($po['due_date'])) : '';
                $transaction['date'] = $po['order_date'] != '' ? date('Y-m-d', strtotime($po['order_date'])) : '';
                $transaction['amount'] = $po['grand_total'];
                $transaction['vendor_id'] = $po['vendor_id'];
                $transaction['description'] = 'Po. No-'.$poDetail['po_no'];
                $this->InsertData('tbl_transaction', $transaction);

                $status_code = 200;
                $message = "Purchase order updated successfully.";
                $returnData = "";
                }
            } else if ($_REQUEST['table_name'] == 'tbl_feedback') {
                $feedback = array();
                $feedback_ans = array();
                
                $feedback['garage_id'] = $_REQUEST['garage_id'];
                $feedback['jobcard_id'] = base64_decode($_REQUEST['jobcard_id']);
                $feedback['rating'] = $_REQUEST['ratings'];
                $feedback['feedback'] = $_REQUEST['overall_feedback'];
                $feedback['created_date'] = date('Y-m-d H:i:s');
                $rec_id = $this->InsertData('tbl_feedback_received', $feedback);

                if(isset($_REQUEST) && $_REQUEST['answer']) {
                    foreach($_REQUEST['answer'] as $k => $v) {
                        $feedback_ans['garage_id'] = $_REQUEST['garage_id'];
                        $feedback_ans['received_id'] = $rec_id;
                        $feedback_ans['question_id'] = $k;
                        $feedback_ans['answer'] = $v;
                        $this->InsertData('tbl_feedback_answers', $feedback_ans);
                    }
                }

                $this->db->select('name,jobcard_no');
                $this->db->from('tbl_jobcard');
                $this->db->join('tbl_customer','tbl_customer.customer_id=tbl_jobcard.customer_id','join');
                $this->db->where('jobcard_id',base64_decode($_REQUEST['jobcard_id']));
                $jobcard_name = $this->db->get()->row_array();

                $notification = array();
                $notification['garage_id'] = $_SESSION['setting']->garage_id;
                $notification['item_type'] = 'feedback';
                $notification['item_id'] = $rec_id;
                $notification['description'] = 'Feedback received from '.$jobcard_name['name'].' for jobcard no '.$jobcard_name['jobcard_no'].'';
                $notification['date'] = date('Y-m-d H:i:s');
                $this->notification($notification);
            } else if ($_REQUEST['table_name'] == 'tbl_ticket') {
            $notification = array();
            $this->db->select('name,jobcard_no');
            $this->db->from('tbl_jobcard');
            $this->db->join('tbl_customer','tbl_customer.customer_id=tbl_jobcard.customer_id','join');
            $this->db->where('jobcard_id',base64_decode($_REQUEST['jobcard_id']));
            $jobcard_name = $this->db->get()->row_array();
            
            if($_REQUEST['ticket_id'] == "") {
                // Get ticket no. 
                $this->db->select('max(ticket_no) as ticket_no');
                $this->db->from('tbl_ticket');
                $this->db->where('garage_id', $_REQUEST['garage_id']);
                $getTicketNo = $this->db->get()->row_array();
                
                if ($getTicketNo['ticket_no'] == NULL) {
                    $ticket_no = 1001;
                } else {
                    $ticket_no = $getTicketNo['ticket_no'] + 1;
                }

                $ticket['garage_id'] = $_SESSION['setting']->garage_id;
                $ticket['jobcard_id'] = base64_decode($_REQUEST['jobcard_id']);
                $ticket['ticket_no'] = $ticket_no;
                $ticket['assign_to'] = $_REQUEST['assign_to'];
                $ticket['description'] = $_REQUEST['description'];
                $ticket['image_path'] = '';
                $ticket['status'] = $_REQUEST['status'];
                $ticket['created_by'] = $_SESSION['data']->user_id;
                $ticket['updated_by'] = $_SESSION['data']->user_id;
                $ticket['created_date'] = date('Y-m-d H:i:s');
                $ticket['updated_date'] = date('Y-m-d H:i:s');
                $tick_id = $this->InsertData('tbl_ticket', $ticket);

                $notification['garage_id'] = $_SESSION['setting']->garage_id;
                $notification['item_type'] = 'ticket';
                $notification['item_id'] = $tick_id;
                $notification['description'] = 'Complaints from '.$jobcard_name['name'].' for jobcard no '.$jobcard_name['jobcard_no'].' is created.';
                $notification['date'] = date('Y-m-d H:i:s');
                $this->notification($notification);
            } else { // update ticket.
                $ticket = array();
                $ticket['status'] = $_REQUEST['status'];
                $ticket['updated_by'] = $_SESSION['data']->user_id;
                $ticket['updated_date'] = date('Y-m-d H:i:s');
                $Condition = array('ticket_id' => $_REQUEST['ticket_id']);
                $this->UpdateData('tbl_ticket', $ticket, $Condition);

                $notification['garage_id'] = $_SESSION['setting']->garage_id;
                $notification['item_type'] = 'ticket';
                $notification['item_id'] = $_REQUEST['ticket_id'];
                $notification['description'] = 'Complaints from '.$jobcard_name['name'].' for jobcard no '.$jobcard_name['jobcard_no'].' is updated.';
                $notification['date'] = date('Y-m-d H:i:s');
                $this->notification($notification);
            }
            } else if ($_REQUEST['table_name'] == 'tbl_po_receive_items') {
                foreach ($_REQUEST['data'] as $k => $v) {
                    $receive_items['po_id'] = $v['po_id'];
                    $receive_items['part_id'] = $v['part_id'];
                    $receive_items['garage_id'] = $_SESSION['setting']->garage_id;
                    $receive_items['received_qty'] = $v['received_qty'];
                    $receive_items['purchase_price'] = $v['purchase_price'];
                    $receive_items['tax_type'] = $v['tax_type'];
                    $receive_items['tax_id'] = $v['tax_id'];
                    $receive_items['tax_amount'] = $v['tax_amount'];
                    $receive_items['total_amount'] = $v['line_total'];
                    $this->InsertData($_REQUEST['table_name'], $receive_items);

                    $Condition = array('po_id' => $v['po_id']);
                    $updatePO['total_amount'] = $v['total_payment'];
                    $updatePO['paid_amount'] = $v['total_paid'];
                    $updatePO['due_amount'] = $v['total_due'];
                    $updatePO['date_received'] = date('Y-m-d');
                    $updatePO['status'] = 'close';
                    $updatePO['updated_date'] = date('Y-m-d H:i:s');
                    $this->UpdateData('tbl_vendor_bills', $updatePO, $Condition);
                }
            } else if ($_REQUEST['table_name'] == 'tbl_packages') {
                if(isset($_REQUEST['mainItem']) && !empty($_REQUEST['mainItem']) && $_REQUEST['package_id'] == '') {
                    $_REQUEST['mainItem']['garage_id'] = $this->session->userdata['setting']->garage_id;
                    $_REQUEST['mainItem']['is_disc_applicable'] = $_SESSION['setting']->show_discount_column;
                    $_REQUEST['mainItem']['is_tax_applicable'] = $_SESSION['setting']->gst_applicable;
                    $_REQUEST['mainItem']['created_date'] = date('Y-m-d H:i:s');
                    $_REQUEST['mainItem']['updated_date'] = date('Y-m-d H:i:s');

                    $package_id = $this->InsertData('tbl_packages', $_REQUEST['mainItem']);
                } else {
                    $package_id = base64_decode($_REQUEST['package_id']);
                    $_REQUEST['mainItem']['updated_date'] = date('Y-m-d H:i:s');

                    $Condition = array('package_id' => $package_id);
                    $this->UpdateData('tbl_packages', $_REQUEST['mainItem'], $Condition);
                }

                if ($_REQUEST['rowItem'] && !empty($_REQUEST['rowItem']) && $package_id != '') {
                    $this->db->where('package_id', $package_id);
                    $this->db->delete('tbl_package_item');
                    
                    foreach ($_REQUEST['rowItem'] as $r => $t) {
                        $t['package_id'] = $package_id;
                        $this->InsertData('tbl_package_item', $t);
                    }
                }


            } else if ($_REQUEST['table_name'] == 'tbl_vendor_bill_item') {
                $notification = array();
                if($_REQUEST['action'] == 'order') {
                    $vehicles = $_REQUEST['mainObj']['vehicle_id'] != "" ? explode("_",$_REQUEST['mainObj']['vehicle_id']) : [];
                    unset($_REQUEST['mainObj']['vehicle_id']);
                    $mainArray = array();
                    $mainArray = $_REQUEST['mainObj'];
                    $mainArray['due_date'] = date('Y-m-d', strtotime($_REQUEST['mainObj']['due_date']));
                    $mainArray['order_date'] = date('Y-m-d', strtotime($_REQUEST['mainObj']['order_date']));
                    $mainArray['garage_id'] = $_SESSION['setting']->garage_id;
                    if($vehicles && !empty($vehicles)) {
                        $mainArray['variant_id'] = $vehicles[0];
                        $mainArray['model_id'] = $vehicles[1];
                        $mainArray['make_id'] = $vehicles[2];
                    }
                
                    if (isset($_REQUEST['mainObj']['po_id']) && $_REQUEST['mainObj']['po_id'] != "") {
                        $po_id = $_REQUEST['mainObj']['po_id'];
                        $mainArray['updated_date'] = date('Y-m-d H:i:s');
                        $Condition = array('po_id' => $_REQUEST['mainObj']['po_id']);
                        unset($mainArray['po_id']);
                        $this->UpdateData('tbl_vendor_bills', $mainArray, $Condition);
                        $this->db->where('po_id', $po_id);
                        $this->db->delete('tbl_vendor_bill_item');

                        $this->db->select('po_no');
                        $this->db->from('tbl_vendor_bills');
                        $this->db->where('po_id', $_REQUEST['mainObj']['po_id']);
                        $po_row = $this->db->get()->row_array();
                        
                        $notification['garage_id'] = $_SESSION['setting']->garage_id;
                        $notification['item_type'] = 'purchase_order';
                        $notification['item_id'] = $_REQUEST['mainObj']['po_id'];
                        $notification['description'] = 'Purchase order '.$po_row['po_no'].' is updated.';
                        $notification['date'] = date('Y-m-d H:i:s');
                        $this->notification($notification);
                        
                    } else {
                        $this->db->select('max(po_no) as max_po');
                        $this->db->from('tbl_vendor_bills');
                        $this->db->where('garage_id', $_SESSION['setting']->garage_id);
                        $po_row = $this->db->get()->row_array();

                        if ($po_row['max_po'] == NULL) {
                            $po_no = '1001';
                        } else {
                            $po_no = $po_row['max_po'] + 1;
                        }

                        $mainArray['po_no'] = $po_no;
                        $mainArray['status'] = "in_progress";
                        $mainArray['created_date'] = date('Y-m-d H:i:s');
                        $mainArray['updated_date'] = date('Y-m-d H:i:s');
                        $po_id = $this->InsertData('tbl_vendor_bills', $mainArray);

                        $notification['garage_id'] = $_SESSION['setting']->garage_id;
                        $notification['item_type'] = 'purchase_order';
                        $notification['item_id'] = $_REQUEST['mainObj']['po_id'];
                        $notification['description'] = 'Purchase order '.$po_no.' is created.';
                        $notification['date'] = date('Y-m-d H:i:s');
                        $this->notification($notification);
                    }

                    foreach ($_REQUEST['itemObj'] as $k => $v) {
                        $this->db->select('tbl_parts.*,tbl_tax_rate.rate');
                        $this->db->from('tbl_parts');
                        $this->db->join('tbl_tax_rate','tbl_tax_rate.tax_id=tbl_parts.tax_id','left');
                        $this->db->where('part_id',$v['part_id']);
                        $partData = $this->db->get()->row_array();

                        $total_price = $v['qty'] * $v['unit_cost'];
                        $tax_value = 0;
                        $tax_rate = 0;
                        if($partData['rate'] != "" && $partData['rate'] != 0) {
                            $tax_rate = $partData['rate'];
                            $tax_value = $total_price * $partData['rate'];
                        }

                        if($partData['tax_type'] == 'Exclusive') {
                            $total_price = $total_price + $tax_value;
                        }
                        $margin_value = 0;

                        if ($partData['margin_value'] != "" && $partData['margin_type'] == '%') {
                            $margin_value = $total_price * $partData['margin_value'] / 100;
                        } else if($partData['margin_value'] != "" && $partData['margin_type'] == 'Fix') {
                            $margin_value = $total_price + $partData['margin_value'];
                        }
                        $mrp = ($total_price + $margin_value) / $v['qty'];

                        $poItem['po_id'] = $po_id;
                        $poItem['part_id'] = $v['part_id'];
                        $poItem['description'] = $v['description'];
                        $poItem['ordered_qty'] = $v['qty'];
                        $poItem['received_qty'] = $v['qty'];
                        $poItem['per_unit_price'] = $v['unit_cost'];
                        $poItem['total_amount'] = $v['qty'] * $v['unit_cost'];
                        $poItem['tax_rate'] = $tax_rate;
                        $poItem['tax_type'] = $partData['tax_type'];
                        $poItem['tax_value'] = $tax_value;
                        $poItem['margin_type'] = $partData['margin_type'];
                        $poItem['margin_value'] = $partData['margin_value'];
                        $poItem['mrp'] = $mrp;
                        
                        $this->InsertData('tbl_vendor_bill_item', $poItem);
                    }
                    $status_code = 200;
                    $message = "Purchase order created successfully.";
                } else if($_REQUEST['action'] == 'update') {
                    $data = array();
                    parse_str($_REQUEST['data'], $data);
                    $total_purchase_price = 0;
                    foreach ($data['part_id'] as $k => $part_id) {
                        $total_price = $data['received_qty'][$k] * $data['per_unit_price'][$k];
                        $cost_price = ($data['received_qty'][$k] * $data['per_unit_price'][$k]) / $data['received_qty'][$k];
                        $sell_price = $cost_price;
                        
                        $tax_value = 0;
                        $tax_rate = 0;
                        if($data['tax_rate'][$k] != "" && $data['tax_rate'][$k] != 0) {
                            $tax_rate = $data['tax_rate'][$k];
                            $tax_value = $total_price * ($data['tax_rate'][$k] / 100);
                        }

                        if($data['tax_type'][$k] == 'Exclusive') {
                            $total_price = $total_price + $tax_value;
                        }
                        
                        if ($data['margin_value'][$k] != "" && $data['margin_type'][$k] == '%') {
                            $margin_amount = $total_price * $data['margin_value'][$k] / 100;
                            $sell_price = $cost_price + $cost_price * $data['margin_value'][$k] / 100;
                        } else if($data['margin_value'][$k] != "" && $data['margin_type'][$k] == 'Fix') {
                            $margin_amount = $data['margin_value'][$k];
                            $sell_price = $cost_price + $data['margin_value'][$k];
                        }

                        $poItem['ordered_qty'] = $data['ordered_qty'][$k];
                        $poItem['received_qty'] = $data['received_qty'][$k];
                        $poItem['per_unit_price'] = $data['per_unit_price'][$k];
                        $poItem['total_amount'] = $data['total_amount'][$k];
                        $poItem['tax_rate'] = $data['tax_rate'][$k];
                        $poItem['tax_type'] = $data['tax_type'][$k];
                        $poItem['tax_value'] = $tax_value;
                        $poItem['margin_type'] =  $data['margin_type'][$k];
                        $poItem['margin_value'] = $data['margin_value'][$k];
                        $poItem['margin_amount'] = $margin_amount;                        
                        $poItem['mrp'] = $data['mrp'][$k];

                        $cond = array('po_item_id' => $data['po_item_id'][$k]);
                        $this->UpdateData('tbl_vendor_bill_item', $poItem,$cond);
                        $total_purchase_price += $data['total_amount'][$k];
                        // update part master.
                        $partMaster = array();
                        $partMaster['tax_type'] = $data['tax_type'][$k];
                        $partMaster['margin_type'] = $data['margin_type'][$k];
                        $partMaster['margin_value'] = $data['margin_value'][$k];
                        $partMaster['cost_price'] = $cost_price;
                        $partMaster['sell_price'] = $sell_price;

                        $condtion = array('part_id' => $part_id);
                        $this->UpdateData('tbl_parts', $partMaster,$condtion);
                        }
                        $condtion = array('po_id' => $data['po_id']);
                        $this->UpdateData('tbl_vendor_bills', array('total_amount' => $total_purchase_price),$condtion);
                } else if($_REQUEST['action'] == 'payment') {
                    $data = array();
                    parse_str($_REQUEST['payment_form'], $data);
                    
                    $this->db->select("max(invoice_no) as max_invoice_no");
                    $this->db->from("tbl_invoices");
                    $this->db->where("garage_id",$_SESSION['setting']->garage_id);
                    $this->db->where("item_type","vendor_invoice");
                    $next_inv_no = $this->db->get()->row_array();

                   if($next_inv_no['max_invoice_no'] == NULL || $next_inv_no['max_invoice_no'] == '') {
                       $next_invoice_no = 1001;
                   } else {
                       $next_invoice_no = $next_inv_no['max_invoice_no'] + 1;
                   }
                   
                   if($data['is_paid_credit'] == 'credit') {
                     $invoice_status = 'credit';
                   } else if($data['paid_amount'] == $data['total_payable']) {
                     $invoice_status = 'paid';
                   } else if($data['paid_amount'] == '' || $data['paid_amount'] == 0) {
                     $invoice_status = 'payment_due';
                   } else {
                     $invoice_status = 'partial_paid';
                   }
                    // invoice detail
                    $invoice = array();
                    $invoice['garage_id'] = $_SESSION['setting']->garage_id;
                    $invoice['item_id'] = $_REQUEST['po_id'];
                    $invoice['item_type'] = 'vendor_invoice';
                    $invoice['payment_term'] = $data['payment_terms'];
                    $invoice['customer_id'] = $_REQUEST['vendor_id'];
                    $invoice['invoice_no'] = $next_invoice_no;
                    $invoice['date'] = date('Y-m-d',strtotime($data['date']));
                    $invoice['due_date'] = date('Y-m-d',strtotime($data['due_date']));
                    $invoice['amount'] = $data['total_payable'];
                    $invoice['status'] = $invoice_status;
                    $invoice['notes'] = $data['notes'];
                    $invoice['created_date'] = date('Y-m-d H:i:s');
                    $invoice_id = $this->InsertData('tbl_invoices', $invoice);

                    $notification = array();
                    $notification['garage_id'] = $_SESSION['setting']->garage_id;
                    $notification['item_type'] = 'purchase_order_invoice';
                    $notification['item_id'] = $invoice_id;
                    $notification['description'] = 'Purchase invoice '.$next_invoice_no.' is created.';
                    $notification['date'] = date('Y-m-d H:i:s');
                    $this->notification($notification);
                    

                    if($data['is_paid_credit'] == 'paid') {
                        // Payment detail...
                        $payment = array();
                        $payment['garage_id'] = $_SESSION['setting']->garage_id;
                        $payment['item_id'] = $invoice_id;
                        $payment['item_type'] = 'vendor_payment';
                        $payment['customer_id'] = $_REQUEST['vendor_id'];
                        $payment['date'] = date('Y-m-d',strtotime($data['date']));
                        $payment['payment_type_id'] = $data['payment_type'];
                        $payment['amount'] = $data['paid_amount'];
                        $payment['reference_no'] = '';
                        $payment['cheque_no'] = '';  
                        $payment['created_date'] = date('Y-m-d H:i:s');
                        $payment['created_by'] = $_SESSION['data']->user_id;
                        $this->InsertData('tbl_payments', $payment);

                        $this->db->select('company_name');
                        $this->db->from('tbl_vendor');
                        $this->db->where('vendor_id',$_REQUEST['vendor_id']);
                        $vendor_row = $this->db->get()->row_array();

                        $notification = array();
                        $notification['garage_id'] = $_SESSION['setting']->garage_id;
                        $notification['item_type'] = 'purchase_order_payment';
                        $notification['item_id'] = $invoice_id;
                        $notification['description'] = 'Payment of '.$data['paid_amount'].' to vendor '.$vendor_row['company_name'].' is received.';
                        $notification['date'] = date('Y-m-d H:i:s');
                        $this->notification($notification);
                    }
                    $condtion = array('po_id' => $_REQUEST['po_id']);
                    $this->UpdateData('tbl_vendor_bills', array('status' => 'close'),$condtion);
                } else if($_REQUEST['action'] == 'add_payment') {
                    $data = array();
                    parse_str($_REQUEST['payment_form'], $data);
                    
                    $payment = array();
                    $payment['garage_id'] = $_SESSION['setting']->garage_id;
                    $payment['item_id'] = $data['payment_invoice_id'];
                    $payment['item_type'] = 'vendor_payment';
                    $payment['customer_id'] = $data['payment_vendor_id'];
                    $payment['date'] = date('Y-m-d',strtotime($data['vendor_date']));
                    $payment['payment_type_id'] = $data['vendor_payment_type'];
                    $payment['amount'] = $data['amount_to_be_paid'];
                    $payment['reference_no'] = $data['vendor_reference_no'];
                    $payment['cheque_no'] = '';  
                    $payment['created_date'] = date('Y-m-d H:i:s');
                    $payment['created_by'] = $_SESSION['data']->user_id;
                    $this->InsertData('tbl_payments', $payment);

                    $this->db->select('company_name');
                    $this->db->from('tbl_vendor');
                    $this->db->where('vendor_id',$data['payment_vendor_id']);
                    $vendor_row = $this->db->get()->row_array();

                    $notification = array();
                    $notification['garage_id'] = $_SESSION['setting']->garage_id;
                    $notification['item_type'] = 'purchase_order_payment';
                    $notification['item_id'] = $data['payment_invoice_id'];
                    $notification['description'] = 'Payment of '.$data['amount_to_be_paid'].' to vendor '.$vendor_row['company_name'].' is received.';
                    $notification['date'] = date('Y-m-d H:i:s');
                    $this->notification($notification);
                }
            } else if ($_REQUEST['table_name'] == 'tbl_vendor_invoices') {
                $this->db->select('max(invoice_no) as max_invoice_no');
                $this->db->from('tbl_invoices');
                $this->db->where('garage_id', $_SESSION['setting']->garage_id);
                $this->db->where('item_type', 'vendor_invoice');
                $vendor_inv_row = $this->db->get()->row_array();

                if ($vendor_inv_row['max_invoice_no'] == NULL) {
                    $inv_no = '1001';
                } else {
                    $inv_no = $vendor_inv_row['max_invoice_no'] + 1;
                }

                $mainArray = array();
                $mainArray = $_REQUEST['mainObj'];
                $mainArray['date'] = date('Y-m-d', strtotime($_REQUEST['mainObj']['date']));
                $mainArray['due_date'] = date('Y-m-d', strtotime($_REQUEST['mainObj']['date']));
                $mainArray['garage_id'] = $_SESSION['setting']->garage_id;


                if (isset($_REQUEST['mainObj']['invoice_id']) && $_REQUEST['mainObj']['invoice_id'] != "") {
                    $invoice_id = $_REQUEST['mainObj']['invoice_id'];
                    $mainArray['updated_date'] = date('Y-m-d H:i:s');
                    $Condition = array('invoice_id' => $_REQUEST['mainObj']['invoice_id']);
                    unset($mainArray['invoice_id']);
                    $this->UpdateData('tbl_invoices', $mainArray, $Condition);
                    $this->db->where('invoice_id', $invoice_id);
                    $this->db->delete('tbl_vendor_invoice_items');

                    $this->db->select('amount,company_name');
                    $this->db->from('tbl_invoices');
                    $this->db->join('tbl_vendor','tbl_vendor.vendor_id=tbl_invoices.customer_id','left');
                    $this->db->where('invoice_id',$_REQUEST['mainObj']['invoice_id']);
                    $vendor_row = $this->db->get()->row_array();

                    $notification = array();
                    $notification['garage_id'] = $_SESSION['setting']->garage_id;
                    $notification['item_type'] = 'purchase_order_invoice';
                    $notification['item_id'] = $_REQUEST['mainObj']['invoice_id'];
                    $notification['description'] = 'Purchase order invoice of '.$vendor_row['amount'].' to vendor '.$vendor_row['company_name'].' is updated.';
                    $notification['date'] = date('Y-m-d H:i:s');
                    $this->notification($notification);                    
                } else {
                    $mainArray['invoice_no'] = $inv_no;
                    $mainArray['status'] = "payment_due";
                    $mainArray['created_date'] = date('Y-m-d H:i:s');
                    $invoice_id = $this->InsertData('tbl_invoices', $mainArray);

                    $notification = array();
                    $notification['garage_id'] = $_SESSION['setting']->garage_id;
                    $notification['item_type'] = 'purchase_order_invoice';
                    $notification['item_id'] = $_REQUEST['mainObj']['invoice_id'];
                    $notification['description'] = 'Purchase invoice '.$inv_no.' is created.';
                    $notification['date'] = date('Y-m-d H:i:s');
                    $this->notification($notification);                    
                }


                foreach ($_REQUEST['itemObj'] as $k => $v) {
                    $itemArray = array();
                    $itemArray['invoice_id'] = $invoice_id;
                    $itemArray['part_id'] = $v['part_id'];
                    $itemArray['description'] = $v['description'];
                    $itemArray['qty'] = $v['qty'];
                    $itemArray['unit_cost'] = $v['unit_cost'];
                    $itemArray['line_total'] = $v['line_total'];
                    $this->InsertData('tbl_vendor_invoice_items', $itemArray);
                }

                $status_code = 200;
                $message = "Vendor Invoice Created Successfully.";
            } else if($_REQUEST['table_name'] == 'tbl_jobcard') {
                if($_REQUEST['action'] != 'create_invoice' && $_REQUEST['action'] != 'add_payment') {
                    if($_REQUEST['action'] != "") {
                        $_REQUEST['mainItem']['status'] = $_REQUEST['action'] == 'create_invoice' ? 'payment_due' : $_REQUEST['action'];
                    }                
                    $_REQUEST['mainItem']['date'] = $_REQUEST['mainItem']['date'] != '' ? date('Y-m-d', strtotime($_REQUEST['mainItem']['date'])) : '';
                    $_REQUEST['mainItem']['expt_delivery_date'] = $_REQUEST['mainItem']['expt_delivery_date'] != '' ? date('Y-m-d', strtotime($_REQUEST['mainItem']['expt_delivery_date'])) : '';
                    if(isset($_REQUEST['mainItem']) && !empty($_REQUEST['mainItem']) && $_REQUEST['jobcard_id'] == '') {
                        $this->db->select("max(jobcard_no) as max_jobcard_no");
                        $this->db->from("tbl_jobcard");
                        $this->db->where("garage_id",$_SESSION['setting']->garage_id);
                        $next_job_no = $this->db->get()->row_array();
                        if(($next_job_no['max_jobcard_no'] == NULL || $next_job_no['max_jobcard_no'] == '') && $_SESSION['setting']->jobcard_no_start != "") {
                            $jobcard_no = $_SESSION['setting']->jobcard_no_start;
                        } else if($next_job_no['max_jobcard_no'] == NULL || $next_job_no['max_jobcard_no'] == '') {
                            $jobcard_no = 1001;
                        } else {
                            $jobcard_no = $next_job_no['max_jobcard_no'] + 1;
                        }

                        $_REQUEST['mainItem']['garage_id'] = $this->session->userdata['setting']->garage_id;
                        $_REQUEST['mainItem']['created_date'] = date('Y-m-d H:i:s');
                        $_REQUEST['mainItem']['updated_date'] = date('Y-m-d H:i:s');
                        $_REQUEST['mainItem']['is_disc_applicable'] = $_SESSION['setting']->show_discount_column;
                        $_REQUEST['mainItem']['is_tax_applicable'] = $_SESSION['setting']->gst_applicable;
                        $_REQUEST['mainItem']['jobcard_no'] = $jobcard_no; 
                        $jobcard_id = $this->InsertData('tbl_jobcard', $_REQUEST['mainItem']);
                    } else {
                        $_REQUEST['mainItem']['updated_date'] = date('Y-m-d H:i:s');
                        $jobcard_id = base64_decode($_REQUEST['jobcard_id']);
                        $Condition = array('jobcard_id' => base64_decode($_REQUEST['jobcard_id']));
                        $this->UpdateData('tbl_jobcard', $_REQUEST['mainItem'], $Condition);

                        $this->db->select('jobcard_no');
                        $this->db->from('tbl_jobcard');
                        $this->db->where('jobcard_id',$jobcard_id);
                        $job_rows = $this->db->get()->row_array();

                        $jobcard_no = $job_rows['jobcard_no'];
                    }
                $notification = array();
                if($_REQUEST['action'] == 'estimate_created') {
                    $message = 'Estimate created successfully !';

                    $notification['garage_id'] = $_SESSION['setting']->garage_id;
                    $notification['item_type'] = 'estimate';
                    $notification['item_id'] = $jobcard_id;
                    $notification['description'] = 'Estimate '.$jobcard_no.' is created.';
                    $notification['date'] = date('Y-m-d H:i:s');
                    $this->notification($notification);         
                } else if($_REQUEST['action'] == 'create_invoice') {
                    $message = 'Invoice created successfully !';     
                } else {
                    $message = 'Jobcard updated successfully !';
                }

                if ($_REQUEST['rowItem'] && !empty($_REQUEST['rowItem']) && $jobcard_id != '') {
                    $this->db->where('jobcard_id', $jobcard_id);
                    $this->db->delete('tbl_jobcard_item');
                    
                    foreach ($_REQUEST['rowItem'] as $r => $t) {
                        $t['jobcard_id'] = $jobcard_id;
                        $this->InsertData('tbl_jobcard_item', $t);
                    }
                }
                $status_code = 200;
                $returnData = array('jobcard_id' => base64_encode($jobcard_id));
                } else  {
                    $total_payment_made = 0;
                    $total_invoice_amount = 0;
                    if($_REQUEST['jobcard_id'] != "") {
                        $jobcard_id = base64_decode($_REQUEST['jobcard_id']);
                        
                        $job_invoice = array();
                        $this->db->select('*');
                        $this->db->from('tbl_invoices');
                        $this->db->where('item_id', $jobcard_id);
                        $this->db->where('item_type', 'job_invoice');
                        $job_invoice = $this->db->get()->row_array();

                        if ($job_invoice && !empty($job_invoice)) {
                            $total_invoice_amount = $job_invoice['amount'];
                        } else {
                            $this->db->select('*');
                            $this->db->from('tbl_jobcard');
                            $this->db->where('jobcard_id', $jobcard_id);
                            $job_card = $this->db->get()->row_array();
                            $total_invoice_amount = $job_card['grand_total'];
                        }
                        
                        $this->db->select('sum(amount) as total_paid');
                        $this->db->from('tbl_transaction');
                        $this->db->where('item_id',$job_invoice['invoice_id']);
                        $this->db->where('customer_id',$job_invoice['customer_id']);
                        $this->db->where('transaction_type','customer_payment');
                        $all_payment_made = $this->db->get()->row_array();
                        $total_payment_made = 0;
                        if ($all_payment_made && $all_payment_made['total_paid'] != NULL) {
                              $total_payment_made += $all_payment_made['total_paid'];
                        }
                    } else {
                        $total_invoice_amount = $_REQUEST['mainItem']['grand_total'];
                    }
                    $status_code = 300;
                    $returnData = array('payment_made' => $total_payment_made, 'payment_due' => $total_invoice_amount, 'difference' => abs($total_payment_made - $total_invoice_amount));
                }
            } else if($_REQUEST['table_name'] == 'tbl_transaction_po') {
                $data = array();
                parse_str($_REQUEST['data'], $data);
                $this->db->select('*');
                $this->db->from('tbl_vendor_bills');
                $this->db->where('po_id',base64_decode($data['po_id']));
                $poDetails = $this->db->get()->row_array();

                $this->db->select('sum(amount) as total_paid');
                $this->db->from('tbl_transaction');
                $this->db->where('item_id',base64_decode($data['po_id']));
                $this->db->where('transaction_type','bill_payment');
                $this->db->where('vendor_id',$poDetails['vendor_id']);
                $paid_details = $this->db->get()->row_array();
                
                $total_due = $poDetails['grand_total'] - ($paid_details['total_paid'] + $data['amount']);

                if($total_due == $poDetails['grand_total']) {
                    $status = 'unpaid';
                } else if($total_due == 0) {
                    $status = 'paid';
                } else if($total_due != $poDetails['grand_total']) {
                    $status = 'partial_paid';
                }

                $cond = array('po_id' => base64_decode($data['po_id']));
                $this->UpdateData('tbl_vendor_bills', array('status' => $status), $cond);


                $transaction = array();
                $transaction['garage_id'] = $_SESSION['setting']->garage_id;
                $transaction['item_id'] = base64_decode($data['po_id']);
                $transaction['transaction_type'] = 'bill_payment';
                $transaction['ap_account'] = $data['ap_account'];
                $transaction['paid_from'] = $data['paid_from'];
                $transaction['date'] = date('Y-m-d',strtotime($data['payment_date']));
                $transaction['amount'] = $data['amount'];
                $transaction['vendor_id'] = $poDetails['vendor_id'];
                $transaction['description'] = 'Po. No-'.$poDetails['po_no'];
                $this->InsertData('tbl_transaction', $transaction);
                $status_code = 200;
            } else if($_REQUEST['table_name'] == 'tbl_vendor_bill_item_update') {
                if($_REQUEST && $_REQUEST['data']) {
                    foreach($_REQUEST['data'] as $k => $v) {
                        $primary_key = $v['po_item_id'];
                        unset($v['po_item_id']);
                        $Condition = array('po_item_id' => $primary_key);
                        $this->UpdateData('tbl_vendor_bill_item', $v, $Condition);
                    }
                    if($_REQUEST['btnType'] == 1) { // receive order..
                     $order_date = $_REQUEST['order_date'] != "" ? date("Y-m-d",strtotime($_REQUEST['order_date'])): '';
                     $updateArray = array('vendor_bill_no' => $_REQUEST['bill_no'],'order_date' => $order_date,'total_amount'=> $_REQUEST['actual_amount'],'status'=>'close','updated_date' => date('Y-m-d H:i:s'));
                     $Condition = array('po_id' => $_REQUEST['data'][0]['po_id']);
                     $this->UpdateData('tbl_vendor_bills', $updateArray, $Condition);
                    
                     $this->db->select('tbl_vendor_bills.vendor_id,tbl_vendor.payment_term');
                     $this->db->from('tbl_vendor_bills');
                     $this->db->join('tbl_vendor','tbl_vendor.vendor_id = tbl_vendor_bills.vendor_id','left');
                     $this->db->where('po_id',$_REQUEST['data'][0]['po_id']);
                     $poData = $this->db->get()->row_array();

                     $payment_term = 0;
                     $payment_status = 'payment_due';
                     $due_date = date('Y-m-d');
                     $is_credit = 'N';

                     $this->db->select('payment_type,name');
                     $this->db->from('tbl_payment_type');
                     $this->db->where('garage_id',$_SESSION['setting']->garage_id);
                     $this->db->where('is_active',1);
                     $this->db->where('name','Credit');
                     $get_payment_type_id = $this->db->get()->row_array();

                     if($poData && $poData['payment_term'] != '' && $get_payment_type_id && $get_payment_type_id['payment_type'] == $_REQUEST['pay_type']) {
                       $payment_term = $poData['payment_term'];
                       $due_date = date('Y-m-d', strtotime(date('Y-m-d'). ' + '.$payment_term.' days'));
                       $payment_status = 'credit';
                       $is_credit = 'Y';
                     }

                     $this->db->select("max(invoice_no) as max_invoice_no");
                     $this->db->from("tbl_invoices");
                     $this->db->where("garage_id",$_SESSION['setting']->garage_id);
                     $this->db->where("item_type","vendor_invoice");
                     $next_inv_no = $this->db->get()->row_array();

                    if($next_inv_no['max_invoice_no'] == NULL || $next_inv_no['max_invoice_no'] == '') {
                        $next_invoice_no = 1001;
                    } else {
                        $next_invoice_no = $next_inv_no['max_invoice_no'] + 1;
                    }


                    $invoice_entry = array();
                    $invoice_entry['item_id'] = $_REQUEST['data'][0]['po_id'];
                    $invoice_entry['item_type'] = 'vendor_invoice';
                    $invoice_entry['payment_term'] = $payment_term;
                    $invoice_entry['garage_id'] = $_SESSION['setting']->garage_id;
                    $invoice_entry['customer_id'] = $poData['vendor_id'];
                    $invoice_entry['invoice_no'] = $next_invoice_no;
                    $invoice_entry['date'] = date('Y-m-d');
                    $invoice_entry['due_date'] = $due_date;
                    $invoice_entry['amount'] = $_REQUEST['actual_amount'];
                    $invoice_entry['status'] = $payment_status;
                    $invoice_entry['notes'] = '';
                    $invoice_entry['created_date'] = date('Y-m-d H:i:s');

                    $invoice_id = $this->InsertData('tbl_invoices', $invoice_entry);

                    $notification = array();
                    $notification['garage_id'] = $_SESSION['setting']->garage_id;
                    $notification['item_type'] = 'vendor_invoice';
                    $notification['item_id'] = $poData['vendor_id'];
                    $notification['description'] = 'Purchase invoice '.$next_invoice_no.' is created.';
                    $notification['date'] = date('Y-m-d H:i:s');
                    $this->notification($notification);

                    if($is_credit == 'N') {
                        $payment_entry = array();
                        $payment_entry['garage_id'] = $_SESSION['setting']->garage_id;
                        $payment_entry['item_id'] = $invoice_id;
                        $payment_entry['item_type'] = 'vendor_payment';
                        $payment_entry['customer_id'] = $poData['vendor_id'];
                        $payment_entry['date'] = date('Y-m-d H:i:s');
                        $payment_entry['payment_type_id'] = $_REQUEST['pay_type'];
                        $payment_entry['amount'] = $_REQUEST['actual_amount'];
                        $payment_entry['reference_no'] = '';
                        $payment_entry['cheque_no'] = '';
                        $payment_entry['created_date'] = date('Y-m-d H:i:s');
                        $payment_entry['created_by'] = $_SESSION['data']->user_id;

                        $this->InsertData('tbl_payments', $payment_entry);

                        $this->db->select('company_name');
                        $this->db->from('tbl_vendor');
                        $this->db->where('vendor_id',$poData['vendor_id']);
                        $vendor_rows = $this->db->get()->row_array();

                        $notification = array();
                        $notification['garage_id'] = $_SESSION['setting']->garage_id;
                        $notification['item_type'] = 'vendor_payment';
                        $notification['item_id'] = $poData['vendor_id'];
                        $notification['description'] = 'Payment of '.$_REQUEST['actual_amount'].' to vendor '.$vendor_rows['company_name'].' is received.';
                        $notification['date'] = date('Y-m-d H:i:s');
                        $this->notification($notification);
                    }

                    } else if($_REQUEST['btnType'] == 2) {
                        $this->db->where('po_id',$_REQUEST['data'][0]['po_id']);
                        $this->db->delete('tbl_vendor_bills');

                        $this->db->where('po_id',$_REQUEST['data'][0]['po_id']);
                        $this->db->delete('tbl_vendor_bill_item');

                        $this->db->where('item_id',$_REQUEST['data'][0]['po_id']);
                        $this->db->where('item_type','vendor_invoice');
                        $this->db->delete('tbl_vendor_bill_item');

                    }
                    $message = 'Order received successfully.';
                    $status_code = 200;
                }
            } else if($_REQUEST['table_name'] == 'tbl_tax_rate') {
                $garage_id = $_SESSION['setting']->garage_id;
                $cond = array('garage_id' => $garage_id);
                $this->UpdateData('tbl_tax_rate', array('is_active' => 0), $cond);
                $taxRates = array();
                parse_str($_REQUEST['data'], $taxRates);
                if(isset($taxRates['name']) && !empty($taxRates['name'])) {
                    foreach ($taxRates['name'] as $tkey => $txname) {
                        $taxData = array();
                        $taxData['garage_id']  = $garage_id;
                        $taxData['name']  = $txname;
                        $taxData['description']  = $taxRates['description'][$tkey];
                        $taxData['HSN']  = $taxRates['HSN'][$tkey];
                        $taxData['rate']  = $taxRates['rate'][$tkey];
                        $taxData['is_active']  = 1;                        
                        $this->InsertData('tbl_tax_rate', $taxData);
                    }
                }
            } else if($_REQUEST['table_name'] == 'tbl_settings') {
                $garage_id = $_SESSION['setting']->garage_id;
                if (!isset($garage_id) && $garage_id == 0) {
                    echo json_encode(array('status' => 400, 'message' => "Your session has expiry ! you will be logged out"));
                    return false;
                }
                $notification = array();
                if($_REQUEST['tabIndex'] == 2) {
                    $invoiceJobcard = array();
                    parse_str($_REQUEST['data'], $invoiceJobcard);
                    if (isset($invoiceJobcard) && !empty($invoiceJobcard)) {
                        $Condition = array('garage_id' => $garage_id);
                        $this->UpdateData('tbl_garage', $invoiceJobcard, $Condition);
                    }

                    $notification['garage_id'] = $_SESSION['setting']->garage_id;
                    $notification['item_type'] = 'garage';
                    $notification['item_id'] = $garage_id;
                    $notification['description'] = 'Garage details is updated.';
                    $notification['date'] = date('Y-m-d H:i:s');
                    $this->notification($notification);

                } else if($_REQUEST['tabIndex'] == 3) {
                    $cond = array('garage_id' => $garage_id);
                    $this->UpdateData('tbl_payment_type', array('is_active' => 0), $cond);
                    $paymentType = array();
                    parse_str($_REQUEST['data'], $paymentType);
                    foreach ($paymentType['name'] as $ptkey => $ptname) {
                        $paymentTypeData = array();
                        $paymentTypeData['garage_id']  = $garage_id;
                        $paymentTypeData['name']  = $ptname;
                        $paymentTypeData['is_active']  = 1;
                        $paymentTypeData['created_date']  = date("Y-m-d H:i:s");
                        $paymentTypeData['updated_date']  = date("Y-m-d H:i:s");
                        $this->InsertData('tbl_payment_type', $paymentTypeData);
                    }
                    
                    $notification['garage_id'] = $_SESSION['setting']->garage_id;
                    $notification['item_type'] = 'payment_type';
                    $notification['item_id'] = $garage_id;
                    $notification['description'] = 'Payment master is updated.';
                    $notification['date'] = date('Y-m-d H:i:s');
                    $this->notification($notification);

                } else if($_REQUEST['tabIndex'] == 4) {
                    $commArray = array();
                    parse_str($_REQUEST['data'], $commArray);
                    if (isset($commArray) && !empty($commArray)) {
                        $Condition = array('garage_id' => $garage_id);
                        $this->UpdateData('tbl_garage', $commArray, $Condition);
                    }
                } else {
                    $defaults = array();
                    $dataArray = array();
                    parse_str($_REQUEST['data'], $defaults);
                    foreach ($defaults['default'] as $k => $v) {
                        $dataArray['default_' . $k] = $v;
                    }
                    $Condition = array('garage_id' => $garage_id);  
                    $this->UpdateData('tbl_garage', $dataArray, $Condition);

                }
                $settings = $this->db->select('*')->from('tbl_garage')->where('garage_id', $garage_id)->get()->row();
                $this->session->set_userdata('setting', $settings);
            } else if($_REQUEST['table_name'] == 'tbl_email_sms_buffer') {
                $templateForm = array();
                parse_str($_REQUEST['form'], $templateForm);
                if($templateForm['item_id'] != "" && $templateForm['template_id'] != "" && ($_REQUEST['email_chk'] == 'Y' || $_REQUEST['sms_chk'] == 'Y')) {
                    // get template.
                    $templateForm['email_chk'] = $_REQUEST['email_chk'];
                    $templateForm['sms_chk'] = $_REQUEST['sms_chk'];
                    $this->replacePlaceholder($templateForm);
                }
                $status_code = 200;
                $message = "Communication send successfully !";
            } else if($_REQUEST['table_name'] == 'tbl_service_reminder_setting') {
                $garageDetail = array();
                $garageDetail = $_REQUEST['data'];
                $cond = array('garage_id' => $_SESSION['setting']->garage_id);
                $this->UpdateData('tbl_garage', $garageDetail, $cond);
                $notification = array();


                $notification['garage_id'] = $_SESSION['setting']->garage_id;
                $notification['item_type'] = 'reminder_setting';
                $notification['item_id'] = $_SESSION['setting']->garage_id;
                $notification['description'] = 'reminder settings updated.';
                $notification['date'] = date('Y-m-d H:i:s');
                $this->notification($notification);
            } else if($_REQUEST['table_name'] == 'tbl_service_reminder') {
                if($_REQUEST['data'] && !empty($_REQUEST['data'])) {
                    //get template..
                    $this->ServiceReminderModel->sendComunication($_REQUEST['data'],$_REQUEST['is_email'] == 'true' ? 'Y' : 'N',$_REQUEST['is_sms'] == 'true' ? 'Y' : 'N');
                }
            } else if($_REQUEST['table_name'] == 'tbl_category') {
                $category = array();
                if($_REQUEST['action'] == 'insert') {
                    $category['garage_id'] = $_SESSION['setting']->garage_id;
                    $category['transaction_type'] = $_REQUEST['transaction_type'];
                    $category['name'] = $_REQUEST['name'];
                    $category['is_active'] = 1;
                    $category['created_date'] = date('Y-m-d H:i:s');
                    $category['updated_date'] = date('Y-m-d H:i:s');
                    $category_id = $this->InsertData($_REQUEST['table_name'], $category);
                    $category['category_id'] = $category_id;
                    $status_code = 200;
                } else if($_REQUEST['action'] == 'delete') {
                    $this->db->where('category_id',$_REQUEST['category_id']);
                    $this->db->delete($_REQUEST['table_name']);
                }
                $this->db->select("*");
                $this->db->from("tbl_category");
                $this->db->where('is_active',1);
                $this->db->order_by('transaction_type');
                $category_list = $this->db->get()->result_array();
                $returnData = json_encode(array('category_list'=> $category_list,'added_category' => $category));
            } else if($_REQUEST['table_name'] == 'tbl_accounts') {
                $accounts_details = array();
                parse_str($_REQUEST['data'], $accounts_details);
                
                if($accounts_details['account_id'] != 0) {
                    $accounts_details['updated_date'] = date('Y-m-d H:i:s');
                    $Condition = array('account_id' => $accounts_details['account_id']);
                    $this->UpdateData($_REQUEST['table_name'],$accounts_details,$Condition);
                    $message = "Account details updated successfully.";
                } else {
                    $accounts_details['garage_id'] = $_SESSION['setting']->garage_id;
                    $accounts_details['created_date'] = date('Y-m-d H:i:s');
                    $accounts_details['updated_date'] = date('Y-m-d H:i:s');
                    $this->InsertData($_REQUEST['table_name'], $accounts_details);
                    $message = "Account details saved successfully.";
                }
            } else {
                $this->InsertData($_REQUEST['table_name'], $_REQUEST);
                $status_code = 200;
                $message = "Data Saved Successfully";
                $returnData = "";
            }
            echo json_encode(array('status' => $status_code, 'message' => $message, 'data' => $returnData));
            return false;
        } else {
            echo json_encode(array('status' => 500, 'message' => "Uable to process requst Table name not found. Please try again.."));
            return false;
        }
    }

    public function notification($data) {
        $this->db->insert('tbl_notification', $data);
        return $this->db->insert_id();
    }
    public function InsertData($TableName, $data) {
        $DataArray = array();
        foreach ($data as $key => $value) {
            if ($key != 'table_name' && $key != 'transcation' && $TableName != 'tbl_email_sms_buffer') {
                $DataArray[$key] = addslashes($value);
            } else if($TableName == 'tbl_email_sms_buffer') {
                $DataArray[$key] = $value;
            }
        }
        $this->db->insert($TableName, $DataArray);
        //echo $this->db->last_query();exit;
        return $this->db->insert_id();
    }

    public function UpdateData($TableName, $DataObject, $Condition) {
        $DataArray = array();
        foreach ($DataObject as $key => $value) {
            if ($key != 'table_name' && $key != 'transcation') {
                $DataArray[$key] = $value;
            }
        }
        $this->db->where($Condition);
        $this->db->update($TableName, $DataArray);
        return $this->db->affected_rows();
    }

    public function CheckDuplication() {
        $this->db->select('*');
        $this->db->from($_REQUEST['table_name']);
        $this->db->where($_REQUEST['field_id'], $_REQUEST['field_value']);
        $this->db->where('garage_id', $this->session->userdata['setting']->garage_id);
        if ($_REQUEST['item_value'] != 0) {
            $this->db->where($_REQUEST['item_field'] . ' != ', $_REQUEST['item_value']);
        }
        $dup_rows = $this->db->get()->result_array();
        echo count($dup_rows);
    }

    public function getDataByFields($table_name, $where_clause) {
        $this->db->select('*');
        $this->db->from($table_name);
        $this->db->where($where_clause);
        $data = $this->db->get()->result_array();
        echo json_encode($data);
    }
    public function replacePlaceholder($idArray) { // jobcard / invoice / payment template send..
                    $selectedTeplate = array();
                    $this->db->select('*');
                    $this->db->from('tbl_template');
                    $this->db->where('template_id',$idArray['template_id']);
                    $selectedTeplate = $this->db->get()->row_array();

                    if($idArray['item_type'] == 'jobcard') {
                        $this->db->select('*,tbl_jobcard.customer_id as job_customer_id,tbl_jobcard.vehicle_id as job_vehicle_id, tbl_garage.name as garage_name,tbl_invoices.date as invoice_date,tbl_jobcard.date as job_date,tbl_make.name as make_name,tbl_model.name as model_name,tbl_variant.name as variant_name,tbl_garage.email as gar_email,tbl_customer.billing_address,tbl_customer.email as cust_email,tbl_customer.mobile_no as cust_mobile_no');
                        $this->db->from('tbl_jobcard');
                        $this->db->join('tbl_garage','tbl_garage.garage_id=tbl_jobcard.garage_id','left');

                        $this->db->join('tbl_customer','tbl_customer.customer_id=tbl_jobcard.customer_id','left');
                        $this->db->join('tbl_customer_vehicle','tbl_customer_vehicle.vehicle_id=tbl_jobcard.vehicle_id','left');
                        $this->db->join('tbl_make','tbl_make.make_id=tbl_customer_vehicle.make_id','left');
                        $this->db->join('tbl_model','tbl_model.model_id=tbl_customer_vehicle.model_id','left');
                        $this->db->join('tbl_variant','tbl_variant.variant_id=tbl_customer_vehicle.variant_id','left');
                        $this->db->join('tbl_invoices','tbl_invoices.item_id=tbl_jobcard.jobcard_id and tbl_invoices.item_type="job_invoice"','left');
                        $this->db->where('tbl_jobcard.jobcard_id',base64_decode($idArray['item_id']));
                        $itemData = $this->db->get()->row_array();
                                                
                        // invoice get total,paid and due amount.
                        $jobcard_id = base64_decode($idArray['item_id']);
                        
                        $job_invoice = array();
                        $this->db->select('*');
                        $this->db->from('tbl_invoices');
                        $this->db->where('item_id', $jobcard_id);
                        $this->db->where('item_type', 'job_invoice');
                        $job_invoice = $this->db->get()->row_array();

                        $total_invoice_amount = 0;
                        $total_invoice_payment_made = 0;
                        $current_paid = 0;
                        $invoice_paid_by = '';
                        $total_due_invoice = 0;
                        if ($job_invoice && !empty($job_invoice)) {
                            $total_invoice_amount = $job_invoice['amount'];
                        }
                        
                        $all_payment_made = array();

                        $this->db->select('*');
                        $this->db->from('tbl_payments');
                        $this->db->join('tbl_payment_type','tbl_payment_type.payment_type=tbl_payments.payment_type_id','left');
                        $this->db->where('tbl_payments.item_id', $job_invoice['invoice_id']);
                        $this->db->where('tbl_payments.item_type', 'job_payment');
                        $all_payment_made = $this->db->get()->result_array();
                        
                        if ($all_payment_made && !empty($all_payment_made)) {
                            foreach ($all_payment_made as $p) {
                                $total_invoice_payment_made += $p['amount'];
                                $current_paid = $p['amount'];
                                $invoice_paid_by = $p['name'];
                            }
                        }
                        
                    //include_once('assets/dbConfig.php');
                    include_once('assets/Shortener.php');
                    $shortener = new Shortener();
                    $invoice_link = FRONT_ROOT.$shortener->urlToShortCode(BASE_URL.'booking/viewInvoicePdf?job_id='.base64_encode($itemData['jobcard_id']));
                    $estimate_link = FRONT_ROOT.$shortener->urlToShortCode(BASE_URL.'booking/viewEstimatePdf?job_id='.base64_encode($itemData['jobcard_id']));
                    $total_due_invoice = $total_invoice_amount - $total_invoice_payment_made;
                    
                    $vars = array(
                    "{{garage_name}}" => $itemData['garage_name'] != "" ? $itemData['garage_name'] : "",
                    "{{garage_address}}" => $itemData['address'] != "" ? $itemData['address'] : "",
                    "{{garage_email}}" => $itemData['gar_email'] != "" ? $itemData['gar_email'] : "",
                    "{{garage_website}}" => $itemData['web'] != "" ? $itemData['web'] : "",
                    "{{garage_contact_no}}" => $itemData['contact_no'] != "" ? $itemData['contact_no'] : "",
                    "{{garage_contact_person}}" => $itemData['contact_person_name'] != "" ? $itemData['contact_person_name'] : "",
                    "{{customer_name}}" => $itemData['name'] != "" ? $itemData['name'] : "",
                    "{{customer_address}}" => $itemData['billing_address'] != "" ? $itemData['billing_address'] : "",
                    "{{customer_contact_no}}" => $itemData['mobile_no'] != "" ? $itemData['mobile_no'] : $itemData['home_phone'] != "" ? $itemData['home_phone'] : $itemData['work_phone'],
                    "{{customer_email}}" => $itemData['cust_email'] != "" ? $itemData['cust_email'] : "",
                    "{{customer_vehicle_no}}" => $itemData['reg_no'] != "" ? $itemData['reg_no'] : "",
                    "{{customer_vehicle_make}}" => $itemData['make_name'] != "" ? $itemData['make_name'] : "",
                    "{{customer_vehicle_model}}" => $itemData['model_name'] != "" ? $itemData['model_name'] : "",
                    "{{customer_vehicle_variant}}" => $itemData['variant_name'] != "" ? $itemData['variant_name'] : "",
                    "{{sender_name}}" => $itemData['email_sender_name'] != "" ? $itemData['email_sender_name'] : "",
                    "{{sender_contact_no}}" => $itemData['email_sender_contact_no'] != "" ? $itemData['email_sender_contact_no'] : "",
                    "{{sender_email}}" => $itemData['email_sender_email'] != "" ? $itemData['email_sender_email'] : "",
                    "{{jobcard_no}}" => $itemData['jobcard_no'] != "" ? $itemData['jobcard_no'] : "",
                    "{{jobcard_date}}" => $itemData['job_date'] != "" ? date("d-m-Y",strtotime($itemData['job_date'])) : "",
                    "{{jobcard_odometer}}" => $itemData['odometer'] != "" ? $itemData['odometer'] : "",
                    "{{jobcard_total_amount}}" => $itemData['grand_total'] != "" ? $itemData['grand_total'] : "",
                    "{{estimate_no}}" => $itemData['jobcard_no'] != "" ? $itemData['jobcard_no'] : "",
                    "{{estimate_date}}" => $itemData['job_date'] != "" ? date("d-m-Y",strtotime($itemData['job_date'])) : "",
                    "{{estimate_link}}" => $estimate_link,
                    "{{estimate_total_amount}}" => $itemData['grand_total'] != "" ? $itemData['grand_total'] : "",
                    "{{invoice_no}}" => $itemData['invoice_no'] != "" ? $itemData['invoice_no'] : "",
                    "{{invoice_date}}" => $itemData['invoice_date'] != "" ? date('d-m-Y',strtotime($itemData['invoice_date'])) : "",
                    "{{invoice_due_date}}" => $itemData['due_date'] != "" ? date('d-m-Y',strtotime($itemData['due_date'])) : "",
                    "{{invoice_link}}" => $invoice_link,
                    "{{invoice_total_amount}}" => $total_invoice_amount,
                    "{{invoice_total_paid}}" => $total_invoice_payment_made,
                    "{{invoice_paid}}" => $current_paid,
                    "{{invoice_paid_by}}" => $invoice_paid_by,
                    "{{invoice_total_due}}" => $total_due_invoice,
                    "{{vendor_company_name}}" => "",
                    "{{vendor_contact_no}}" => "",
                    "{{vendor_email}}" => "",
                    "{{vendor_contact_persion}}" => "",
                    "{{vendor_contact_persion_no}}" => "",
                    );
                    
                    $email_subject = strtr($selectedTeplate['email_subject'], $vars);
                    $email_body = strtr($selectedTeplate['email_body'], $vars);
                    $sms_body = strip_tags(strtr($selectedTeplate['sms_body'], $vars));
                
                    $emailSMSBuffer = array();
                    $emailSMSBuffer['garage_id'] = $_SESSION['setting']->garage_id;
                    $emailSMSBuffer['item_type'] = 'jobcard';
                    $emailSMSBuffer['item_id'] = $jobcard_id;
                    $emailSMSBuffer['template_id'] = $idArray['template_id'];
                    $emailSMSBuffer['customer_id'] = $itemData['job_customer_id'];
                    $emailSMSBuffer['vehicle_id'] = $itemData['job_vehicle_id'];
                    $emailSMSBuffer['contact_no'] = $idArray['contact_no'] != "" ? $idArray['contact_no'] : $itemData['cust_mobile_no'];
                    $emailSMSBuffer['email_id'] = $idArray['email_address'] != "" ? $idArray['email_address'] : $itemData['cust_email'];
                    $emailSMSBuffer['is_email'] = $idArray['email_chk'];
                    $emailSMSBuffer['is_sms'] = $idArray['sms_chk'];
                    $emailSMSBuffer['email_subject'] = $email_subject;
                    $emailSMSBuffer['email_body'] = $email_body;
                    $emailSMSBuffer['sms_body'] = $sms_body;
                    $emailSMSBuffer['date'] = date('Y-m-d H:i:s');
                    $emailSMSBuffer['email_sent_date'] = date('Y-m-d H:i:s');
                    $emailSMSBuffer['email_sent_status'] = 'pending';
                    $emailSMSBuffer['sms_sent_date'] = date('Y-m-d H:i:s');
                    $emailSMSBuffer['sms_sent_status'] = 'pending';
                    $this->InsertData('tbl_email_sms_buffer', $emailSMSBuffer);
                    }
    }

}
