<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ReportsModel extends CI_Model {

    public function getAccountPayable() {
        $duration = explode(" - ",$_REQUEST['d']);
        if($duration && $duration[0] =="") {
            $st_date = date('Y-m-01', strtotime(date('Y-m-d')));
            $ed_date = date('Y-m-t', strtotime(date('Y-m-d')));
        } else {
            $st_date = date('Y-m-d',strtotime($duration[0]));
            $ed_date = date('Y-m-d',strtotime($duration[1]));
        }

        $result = array();
        //get payable data..
        $this->db->select('tbl_invoices.*,company_name');
        $this->db->from('tbl_invoices');
        $this->db->join('tbl_vendor','tbl_vendor.vendor_id=tbl_invoices.customer_id','left');
        $this->db->where('item_type','vendor_invoice');
        $dateWhere = 'tbl_invoices.date between "'.$st_date.'" and "'.$ed_date.'"';
        $this->db->where($dateWhere);
        $this->db->where('tbl_invoices.garage_id',$_SESSION['setting']->garage_id);
        $result['payable_invoices'] = $this->db->get()->result_array();

			//get this customer payment made..
        $this->db->select('tbl_payments.*,tbl_payment_type.name,tbl_invoices.invoice_no,company_name');
        $this->db->from('tbl_payments');
        $this->db->join('tbl_payment_type','tbl_payment_type.payment_type = tbl_payments.payment_type_id','left');
        $this->db->join('tbl_invoices','tbl_invoices.invoice_id = tbl_payments.item_id','left');
        $this->db->join('tbl_vendor','tbl_vendor.vendor_id = tbl_payments.customer_id','left');
        $dateWhere = 'date_format(tbl_payments.date,"%Y-%m-%d")  between "'.$st_date.'" and "'.$ed_date.'"';
        $this->db->where($dateWhere);
        $this->db->where('tbl_payments.item_type','vendor_payment');
        $this->db->where('tbl_payments.garage_id',$_SESSION['setting']->garage_id);
        $result['payable_payments'] = $this->db->get()->result_array();

        $accountArray = array();
        $refArray = array();
        $index = 0;
        foreach($result['payable_invoices'] as $k => $v) {
            $accountArray[$index]['date'] = $v['date'];
            $accountArray[$index]['description'] = 'Invoice - '.$v['invoice_no'].' generated for vendor '.$v['company_name'].'.';
            $accountArray[$index]['debit'] = $v['amount'];
            $accountArray[$index]['credit'] = '';
            $refArray[$index] = strtotime($v['date']);
            $index++;
        }
	
        foreach($result['payable_payments'] as $k => $v) {
            $accountArray[$index]['date'] = $v['date'];
            if($v['invoice_no'] != "") {
                $accountArray[$index]['description'] = 'Payment made against invoice - '.$v['invoice_no'].' for vendor '.$v['company_name'].'';
            } else {
                $accountArray[$index]['description'] = 'Payment made to '.$v['company_name'].'.';
            }
            $accountArray[$index]['debit'] = '';
            $accountArray[$index]['credit'] = $v['amount'];
            $refArray[$index] = strtotime($v['date']);
            $index++;
        }
        array_multisort($refArray,SORT_ASC,$accountArray);
        $result['payable_accounts'] = $accountArray;	

        $total_amt = 0;
        $total_paid = 0;
        foreach ($result['payable_invoices'] as $k => $v) {
            $total_amt += $v['amount'];
        }

        foreach ($result['payable_payments'] as $k1 => $v1) {
            $total_paid += $v1['amount'];
        }

        $result['payable_summary'] = array('total_amt' => $total_amt,'total_paid' => $total_paid);

        $this->db->select('*');
        $this->db->from('tbl_payment_type');
        $result['payment_types'] = $this->db->get()->result_array();

        return $result;
    }
    public function getAccountReceivable() {
        $duration = explode(" - ",$_REQUEST['d']);
        if($duration && $duration[0] =="") {
            $st_date = date('Y-m-01', strtotime(date('Y-m-d')));
            $ed_date = date('Y-m-t', strtotime(date('Y-m-d')));
        } else {
            $st_date = date('Y-m-d',strtotime($duration[0]));
            $ed_date = date('Y-m-d',strtotime($duration[1]));
        }

        $result = array();
        		//get this customer invoices..
		$this->db->select('tbl_invoices.*,name');
        $this->db->from('tbl_invoices');
        $this->db->join('tbl_customer','tbl_customer.customer_id=tbl_invoices.customer_id','left');
        $this->db->where('item_type','job_invoice');
        $dateWhere = 'tbl_invoices.date between "'.$st_date.'" and "'.$ed_date.'"';
        $this->db->where($dateWhere);
        $this->db->where('tbl_invoices.garage_id',$_SESSION['setting']->garage_id);
        $result['receivable_invoices'] = $this->db->get()->result_array();

			//get this customer payment made..
        $this->db->select('tbl_payments.*,tbl_payment_type.name,tbl_invoices.invoice_no,name');
        $this->db->from('tbl_payments');
        $this->db->join('tbl_payment_type','tbl_payment_type.payment_type = tbl_payments.payment_type_id','left');
        $this->db->join('tbl_invoices','tbl_invoices.invoice_id = tbl_payments.item_id','left');
        $this->db->join('tbl_customer','tbl_customer.customer_id = tbl_payments.customer_id','left');
        $dateWhere = 'date_format(tbl_payments.date,"%Y-%m-%d")  between "'.$st_date.'" and "'.$ed_date.'"';
        $this->db->where($dateWhere);
        $this->db->where('tbl_payments.item_type','job_payment');
        $this->db->where('tbl_payments.garage_id',$_SESSION['setting']->garage_id);
        $result['receivable_payments'] = $this->db->get()->result_array();

        $accountArray = array();
        $refArray = array();
        $index = 0;
        foreach($result['receivable_invoices'] as $k => $v) {
            $accountArray[$index]['date'] = $v['date'];
            $accountArray[$index]['description'] = 'Invoice - '.$v['invoice_no'].' generated for '.$v['name'].'.';
            $accountArray[$index]['debit'] = $v['amount'];
            $accountArray[$index]['credit'] = '';
            $refArray[$index] = strtotime($v['date']);
            $index++;
        }
	
        foreach($result['receivable_payments'] as $k => $v) {
            $accountArray[$index]['date'] = $v['date'];
            if($v['invoice_no'] != "") {
                $accountArray[$index]['description'] = 'Payment made against invoice - '.$v['invoice_no'].' by '.$v['name'];
            } else {
                $accountArray[$index]['description'] = 'Payment made by '.$v['name'].'.';
            }
            $accountArray[$index]['debit'] = '';
            $accountArray[$index]['credit'] = $v['amount'];
            $refArray[$index] = strtotime($v['date']);
            $index++;
        }
        array_multisort($refArray,SORT_ASC,$accountArray);
        $result['receivable_accounts'] = $accountArray;

        $total_amt = 0;
        $total_paid = 0;
        foreach ($result['receivable_invoices'] as $k => $v) {
            $total_amt += $v['amount'];
        }

        foreach ($result['receivable_payments'] as $k1 => $v1) {
            $total_paid += $v1['amount'];
        }

        $result['receivable_summary'] = array('total_amt' => $total_amt,'total_paid' => $total_paid);

        $this->db->select('*');
        $this->db->from('tbl_payment_type');
        $result['payment_types'] = $this->db->get()->result_array();

        return $result;        
    }
    public function getAccountStatement() {
        $duration = explode(" - ",$_REQUEST['d']);

        if($duration && $duration[0] == "") {
            $st_date = date('Y-m-01', strtotime(date('Y-m-d')));
            $ed_date = date('Y-m-t', strtotime(date('Y-m-d')));
        } else {
            $st_date = date('Y-m-d',strtotime($duration[0]));
            $ed_date = date('Y-m-d',strtotime($duration[1]));
        }
        $dateSort = array();
        $result = array();
        $total_credit = 0;
        $total_debit = 0;
        $this->db->select('tbl_invoices.*,company_name');
        $this->db->from('tbl_invoices');
        $this->db->join('tbl_vendor','tbl_vendor.vendor_id=tbl_invoices.customer_id','left');
        $this->db->where('item_type','vendor_invoice');
        $dateWhere = 'tbl_invoices.date between "'.$st_date.'" and "'.$ed_date.'"';
        $this->db->where($dateWhere);
        $this->db->where('tbl_invoices.garage_id',$_SESSION['setting']->garage_id);
        $result['payable_invoices'] = $this->db->get()->result_array();

		//get this customer payment made..
        $this->db->select('tbl_payments.*,tbl_payment_type.name,tbl_invoices.invoice_no,company_name');
        $this->db->from('tbl_payments');
        $this->db->join('tbl_payment_type','tbl_payment_type.payment_type = tbl_payments.payment_type_id','left');
        $this->db->join('tbl_invoices','tbl_invoices.invoice_id = tbl_payments.item_id','left');
        $this->db->join('tbl_vendor','tbl_vendor.vendor_id = tbl_payments.customer_id','left');
        $dateWhere = 'date_format(tbl_payments.date,"%Y-%m-%d")  between "'.$st_date.'" and "'.$ed_date.'"';
        $this->db->where($dateWhere);
        $this->db->where('tbl_payments.item_type','vendor_payment');
        $this->db->where('tbl_payments.garage_id',$_SESSION['setting']->garage_id);
        $result['payable_payments'] = $this->db->get()->result_array();

        $accountArray = array();
        $index = 0;
        foreach($result['payable_invoices'] as $k => $v) {
            $accountArray[$index]['date'] = $v['date'];
            $accountArray[$index]['description'] = 'Purchase invoice - '.$v['invoice_no'].' generated for vendor '.$v['company_name'].'.';
            $accountArray[$index]['debit'] = $v['amount'];
            $accountArray[$index]['credit'] = '';
            $dateSort[$index] = strtotime($v['date']);
            $total_debit += $v['amount'];
            $index++;
        }
	
        foreach($result['payable_payments'] as $k => $v) {
            $accountArray[$index]['date'] = $v['date'];
            if($v['invoice_no'] != "") {
                $accountArray[$index]['description'] = 'Payment made against purchase invoice - '.$v['invoice_no'].' to vendor '.$v['company_name'].'';
            } else {
                $accountArray[$index]['description'] = 'Payment made to '.$v['company_name'].'.';
            }
            $accountArray[$index]['debit'] = '';
            $accountArray[$index]['credit'] = $v['amount'];
            $dateSort[$index] = strtotime($v['date']);
            $total_credit += $v['amount'];
            $index++;
        }

        $this->db->select('tbl_customer_vehicle.reg_no,tbl_invoices.*,name');
        $this->db->from('tbl_invoices');
        $this->db->join('tbl_customer','tbl_customer.customer_id=tbl_invoices.customer_id','left');
        $this->db->join('tbl_jobcard','tbl_jobcard.jobcard_id=tbl_invoices.item_id','left');
        $this->db->join('tbl_customer_vehicle','tbl_customer_vehicle.vehicle_id=tbl_jobcard.vehicle_id','left');
        $this->db->where('item_type','job_invoice');
        $dateWhere = 'tbl_invoices.date between "'.$st_date.'" and "'.$ed_date.'"';
        $this->db->where($dateWhere);
        $this->db->where('tbl_invoices.garage_id',$_SESSION['setting']->garage_id);
        $result['receivable_invoices'] = $this->db->get()->result_array();

			//get this customer payment made..
        $this->db->select('tbl_payments.*,tbl_payment_type.name,tbl_invoices.invoice_no,name');
        $this->db->from('tbl_payments');
        $this->db->join('tbl_payment_type','tbl_payment_type.payment_type = tbl_payments.payment_type_id','left');
        $this->db->join('tbl_invoices','tbl_invoices.invoice_id = tbl_payments.item_id','left');
        $this->db->join('tbl_customer','tbl_customer.customer_id = tbl_payments.customer_id','left');
        $this->db->where('tbl_payments.item_type','job_payment');
        $dateWhere = 'date_format(tbl_payments.date,"%Y-%m-%d")  between "'.$st_date.'" and "'.$ed_date.'"';
        $this->db->where($dateWhere);
        $this->db->where('tbl_payments.garage_id',$_SESSION['setting']->garage_id);
        $result['receivable_payments'] = $this->db->get()->result_array();

        // foreach($result['receivable_invoices'] as $k => $v) {
        //     $accountArray[$index]['date'] = $v['date'];
        //     $accountArray[$index]['description'] = 'Invoice - '.$v['invoice_no'].' generated for '.$v['first_name'].' '.$v['last_name'].' '.$v['reg_no'].'';
        //     $accountArray[$index]['debit'] = $v['amount'];
        //     $accountArray[$index]['credit'] = '';
        //     $dateSort[$index] = strtotime($v['date']);
        //     $total_debit += $v['amount'];
        //     $index++;
        // }
	
        foreach($result['receivable_payments'] as $k => $v) {
            $accountArray[$index]['date'] = $v['date'];
            if($v['invoice_no'] != "") {
                $accountArray[$index]['description'] = 'Payment received against invoice - '.$v['invoice_no'].' from '.$v['name'];
            } else {
                $accountArray[$index]['description'] = 'Payment received from '.$v['name'].'.';
            }
            $accountArray[$index]['debit'] = '';
            $accountArray[$index]['credit'] = $v['amount'];
            $dateSort[$index] = strtotime($v['date']);
            $total_credit += $v['amount'];
            $index++;
        }
        // manual income/expense...
        $this->db->select();
        $this->db->from('tbl_transaction');
        $dateWhere = 'trans_date between "'.$st_date.'" and "'.$ed_date.'"';
        $this->db->where($dateWhere);
        $this->db->where('tbl_transaction.garage_id',$_SESSION['setting']->garage_id);
        $transcation = $this->db->get()->result_array();

        foreach($transcation as $a => $b) {
            if($b['transaction_type'] == 'Income') {
                $accountArray[$index]['date'] = $b['trans_date'];
                $accountArray[$index]['description'] = $b['description'];
                $accountArray[$index]['debit'] = '';
                $accountArray[$index]['credit'] = $b['amount'];;
                $total_credit += $b['amount'];
            } else if($b['transaction_type'] == 'Expense') {
                $accountArray[$index]['date'] = $b['trans_date'];
                $accountArray[$index]['description'] = $b['description'];
                $accountArray[$index]['debit'] = $b['amount'];
                $accountArray[$index]['credit'] = '';
                $total_debit += $b['amount'];
            }
            $dateSort[$index] = strtotime($b['date']);
            $index++;
        }

        array_multisort($dateSort,SORT_ASC,$accountArray);
        $result['account_statement'] = $accountArray;
        
        $result['receivable_summary'] = array('total_credit' => $total_credit,'total_debit' => $total_debit,'total_diff' => $total_credit - $total_debit);
        
        return $result;   
    }
    public function parts_purchase() {
       $result = array();
       $this->db->select('tbl_parts.part_name,tbl_vendor_bill_item.*,tbl_vendor_bill_item.total_amount as part_total_amount,tbl_vendor_bills.*,tbl_vendor.company_name');
       $this->db->from('tbl_vendor_bills');
       $this->db->join('tbl_vendor','tbl_vendor.vendor_id=tbl_vendor_bills.vendor_id','left');
       $this->db->join('tbl_vendor_bill_item','tbl_vendor_bill_item.po_id=tbl_vendor_bills.po_id','left');
       $this->db->join('tbl_parts','tbl_parts.part_id=tbl_vendor_bill_item.part_id','left');
       if($_REQUEST['p_id'] && $_REQUEST['p_id'] != "") {
         $this->db->where('tbl_vendor_bill_item.part_id',$_REQUEST['p_id']);
       }
       if($_REQUEST['v_id'] && $_REQUEST['v_id'] != "") {
        $this->db->where('tbl_vendor_bills.vendor_id',$_REQUEST['v_id']);
       }
       $this->db->where('tbl_vendor_bills.garage_id',$_SESSION['setting']->garage_id);
       $this->db->order_by('tbl_vendor_bills.po_no');
       $result['purchase_orders'] = $this->db->get()->result_array();

       $result['parts'] = array_column($result['purchase_orders'], 'part_name','part_id');
       
       $this->db->select('*');
       $this->db->from('tbl_vendor');
       $this->db->where('garage_id',$_SESSION['setting']->garage_id);
       $result['vendors'] = $this->db->get()->result_array();

       return $result;
    }
    function array_columns(array $arr, array $keysSelect)
    {    
        $keys = array_flip($keysSelect);
        $filteredArray = array_map(function($a) use($keys){
            return array_intersect_key($a,$keys);
        }, $arr);

        return $filteredArray;
    }
    public function jobcard_reports() {
        $result = array();
        $this->db->select('tbl_customer_vehicle.reg_no,name as customer_name,tbl_jobcard.*');
        $this->db->from('tbl_jobcard');
        $this->db->join('tbl_customer','tbl_customer.customer_id=tbl_jobcard.customer_id','left');
        $this->db->join('tbl_customer_vehicle','tbl_customer_vehicle.vehicle_id=tbl_jobcard.vehicle_id','left');
        $this->db->where('tbl_jobcard.garage_id',$_SESSION['setting']->garage_id);
        if($_REQUEST['cust_id'] && $_REQUEST['cust_id'] != "") {
            $vh_id = explode("_",$_REQUEST['cust_id']);
            $this->db->where('tbl_jobcard.customer_id',$vh_id[0]);
            $this->db->where('tbl_jobcard.vehicle_id',$vh_id[1]);
        }
        $result['jobcards'] = $this->db->get()->result_array();

        $this->db->select('vehicle_id,reg_no,name as customer_name,tbl_customer.customer_id');
        $this->db->from('tbl_customer');
        $this->db->join('tbl_customer_vehicle','tbl_customer_vehicle.customer_id=tbl_customer.customer_id','left');
        $this->db->where('tbl_customer.garage_id',$_SESSION['setting']->garage_id);
        $result['customer'] = $this->db->get()->result_array();

        return $result;
    }
    public function invoice_reports() {
        $result = array();
        $this->db->select('tbl_jobcard.jobcard_no,name as customer_name,tbl_invoices.*');
        $this->db->from('tbl_invoices');
        $this->db->join('tbl_jobcard','tbl_jobcard.jobcard_id=tbl_invoices.item_id','left');
        $this->db->join('tbl_customer','tbl_customer.customer_id=tbl_invoices.customer_id','left');
        $this->db->where('tbl_invoices.garage_id',$_SESSION['setting']->garage_id);
        if($_REQUEST['cust_id'] && $_REQUEST['cust_id'] != "") {
            $this->db->where('tbl_invoices.customer_id',$_REQUEST['cust_id']);
        }
        $this->db->where('item_type','job_invoice');
        $result['invoices'] = $this->db->get()->result_array();

        $this->db->select('vehicle_id,reg_no,name as customer_name,tbl_customer.customer_id');
        $this->db->from('tbl_customer');
        $this->db->join('tbl_customer_vehicle','tbl_customer_vehicle.customer_id=tbl_customer.customer_id','left');
        $this->db->where('tbl_customer.garage_id',$_SESSION['setting']->garage_id);
        $result['customer'] = $this->db->get()->result_array();
        return $result;
    }
    public function ledger() {
        $result = array();
            
        $start_date = '';
        $end_date = date('Y-m-d');
        if(date('m') < 4) {
            $start_date = (date('Y')-1).'-04-01';
        } else {
            $start_date = date('Y').'-04-01';
        }

        if(isset($_REQUEST['d']) && $_REQUEST['d'] != "") {
            $dates = explode(" - ",$_REQUEST['d']);
            $start_date = date('Y-m-d',strtotime($dates[0]));
            $end_date = date('Y-m-d',strtotime($dates[1]));
        }
        
        $this->db->select('*');
        $this->db->from('tbl_customer');
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
        $result['customers'] = $this->db->get()->result_array();

        $this->db->select('*');
        $this->db->from('tbl_vendor');
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
        $result['vendors'] = $this->db->get()->result_array();

        if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'vndr' && $_REQUEST['vndr_id'] != "*") {
            $this->db->select('sum(amount) as total_amount,transaction_type');
            $this->db->from('tbl_transaction');
            $this->db->where('tbl_transaction.vendor_id',$_REQUEST['vndr_id']);
            $dateBetween = "tbl_transaction.date < '".$start_date."'";
            $this->db->where($dateBetween);
            $this->db->group_by('transaction_type');
            $openingBal = $this->db->get()->result_array();

            $opening_credit = 0;
            $opening_debit = 0;
            foreach($openingBal as $opn) {
                if($opn['transaction_type'] == 'bill') {
                    $opening_credit = $opn['total_amount'];
                } else if($opn['transaction_type'] == 'bill_payment') {
                    $opening_debit = $opn['total_amount'];
                }
            }
            
            $this->db->select('tbl_transaction.*,DATE_FORMAT(tbl_transaction.date,"%d-%m-%Y") as trans_date,tbl_vendor.company_name,opening_balance');
            $this->db->from('tbl_transaction');
            $this->db->join('tbl_vendor','tbl_vendor.vendor_id=tbl_transaction.vendor_id','left');
            $this->db->where('tbl_transaction.vendor_id',$_REQUEST['vndr_id']);
            $dateBetween = "tbl_transaction.date between '".$start_date."' and '".$end_date."'";
            $this->db->where($dateBetween);
            $transType = "(transaction_type = 'bill' OR transaction_type = 'bill_payment')";
            $this->db->where($transType);
            $transactions = $this->db->get()->result_array();

            $this->db->select('opening_balance');
            $this->db->from('tbl_vendor');
            $this->db->where('vendor_id',$_REQUEST['vndr_id']);
            $opening_balance = $this->db->get()->row_array();


            $fix_opening_balance = 0;
            if($opening_balance && !empty($opening_balance)) {
                $fix_opening_balance = $opening_balance['opening_balance'];
            }

            $result['fix_opening_balance'] = $fix_opening_balance;
            $result['opening_credit'] = $opening_credit;
            $result['opening_debit'] = $opening_debit;
            $result['transactions'] = $transactions;
        } else if($_REQUEST['type'] == 'cstr' && $_REQUEST['cstr_id'] != "*") {
            $this->db->select('sum(amount) as total_amount,transaction_type');
            $this->db->from('tbl_transaction');
            $this->db->where('tbl_transaction.customer_id',$_REQUEST['cstr_id']);
            $dateBetween = "tbl_transaction.date < '".$start_date."'";
            $this->db->where($dateBetween);
            $this->db->group_by('transaction_type');
            $openingBal = $this->db->get()->result_array();
     
            $opening_credit = 0;
            $opening_debit = 0;
            foreach($openingBal as $opn) {
               if($opn['transaction_type'] == 'customer_payment') {
                   $opening_credit += $opn['total_amount'];
               } else if($opn['transaction_type'] == 'customer_invoice') {
                   $opening_debit += $opn['total_amount'];
               }
            }
            
            $this->db->select('tbl_transaction.*,DATE_FORMAT(tbl_transaction.date,"%d-%m-%Y") as trans_date');
            $this->db->from('tbl_transaction');
            $this->db->where('tbl_transaction.customer_id',$_REQUEST['cstr_id']);
            $dateBetween = "tbl_transaction.date between '".$start_date."' and '".$end_date."'";
            $this->db->where($dateBetween);
            $transType = "(transaction_type = 'customer_payment' OR transaction_type = 'customer_invoice')";
            $this->db->where($transType);
            $transactions = $this->db->get()->result_array();
     
            $this->db->select('opening_balance');
            $this->db->from('tbl_customer');
            $this->db->where('customer_id',$_REQUEST['cstr_id']);
            $opening_balance = $this->db->get()->row_array();

            $fix_opening_balance = 0;
            if($opening_balance && !empty($opening_balance)) {
                $fix_opening_balance = $opening_balance['opening_balance'];
            }

            $result['fix_opening_balance'] = $fix_opening_balance;
            $result['opening_credit'] = $opening_credit;
            $result['opening_debit'] = $opening_debit;
            $result['transactions'] = $transactions;
        }
        return $result;
    } 
    public function transactions() {
      $result = array();
      if(isset($_REQUEST['type']) && $_REQUEST['type'] != "") {
        $accounts = array();
        $this->db->select('*');
        $this->db->from('tbl_accounts');
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
        $this->db->where('account_id',$_REQUEST['type']);
        $accounts = $this->db->get()->row_array();

        if($accounts && !empty($accounts)) {
            $start_date = '';
            $end_date = date('Y-m-d');
            if(date('m') < 4) {
                $start_date = (date('Y')-1).'-04-01';
            } else {
                $start_date = date('Y').'-04-01';
            }

            if(isset($_REQUEST['d']) && $_REQUEST['d'] != "") {
                $dates = explode(" - ",$_REQUEST['d']);
                $start_date = date('Y-m-d',strtotime($dates[0]));
                $end_date = date('Y-m-d',strtotime($dates[1]));
            }
        }

        $this->db->select("tbl_accounts.*,tbl_account_type.name as account_type_name");
        $this->db->from('tbl_accounts');
        $this->db->join('tbl_account_type','tbl_account_type.acc_type_id = tbl_accounts.account_type ','left');
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
        $result['accounts'] = $this->db->get()->result_array();

        $this->db->select('*');
        $this->db->from('tbl_account_type');
        $result['account_type'] = $this->db->get()->result_array();
        
        $this->db->select('*');
        $this->db->from('tbl_payment_type');
        $result['payment_types'] = $this->db->get()->result_array();

        $this->db->select('*');
        $this->db->from('tbl_vendor');
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
        $result['vendors'] = $this->db->get()->result_array();

        $this->db->select('*');
        $this->db->from('tbl_category');
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
        $this->db->where('is_active',1);
        $this->db->order_by('transaction_type');
        $result['category'] = $this->db->get()->result_array();

        $this->db->select('tbl_customer_vehicle.vehicle_id,tbl_customer.customer_id,name,reg_no');
        $this->db->from('tbl_customer');
        $this->db->join('tbl_customer_vehicle','tbl_customer_vehicle.customer_id=tbl_customer.customer_id','left');
        $this->db->where('tbl_customer.garage_id',$_SESSION['setting']->garage_id);
        $this->db->where('tbl_customer_vehicle.is_active','1');
        $result['customers'] = $this->db->get()->result_array();

        $this->load->model('CountryStateCityModel');
        $result['state'] = $this->CountryStateCityModel->getState('101');
        $state_id = $_SESSION['setting']->default_state != 0 ? $_SESSION['setting']->default_state : $result['state'][0]['id'];
        $result['city']  =  $this->CountryStateCityModel->getCity($state_id);

        $this->db->select('*');
        $this->db->from('tbl_make');
        $this->db->where('is_active',1);
        $result['make'] = $this->db->get()->result_array();
      }
      return $result;
    }
    public function sales_summary() {
        $start_date = '';
        $end_date = date('Y-m-d');
        if(date('m') < 4) {
            $start_date = (date('Y')-1).'-04-01';
        } else {
            $start_date = date('Y').'-04-01';
        }

        if(isset($_REQUEST['d']) && $_REQUEST['d'] != "") {
            $dates = explode(" - ",$_REQUEST['d']);
            $start_date = date('Y-m-d',strtotime($dates[0]));
            $end_date = date('Y-m-d',strtotime($dates[1]));
        }

        $result = array();
        $this->db->select('tbl_invoices.*,tbl_customer.name as customer_name');
        $this->db->from('tbl_invoices');
        $this->db->join('tbl_customer','tbl_customer.customer_id=tbl_invoices.customer_id and tbl_invoices.item_type="job_invoice"','left');
        $this->db->where('tbl_invoices.garage_id',$_SESSION['setting']->garage_id);
        if(isset($_REQUEST['cust_id']) && $_REQUEST['cust_id'] != "" && $_REQUEST['cust_id'] !="*") {
            $this->db->where('tbl_invoices.customer_id', $_REQUEST['cust_id']);
        }
        $inv_dates = 'tbl_invoices.date between "'.$start_date.'" and "'.$end_date.'"';
        $this->db->where($inv_dates);
        $this->db->order_by('tbl_invoices.date','asc');
        $result['sales'] = $this->db->get()->result_array();
        //echo $this->db->last_query();
        
        $this->db->select('*');
        $this->db->from('tbl_customer');
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
        $result['customers'] = $this->db->get()->result_array();
        return $result;
    }
    public function daybook() {
        $start_date = '';
        $end_date = date('Y-m-d');
        if(date('m') < 4) {
            $start_date = (date('Y')-1).'-04-01';
        } else {
            $start_date = date('Y').'-04-01';
        }

        if(isset($_REQUEST['d']) && $_REQUEST['d'] != "") {
            $dates = explode(" - ",$_REQUEST['d']);
            $start_date = date('Y-m-d',strtotime($dates[0]));
            $end_date = date('Y-m-d',strtotime($dates[1]));
        }

        $result = array();
        $this->db->select('tbl_transaction.*,tbl_customer.name as customer_name,tbl_vendor.company_name');
        $this->db->from('tbl_transaction');
        $this->db->join('tbl_customer','tbl_customer.customer_id=tbl_transaction.customer_id','left');
        $this->db->join('tbl_vendor','tbl_vendor.vendor_id=tbl_transaction.vendor_id','left');
        $this->db->where('tbl_transaction.garage_id',$_SESSION['setting']->garage_id);
        $transNotIn = "tbl_transaction.transaction_type NOT IN ('deposit','bill','customer_invoice')";
        $this->db->where($transNotIn);
        $this->db->where('tbl_transaction.date between "'.$start_date.'" and "'.$end_date.'"');
        $this->db->order_by('tbl_transaction.date,tbl_transaction.transcation_id','asc');
        $result['daybook'] = $this->db->get()->result_array();
        return $result;
    }
    public function outstanding() {        
        $start_date = '';
        $end_date = date('Y-m-d');
        if(date('m') < 4) {
            $start_date = (date('Y')-1).'-04-01';
        } else {
            $start_date = date('Y').'-04-01';
        }

        if(isset($_REQUEST['d']) && $_REQUEST['d'] != "") {
            $dates = explode(" - ",$_REQUEST['d']);
            $start_date = date('Y-m-d',strtotime($dates[0]));
            $end_date = date('Y-m-d',strtotime($dates[1]));
        }

        $result = array();
        
        $this->db->select('*');
        $this->db->from('tbl_customer');
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
        $result['customers'] = $this->db->get()->result_array();

        $this->db->select('*');
        $this->db->from('tbl_vendor');
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
        $result['vendors'] = $this->db->get()->result_array();

        if($_REQUEST['type'] == 'cstr' || !isset($_REQUEST['type'])) {
            $this->db->select('tbl_customer.name as customer_name,mobile_no,sum(tbl_invoices.amount) as total_invoiced,sum(tbl_transaction.amount) as total_received');
            $this->db->from('tbl_invoices');
            $this->db->join('tbl_transaction','tbl_transaction.item_id=tbl_invoices.invoice_id and tbl_transaction.transaction_type="customer_payment"','left');
            $this->db->join('tbl_customer','tbl_customer.customer_id=tbl_invoices.customer_id','left');
            $this->db->where('tbl_invoices.item_type','job_invoice');
            $this->db->where('tbl_invoices.garage_id',$_SESSION['setting']->garage_id);
            if(isset($_REQUEST['cstr_id']) && $_REQUEST['cstr_id'] != "*") {
                $this->db->where('tbl_invoices.customer_id',$_REQUEST['cstr_id']);
            }
            $inv_dates = 'tbl_invoices.date between "'.$start_date.'" and "'.$end_date.'"';
            $this->db->where($inv_dates);
            $this->db->group_by('tbl_invoices.customer_id');
            $result['data'] = $this->db->get()->result_array();   
        } else if($_REQUEST['type'] == 'vndr') {
            $this->db->select('tbl_vendor.company_name,mobile_no,sum(tbl_invoices.amount) as total_invoiced,sum(tbl_transaction.amount) as total_received');
            $this->db->from('tbl_invoices');
            $this->db->join('tbl_transaction','tbl_transaction.item_id=tbl_invoices.invoice_id and tbl_transaction.transaction_type="customer_payment"','left');
            $this->db->join('tbl_vendor','tbl_vendor.vendor_id=tbl_invoices.customer_id','left');
            $this->db->where('tbl_invoices.item_type','vendor_invoice');
            $this->db->where('tbl_invoices.garage_id',$_SESSION['setting']->garage_id);
            $inv_dates = 'tbl_invoices.date between "'.$start_date.'" and "'.$end_date.'"';
            $this->db->where($inv_dates);
            if(isset($_REQUEST['vndr_id']) && $_REQUEST['vndr_id'] != "*") {
                $this->db->where('tbl_invoices.customer_id',$_REQUEST['vndr_id']);
            }
            $this->db->group_by('tbl_invoices.customer_id');
            $result['data'] = $this->db->get()->result_array();
        }
        //echo $this->db->last_query();
        return $result;
    }
    public function gstr_1() {
        $start_date = '';
        $end_date = date('Y-m-d');
        if(date('m') < 4) {
            $start_date = (date('Y')-1).'-04-01';
        } else {
            $start_date = date('Y').'-04-01';
        }

        if(isset($_REQUEST['d']) && $_REQUEST['d'] != "") {
            $dates = explode(" - ",$_REQUEST['d']);
            $start_date = date('Y-m-d',strtotime($dates[0]));
            $end_date = date('Y-m-d',strtotime($dates[1]));
        }
        $result = array();

        $this->db->select('tbl_insurance.gst_no as insur_gst_no,tbl_insurance.name as insurance_name,tbl_invoices.*,tbl_jobcard.tax_type,tbl_jobcard.insurance_id,tbl_jobcard_item.*,tbl_customer.gst_no,tbl_customer.name as customer_name');
        $this->db->from('tbl_invoices');
        $this->db->join('tbl_jobcard','tbl_jobcard.jobcard_id=tbl_invoices.item_id','left');
        $this->db->join('tbl_jobcard_item','tbl_jobcard_item.jobcard_id=tbl_jobcard.jobcard_id','left');
        $this->db->join('tbl_customer','tbl_customer.customer_id=tbl_invoices.customer_id','left');
        $this->db->join('tbl_insurance','tbl_insurance.insurance_id=tbl_jobcard.insurance_id','left');
        $this->db->where('tbl_invoices.item_type','job_invoice');
        $this->db->where('tbl_jobcard.is_gst_bill','Y');
        $this->db->where('tbl_invoices.garage_id',$_SESSION['setting']->garage_id);
        $inv_dates = 'tbl_invoices.date between "'.$start_date.'" and "'.$end_date.'"';
        $this->db->where($inv_dates);
        $this->db->order_by('tbl_invoices.invoice_id');
        $allInvoices = $this->db->get()->result_array();

        foreach ($allInvoices as $key => $value) {
            $taxDetails = array();
            $gstr1 = array();
            if($value['insurance_id'] != 0) { // For Insurance customer...
                $gstr1['customer_name'] = $value['customer_name'];
                $gstr1['gst_no'] = $value['gst_no'];
                $gstr1['invoice_no'] = $value['invoice_no'];
                $gstr1['invoice_date'] = date('d-m-Y', strtotime($value['date']));
                $gstr1['invoice_amount'] = $value['amount'];

                //Customer Payable.
                $customerTotalTax = (($value['customer_payable'] * $value['tax_rate']) / 100);
                                
                $gstr1['tax_percentage'] = $value['tax_rate'];
                $gstr1['taxable_value']  = $value['customer_payable'];
                $gstr1['total_tax']  = $customerTotalTax;

                if($value['tax_type'] == 'scgst') {
                    $gstr1['sgst']  = round(($customerTotalTax / 2),2);
                    $gstr1['cgst']  = round(($customerTotalTax / 2),2);
                } else {
                    $gstr1['igst']  = round($customerTotalTax,2);
                }
            } else { // without insurance customer.
                $gstr1['customer_name'] = $value['customer_name'];
                $gstr1['gst_no'] = $value['gst_no'];
                $gstr1['invoice_no'] = $value['invoice_no'];
                $gstr1['invoice_date'] = date('d-m-Y', strtotime($value['date']));
                $gstr1['invoice_amount'] = $value['amount'];

                $gstr1['tax_percentage'] = $value['tax_rate'];
                $gstr1['taxable_value']  = $value['taxable_amount'];
                $gstr1['total_tax']  = $value['tax_amount'];

                if($value['tax_type'] == 'scgst') {
                    $gstr1['sgst']  = round(($value['tax_amount'] / 2),2);
                    $gstr1['cgst']  = round(($value['tax_amount'] / 2),2);
                } else {
                    $gstr1['igst']  = round($value['tax_amount'],2);
                }
            }
            $customer[] = $gstr1;
        }

        // insurance GST.
        $gstr1 = array();
        $taxDetails = array();
        foreach ($allInvoices as $key => $value) {
            $taxDetails = array();
            $gstr1 = array();
            if($value['insurance_id'] != 0) { // For Insurance customer...
                $gstr1['insurance_name'] = $value['insurance_name'];
                $gstr1['gst_no'] = $value['insur_gst_no'];
                $gstr1['invoice_no'] = $value['invoice_no'];
                $gstr1['invoice_date'] = date('d-m-Y', strtotime($value['date']));
                $gstr1['invoice_amount'] = $value['amount'];
                
                $insurance_payable = (($value['unit_price'] * ($value['qty'] != 0 ? $value['qty'] : 1)) - $value['discount_value'] - $value['customer_payable']);
                $insuranceTotalTax = (($insurance_payable * $value['tax_rate']) / 100);
                                
                $gstr1['tax_percentage'] = $value['tax_rate'];
                $gstr1['taxable_value']  = $insurance_payable;
                $gstr1['total_tax']  = $insuranceTotalTax;

                if($value['tax_type'] == 'scgst') {
                    $gstr1['sgst']  = round(($insuranceTotalTax / 2),2);
                    $gstr1['cgst']  = round(($insuranceTotalTax / 2),2);
                } else {
                    $gstr1['igst']  = round($insuranceTotalTax,2);
                }
            }
            $insurance[] = $gstr1;
        }

        $tempInv = 0;
        foreach ($insurance as $key => $value) {
            if($tempInv == 0 || $tempInv != $value['invoice_no']) { 
                $result['insurance'][$value['invoice_no']] = $value;
                $result['insurance'][$value['invoice_no']]['taxDetails'][] = $value;
            } else {
                $result['insurance'][$value['invoice_no']]['taxDetails'][] = $value;
            }
            $tempInv = $value['invoice_no'];
        }

        $tempCust = 0;
        foreach ($customer as $key => $value) {
            if($tempCust == 0 || $tempCust != $value['invoice_no']) {
                $result['customer'][$value['invoice_no']] = $value;
                $result['customer'][$value['invoice_no']]['taxDetails'][] = $value;
            } else {
                $result['customer'][$value['invoice_no']]['taxDetails'][] = $value;
            }
            $tempCust = $value['invoice_no'];
        }

        $result['garageDetails'] = array("name" => $_SESSION['setting']->name,"gst_no" => $_SESSION['setting']->gstin_no,"phone_no" => $_SESSION['setting']->contact_no);
        return $result;
    }
    public function gstr_2() {
        $result = array();
        $this->db->select('tbl_vendor_bills.*,tbl_vendor_bill_item.*,company_name');
        $this->db->from('tbl_vendor_bills');
        $this->db->join('tbl_vendor_bill_item','tbl_vendor_bill_item.po_id=tbl_vendor_bills.po_id','left');
        $this->db->join('tbl_vendor','tbl_vendor.vendor_id=tbl_vendor_bills.vendor_id','left');
        $this->db->where('tbl_vendor_bills.garage_id',$_SESSION['setting']->garage_id);
        if($_REQUEST['sear_id']) {
            $this->db->where('tbl_vendor_bills.vendor_id',$_REQUEST['sear_id']);
        }
        $this->db->order_by('tbl_vendor_bills.po_id');
        $vendorBills = $this->db->get()->result_array();

        $this->db->select('*');
        $this->db->from('tbl_vendor');
        $this->db->where('tbl_vendor.garage_id',$_SESSION['setting']->garage_id);
        $result['vendor_master'] = $this->db->get()->result_array();

        $gstr2 = array();        
        $tempInv = 0;
        foreach ($vendorBills as $key => $value) {
            $taxDetails = array();

            $gstr2['company_name'] = $value['company_name'];
            $gstr2['po_no'] = $value['po_no'];
            $gstr2['invoice_no'] = $value['bill_no'];
            $gstr2['invoice_date'] = date('d-m-Y', strtotime($value['invoice_date']));
            $gstr2['invoice_amount'] = $value['grand_total'];

            $totalUnitPrice = $value['unit_price'] * ($value['qty'] ? $value['qty'] : 1);
            $taxableAmount = ($totalUnitPrice - $value['discount_value']);
            $taxAmount = ($taxableAmount * $value['tax_rate']) / 100;
                            
            $gstr2['tax_percentage'] = $value['tax_rate'];
            $gstr2['taxable_value']  = $taxableAmount;
            $gstr2['total_tax']  = $taxAmount;

            if($value['tax_type'] == 'scgst') {
                $gstr2['sgst']  = round(($taxAmount / 2),2);
                $gstr2['cgst']  = round(($taxAmount / 2),2);
            } else {
                $gstr2['igst']  = round($taxAmount,2);
            }
            if($tempInv == 0 || $tempInv != $value['po_no']) {
                $result['vendors'][$value['po_no']] = $gstr2;
                $result['vendors'][$value['po_no']]['taxDetails'][] = $gstr2;
            } else {
                $result['vendors'][$value['po_no']]['taxDetails'][] = $gstr2;
            }
            $tempInv = $value['po_no'];
        }
        return $result;
    }
    public function bill_wise_profit() {
        $result = array();
        $start_date = '';
        $end_date = date('Y-m-d');
        if(date('m') < 4) {
            $start_date = (date('Y')-1).'-04-01';
        } else {
            $start_date = date('Y').'-04-01';
        }

        if(isset($_REQUEST['d']) && $_REQUEST['d'] != "") {
            $dates = explode(" - ",$_REQUEST['d']);
            $start_date = date('Y-m-d',strtotime($dates[0]));
            $end_date = date('Y-m-d',strtotime($dates[1]));
        }

        $this->db->select('*');
        $this->db->from('tbl_customer');
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
        $result['customers'] = $this->db->get()->result_array();

        $this->db->select('tbl_invoices.date,tbl_invoices.amount,tbl_invoices.invoice_no,tbl_customer.name as customer_name,sum(tbl_items.purchase_price) as total_purchase_price,sum(tbl_items.sell_price) as total_sell_price');
        $this->db->from('tbl_invoices');
        $this->db->join('tbl_jobcard','tbl_jobcard.jobcard_id=tbl_invoices.item_id','left');
        $this->db->join('tbl_jobcard_item','tbl_jobcard_item.jobcard_id=tbl_jobcard.jobcard_id','left');
        $this->db->join('tbl_items','tbl_items.item_id=tbl_jobcard_item.item_id','left');
        $this->db->join('tbl_customer','tbl_customer.customer_id=tbl_invoices.customer_id','left');
        $this->db->where('tbl_invoices.item_type','job_invoice');
        $this->db->where('tbl_invoices.garage_id',$_SESSION['setting']->garage_id);
        $inv_dates = 'tbl_invoices.date between "'.$start_date.'" and "'.$end_date.'"';
        $this->db->where($inv_dates);
        if(isset($_REQUEST['cstr_id']) && $_REQUEST['cstr_id'] != "*") {
            $this->db->where('tbl_invoices.customer_id',$_REQUEST['cstr_id']);
        }
        $this->db->group_by('tbl_invoices.invoice_id');
        $result['data'] = $this->db->get()->result_array();
        return $result;
    }
    public function getProfitLoss() {
        $result = array();
        return $result;
    }
    public function getStockReports() {
        $result = array();
        $start_date = '';
        $end_date = date('Y-m-d');
        if(date('m') < 4) {
            $start_date = (date('Y')-1).'-04-01';
        } else {
            $start_date = date('Y').'-04-01';
        }

        if(isset($_REQUEST['d']) && $_REQUEST['d'] != "") {
            $dates = explode(" - ",$_REQUEST['d']);
            $start_date = date('Y-m-d',strtotime($dates[0]));
            $end_date = date('Y-m-d',strtotime($dates[1]));
        }

        if($_REQUEST['type'] == 'lwstsumm') {
            $this->db->select('tbl_items.*,sum(tbl_inventory.qty) as total_stock');
            $this->db->from('tbl_items');
            $this->db->join('tbl_inventory','tbl_inventory.product_id=tbl_items.item_id','left');
            $this->db->where('tbl_items.garage_id',$_SESSION['setting']->garage_id);
            $dates = 'tbl_inventory.date between "'.$start_date.'" and "'.$end_date.'"';
            $this->db->where($dates);
            $this->db->having('total_stock < 0');
            $this->db->group_by('tbl_inventory.product_id');

            $result['data'] = $this->db->get()->result_array();
        } else if($_REQUEST['type'] == 'stsumm') {
            $this->db->select('tbl_items.*,sum(tbl_inventory.qty) as total_stock');
            $this->db->from('tbl_items');
            $this->db->join('tbl_inventory','tbl_inventory.product_id=tbl_items.item_id','left');
            $this->db->where('tbl_items.garage_id',$_SESSION['setting']->garage_id);
            $dates = 'tbl_inventory.date between "'.$start_date.'" and "'.$end_date.'"';
            $this->db->where($dates);
            $this->db->group_by('tbl_inventory.product_id');

            $result['data'] = $this->db->get()->result_array();
        } else if($_REQUEST['type'] == 'itmsalsumm') {
            $this->db->select('tbl_items.item_name,sum(tbl_inventory.qty) as total_stock');
            $this->db->from('tbl_items');
            $this->db->join('tbl_inventory','tbl_inventory.product_id=tbl_items.item_id','left');
            $this->db->where('tbl_items.garage_id',$_SESSION['setting']->garage_id);
            $this->db->where('tbl_inventory.item_type','job');
            $dates = 'tbl_inventory.date between "'.$start_date.'" and "'.$end_date.'"';
            $this->db->where($dates);
            $this->db->group_by('tbl_inventory.product_id');
            $result['data'] = $this->db->get()->result_array();
        } else if($_REQUEST['type'] == 'itmpursumm') {
            $this->db->select('tbl_items.item_name,sum(tbl_inventory.qty) as total_stock');
            $this->db->from('tbl_items');
            $this->db->join('tbl_inventory','tbl_inventory.product_id=tbl_items.item_id','left');
            $this->db->where('tbl_items.garage_id',$_SESSION['setting']->garage_id);
            $this->db->where('tbl_inventory.item_type','po');
            $dates = 'tbl_inventory.date between "'.$start_date.'" and "'.$end_date.'"';
            $this->db->where($dates);
            $this->db->group_by('tbl_inventory.product_id');
            $result['data'] = $this->db->get()->result_array();
        }
        return $result;
    }
}
