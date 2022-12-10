<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class BookingModel extends CI_Model {
	
    public function getcustomerByRegNo($searchterm,$vehicle_id = "") {
        $this->db->select('tbl_make.make_id,tbl_make.name as make_name,tbl_model.model_id,tbl_model.name as model_name,tbl_customer_vehicle.*,tbl_customer.customer_id,tbl_customer.mobile_no,tbl_customer.email,tbl_customer.name,tbl_customer.billing_address,tbl_customer.gst_no,tbl_customer_vehicle.reg_no,tbl_customer_vehicle.vehicle_id,(SUM(IF(transaction_type = "customer_invoice", amount, 0)) - SUM(IF(transaction_type = "customer_payment", amount, 0))) AS "balance"');
		$this->db->from('tbl_customer_vehicle');
		$this->db->join('tbl_customer','tbl_customer.customer_id=tbl_customer_vehicle.customer_id','left');
        $this->db->join('tbl_transaction','tbl_transaction.customer_id=tbl_customer.customer_id','left');
		$this->db->join('tbl_make','tbl_customer_vehicle.make_id=tbl_make.make_id','left');
		$this->db->join('tbl_model','tbl_customer_vehicle.model_id=tbl_model.model_id','left');
		$this->db->where('tbl_customer_vehicle.garage_id',$_SESSION['setting']->garage_id);
		$this->db->where('tbl_customer_vehicle.is_active','1');
        if($vehicle_id == "") {
            $likeWhere = "(tbl_customer.name LIKE '%$searchterm%' OR tbl_customer.mobile_no LIKE '%$searchterm%' OR tbl_customer_vehicle.reg_no LIKE '%$searchterm%')";
            $this->db->where($likeWhere);
        } else {
            $this->db->where('vehicle_id', $vehicle_id);
        }
        $this->db->group_by('tbl_customer_vehicle.vehicle_id');
        $result = $this->db->get()->result_array();
        //echo $this->db->last_query();
		return $result;
    }
    public function getCustomerHistoryData() {
        $result = array();

        $this->db->select('tbl_invoices.invoice_no,tbl_jobcard.odometer,TO_BASE64(tbl_jobcard.jobcard_id) as jobcard_id,tbl_jobcard.jobcard_no,DATE_FORMAT(tbl_jobcard.date,"%d-%m-%Y") as date,IFNULL(CASE WHEN tbl_invoices.amount IS NULL THEN 0 ELSE tbl_invoices.amount END,0) as total_invoiced,IFNULL(SUM(CASE WHEN tbl_transaction.amount IS NULL THEN 0 ELSE tbl_transaction.amount END),0) as total_paid');
        $this->db->from('tbl_jobcard');
        $this->db->join('tbl_invoices','tbl_invoices.item_id=tbl_jobcard.jobcard_id and tbl_invoices.item_type="job_invoice"','left');
        $this->db->join('tbl_transaction','tbl_transaction.item_id=tbl_invoices.invoice_id and tbl_transaction.transaction_type="customer_payment"','left');
        $this->db->where('tbl_jobcard.customer_id',$_REQUEST['customer_id']);
        $this->db->where('tbl_jobcard.vehicle_id',$_REQUEST['vehicle_id']);
        $this->db->order_by('tbl_jobcard.jobcard_id','desc');
        $this->db->group_by('tbl_jobcard.jobcard_id');
        $result['service_hist'] = $this->db->get()->result_array();
        echo $this->db->last_query();
        $total_invoiced=0;
        $total_paid=0;
        $total_due=0;
        
        foreach($result['service_hist'] as $v) {
            $total_invoiced += $v['total_invoiced'];
            $total_paid += $v['total_paid'];
            $total_due += $v['total_invoiced'] - $v['total_paid'];
        }
        $result['hist_summary'] = array('total_invoiced' => $total_invoiced,'total_paid' => $total_paid,'total_due' => $total_due);
        return $result;
    }
	public function getcustomerByMobileNo() {
		$this->db->select('*');
		$this->db->from('tbl_customer');
		$this->db->join('tbl_customer_vehicle','tbl_customer_vehicle.customer_id=tbl_customer.customer_id','left');
		$this->db->where('tbl_customer_vehicle.is_active','1');
		$this->db->like('mobile_no', $_REQUEST['term']);
		$result['search'] = $this->db->get()->result_array();
		return $result;
	}
        public function FindCustomer() {
            if($_REQUEST['item_type'] == 'vehicle_id') {
                $this->db->select('tbl_model.name,tbl_customer.customer_id,tbl_customer.name,tbl_customer.billing_address,tbl_customer_vehicle.reg_no,tbl_customer_vehicle.model_id as cust_model_id,tbl_customer_vehicle.vehicle_id');
                $this->db->from('tbl_customer_vehicle');
                $this->db->join('tbl_customer','tbl_customer.customer_id=tbl_customer_vehicle.customer_id','left');
                $this->db->join('tbl_model','tbl_model.model_id=tbl_customer_vehicle.model_id','left');
                $this->db->where('vehicle_id', $_REQUEST['item_value']);
                $result = $this->db->get()->result_array();
            } else {
                $this->db->select('*,tbl_customer_vehicle.model_id as cust_model_id');
                $this->db->from('tbl_customer');
                $this->db->join('tbl_customer_vehicle','tbl_customer_vehicle.customer_id=tbl_customer.customer_id','left');
                $this->db->join('tbl_model','tbl_model.model_id=tbl_customer_vehicle.model_id','left');
        		$this->db->where('tbl_customer.customer_id', $_REQUEST['item_value']);
                $this->db->where('tbl_customer_vehicle.is_active','1');
                $result = $this->db->get()->result_array();
            }
            return $result;
        }
        public function getJobcards() {
            $result = array();
            $this->db->select('tbl_jobcard.*,tbl_invoices.invoice_no,tbl_customer.mobile_no,tbl_customer.name,tbl_customer_vehicle.reg_no,tbl_make.name as make_name,tbl_model.name as model_name,sum(tbl_transaction.amount) as total_paid');
            $this->db->from('tbl_jobcard');
            $this->db->join('tbl_customer','tbl_customer.customer_id=tbl_jobcard.customer_id','left');
            $this->db->join('tbl_customer_vehicle','tbl_customer_vehicle.vehicle_id=tbl_jobcard.vehicle_id','left');
            $this->db->join('tbl_invoices','tbl_invoices.item_id=tbl_jobcard.jobcard_id and tbl_invoices.item_type="job_invoice"','left');
            $this->db->join('tbl_transaction','tbl_transaction.item_id=tbl_invoices.invoice_id and tbl_transaction.transaction_type="customer_payment"','left');
            $this->db->join('tbl_make','tbl_make.make_id=tbl_customer_vehicle.make_id','left');
            $this->db->join('tbl_model','tbl_model.model_id=tbl_customer_vehicle.model_id','left');
            $this->db->where('tbl_jobcard.garage_id',$_SESSION['setting']->garage_id);
            $this->db->group_by('tbl_jobcard.jobcard_id');
            $this->db->order_by('tbl_jobcard.jobcard_id','desc');
            $result['jobcards'] = $this->db->get()->result_array();
            return $result;
        }
        public function getDrpData() {
            $result = array();
            $this->db->select('*');
            $this->db->from('tbl_users');
            $this->db->where('garage_id',$_SESSION['setting']->garage_id);
            $result['employee'] = $this->db->get()->result_array();
            return $result;
        }
        public function GetBookingsEvents() {
            $this->db->select('tbl_jobcard.status,tbl_jobcard.jobcard_id,tbl_jobcard.date,tbl_customer.mobile_no,tbl_customer.name,tbl_customer_vehicle.reg_no');
            $this->db->from('tbl_jobcard');
            $this->db->join('tbl_customer','tbl_customer.customer_id=tbl_jobcard.customer_id','left');
            $this->db->join('tbl_customer_vehicle','tbl_customer_vehicle.vehicle_id=tbl_jobcard.vehicle_id','left');
            $this->db->where('tbl_jobcard.garage_id',$_SESSION['setting']->garage_id);
            $job_date = "DATE_FORMAT(tbl_jobcard.date,'%Y-%m-%d' ) between '".$_REQUEST['start']."' and '".$_REQUEST['end']."'";
            $this->db->where($job_date);
            $data = $this->db->get()->result_array();

            foreach ($data as $v) {
                $event['jobcard_id'] = base64_encode($v['jobcard_id']);
                $event['start'] = date('Y-m-d',  strtotime($v['date']));
                $event['end'] = date('Y-m-d',  strtotime($v['date']));
                if($v['status'] == 'close') {
                    $event['color'] = '#90EE90';
                } else if($v['status'] == 'payment_due') { 
                    $event['color'] = '#ffcccb';
                } else if($v['status'] == 'work_in_progress') {
                    $event['color'] = '#f4c430';
                } else {
                    $event['color'] = '#6495ED';
                }
                
                $event['title'] = $v['name'].' - '.$v['reg_no'];
                $event['mobile'] = $v['mobile'];
                $events[] = $event; 
            }
            echo json_encode($events);
        }
        public function getBookingDetail() {
            $returnData = array();
            $setting = array();
            
            $job_id = '';
            $jobCard = array();
            $jobItem = array();
            $total_enable = 5;
            
            if(isset($_REQUEST['job_id']) && !empty($_REQUEST['job_id'])) {
                $job_id = base64_decode($_REQUEST['job_id']);
            }
            
            $this->db->select('*');
            $this->db->from('tbl_garage');
            $this->db->where('garage_id',$_SESSION['setting']->garage_id);
            $setting = $this->db->get()->row_array();
            
            if(empty($setting)) { // if garage setting is not saved return back...
                return array('message' => 'Garage setting is not updated.please update in setting menu.','status_code'=>'101','data'=> '');
            }
            $result['setting'] = $setting;

            $this->db->select('*');
            $this->db->from('tbl_insurance');
            $this->db->where('garage_id',$_SESSION['setting']->garage_id);
            $result['insurance'] = $this->db->get()->result_array();

            if($job_id != '') {
               $this->db->select('tbl_jobcard.*,tbl_customer.billing_address,tbl_customer.customer_id,tbl_customer.name,tbl_customer.mobile_no,tbl_customer.email,tbl_customer_vehicle.model_id as cust_model_id,tbl_customer_vehicle.reg_no,tbl_make.make_id,tbl_make.name as make_name,tbl_model.model_id,tbl_model.name as model_name,tbl_variant.name as variant_name');
               $this->db->from('tbl_jobcard');
               $this->db->join('tbl_customer','tbl_customer.customer_id=tbl_jobcard.customer_id','left');
               $this->db->join('tbl_customer_vehicle','tbl_customer_vehicle.vehicle_id=tbl_jobcard.vehicle_id','left');
               $this->db->join('tbl_make','tbl_make.make_id=tbl_customer_vehicle.make_id','left');
               $this->db->join('tbl_model','tbl_model.model_id=tbl_customer_vehicle.model_id','left');
               $this->db->join('tbl_variant','tbl_variant.variant_id=tbl_customer_vehicle.variant_id','left');
               $this->db->where('jobcard_id',$job_id);
               $result['jobcard'] = $this->db->get()->row_array();
                
               $this->db->select('*');
               $this->db->from('tbl_jobcard_item');
               $this->db->where('jobcard_id',$job_id);
               $result['jobItem'] = $this->db->get()->result_array();

               $this->db->select('tbl_ticket.*,concat(a.first_name," ",a.last_name) as assign_to_user,concat(b.first_name," ",b.last_name) as created_by_user');
               $this->db->from('tbl_ticket');
               $this->db->join('tbl_users as a','a.user_id = tbl_ticket.assign_to','left');
               $this->db->join('tbl_users as b','b.user_id = tbl_ticket.created_by','left');
               $this->db->where('tbl_ticket.garage_id',$_SESSION['setting']->garage_id);
               $this->db->where('jobcard_id',$job_id);
               $result['tickets'] = $this->db->get()->result_array();

               $this->db->select('tbl_invoices.invoice_no,tbl_jobcard.odometer,TO_BASE64(tbl_jobcard.jobcard_id) as jobcard_id,tbl_jobcard.jobcard_no,DATE_FORMAT(tbl_jobcard.date,"%d-%m-%Y") as date,IFNULL(CASE WHEN tbl_invoices.amount IS NULL THEN 0 ELSE tbl_invoices.amount END,0) as total_invoiced,IFNULL(SUM(CASE WHEN tbl_transaction.amount IS NULL THEN 0 ELSE tbl_transaction.amount END),0) as total_paid');
               $this->db->from('tbl_jobcard');
               $this->db->join('tbl_invoices','tbl_invoices.item_id=tbl_jobcard.jobcard_id and tbl_invoices.item_type="job_invoice"','left');
               $this->db->join('tbl_transaction','tbl_transaction.item_id=tbl_invoices.invoice_id and tbl_transaction.transaction_type="customer_payment"','left');
               $this->db->where('tbl_jobcard.customer_id',$result['jobcard']['customer_id']);
               $this->db->where('tbl_jobcard.vehicle_id',$result['jobcard']['vehicle_id']);
               $this->db->order_by('tbl_jobcard.jobcard_id','desc');
               $this->db->group_by('tbl_jobcard.jobcard_id');
               $result['service_hist'] = $this->db->get()->result_array();
       
               $total_invoiced=0;
               $total_paid=0;
               $total_due=0;
               
               foreach($result['service_hist'] as $v) {
                   $total_invoiced += $v['total_invoiced'];
                   $total_paid += $v['total_paid'];
                   $total_due += $v['total_invoiced'] - $v['total_paid'];
               }
               $result['hist_summary'] = array('total_invoiced' => $total_invoiced,'total_paid' => $total_paid,'total_due' => $total_due);
            
            } else {
                $next_jobcard_no = 0;
                //get next jobcard no.
                $this->db->select('max(CAST(jobcard_no AS UNSIGNED)) as max_jobcard_no');
                $this->db->from('tbl_jobcard');
                $this->db->where('garage_id',$_SESSION['setting']->garage_id);
                $jobcards = $this->db->get()->row_array();
                if($jobcards['max_jobcard_no'] != "" || $jobcards['max_jobcard_no'] != NULL) {
                    $next_jobcard_no = $jobcards['max_jobcard_no'] + 1;
                } else if($_SESSION['setting']->jobcard_no_start != NULL || $_SESSION['setting']->jobcard_no_start != "") {
                    $next_jobcard_no = $_SESSION['setting']->jobcard_no_start + 1;
                } else {
                    $next_jobcard_no = 1001;
                }
                $result['next_jobcard_no'] = $next_jobcard_no;
            }
            $is_disc_applicable = 'N';
            if(isset($result['jobcard']['is_disc_applicable']) && $result['jobcard']['is_disc_applicable'] == 'Y') {
                $total_enable++;
                $is_disc_applicable = 'Y';
            } else if(!isset($result['jobcard']['is_disc_applicable']) && $result['setting']['show_discount_column'] == 'Y') {
                $total_enable++; 
                $is_disc_applicable = 'Y';
            }
            $is_tax_applicable = 'N';
            if(isset($result['jobcard']['is_tax_applicable']) && $result['jobcard']['is_tax_applicable'] == 'Y') {
                $total_enable++;
                $is_tax_applicable = 'Y';
            } else if(!isset($result['jobcard']['is_tax_applicable']) && $result['setting']['gst_applicable'] == 'Y') {
                $total_enable++; 
                $is_tax_applicable = 'Y';
            }
            
            $result['setting']['total_enable'] = $total_enable;
            $result['is_tax_applicable'] = $is_tax_applicable;
            $result['is_disc_applicable'] = $is_disc_applicable;
            
            $this->db->select('*');
            $this->db->from('tbl_invoices');
            $this->db->where('item_id',$job_id);
            $this->db->where('item_id !=',0);
            $this->db->where('item_type','job_invoice');
            $result['invoices'] = $this->db->get()->row_array();

            $this->db->select('*');
            $this->db->from('tbl_transaction');
            $this->db->where('item_id',$result['invoices']['invoice_id']);
            $this->db->where('customer_id',$result['invoices']['customer_id']);
            $this->db->where('transaction_type','customer_payment');
            $payments = $this->db->get()->result_array();

            $this->db->select('tbl_make.make_id,tbl_model.model_id,variant_id,concat(tbl_make.name," ",tbl_model.name," ",tbl_variant.name) as vehicle_name');
            $this->db->from('tbl_variant');
            $this->db->join('tbl_model','tbl_model.model_id = tbl_variant.model_id','left');
            $this->db->join('tbl_make','tbl_make.make_id = tbl_model.make_id','left');
            $result['vehicle_list'] = $this->db->get()->result_array();

            $total_payment_paid = 0;
            foreach($payments as $p) {
                $total_payment_paid += $p['amount'];
            }

            $result['payments'] = array('total_paid' => $total_payment_paid,'data' => $payments);

            $this->db->select("*");
            $this->db->from("tbl_template");
            $this->db->where("garage_id",$_SESSION['setting']->garage_id);
            $result['templates'] = $this->db->get()->result_array();

            $this->db->select('*');
            $this->db->from('tbl_users');
            $this->db->where('garage_id',$_SESSION['setting']->garage_id);
            $result['assign_tos'] = $this->db->get()->result_array();
            
            $this->db->select('*');
            $this->db->from('tbl_packages');
            $this->db->where('garage_id',$_SESSION['setting']->garage_id);
            $result['packages'] = $this->db->get()->result_array();
            
            $this->db->select('*');

            $this->db->from('tbl_vendor');
            $this->db->where('garage_id',$_SESSION['setting']->garage_id);
            $result['vendor'] = $this->db->get()->result_array();

            $this->db->select('tbl_make.make_id,tbl_make.name as make_name,tbl_model.name as model_name,tbl_model.model_id');
            $this->db->from('tbl_make');
            $this->db->join('tbl_model','tbl_model.make_id=tbl_make.make_id','left');
            $make_model = $this->db->get()->result_array();

            foreach($make_model as $k => $v) {
                $result['make_model'][$v['make_id']][] = $v;
            }
        
            $this->db->select('tbl_email_sms_buffer.*,tbl_template.name as template_name');
            $this->db->from('tbl_email_sms_buffer');
            $this->db->join('tbl_template','tbl_template.template_id=tbl_email_sms_buffer.template_id','left');
            $this->db->where('item_id',$job_id);
            $this->db->where('item_type','jobcard');
            $this->db->where('email_sent_status','sent');
            $this->db->where('sms_sent_status','sent');
            $result['email_sms_sent'] = $this->db->get()->result_array();

            $this->load->model('CountryStateCityModel');
            $result['state'] = $this->CountryStateCityModel->getState('101');
            $state_id = $_SESSION['setting']->default_state;
            $result['city']  =  $this->CountryStateCityModel->getCity($state_id);

            return array('message' =>'Successfully fetch data.','status_code' => '200','data' => $result);
        }
        public function getItemDetail() {
		$IdArray = explode('_',$_REQUEST['item_id']);
		$result = array();
		if($IdArray[0] == 'P') {
			$this->db->select('*');
			$this->db->from('tbl_parts');
                        $this->db->join('tbl_tax_rate','tbl_tax_rate.tax_id=tbl_parts.tax_id','left');
			$this->db->where('part_id',$IdArray[1]);
			$result['part'] = $this->db->get()->row_array();
		} else if($IdArray[0] == 'S') {
			$this->db->select('*');
			$this->db->from('tbl_services');
                        $this->db->join('tbl_tax_rate','tbl_tax_rate.tax_id=tbl_services.tax_id','left');
			$this->db->where('service_id',$IdArray[1]);
			$result['service'] = $this->db->get()->row_array();
		}
        	return $result;
    }
    public function generatePdf($jobcard_id) {
        $logo_path = LOGO_DOCUMENT_ROOT.'thumbs/garage_'.$_SESSION['setting']->garage_id;
        include_once(DOCUMENT_ROOT."uploads/mpdf/mpdf.php");
        $mpdf = new mPDF('c','A4');
        $taxSummary = array();
        $taxAbleAmt = array();
        
        // jobcard and jobcard items.
	    $this->db->select('tbl_insurance.name as insurance_name,tbl_insurance.address as insurance_address,tbl_insurance.gst_no as insurance_gstno,tbl_invoices.invoice_id,tbl_invoices.notes as invoice_remarks,tbl_invoices.date as invoice_date,tbl_invoices.invoice_no,tbl_jobcard.*,tbl_customer.*,tbl_customer_vehicle.reg_no,tbl_customer_vehicle.fuel_type,tbl_make.make_id,tbl_make.name as make_name,tbl_model.model_id,tbl_model.name as model_name');
        $this->db->from('tbl_jobcard');
        $this->db->join('tbl_invoices','tbl_invoices.item_id=tbl_jobcard.jobcard_id AND item_type="job_invoice"','left');
        $this->db->join('tbl_customer','tbl_customer.customer_id=tbl_jobcard.customer_id','left');
        $this->db->join('tbl_customer_vehicle','tbl_customer_vehicle.vehicle_id=tbl_jobcard.vehicle_id','left');
        $this->db->join('tbl_make','tbl_make.make_id=tbl_customer_vehicle.make_id','left');
        $this->db->join('tbl_model','tbl_model.model_id=tbl_customer_vehicle.model_id','left');
        $this->db->join('tbl_insurance','tbl_insurance.insurance_id=tbl_jobcard.insurance_id','left');
        $this->db->where('tbl_jobcard.jobcard_id',$jobcard_id);
        $jobcard = $this->db->get()->row_array();
        

        // garage detail.
        $this->db->select('*,tbl_garage.name as garage_name');
        $this->db->from('tbl_garage');
        $this->db->where('tbl_garage.garage_id',$jobcard['garage_id']);
        $garageDetail = $this->db->get()->row_array();
        
        $file_name = DOCUMENT_ROOT.'uploads/invoices/Invoice-'.$jobcard['jobcard_no'].'-'.date('d-m-Y');
        $is_disc = $jobcard['is_disc_applicable'];
        $is_tax  = $jobcard['is_tax_applicable'];
        $grandTotalrowspan = 5;
        $paidTotalrowspan = 5;
        
        if($is_tax == 'Y') {
            $grandTotalrowspan++;
            $paidTotalrowspan++;
            $paidTotalrowspan++;
            $paidTotalrowspan++;
        }
		
        $this->db->select('tbl_jobcard_item.*,tbl_items.item_name,tbl_items.hsn_sac_code');
        $this->db->from('tbl_jobcard_item');
        $this->db->join('tbl_items','tbl_items.item_id = tbl_jobcard_item.item_id','left');
        $this->db->where('jobcard_id',$jobcard_id);
        $this->db->order_by('tbl_jobcard_item.item_type','asc');
        $jobcard_item = $this->db->get()->result_array();

        $this->db->select('sum(tbl_transaction.amount) as total_paid');
        $this->db->from('tbl_transaction');
        $this->db->where('customer_id',$jobcard['customer_id']);
        $this->db->where('item_id',$jobcard['invoice_id']);
        $this->db->where('transaction_type','customer_payment');
        $total_paid = $this->db->get()->row_array();
        
        $blankLineLength = 190;
        $this->load->model('SettingModel');
        $invoiceAmtInWords = $this->SettingModel->getIndianCurrencyInWords($jobcard['grand_total']);
        $html .= "<table style='width:100%;'>";
        $html .= "<tr><td style='text-align:center;font-family:TimesNewRoman'>";
        if($jobcard['is_gst_bill'] == 'Y') {
            $html .= "<h2 style='font-family:TimesNewRoman;'>TAX INVOICE</h2>";
        } else {
            $html .= "<h2 style='font-family:TimesNewRoman;'>INVOICE</h2>";
        }
        $html .= "</td></tr>";
        $html .= "</table>";
        $html .= "<table class='padding_table' style='border-collapse:collapse;width:100%;font-family:Helvetica;'>";
        $html .= '<tr>';
        $html .= '<th colspan ="3" style="border-collpase:collpased;padding:4px;border-left:1px solid black;border-right:1px solid black;border-top:1px solid black;border-color: black;border-width: thin;font-size:25px;text-align:center;font-family:TimesNewRoman;background-color:lightgray;">'.strtoupper($garageDetail['garage_name']).'</th>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td colspan ="3" style="border-collpase:collpased;padding:4px;border-left:1px solid black;border-right:1px solid black;border-top:1px solid black;border-color: black;border-width: thin;text-align:center;font-family:TimesNewRoman;">'.strtoupper($garageDetail['address']).'<br>';
        $html .= '<b>MO:</b> '.$garageDetail['contact_no'].'';
        if($garageDetail['gstin_no'] !="") {
            $html .= ', <b>GSTIN</b> : '.$garageDetail['gstin_no'];
        } else {
            $html .= ', <b>EMAIL</b> : '.$garageDetail['email'];
        }
        '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td rowspan="2" valign="top" style="height:35px;padding: 4px;border-top:1px solid black;border-left:1px solid black;border-right:1px solid black;border-color: black;">';
        $html .= '<span class="fsize14">';
        $html .= '<b>Customer </b> : ';
        $html .= strtoupper($jobcard['name']).'</span><br>';
        $html .= '<span class="fsize14">';
        $html .= '<b>Contact No.</b> : ';
        $html .= $jobcard['mobile_no'].'</span><br>';
        $html .= '<span class="fsize14"><b>Vehicle</b> : ';
        $html .= strtoupper($jobcard['make_name'].' '.$jobcard['model_name']).'</span><br>';
        $html .= '<span class="fsize14"><b>Kilometer</b> : ';
        $html .= $jobcard['odometer'].'</span><br>';
        $html .= '<span class="fsize14"><b>GSTIN</b> : ';
        $html .= strtoupper($jobcard['gst_no']).'</span><br>';
        $html .= '</td>';
        $html .= '<td style="padding: 4px;height:20px;width:25%;border-collpase:collpased;padding : 4px;border:1px solid black;border-color: black;border-width: thin;border-style: solid" valign="top"><span class="fsize14"><b>Invoice No</b>  <br>'.$jobcard['invoice_no'].' </span></td>';
        $html .= '<td style="padding: 4px;height:20px;width:25%;padding: 4px;border:1px solid black;border-color: black;border-width: thin;border-style: solid" valign="top"><span class="fsize14"><b>Date</b> <br>'.date('d-m-Y', strtotime($jobcard['invoice_date'])).'</span></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td valign="top" style="height:20px;padding: 4px;border-top:1px solid black;border-left:1px solid black;border-right:1px solid black;border-color: black;">';
        $html .= '<span class="fsize14"><b>Jobcard No </b><br>'.$jobcard['jobcard_no'].'</span>';
        $html .= '</td>';
        $html .= '<td valign="top" style="height:20px;padding: 4px;border:1px solid black;border-color: black;">';
        $html .= '<span class="fsize14"><b>Vehicle No</b> <br>'.strtoupper($jobcard['reg_no']).'</span>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<div style="float:right;width:100%;text-align:right;font-family:TimesNewRoman;">';
        $html .= '<table style="width:100%;border-collapse:collapse;font-family:TimesNewRoman;font-size:90%;">';
        $html .= '<tr>';
        $html .= '<td style="border-top:1px solid black;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;text-align:left;padding:4px;width:3%;">Sr.No</td>';
        $html .= '<td style="border-top:1px solid black;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;text-align:left;padding:4px;">Description</td>';
        if($is_tax == 'Y') {
            $html .= '<td style="border-top:1px solid black;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;text-align:right;padding:4px;width:10%;">HSN/SAC</td>';
        }
        $html .= '<td style="text-align:right;padding:4px;width:5%;border-left:1px solid black;border-right:1px solid black;border-color: black;border-top:1px solid black;border-width: thin;">Qty</td>';
        $html .= '<td style="text-align:right;padding:4px;width:10%;border-left:1px solid black;border-right:1px solid black;border-color: black;border-top:1px solid black;border-width: thin;">Unit Price</td>';
        $html .= '<td style="text-align:right;padding:4px;width:8%;border-left:1px solid black;border-right:1px solid black;border-color: black;border-top:1px solid black;border-width: thin;">Discount</td>';
        
        if($is_tax == 'Y') {
            $html .= '<td style="text-align:right;padding:4px;width:8%;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;">Tax</td>';
            $html .= '<td style="text-align:right;padding:4px;width:4%;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;">%</td>';
        }
        $html .= '<td style="text-align:right;padding:4px;width:10%;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;">Amount</td>';
        $html .= '</tr>';
        $srno = 1;
        $isPartAdded = 'N';
        $isServiceAdded = 'N';
        $partsTotal = 0;
        $laborTotal = 0;
        $lineTotal = 0;
        $totalTax = 0;
        $totalDisc = 0;
        foreach ($jobcard_item as $i => $v) {
            $description = $v['description'];
            if($v['item_type'] == 'P' && $isPartAdded =='N') {
                $html .= '<tr>';
                $html .= '<td colspan="2" class="no-b non-bold-item" style="border-left:1px solid black;border-right:1px solid black;border-top:1px solid black;border-color: black;border-width: thin;background-color:lightgray;vertical-align:top;border-collapse:collapse;padding:3px;text-align:left;">PART</td>';
                if($is_tax == 'Y') {
                    $html .= '<td class="no-b non-bold-item" style="text-align:right;padding:4px;width:10%;"></td>';
                }
                $html .= '<td class="no-b non-bold-item" style="border-left:1px solid black;border-right:1px solid black;border-top:1px solid black;border-color: black;border-width: thin;text-align:right;padding:4px;width:5%;"></td>';
                $html .= '<td class="no-b non-bold-item" style="border-left:1px solid black;border-right:1px solid black;border-top:1px solid black;border-color: black;border-width: thin;text-align:right;padding:4px;width:10%;"></td>';
                $html .= '<td class="no-b non-bold-item" style="border-left:1px solid black;border-right:1px solid black;border-top:1px solid black;border-color: black;border-width: thin;text-align:right;padding:4px;width:10%;"></td>';
                
                if($is_tax == 'Y') {
                    $html .= '<td class="no-b non-bold-item" style="border-left:1px solid black;border-right:1px solid black;border-top:1px solid black;border-color: black;border-width: thin;text-align:right;padding:4px;width:8%;"></td>';
                    $html .= '<td class="no-b non-bold-item" style="border-left:1px solid black;border-right:1px solid black;border-top:1px solid black;border-color: black;border-width: thin;text-align:right;padding:4px;width:4%;"></td>';
                }
                $html .= '<td class="no-b non-bold-item" style="border-left:1px solid black;border-right:1px solid black;border-top:1px solid black;border-color: black;border-width: thin;text-align:right;padding:4px;width:10%;"></td>';
                $html .= '</tr>';
                $isPartAdded = 'Y';
            }
            if($v['item_type'] == 'S' && $isServiceAdded =='N') {
                $html .= '<tr>';
                $html .= '<td colspan="2" class="no-b non-bold-item" style="background-color:lightgray;vertical-align:top;border-collapse:collapse;padding:3px;text-align:left;">LABOR</td>';
                if($is_tax == 'Y') {
                    $html .= '<td class="no-b non-bold-item" style="text-align:right;padding:4px;width:10%;"></td>';
                }
                $html .= '<td class="no-b non-bold-item" style="text-align:right;padding:4px;width:5%;"></td>';
                $html .= '<td class="no-b non-bold-item" style="text-align:right;padding:4px;width:10%;"></td>';
                $html .= '<td class="no-b non-bold-item" style="text-align:right;padding:4px;width:10%;"></td>';
                
                if($is_tax == 'Y') {
                    $html .= '<td class="no-b non-bold-item" style="text-align:right;padding:4px;width:8%;"></td>';
                    $html .= '<td class="no-b non-bold-item" style="text-align:right;padding:4px;width:4%;"></td>';
                }
                $html .= '<td class="no-b non-bold-item" style="text-align:right;padding:4px;width:10%;"></td>';
                $html .= '</tr>';
                $isServiceAdded = 'Y';
            }
            $html .= '<tr>';
            $html .= '<td class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:3px;text-align:left;">'.$srno.'</td>';
            $html .= '<td class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:3px;text-align:left;">'.strtoupper($v['item_name']).'<br><small>'.$description.'</small></td>';
            if($is_tax == 'Y') {
                $hsn_sac_code = $v['hsn_sac_code'] != "" && $v['hsn_sac_code'] != 0 ? $v['hsn_sac_code'] : '';
                $html .= '<td class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:5px;text-align:right;">'.$hsn_sac_code.'</td>';
            }
            $qty = $v['qty'] != "" && $v['qty'] != 0 ? $v['qty'] : '';
            $html .= '<td  class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:5px;text-align:right;">'.$qty.'</td>';
            $html .= '<td  class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:5px;text-align:right;">'.$this->moneyFormatIndia($v['unit_price']).'</td>';
            
            $disc = $v['discount_value'] != "" && $v['discount_value'] != 0 ? $this->moneyFormatIndia($v['discount_value']) : '';
            $html .= '<td class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:5px;text-align:right;">'.$disc.'</td>';
            
            $per_unit_price = $v['unit_price'] * ($qty != '' ? $qty : 1);
            if($is_tax == 'Y') {
                $tax = $v['tax_amount'] != "" && $v['tax_amount'] != 0 ? $this->moneyFormatIndia($v['tax_amount']) : '';
                $tAmount = $v['tax_amount'] != "" && $v['tax_amount'] != 0 ? $v['tax_amount'] : '';
                $tax_rate = $v['tax_rate'] != "" && $v['tax_rate'] != 0 ? '('.$this->moneyFormatIndia($v['tax_rate']).'%)' : '';
                $html .= '<td class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:5px;text-align:right;">'.$tax.'</td>';
                $html .= '<td class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:5px;text-align:right;">'.$tax_rate.'</td>';
                if($v['tax_rate'] != 0 && $v['tax_rate'] != '') {
                    $taxSummary[$hsn_sac_code][$v['tax_rate']] += $tAmount;
                    $taxAbleAmt[$hsn_sac_code][$v['tax_rate']] += $per_unit_price - $disc;
                }
                $totalTax += $tAmount;
            }   
            $lineTotal += $per_unit_price + $tAmount - $disc;
            if($v['item_type'] == 'P') {
                $partsTotal += $per_unit_price + $tAmount - $disc;
            } else {
                $laborTotal += $per_unit_price + $tAmount - $disc;
            }
            $html .= '<td class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:5px;text-align:right;">'.$this->moneyFormatIndia(number_format((float)$per_unit_price + $tAmount -$disc, 2, '.', '')).'</td>';
            $html .= '</tr>';
            $srno++;
        }
        for ($i=count($jobcard_item); $i <=(150 - (count($jobcard_item) * 7)); $i++) {
            $html .= '<tr class="no-b">';
            $html .= '<td class="no-b"></td>';
            $html .= '<td class="no-b"></td>';
            
            $html .= '<td class="no-b"></td>';
            $html .= '<td class="no-b"></td>';
            $html .= '<td class="no-b"></td>';
           
            if($is_tax == 'Y') {
                $html .= '<td class="no-b"></td>';
                $html .= '<td class="no-b"></td>';
                $html .= '<td class="no-b"></td>';
            }
            $html .= '<td class="no-b"></td>';
            $html .= '</tr>';
    	}

        $html .= "<tr>";
        $html .= "<td colspan=".$grandTotalrowspan." valign='top' style='font-family: TimesNewRoman;border:1px solid black;border-collapse:collapse;padding-right:5px;text-align:right;'>Sub Total</td>";

        if($is_tax == 'Y') {
            $html .= "<td style='font-family: TimesNewRoman;text-align:right;border:1px solid black;border-collapse:collapse;padding-right:5px;text-align:right;'>".$this->moneyFormatIndia(number_format((float)$totalTax, 2, '.', ''))."</td>";
            $html .= "<td style='font-family: TimesNewRoman;text-align:right;border:1px solid black;border-collapse:collapse;padding-right:5px;text-align:right;'></td>";
        }
        $html .= "<td style='font-family: TimesNewRoman;text-align:right;border:1px solid black;border-collapse:collapse;padding-right:5px;text-align:right;'>".$this->moneyFormatIndia(number_format((float)$lineTotal, 2, '.', ''))."</td>";
        $html .= "</tr>";
        
        $total_paid = $total_paid['total_paid'] != null ? $total_paid['total_paid'] : 0;
        
        $html .= "<tr>";
        $html .= "<td colspan=".$paidTotalrowspan." valign='top' style='font-family: TimesNewRoman;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;border-collapse:collapse;padding-right:5px;text-align:right;'>Parts Total</td>";
        $html .= "<td style='font-family: TimesNewRoman;text-align:right;border-left:1px solid black;border-right:1px solid black;border-top:1px solid black;border-color: black;border-width: thin;border-collapse:collapse;padding-right:5px;text-align:right;'>".$this->moneyFormatIndia(number_format((float)$partsTotal, 2, '.', ''))."</td>";
        $html .= "</tr>";
        $html .= "<tr>";

        $html .= "<tr>";
        $html .= "<td colspan=".$paidTotalrowspan." valign='top' style='font-family: TimesNewRoman;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;border-collapse:collapse;padding-right:5px;text-align:right;'>Labour Total</td>";
        $html .= "<td style='font-family: TimesNewRoman;text-align:right;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;border-collapse:collapse;padding-right:5px;text-align:right;'>".$this->moneyFormatIndia(number_format((float)$laborTotal, 2, '.', ''))."</td>";
        $html .= "</tr>";
        $html .= "<tr>";

        $html .= "<tr>";
        $html .= "<td colspan=".$paidTotalrowspan." valign='top' style='font-family: TimesNewRoman;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;border-collapse:collapse;padding-right:5px;text-align:right;font-weight:bold;'>Total Payable</td>";
        $html .= "<td style='font-family: TimesNewRoman;text-align:right;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;border-collapse:collapse;padding-right:5px;text-align:right;font-weight:bold;'>".$this->moneyFormatIndia(number_format((float)round($lineTotal,0), 2, '.', ''))."</td>";
        $html .= "</tr>";        
        
        $total_pd = $total_paid['total_paid'] != null ? $total_paid['total_paid'] : 0;
        $html .= "<tr>";
        $html .= "<td colspan=".$paidTotalrowspan." valign='top' style='font-family: TimesNewRoman;border-left:1px solid black;border-right:1px solid black;border-collapse:collapse;padding-right:5px;text-align:right;'>Paid</td>";
        $html .= "<td style='font-family: TimesNewRoman;text-align:right;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;border-collapse:collapse;padding-right:5px;text-align:right;'>".$this->moneyFormatIndia(number_format((float)round($total_paid,0), 2, '.', ''))."</td>";
        $html .= "</tr>";
        
        $bal = $lineTotal - $total_paid;
        $html .= "<tr>";
        $html .= "<td colspan=".$paidTotalrowspan." valign='top' style='font-family: TimesNewRoman;border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black;border-collapse:collapse;padding-right:5px;text-align:right;'>Balance</td>";
        $html .= "<td style='font-family: TimesNewRoman;text-align:right;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;border-collapse:collapse;padding-right:5px;text-align:right;'>".$this->moneyFormatIndia(number_format((float)round($bal,0), 2, '.', ''))."</td>";
        $html .= "</tr>";

        $html .= "<tr>";
        $html .= "<td colspan=".($paidTotalrowspan + 1)." valign='top' style='font-family: TimesNewRoman;border:1px solid black;border-color: black;border-width: thin;border-collapse:collapse;'>";
        $html .= "Amount in words: ";
        $html .= $invoiceAmtInWords.' Only';
        $html .= "</b></td>";
        $html .= "</tr>";
        $html .= '</table>';


        if($is_tax == 'Y' && !empty($taxSummary) && $jobcard['tax_type'] == 'scgst') {
            $html .= "<table style='margin-top:2px;border:1px solid black;border-collapse:collapse;width:100%;font-family:Arial;font-size:90%;'>";
            $html .= "<tr>";
            $html .= "<td style='font-family: TimesNewRoman;text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;' rowspan='2'>HSN/SAC</td>";
            $html .= "<td style='font-family: TimesNewRoman;text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;' rowspan='2'>Taxable Amt</td>";
            $html .= "<td style='font-family: TimesNewRoman;text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;' colspan='2'>CGST</td>";
            $html .= "<td style='font-family: TimesNewRoman;text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;' colspan='2'>SGST</td>";
            $html .= "<td style='font-family: TimesNewRoman;text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;' rowspan='2'>Total Tax</td>";
            $html .= '</tr>';
            $html .= "<tr>";
            $html .= "<td style='font-family: TimesNewRoman;text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;'>Rate</td>";
            $html .= "<td style='font-family: TimesNewRoman;text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;'>Amount</td>";
            $html .= "<td style='font-family: TimesNewRoman;text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;'>Rate</td>";
            $html .= "<td style='font-family: TimesNewRoman;text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;'>Amount</td>";
            $html .= '</tr>';

            foreach($taxSummary as $k => $v) {
            foreach($v as $k1 => $v1) {
                $html .= '<tr>';
                $html .= '<td style="font-family: TimesNewRoman;text-align:center;border:1px solid black;border-collapse:collapse;">'.$k.'</td>';
                $html .= '<td style="font-family: TimesNewRoman;text-align:center;border:1px solid black;border-collapse:collapse;">'.$this->moneyFormatIndia(number_format((float)$taxAbleAmt[$k][$k1], 2, '.', '')).'</td>';
                $html .= '<td style="font-family: TimesNewRoman;text-align:center;border:1px solid black;border-collapse:collapse;">'.sprintf('%0.2f', $k1/2).'</td>';
                $html .= '<td style="font-family: TimesNewRoman;text-align:center;border:1px solid black;border-collapse:collapse;">'.$this->moneyFormatIndia(number_format((float)$v1/2, 2, '.', '')).'</td>';
                $html .= '<td style="font-family: TimesNewRoman;text-align:center;border:1px solid black;border-collapse:collapse;">'.sprintf('%0.2f', $k1/2).'</td>';
                $html .= '<td style="font-family: TimesNewRoman;text-align:center;border:1px solid black;border-collapse:collapse;">'.$this->moneyFormatIndia(number_format((float)$v1/2, 2, '.', '')).'</td>';
                $html .= '<td style="font-family: TimesNewRoman;text-align:center;border:1px solid black;border-collapse:collapse;">'.$this->moneyFormatIndia(number_format((float)$v1, 2, '.', '')).'</td>';
                $html .= '</tr>';
            }
            }
            $html .= "</table>";
        } else if($is_tax == 'Y' && !empty($taxSummary) && $jobcard['tax_type'] == 'igst') {
            $html .= "<table style='margin-top:2px;border:1px solid black;border-collapse:collapse;width:100%;font-family:Arial;font-size:90%;'>";
            $html .= "<tr>";
            $html .= "<td style='font-family: TimesNewRoman;text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;' rowspan='2'>HSN/SAC</td>";
            $html .= "<td style='font-family: TimesNewRoman;text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;' rowspan='2'>Taxable Amt</td>";
            $html .= "<td style='font-family: TimesNewRoman;text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;' colspan='2'>IGST</td>";
            $html .= "<td style='font-family: TimesNewRoman;text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;' rowspan='2'>Total Tax</td>";
            $html .= '</tr>';
            $html .= "<tr>";
            $html .= "<td style='font-family: TimesNewRoman;text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;'>Rate</td>";
            $html .= "<td style='font-family: TimesNewRoman;text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;'>Amount</td>";
            $html .= '</tr>';

            foreach($taxSummary as $k => $v) {
            foreach($v as $k1 => $v1) {
                $html .= '<tr>';
                $html .= '<td style="font-family: TimesNewRoman;text-align:center;border:1px solid black;border-collapse:collapse;">'.$k.'</td>';
                $html .= '<td style="font-family: TimesNewRoman;text-align:center;border:1px solid black;border-collapse:collapse;">'.$this->moneyFormatIndia(number_format((float)$taxAbleAmt[$k][$k1], 2, '.', '')).'</td>';
                $html .= '<td style="font-family: TimesNewRoman;text-align:center;border:1px solid black;border-collapse:collapse;">'.sprintf('%0.2f', $k1).'</td>';
                $html .= '<td style="font-family: TimesNewRoman;text-align:center;border:1px solid black;border-collapse:collapse;">'.$this->moneyFormatIndia(number_format((float)$v1, 2, '.', '')).'</td>';
                $html .= '<td style="font-family: TimesNewRoman;text-align:center;border:1px solid black;border-collapse:collapse;">'.$this->moneyFormatIndia(number_format((float)$v1, 2, '.', '')).'</td>';
                $html .= '</tr>';
            }
            }
            $html .= "</table>";
        }

        $html  .= "<table style='margin-top:1px;border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black;border-collapse:collapse;width:100%;font-family:Arial;font-size:90%;'>";
        if($jobcard['invoice_notes'] != "") {
            $html .= '<tr>';
            $html .= "<td colspan='2' style='font-family: TimesNewRoman;border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black;'>";
            $html .= '<span><span style="font-family: TimesNewRoman;text-decoration: underline;"><b>Remarks:</span> <br>'.$jobcard['invoice_notes'].'</span>';
            $html .= "</td>";
            $html .= '</tr>';
        }
        $html .= '<tr>';
        $html .= "<td style='vertical-align:bottom;'>";
        if($garageDetail['invoice_notes'] !="") {
            $html .= "<span align='left' style='font-family: TimesNewRoman;text-align:left;float:left;width:55%;'><span style='text-decoration: underline;'><b>TERMS AND CONDITIONS: </b></span>".$garageDetail['invoice_notes']."</span>";
        } else {
            $html .= "<br><br><br>";
        }
        $html .= "</td>";
        $html .= "<td style='font-family: TimesNewRoman;min-height:150%;vertical-align:bottom;text-align:right;'>";
        $html .= "<span>For <b>".strtoupper($garageDetail['garage_name'])."</b></span>";
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '<div style="font-family: TimesNewRoman;text-align:center;width:100%;font-size:10px;"><i>THIS IS A COMPUTER GENERATED INVOICE AND REQUIRES NO SIGNATURE.</i></div>';
        $html .= '</div>';
        $html .= '<style> .fsize14 { font-size: 14px;} .bold-item { font-size: 14px;} .non-bold-item { font-size: 12px;font-family: TimesNewRoman;}  h2 { font-size: 16px;font-weight:bolder;font-family:TimesNewRoman;} span { font-size : 12px;font-family:TimesNewRoman;}  .no-b{ border:1px solid black;border-top:0px !important;border-bottom: 0px !important; } @page { margin: 35px 25px 25px 25px; }</style>';
        
        $flName = 'Invoice-'.$jobcard['invoice_no'].'-'.strtoupper($jobcard['name']).'-'.date('d-m-Y',strtotime($jobcard['date'])).'.pdf';
        
        //$mpdf->cacheTables = true;
        $mpdf->SetTitle($flName);
        $mpdf->WriteHTML($html);
        
        $mpdf->Output($file_name,'F');

        header('Content-type:application/pdf');
        header('Content-disposition: inline; filename="'.$flName.'"');
        @ readfile($file_name);
    }
    public function viewInsuranceInvoicePdf($jobcard_id) {
        $logo_path = LOGO_DOCUMENT_ROOT.'thumbs/garage_'.$_SESSION['setting']->garage_id;
        include_once(DOCUMENT_ROOT."uploads/mpdf/mpdf.php");
        $mpdf = new mPDF('c','A4');
        $taxSummary = array();
        $taxAbleAmt = array();
        
        // jobcard and jobcard items.
	    $this->db->select('tbl_insurance.name as insurance_name,tbl_insurance.address as insurance_address,tbl_insurance.gst_no as insurance_gstno,tbl_invoices.invoice_id,tbl_invoices.notes as invoice_remarks,tbl_invoices.date as invoice_date,tbl_invoices.invoice_no,tbl_jobcard.*,tbl_customer.*,tbl_customer_vehicle.reg_no,tbl_customer_vehicle.fuel_type,tbl_make.make_id,tbl_make.name as make_name,tbl_model.model_id,tbl_model.name as model_name');
        $this->db->from('tbl_jobcard');
        $this->db->join('tbl_invoices','tbl_invoices.item_id=tbl_jobcard.jobcard_id AND item_type="job_invoice"','left');
        $this->db->join('tbl_customer','tbl_customer.customer_id=tbl_jobcard.customer_id','left');
        $this->db->join('tbl_customer_vehicle','tbl_customer_vehicle.vehicle_id=tbl_jobcard.vehicle_id','left');
        $this->db->join('tbl_make','tbl_make.make_id=tbl_customer_vehicle.make_id','left');
        $this->db->join('tbl_model','tbl_model.model_id=tbl_customer_vehicle.model_id','left');
        $this->db->join('tbl_insurance','tbl_insurance.insurance_id=tbl_jobcard.insurance_id','left');
        $this->db->where('tbl_jobcard.jobcard_id',$jobcard_id);
        $jobcard = $this->db->get()->row_array();
        

        // garage detail.
        $this->db->select('*,tbl_garage.name as garage_name');
        $this->db->from('tbl_garage');
        $this->db->where('tbl_garage.garage_id',$jobcard['garage_id']);
        $garageDetail = $this->db->get()->row_array();
        
        $file_name = DOCUMENT_ROOT.'uploads/invoices/Invoice-'.$jobcard['jobcard_no'].'-'.date('d-m-Y');
        $is_disc = $jobcard['is_disc_applicable'];
        $is_tax  = $jobcard['is_tax_applicable'];
        $grandTotalrowspan = 4;
        $paidTotalrowspan = 4;
        
        if($is_tax == 'Y') {
            $grandTotalrowspan++;
            $paidTotalrowspan++;
            $paidTotalrowspan++;
            $paidTotalrowspan++;
        }
		
        $this->db->select('tbl_jobcard_item.*,tbl_items.item_name,tbl_items.hsn_sac_code');
        $this->db->from('tbl_jobcard_item');
        $this->db->join('tbl_items','tbl_items.item_id = tbl_jobcard_item.item_id','left');
        $this->db->where('jobcard_id',$jobcard_id);
        $this->db->order_by('tbl_jobcard_item.item_type','asc');
        $jobcard_item = $this->db->get()->result_array();

        $this->db->select('sum(tbl_transaction.amount) as total_paid');
        $this->db->from('tbl_transaction');
        $this->db->where('customer_id',$jobcard['customer_id']);
        $this->db->where('item_id',$jobcard['invoice_id']);
        $this->db->where('transaction_type','customer_payment');
        $total_paid = $this->db->get()->row_array();
        
        $blankLineLength = 190;
        $this->load->model('SettingModel');
        $invoiceAmtInWords = $this->SettingModel->getIndianCurrencyInWords($jobcard['grand_total']);
        $html .= "<table style='width:100%;'>";
        $html .= "<tr><td style='text-align:center;font-family:TimesNewRoman;'>";
        $html .= "<h2>TAX INVOICE</h2>";
        $html .= "</td></tr>";
        $html .= "</table>";
        $html .= "<table class='padding_table' style='border-collapse:collapse;width:100%;font-family:TimesNewRoman;'>";
        $html .= '<tr>';
        $logotdcolSpan = 2;
        $logotdStyle = "width:35%;border-collpase:collpased;padding:4px;border-left:1px solid black;border-right:1px solid black;border-top:1px solid black;border-color: black;border-width: thin;";
        if(file_exists($logo_path.'/'.$garageDetail['logo_path'])) {
            $html .= '<td rowspan="2" valign="top" style="width:15%;border-collpase:collpased;padding : 4px;border-bottom:1px solid black;border-left:1px solid black;border-top:1px solid black;border-color: black;border-width: thin;">';
            $html .= '<img src="'.$logo_path.'/'.$garageDetail['logo_path'].'">';
            $html .= '</td>';
            $logotdcolSpan = 0;
            $logotdStyle = "width:35%;border-collpase:collpased;padding : 4px;border-bottom:1px solid black;border-right:1px solid black;border-top:1px solid black;border-color: black;border-width: thin;";
        }

        $html .= '<td rowspan="2" colspan="'.$logotdcolSpan.'" valign="top" style="'.$logotdStyle.'">';
        $html .= '<span class="bold-item"><b>'.strtoupper($garageDetail['garage_name']).'</b></span><br>';
        $html .= '<span class="non-bold-item">'.strtoupper($garageDetail['address']).'</span><br>';

        if($garageDetail['gstin_no'] !="" && $is_tax == 'Y') {
            $html .= '<span class="non-bold-item">GSTIN : '.$garageDetail['gstin_no'].'.</span><br>';
        }
        $html .= '<span class="non-bold-item">';
        if($garageDetail['contact_no'] != "") {
            $html .= 'PH: '.$garageDetail['contact_no'];
        }

        if($garageDetail['alternate_contact'] != "" && $garageDetail['contact_no'] != "") {
            $html .= ', '.$garageDetail['alternate_contact'];
        } else if($garageDetail['alternate_contact'] != "" && $garageDetail['contact_no'] == "") {
            $html .= $garageDetail['alternate_contact'];
        }
        $html .= '</span>';
        $html .= '</td>';
        $html .= '<td style="height:20px;width:25%;border-collpase:collpased;padding : 4px;border:1px solid black;border-color: black;border-width: thin;border-style: solid" valign="top"><span class="fsize14">Invoice No  <br>'.$jobcard['invoice_no'].' </span></td>';
        $html .= '<td style="height:20px;width:25%;padding : 4px;border:1px solid black;border-color: black;border-width: thin;border-style: solid" valign="top"><span class="fsize14">Date <br>'.date('d-m-Y', strtotime($jobcard['invoice_date'])).'</span></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td valign="top" style="height:20px;padding : 4px;border:1px solid black;border-color: black;border-width: thin;border-style: solid">';
        $html .= '<span class="fsize14">Jobcard No <br>'.$jobcard['jobcard_no'].'</span>';
        $html .= '</td>';
        $html .= '<td valign="top" style="height:20px;padding : 4px;border:1px solid black;border-color: black;border-width: thin;border-style: solid">';
        $html .= '<span class="fsize14">Vehicle No <br>'.strtoupper($jobcard['reg_no']).'</span>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td colspan="2" valign="top" style="padding : 4px;border:1px solid black;border-color: black;border-width: thin;border-style: solid">';
        $html .= '<span class="fsize14"><b>Company Name</b> : ';
        $html .= $jobcard['insurance_name'].'</span><br>';
        $html .= '<span class="fsize14"><b>Address </b>: ';
        $html .= $jobcard['insurance_address'].'</span><br>';
        $html .= '<span class="fsize14"><b>GSTIN </b>: ';
        $html .= $jobcard['insurance_gstno'].'</span><br>';
        $html .= '</td>';
        $html .= '<td colspan="2" valign="top" style="height:35px;padding : 4px;border:1px solid black;border-color: black;border-width: thin;border-style: solid">';
        $html .= '<span class="fsize14">';
        $html .= '<b>Customer </b> : ';
        $html .= strtoupper($jobcard['name']).'</span><br>';
        $html .= '<span class="fsize14">';
        $html .= '<b>Contact No.</b> : ';
        $html .= $jobcard['mobile_no'].'</span><br>';
        $html .= '<span class="fsize14"><b>Vehicle</b> : ';
        $html .= strtoupper($jobcard['make_name'].' '.$jobcard['model_name']).'</span><br>';
        $html .= '<span class="fsize14"><b>Kilometer</b> : ';
        $html .= $jobcard['odometer'].'</span><br>';
        $html .= '<span class="fsize14"><b>GSTIN</b> : ';
        $html .= strtoupper($jobcard['gst_no']).'</span><br>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<div style="float:right;width:100%;text-align:right;font-family:TimesNewRoman;">';
        $html .= '<table style="width:100%;border-collapse:collapse;font-family:TimesNewRoman;font-size:90%;">';
        $html .= '<tr>';
        $html .= '<td style="border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;text-align:left;padding:4px;width:3%;">SrNo</td>';
        $html .= '<td style="border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;text-align:left;padding:4px;">Description</td>';
        if($is_tax == 'Y') {
        $html .= '<td style="border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;text-align:right;padding:4px;width:10%;">HSN/SAC</td>';
        }
        $html .= '<td style="border-bottom:1px solid black;text-align:right;padding:4px;width:5%;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;">Qty</td>';
        $html .= '<td style="border-bottom:1px solid black;text-align:right;padding:4px;width:10%;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;">Unit Price</td>';
        
        if($is_tax == 'Y') {
            $html .= '<td style="border-bottom:1px solid black;text-align:right;padding:4px;width:8%;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;">Tax</td>';
            $html .= '<td style="border-bottom:1px solid black;text-align:right;padding:4px;width:4%;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;">%</td>';
        }
        $html .= '<td style="border-bottom:1px solid black;text-align:right;padding:4px;width:10%;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;">Amount</td>';
        $html .= '</tr>';
        $srno = 1;
        $isPartAdded = 'N';
        $isServiceAdded = 'N';
        $partsTotal = 0;
        $laborTotal = 0;
        $lineTotal = 0;
        $totalTax = 0;
        foreach ($jobcard_item as $i => $v) {
            $description = $v['description'];
            $qty = $v['qty'] != "" && $v['qty'] != 0 ? $v['qty'] : '';
            if($qty != '') {
                $per_unit_price = round(($v['unit_price'] * $qty) - $v['discount_value'] - $v['customer_payable'],2);
            } else {
                $per_unit_price = round($v['unit_price'] - $v['discount_value'] - $v['customer_payable'],2);
            }

            if($per_unit_price != 0) {
                if($v['item_type'] == 'P' && $isPartAdded =='N') {
                    $html .= '<tr>';
                    $html .= '<td colspan="2" class="no-b non-bold-item" style="border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;background-color:lightgray;vertical-align:top;border-collapse:collapse;padding:3px;text-align:left;">PART</td>';
                    if($is_tax == 'Y') {
                        $html .= '<td class="no-b non-bold-item" style="border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;text-align:right;padding:4px;width:10%;"></td>';
                    }
                    $html .= '<td class="no-b non-bold-item" style="border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;text-align:right;padding:4px;width:5%;"></td>';
                    $html .= '<td class="no-b non-bold-item" style="border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;text-align:right;padding:4px;width:10%;"></td>';
                        
                    if($is_tax == 'Y') {
                        $html .= '<td class="no-b non-bold-item" style="border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;text-align:right;padding:4px;width:8%;"></td>';
                        $html .= '<td class="no-b non-bold-item" style="border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;text-align:right;padding:4px;width:4%;"></td>';
                    }
                    $html .= '<td class="no-b non-bold-item" style="border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;text-align:right;padding:4px;width:10%;"></td>';
                    $html .= '</tr>';
                    $isPartAdded = 'Y';
                }
                if($v['item_type'] == 'S' && $isServiceAdded =='N') {
                    $html .= '<tr>';
                    $html .= '<td colspan="2" class="no-b non-bold-item" style="background-color:lightgray;vertical-align:top;border-collapse:collapse;padding:3px;text-align:left;">LABOR</td>';
                    if($is_tax == 'Y') {
                        $html .= '<td class="no-b non-bold-item" style="text-align:right;padding:4px;width:10%;"></td>';
                    }
                    $html .= '<td class="no-b non-bold-item" style="text-align:right;padding:4px;width:5%;"></td>';
                    $html .= '<td class="no-b non-bold-item" style="text-align:right;padding:4px;width:10%;"></td>';
                        
                    if($is_tax == 'Y') {
                        $html .= '<td class="no-b non-bold-item" style="text-align:right;padding:4px;width:8%;"></td>';
                        $html .= '<td class="no-b non-bold-item" style="text-align:right;padding:4px;width:4%;"></td>';
                    }
                    $html .= '<td class="no-b non-bold-item" style="text-align:right;padding:4px;width:10%;"></td>';
                    $html .= '</tr>';
                    $isServiceAdded = 'Y';
                }
                
                $html .= '<tr>';
                $html .= '<td class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:3px;text-align:left;">'.$srno.'</td>';
                $html .= '<td class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:3px;text-align:left;">'.strtoupper($v['item_name']).'<br><small>'.$description.'</small></td>';
                if($is_tax == 'Y') {
                $hsn_sac_code = $v['hsn_sac_code'] != "" && $v['hsn_sac_code'] != 0 ? $v['hsn_sac_code'] : '';
                $html .= '<td class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:5px;text-align:right;">'.$hsn_sac_code.'</td>';
                }

                
                $html .= '<td  class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:5px;text-align:right;">'.$qty.'</td>';
                $html .= '<td  class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:5px;text-align:right;">'.$this->moneyFormatIndia($per_unit_price).'</td>';

                if($is_tax == 'Y') {
                    $tax = $v['tax_rate'] != "" && $v['tax_rate'] != 0 ? $this->moneyFormatIndia(round(($v['tax_rate'] * $per_unit_price) / 100 , 2)) : '';
                    $tAmount = $v['tax_rate'] != "" && $v['tax_rate'] != 0 ? round(($v['tax_rate'] * $per_unit_price) / 100 , 2) : '';
                    $tax_rate = $v['tax_rate'] != "" && $v['tax_rate'] != 0 ? '('.$this->moneyFormatIndia($v['tax_rate']).'%)' : '';
                    $html .= '<td class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:5px;text-align:right;">'.$tax.'</td>';
                    $html .= '<td class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:5px;text-align:right;">'.$tax_rate.'</td>';
                    if($v['tax_rate'] != 0 && $v['tax_rate'] != '') {
                        $taxSummary[$hsn_sac_code][$v['tax_rate']] += $tAmount;
                        $taxAbleAmt[$hsn_sac_code][$v['tax_rate']] += $per_unit_price;
                    }
                    $totalTax += $tAmount;
                }   
                $lineTotal += $per_unit_price + $tAmount;
                if($v['item_type'] == 'P') {
                    $partsTotal += $per_unit_price + $tAmount;
                } else {
                    $laborTotal += $per_unit_price + $tAmount;
                }
                $html .= '<td class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:5px;text-align:right;">'.$this->moneyFormatIndia($per_unit_price + $tAmount).'</td>';
                $html .= '</tr>';
                $srno++;
            }
        }
        for ($i=count($jobcard_item); $i <=(150 - (count($jobcard_item) * 7)); $i++) {
            $html .= '<tr class="no-b">';
            $html .= '<td class="no-b"></td>';
            $html .= '<td class="no-b"></td>';
            if($is_tax == 'Y') {
            $html .= '<td class="no-b"></td>';
            }
            $html .= '<td class="no-b"></td>';
            $html .= '<td class="no-b"></td>';
           
            if($is_tax == 'Y') {

                $html .= '<td class="no-b"></td>';
                $html .= '<td class="no-b"></td>';
            }
            $html .= '<td class="no-b"></td>';
            $html .= '</tr>';
    	}

        $html .= "<tr>";
        $html .= "<td colspan=".$grandTotalrowspan." valign='top' style='border:1px solid black;border-collapse:collapse;padding-right:5px;text-align:right;'>Sub Total</td>";
        
        if($is_tax == 'Y') {
            $html .= "<td style='text-align:right;border:1px solid black;border-collapse:collapse;padding-right:5px;text-align:right;'>".$this->moneyFormatIndia(number_format((float)$totalTax, 2, '.', ''))."</td>";
            $html .= "<td style='text-align:right;border:1px solid black;border-collapse:collapse;padding-right:5px;text-align:right;'></td>";
        }
        $html .= "<td style='text-align:right;border:1px solid black;border-collapse:collapse;padding-right:5px;text-align:right;'>".$this->moneyFormatIndia(number_format((float)$lineTotal, 2, '.', ''))."</td>";
        $html .= "</tr>";
        
        $total_paid = $total_paid['total_paid'] != null ? $total_paid['total_paid'] : 0;
        
        $html .= "<tr>";
        $html .= "<td colspan=".$paidTotalrowspan." valign='top' style='border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;border-collapse:collapse;padding-right:5px;text-align:right;'>Parts Total</td>";
        $html .= "<td style='text-align:right;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;border-collapse:collapse;padding-right:5px;text-align:right;'>".$this->moneyFormatIndia(number_format((float)$partsTotal, 2, '.', ''))."</td>";
        $html .= "</tr>";
        $html .= "<tr>";

        $html .= "<tr>";
        $html .= "<td colspan=".$paidTotalrowspan." valign='top' style='border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;border-collapse:collapse;padding-right:5px;text-align:right;'>Labour Total</td>";
        $html .= "<td style='text-align:right;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;border-collapse:collapse;padding-right:5px;text-align:right;'>".$this->moneyFormatIndia(number_format((float)$laborTotal, 2, '.', ''))."</td>";
        $html .= "</tr>";
        $html .= "<tr>";

        $html .= "<tr>";
        $html .= "<td colspan=".$paidTotalrowspan." valign='top' style='border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;border-collapse:collapse;padding-right:5px;text-align:right;font-weight:bold;'>Total Payable</td>";
        $html .= "<td style='text-align:right;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;border-collapse:collapse;padding-right:5px;text-align:right;font-weight:bold;'>".$this->moneyFormatIndia(number_format((float)round($lineTotal,0), 2, '.', ''))."</td>";
        $html .= "</tr>";        
        
        $html .= "<tr>";
        $html .= "<td colspan=".$paidTotalrowspan." valign='top' style='border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black;border-collapse:collapse;padding-right:5px;text-align:right;'>Balance</td>";
        $html .= "<td style='text-align:right;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;border-collapse:collapse;padding-right:5px;text-align:right;'>".$this->moneyFormatIndia(number_format((float)round($lineTotal,0), 2, '.', ''))."</td>";
        $html .= "</tr>";

        $html .= "<tr>";
        $html .= "<td colspan=".($paidTotalrowspan + 1)." valign='top' style='border:1px solid black;border-color: black;border-width: thin;border-collapse:collapse;'>";
        $html .= "Amount in words: ";
        $html .= $invoiceAmtInWords.' Only';
        $html .= "</b></td>";
        $html .= "</tr>";
        $html .= '</table>';


        if($is_tax == 'Y' && !empty($taxSummary) && $jobcard['tax_type'] == 'scgst') {
            $html .= "<table style='margin-top:2px;border:1px solid black;border-collapse:collapse;width:100%;font-family:TimesNewRoman;font-size:90%;'>";
            $html .= "<tr>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;' rowspan='2'>HSN/SAC</td>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;' rowspan='2'>Taxable Amt</td>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;' colspan='2'>CGST</td>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;' colspan='2'>SGST</td>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;' rowspan='2'>Total Tax</td>";
            $html .= '</tr>';
            $html .= "<tr>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;'>Rate</td>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;'>Amount</td>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;'>Rate</td>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;'>Amount</td>";
            $html .= '</tr>';

            foreach($taxSummary as $k => $v) {
            foreach($v as $k1 => $v1) {
                $html .= '<tr>';
                $html .= '<td style="text-align:center;border:1px solid black;border-collapse:collapse;">'.$k.'</td>';
                $html .= '<td style="text-align:center;border:1px solid black;border-collapse:collapse;">'.$this->moneyFormatIndia(number_format((float)$taxAbleAmt[$k][$k1], 2, '.', '')).'</td>';
                $html .= '<td style="text-align:center;border:1px solid black;border-collapse:collapse;">'.sprintf('%0.2f', $k1/2).'</td>';
                $html .= '<td style="text-align:center;border:1px solid black;border-collapse:collapse;">'.$this->moneyFormatIndia(number_format((float)$v1/2, 2, '.', '')).'</td>';
                $html .= '<td style="text-align:center;border:1px solid black;border-collapse:collapse;">'.sprintf('%0.2f', $k1/2).'</td>';
                $html .= '<td style="text-align:center;border:1px solid black;border-collapse:collapse;">'.$this->moneyFormatIndia(number_format((float)$v1/2, 2, '.', '')).'</td>';
                $html .= '<td style="text-align:center;border:1px solid black;border-collapse:collapse;">'.$this->moneyFormatIndia(number_format((float)$v1, 2, '.', '')).'</td>';
                $html .= '</tr>';
            }
            }
            $html .= "</table>";
        } else if($is_tax == 'Y' && !empty($taxSummary) && $jobcard['tax_type'] == 'igst') {
            $html .= "<table style='margin-top:2px;border:1px solid black;border-collapse:collapse;width:100%;font-family:TimesNewRoman;font-size:90%;'>";
            $html .= "<tr>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;' rowspan='2'>HSN/SAC</td>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;' rowspan='2'>Taxable Amt</td>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;' colspan='2'>IGST</td>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;' rowspan='2'>Total Tax</td>";
            $html .= '</tr>';
            $html .= "<tr>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;'>Rate</td>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;'>Amount</td>";
            $html .= '</tr>';

            foreach($taxSummary as $k => $v) {
            foreach($v as $k1 => $v1) {
                $html .= '<tr>';
                $html .= '<td style="text-align:center;border:1px solid black;border-collapse:collapse;">'.$k.'</td>';
                $html .= '<td style="text-align:center;border:1px solid black;border-collapse:collapse;">'.$this->moneyFormatIndia(number_format((float)$taxAbleAmt[$k][$k1], 2, '.', '')).'</td>';
                $html .= '<td style="text-align:center;border:1px solid black;border-collapse:collapse;">'.sprintf('%0.2f', $k1).'</td>';
                $html .= '<td style="text-align:center;border:1px solid black;border-collapse:collapse;">'.$this->moneyFormatIndia(number_format((float)$v1, 2, '.', '')).'</td>';
                $html .= '<td style="text-align:center;border:1px solid black;border-collapse:collapse;">'.$this->moneyFormatIndia(number_format((float)$v1, 2, '.', '')).'</td>';
                $html .= '</tr>';
            }
            }
            $html .= "</table>";
        }

        $html  .= "<table style='margin-top:1px;border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black;border-collapse:collapse;width:100%;font-family:TimesNewRoman;font-size:90%;'>";
        if($jobcard['invoice_notes'] != "") {
            $html .= '<tr>';
            $html .= "<td colspan='2' style='border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black;'>";
            $html .= '<span><span style="font-family:TimesNewRoman;text-decoration: underline;"><b>Remarks:</span> <br>'.$jobcard['invoice_notes'].'</span>';
            $html .= "</td>";
            $html .= '</tr>';
        }
        $html .= '<tr>';
        $html .= "<td style='vertical-align:bottom;'>";
        if($garageDetail['invoice_notes'] !="") {
            $html .= "<span align='left' style='font-family:TimesNewRoman;text-align:left;float:left;width:55%;'><span style='text-decoration: underline;'><b>TERMS AND CONDITIONS: </b></span>".$garageDetail['invoice_notes']."</span>";
        } else {
            $html .= "<br><br><br>";
        }
        $html .= "</td>";
        $html .= "<td style='min-height:150%;vertical-align:bottom;text-align:right;'>";
        $html .= "<span style='font-family:TimesNewRoman;'>For <b>".strtoupper($garageDetail['garage_name'])."</b></span>";
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '<div style="text-align:center;width:100%;font-size:10px;"><i>THIS IS A COMPUTER GENERATED INVOICE AND REQUIRES NO SIGNATURE.</i></div>';
        $html .= '</div>';
        $html .= '<style> .fsize14 { font-size: 14px;font-family:TimesNewRoman;} .bold-item { font-size: 14px;font-family:TimesNewRoman;} .non-bold-item { font-size: 12px;font-family:TimesNewRoman;}  h2 { font-size: 16px;font-weight:bolder;font-family:Helvetica;} span { font-size : 12px;font-family:Helvetica;}  .no-b{ border:1px solid black;border-top:0px !important;border-bottom: 0px !important; } @page { margin: 35px 25px 25px 25px; }</style>';
        
        $flName = 'Invoice-'.$jobcard['invoice_no'].'-'.strtoupper($jobcard['name']).'-'.date('d-m-Y',strtotime($jobcard['date'])).'.pdf';
        
        //$mpdf->cacheTables = true;
        $mpdf->SetTitle($flName);
        $mpdf->WriteHTML($html);
        
        $mpdf->Output($file_name,'F');

        header('Content-type:application/pdf');
        header('Content-disposition: inline; filename="'.$flName.'"');
        @ readfile($file_name);
    }
    public function viewCustomerInvoicePdf($jobcard_id) {
        $logo_path = LOGO_DOCUMENT_ROOT.'thumbs/garage_'.$_SESSION['setting']->garage_id;
        include_once(DOCUMENT_ROOT."uploads/mpdf/mpdf.php");
        $mpdf = new mPDF('c','A4');
        $taxSummary = array();
        $taxAbleAmt = array();
        
        // jobcard and jobcard items.
	    $this->db->select('tbl_insurance.name as insurance_name,tbl_insurance.address as insurance_address,tbl_insurance.gst_no as insurance_gstno,tbl_invoices.invoice_id,tbl_invoices.notes as invoice_remarks,tbl_invoices.date as invoice_date,tbl_invoices.invoice_no,tbl_jobcard.*,tbl_customer.*,tbl_customer_vehicle.reg_no,tbl_customer_vehicle.fuel_type,tbl_make.make_id,tbl_make.name as make_name,tbl_model.model_id,tbl_model.name as model_name');
        $this->db->from('tbl_jobcard');
        $this->db->join('tbl_invoices','tbl_invoices.item_id=tbl_jobcard.jobcard_id AND item_type="job_invoice"','left');
        $this->db->join('tbl_customer','tbl_customer.customer_id=tbl_jobcard.customer_id','left');
        $this->db->join('tbl_customer_vehicle','tbl_customer_vehicle.vehicle_id=tbl_jobcard.vehicle_id','left');
        $this->db->join('tbl_make','tbl_make.make_id=tbl_customer_vehicle.make_id','left');
        $this->db->join('tbl_model','tbl_model.model_id=tbl_customer_vehicle.model_id','left');
        $this->db->join('tbl_insurance','tbl_insurance.insurance_id=tbl_jobcard.insurance_id','left');
        $this->db->where('tbl_jobcard.jobcard_id',$jobcard_id);
        $jobcard = $this->db->get()->row_array();
        

        // garage detail.
        $this->db->select('*,tbl_garage.name as garage_name');
        $this->db->from('tbl_garage');
        $this->db->where('tbl_garage.garage_id',$jobcard['garage_id']);
        $garageDetail = $this->db->get()->row_array();
        
        $file_name = DOCUMENT_ROOT.'uploads/invoices/Invoice-'.$jobcard['jobcard_no'].'-'.date('d-m-Y');
        $is_disc = $jobcard['is_disc_applicable'];
        $is_tax  = $jobcard['is_tax_applicable'];
        $grandTotalrowspan = 4;
        $paidTotalrowspan = 4;
        
        if($is_tax == 'Y') {
            $grandTotalrowspan++;
            $paidTotalrowspan++;
            $paidTotalrowspan++;
            $paidTotalrowspan++;
        }
		
        $this->db->select('tbl_jobcard_item.*,tbl_items.item_name,tbl_items.hsn_sac_code');
        $this->db->from('tbl_jobcard_item');
        $this->db->join('tbl_items','tbl_items.item_id = tbl_jobcard_item.item_id','left');
        $this->db->where('jobcard_id',$jobcard_id);
        $this->db->order_by('tbl_jobcard_item.item_type','asc');
        $jobcard_item = $this->db->get()->result_array();

        $this->db->select('sum(tbl_transaction.amount) as total_paid');
        $this->db->from('tbl_transaction');
        $this->db->where('customer_id',$jobcard['customer_id']);
        $this->db->where('item_id',$jobcard['invoice_id']);
        $this->db->where('transaction_type','customer_payment');
        $total_paid = $this->db->get()->row_array();
        
        $blankLineLength = 190;
        $this->load->model('SettingModel');
        $invoiceAmtInWords = $this->SettingModel->getIndianCurrencyInWords($jobcard['grand_total']);
        $html .= "<table style='width:100%;'>";
        $html .= "<tr><td style='text-align:center;'>";
        $html .= "<h2>TAX INVOICE</h2>";
        $html .= "</td></tr>";
        $html .= "</table>";
        $html .= "<table class='padding_table' style='border-collapse:collapse;width:100%;font-family:TimesNewRoman;'>";
        $html .= '<tr>';
        $logotdcolSpan = 2;
        $logotdStyle = "width:35%;border-collpase:collpased;padding:4px;border-left:1px solid black;border-right:1px solid black;border-top:1px solid black;border-color: black;border-width: thin;";
        if(file_exists($logo_path.'/'.$garageDetail['logo_path'])) {
            $html .= '<td rowspan="2" valign="top" style="width:15%;border-collpase:collpased;padding : 4px;border-bottom:1px solid black;border-left:1px solid black;border-top:1px solid black;border-color: black;border-width: thin;">';
            $html .= '<img src="'.$logo_path.'/'.$garageDetail['logo_path'].'">';
            $html .= '</td>';
            $logotdcolSpan = 0;
            $logotdStyle = "width:35%;border-collpase:collpased;padding : 4px;border-bottom:1px solid black;border-right:1px solid black;border-top:1px solid black;border-color: black;border-width: thin;";
        }

        $html .= '<td rowspan="4" style="border-bottom:1px solid black;" colspan="'.$logotdcolSpan.'" valign="top" style="'.$logotdStyle.'">';
        $html .= '<span class="bold-item"><b>'.strtoupper($garageDetail['garage_name']).'</b></span><br>';
        $html .= '<span class="non-bold-item">'.strtoupper($garageDetail['address']).'</span><br>';

        if($garageDetail['gstin_no'] !="" && $is_tax == 'Y') {
            $html .= '<span class="non-bold-item">GSTIN : '.$garageDetail['gstin_no'].'.</span><br>';
        }
        $html .= '<span class="non-bold-item">';
        if($garageDetail['contact_no'] != "") {
            $html .= 'PH: '.$garageDetail['contact_no'];
        }

        if($garageDetail['alternate_contact'] != "" && $garageDetail['contact_no'] != "") {
            $html .= ', '.$garageDetail['alternate_contact'];
        } else if($garageDetail['alternate_contact'] != "" && $garageDetail['contact_no'] == "") {
            $html .= $garageDetail['alternate_contact'];
        }
        $html .= '</span>';
        $html .= '</td>';
        $html .= '<td style="padding: 4px;height:20px;width:25%;border-collpase:collpased;padding : 4px;border:1px solid black;border-color: black;border-width: thin;border-style: solid" valign="top"><span class="fsize14"><b>Invoice No</b>  <br>'.$jobcard['invoice_no'].' </span></td>';
        $html .= '<td style="padding: 4px;height:20px;width:25%;padding: 4px;border:1px solid black;border-color: black;border-width: thin;border-style: solid" valign="top"><span class="fsize14"><b>Date</b> <br>'.date('d-m-Y', strtotime($jobcard['invoice_date'])).'</span></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td valign="top" style="height:20px;padding: 4px;border:1px solid black;border-color: black;border-width: thin;border-style: solid">';
        $html .= '<span class="fsize14"><b>Jobcard No </b><br>'.$jobcard['jobcard_no'].'</span>';
        $html .= '</td>';
        $html .= '<td valign="top" style="height:20px;padding: 4px;border:1px solid black;border-color: black;border-width: thin;border-style: solid">';
        $html .= '<span class="fsize14"><b>Vehicle No</b> <br>'.strtoupper($jobcard['reg_no']).'</span>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td colspan="2" valign="top" style="height:35px;padding: 4px;border-top:1px solid black;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;border-style: solid">';
        $html .= '<span class="fsize14">';
        $html .= '<b>Customer </b> : ';
        $html .= strtoupper($jobcard['name']).'</span><br>';
        $html .= '<span class="fsize14">';
        $html .= '<b>Contact No.</b> : ';
        $html .= $jobcard['mobile_no'].'</span><br>';
        $html .= '<span class="fsize14"><b>Vehicle</b> : ';
        $html .= strtoupper($jobcard['make_name'].' '.$jobcard['model_name']).'</span><br>';
        $html .= '<span class="fsize14"><b>Kilometer</b> : ';
        $html .= $jobcard['odometer'].'</span><br>';
        $html .= '<span class="fsize14"><b>GSTIN</b> : ';
        $html .= strtoupper($jobcard['gst_no']).'</span><br>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<div style="float:right;width:100%;text-align:right;font-family:TimesNewRoman;">';
        $html .= '<table style="width:100%;border-collapse:collapse;font-family:TimesNewRoman;font-size:90%;">';
        $html .= '<tr>';
        $html .= '<td style="border-bottom:1px solid black;border-top:1px solid black;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;text-align:left;padding:4px;width:3%;">SrNo</td>';
        $html .= '<td style="border-bottom:1px solid black;border-top:1px solid black;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;text-align:left;padding:4px;">Description</td>';
        if($is_tax == 'Y') {
            $html .= '<td style="border-bottom:1px solid black;border-top:1px solid black;border-bottom:1px solid black;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;text-align:right;padding:4px;width:10%;">HSN/SAC</td>';
        }
        $html .= '<td style="border-bottom:1px solid black;text-align:right;padding:4px;width:5%;border-left:1px solid black;border-right:1px solid black;border-color: black;border-top:1px solid black;border-width: thin;">Qty</td>';
        $html .= '<td style="border-bottom:1px solid black;text-align:right;padding:4px;width:10%;border-left:1px solid black;border-right:1px solid black;border-color: black;border-top:1px solid black;border-width: thin;">Unit Price</td>';
        
        
        if($is_tax == 'Y') {
            $html .= '<td style="border-bottom:1px solid black;text-align:right;padding:4px;width:8%;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;">Tax</td>';
            $html .= '<td style="border-bottom:1px solid black;text-align:right;padding:4px;width:4%;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;">%</td>';
        }
        $html .= '<td style="border-bottom:1px solid black;text-align:right;padding:4px;width:10%;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;">Amount</td>';
        $html .= '</tr>';
        $srno = 1;
        $isPartAdded = 'N';
        $isServiceAdded = 'N';
        $partsTotal = 0;
        $laborTotal = 0;
        $lineTotal = 0;
        $totalTax = 0;
        $totalDisc = 0;
        foreach ($jobcard_item as $i => $v) {
            $description = $v['description'];
            if($v['item_type'] == 'P' && $isPartAdded =='N') {
                $html .= '<tr>';
                $html .= '<td colspan="2" class="no-b non-bold-item" style="border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;background-color:lightgray;vertical-align:top;border-collapse:collapse;padding:3px;text-align:left;">PART</td>';
                if($is_tax == 'Y') {
                    $html .= '<td class="no-b non-bold-item" style="text-align:right;padding:4px;width:10%;"></td>';
                }
                $html .= '<td class="no-b non-bold-item" style="border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;text-align:right;padding:4px;width:5%;"></td>';
                $html .= '<td class="no-b non-bold-item" style="border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;text-align:right;padding:4px;width:10%;"></td>';
                
                if($is_tax == 'Y') {
                    $html .= '<td class="no-b non-bold-item" style="border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;text-align:right;padding:4px;width:8%;"></td>';
                    $html .= '<td class="no-b non-bold-item" style="border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;text-align:right;padding:4px;width:4%;"></td>';
                }
                $html .= '<td class="no-b non-bold-item" style="border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;text-align:right;padding:4px;width:10%;"></td>';
                $html .= '</tr>';
                $isPartAdded = 'Y';
            }
            if($v['item_type'] == 'S' && $isServiceAdded =='N') {
                $html .= '<tr>';
                $html .= '<td colspan="2" class="no-b non-bold-item" style="background-color:lightgray;vertical-align:top;border-collapse:collapse;padding:3px;text-align:left;">LABOR</td>';
                if($is_tax == 'Y') {
                    $html .= '<td class="no-b non-bold-item" style="text-align:right;padding:4px;width:10%;"></td>';
                }
                $html .= '<td class="no-b non-bold-item" style="text-align:right;padding:4px;width:5%;"></td>';
                $html .= '<td class="no-b non-bold-item" style="text-align:right;padding:4px;width:10%;"></td>';
                
                if($is_tax == 'Y') {
                    $html .= '<td class="no-b non-bold-item" style="text-align:right;padding:4px;width:8%;"></td>';
                    $html .= '<td class="no-b non-bold-item" style="text-align:right;padding:4px;width:4%;"></td>';
                }
                $html .= '<td class="no-b non-bold-item" style="text-align:right;padding:4px;width:10%;"></td>';
                $html .= '</tr>';
                $isServiceAdded = 'Y';
            }
            
            $html .= '<tr>';
            $html .= '<td class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:3px;text-align:left;">'.$srno.'</td>';
            $html .= '<td class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:3px;text-align:left;">'.strtoupper($v['item_name']).'<br><small>'.$description.'</small></td>';
            if($is_tax == 'Y') {
                $hsn_sac_code = $v['hsn_sac_code'] != "" && $v['hsn_sac_code'] != 0 ? $v['hsn_sac_code'] : '';
                $html .= '<td class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:5px;text-align:right;">'.$hsn_sac_code.'</td>';
            }
            $qty = $v['qty'] != "" && $v['qty'] != 0 ? $v['qty'] : '';
            if($qty != '') {    
                $untprice = round($v['customer_payable'] / $qty,2);
            } else {
                $untprice = $v['customer_payable'];
            }

            $html .= '<td  class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:5px;text-align:right;">'.$qty.'</td>';
            $html .= '<td  class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:5px;text-align:right;">'.$this->moneyFormatIndia($untprice).'</td>';
            
            $per_unit_price = $v['customer_payable'];
            if($is_tax == 'Y') {
                $tax = $v['tax_rate'] != "" && $v['tax_rate'] != 0 ? $this->moneyFormatIndia(round(($v['tax_rate'] * $per_unit_price) / 100 , 2)) : '';
                $tAmount = $v['tax_rate'] != "" && $v['tax_rate'] != 0 ? round(($v['tax_rate'] * $per_unit_price) / 100 , 2) : '';
                $tax_rate = $v['tax_rate'] != "" && $v['tax_rate'] != 0 ? '('.$this->moneyFormatIndia($v['tax_rate']).'%)' : '';
                $html .= '<td class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:5px;text-align:right;">'.$tax.'</td>';
                $html .= '<td class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:5px;text-align:right;">'.$tax_rate.'</td>';
                if($v['tax_rate'] != 0 && $v['tax_rate'] != '') {
                    $taxSummary[$hsn_sac_code][$v['tax_rate']] += $tAmount;
                    $taxAbleAmt[$hsn_sac_code][$v['tax_rate']] += $per_unit_price;
                }
                $totalTax += $tAmount;
            }   
            $lineTotal += $per_unit_price + $tAmount;
            if($v['item_type'] == 'P') {
                $partsTotal += $per_unit_price + $tAmount;
            } else {
                $laborTotal += $per_unit_price + $tAmount;
            }
            $html .= '<td class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:5px;text-align:right;">'.$this->moneyFormatIndia(number_format((float)$per_unit_price + $tAmount, 2, '.', '')).'</td>';
            $html .= '</tr>';
            $srno++;
        }
        for ($i=count($jobcard_item); $i <=(150 - (count($jobcard_item) * 7)); $i++) {
            $html .= '<tr class="no-b">';
            $html .= '<td class="no-b"></td>';
            $html .= '<td class="no-b"></td>';
            
            $html .= '<td class="no-b"></td>';
            $html .= '<td class="no-b"></td>';
           
            if($is_tax == 'Y') {
                $html .= '<td class="no-b"></td>';
                $html .= '<td class="no-b"></td>';
                $html .= '<td class="no-b"></td>';
            }
            $html .= '<td class="no-b"></td>';
            $html .= '</tr>';
    	}

        $html .= "<tr>";
        $html .= "<td colspan=".$grandTotalrowspan." valign='top' style='border:1px solid black;border-collapse:collapse;padding-right:5px;text-align:right;'>Sub Total</td>";

        if($is_tax == 'Y') {
            $html .= "<td style='text-align:right;border:1px solid black;border-collapse:collapse;padding-right:5px;text-align:right;'>".$this->moneyFormatIndia(number_format((float)$totalTax, 2, '.', ''))."</td>";
            $html .= "<td style='text-align:right;border:1px solid black;border-collapse:collapse;padding-right:5px;text-align:right;'></td>";
        }
        $html .= "<td style='text-align:right;border:1px solid black;border-collapse:collapse;padding-right:5px;text-align:right;'>".$this->moneyFormatIndia(number_format((float)$lineTotal, 2, '.', ''))."</td>";
        $html .= "</tr>";
        
        $total_paid = $total_paid['total_paid'] != null ? $total_paid['total_paid'] : 0;
        
        $html .= "<tr>";
        $html .= "<td colspan=".$paidTotalrowspan." valign='top' style='border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;border-collapse:collapse;padding-right:5px;text-align:right;'>Parts Total</td>";
        $html .= "<td style='text-align:right;border-left:1px solid black;border-right:1px solid black;border-top:1px solid black;border-color: black;border-width: thin;border-collapse:collapse;padding-right:5px;text-align:right;'>".$this->moneyFormatIndia(number_format((float)$partsTotal, 2, '.', ''))."</td>";
        $html .= "</tr>";
        $html .= "<tr>";

        $html .= "<tr>";
        $html .= "<td colspan=".$paidTotalrowspan." valign='top' style='border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;border-collapse:collapse;padding-right:5px;text-align:right;'>Labour Total</td>";
        $html .= "<td style='text-align:right;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;border-collapse:collapse;padding-right:5px;text-align:right;'>".$this->moneyFormatIndia(number_format((float)$laborTotal, 2, '.', ''))."</td>";
        $html .= "</tr>";
        $html .= "<tr>";

        $html .= "<tr>";
        $html .= "<td colspan=".$paidTotalrowspan." valign='top' style='border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;border-collapse:collapse;padding-right:5px;text-align:right;font-weight:bold;'>Total Payable</td>";
        $html .= "<td style='text-align:right;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;border-collapse:collapse;padding-right:5px;text-align:right;font-weight:bold;'>".$this->moneyFormatIndia(number_format((float)round($lineTotal,0), 2, '.', ''))."</td>";
        $html .= "</tr>";        
        
        $total_pd = $total_paid['total_paid'] != null ? $total_paid['total_paid'] : 0;
        $html .= "<tr>";
        $html .= "<td colspan=".$paidTotalrowspan." valign='top' style='border-left:1px solid black;border-right:1px solid black;border-collapse:collapse;padding-right:5px;text-align:right;'>Paid</td>";
        $html .= "<td style='text-align:right;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;border-collapse:collapse;padding-right:5px;text-align:right;'>".$this->moneyFormatIndia(number_format((float)round($total_paid,0), 2, '.', ''))."</td>";
        $html .= "</tr>";

        $bal = $lineTotal - $total_pd;
        $html .= "<tr>";
        $html .= "<td colspan=".$paidTotalrowspan." valign='top' style='border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black;border-collapse:collapse;padding-right:5px;text-align:right;'>Balance</td>";
        $html .= "<td style='text-align:right;border-left:1px solid black;border-right:1px solid black;border-color: black;border-width: thin;border-collapse:collapse;padding-right:5px;text-align:right;'>".$this->moneyFormatIndia(number_format((float)round($bal,0), 2, '.', ''))."</td>";
        $html .= "</tr>";

        $html .= "<tr>";
        $html .= "<td colspan=".($paidTotalrowspan + 1)." valign='top' style='border:1px solid black;border-color: black;border-width: thin;border-collapse:collapse;'>";
        $html .= "Amount in words: ";
        $html .= $invoiceAmtInWords.' Only';
        $html .= "</b></td>";
        $html .= "</tr>";
        $html .= '</table>';


        if($is_tax == 'Y' && !empty($taxSummary) && $jobcard['tax_type'] == 'scgst') {
            $html .= "<table style='margin-top:2px;border:1px solid black;border-collapse:collapse;width:100%;font-family:TimesNewRoman;font-size:90%;'>";
            $html .= "<tr>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;' rowspan='2'>HSN/SAC</td>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;' rowspan='2'>Taxable Amt</td>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;' colspan='2'>CGST</td>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;' colspan='2'>SGST</td>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;' rowspan='2'>Total Tax</td>";
            $html .= '</tr>';
            $html .= "<tr>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;'>Rate</td>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;'>Amount</td>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;'>Rate</td>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;'>Amount</td>";
            $html .= '</tr>';

            foreach($taxSummary as $k => $v) {
            foreach($v as $k1 => $v1) {
                $html .= '<tr>';
                $html .= '<td style="text-align:center;border:1px solid black;border-collapse:collapse;">'.$k.'</td>';
                $html .= '<td style="text-align:center;border:1px solid black;border-collapse:collapse;">'.$this->moneyFormatIndia(number_format((float)$taxAbleAmt[$k][$k1], 2, '.', '')).'</td>';
                $html .= '<td style="text-align:center;border:1px solid black;border-collapse:collapse;">'.sprintf('%0.2f', $k1/2).'</td>';
                $html .= '<td style="text-align:center;border:1px solid black;border-collapse:collapse;">'.$this->moneyFormatIndia(number_format((float)$v1/2, 2, '.', '')).'</td>';
                $html .= '<td style="text-align:center;border:1px solid black;border-collapse:collapse;">'.sprintf('%0.2f', $k1/2).'</td>';
                $html .= '<td style="text-align:center;border:1px solid black;border-collapse:collapse;">'.$this->moneyFormatIndia(number_format((float)$v1/2, 2, '.', '')).'</td>';
                $html .= '<td style="text-align:center;border:1px solid black;border-collapse:collapse;">'.$this->moneyFormatIndia(number_format((float)$v1, 2, '.', '')).'</td>';
                $html .= '</tr>';
            }
            }
            $html .= "</table>";
        } else if($is_tax == 'Y' && !empty($taxSummary) && $jobcard['tax_type'] == 'igst') {
            $html .= "<table style='margin-top:2px;border:1px solid black;border-collapse:collapse;width:100%;font-family:TimesNewRoman;font-size:90%;'>";
            $html .= "<tr>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;' rowspan='2'>HSN/SAC</td>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;' rowspan='2'>Taxable Amt</td>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;' colspan='2'>IGST</td>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;' rowspan='2'>Total Tax</td>";
            $html .= '</tr>';
            $html .= "<tr>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;'>Rate</td>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;background-color:lightgray;'>Amount</td>";
            $html .= '</tr>';

            foreach($taxSummary as $k => $v) {
            foreach($v as $k1 => $v1) {
                $html .= '<tr>';
                $html .= '<td style="text-align:center;border:1px solid black;border-collapse:collapse;">'.$k.'</td>';
                $html .= '<td style="text-align:center;border:1px solid black;border-collapse:collapse;">'.$this->moneyFormatIndia(number_format((float)$taxAbleAmt[$k][$k1], 2, '.', '')).'</td>';
                $html .= '<td style="text-align:center;border:1px solid black;border-collapse:collapse;">'.sprintf('%0.2f', $k1).'</td>';
                $html .= '<td style="text-align:center;border:1px solid black;border-collapse:collapse;">'.$this->moneyFormatIndia(number_format((float)$v1, 2, '.', '')).'</td>';
                $html .= '<td style="text-align:center;border:1px solid black;border-collapse:collapse;">'.$this->moneyFormatIndia(number_format((float)$v1, 2, '.', '')).'</td>';
                $html .= '</tr>';
            }
            }
            $html .= "</table>";
        }

        $html  .= "<table style='margin-top:1px;border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black;border-collapse:collapse;width:100%;font-family:TimesNewRoman;font-size:90%;'>";
        if($jobcard['invoice_notes'] != "") {
            $html .= '<tr>';
            $html .= "<td colspan='2' style='border-left:1px solid black;border-right:1px solid black;border-bottom:1px solid black;'>";
            $html .= '<span><span style="text-decoration: underline;"><b>Remarks:</span> <br>'.$jobcard['invoice_notes'].'</span>';
            $html .= "</td>";
            $html .= '</tr>';
        }
        $html .= '<tr>';
        $html .= "<td style='vertical-align:bottom;'>";
        if($garageDetail['invoice_notes'] !="") {
            $html .= "<span align='left' style='font-family:TimesNewRoman;text-align:left;float:left;width:55%;'><span style='text-decoration: underline;'><b>TERMS AND CONDITIONS: </b></span>".$garageDetail['invoice_notes']."</span>";
        } else {
            $html .= "<br><br><br>";
        }
        $html .= "</td>";
        $html .= "<td style='min-height:150%;vertical-align:bottom;text-align:right;'>";
        $html .= "<span style='font-family:TimesNewRoman;'>For <b>".strtoupper($garageDetail['garage_name'])."</b></span>";
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '<div style="text-align:center;width:100%;font-size:10px;"><i>THIS IS A COMPUTER GENERATED INVOICE AND REQUIRES NO SIGNATURE.</i></div>';
        $html .= '</div>';
        $html .= '<style> .fsize14 { font-size: 14px;font-family:TimesNewRoman;} .bold-item { font-size: 14px;font-family:TimesNewRoman;} .non-bold-item { font-size: 12px;font-family:TimesNewRoman;}  h2 { font-size: 16px;font-weight:bolder;font-family:Helvetica;} span { font-size : 12px;font-family:Helvetica;}  .no-b{ border:1px solid black;border-top:0px !important;border-bottom: 0px !important; } @page { margin: 35px 25px 25px 25px; }</style>';
        
        $flName = 'Invoice-'.$jobcard['invoice_no'].'-'.strtoupper($jobcard['name']).'-'.date('d-m-Y',strtotime($jobcard['date'])).'.pdf';
        
        //$mpdf->cacheTables = true;
        $mpdf->SetTitle($flName);
        $mpdf->WriteHTML($html);
        
        $mpdf->Output($file_name,'F');

        header('Content-type:application/pdf');
        header('Content-disposition: inline; filename="'.$flName.'"');
        @ readfile($file_name);
    }
    
    public function generateEstimate($jobcard_id) {
    	// $file_name = DOCUMENT_ROOT.'uploads/jobcards/jobcard.pdf';
        $logo_path = LOGO_DOCUMENT_ROOT.'thumbs/garage_'.$_SESSION['setting']->garage_id;
        require_once DOCUMENT_ROOT."uploads/mpdf/mpdf.php";
        
        $mpdf = new mPDF('c','A4');
        
        // jobcard and jobcard items.
	    $this->db->select('tbl_invoices.invoice_no,tbl_jobcard.*,tbl_jobcard.date as jobcard_date,tbl_customer.*,tbl_customer_vehicle.reg_no,tbl_make.make_id,tbl_make.name as make_name,tbl_model.model_id,tbl_model.name as model_name');
        $this->db->from('tbl_jobcard');
        $this->db->join('tbl_invoices','tbl_invoices.item_id=tbl_jobcard.jobcard_id AND item_type="job_invoice"','left');
        $this->db->join('tbl_customer','tbl_customer.customer_id=tbl_jobcard.customer_id','left');
        $this->db->join('tbl_customer_vehicle','tbl_customer_vehicle.vehicle_id=tbl_jobcard.vehicle_id','left');
        $this->db->join('tbl_make','tbl_make.make_id=tbl_customer_vehicle.make_id','left');
        $this->db->join('tbl_model','tbl_model.model_id=tbl_customer_vehicle.model_id','left');
        $this->db->where('jobcard_id',$jobcard_id);
        $jobcard = $this->db->get()->row_array();
        
        // garage detail.
        $this->db->select('*,tbl_garage.name as garage_name');
        $this->db->from('tbl_garage');
        $this->db->where('tbl_garage.garage_id',$jobcard['garage_id']);
        $garageDetail = $this->db->get()->row_array();
        
        $file_name = DOCUMENT_ROOT.'uploads/estimate/Estimate-'.$jobcard['jobcard_no'].'-'.date('d-m-Y');
        $is_disc = $jobcard['is_disc_applicable'];
        $is_tax  = $jobcard['is_tax_applicable'];
        $grandTotalrowspan = 3;
        $paidTotalrowspan = 3;
        
        if($is_disc == 'Y') {
            $paidTotalrowspan++;
        }
        
        if($is_tax == 'Y') {
            $grandTotalrowspan = 4;
            $paidTotalrowspan++;
            $paidTotalrowspan++;
            $paidTotalrowspan++;
        }
		
        $this->db->select('tbl_jobcard_item.*,tbl_items.item_name,tbl_items.hsn_sac_code');
        $this->db->from('tbl_jobcard_item');
        $this->db->join('tbl_items','tbl_items.item_id = tbl_jobcard_item.item_id','left');
        $this->db->where('jobcard_id',$jobcard_id);
        $jobcard_item = $this->db->get()->result_array();
        
        $this->load->model('SettingModel');
        $invoiceAmtInWords = $this->SettingModel->getIndianCurrencyInWords($jobcard['grand_total']);
        $html .= "<table style='width:100%;'>";
        $html .= "<tr><td style='text-align:center;'>";
        $html .= "<h2>ESTIMATION / QUOTATION</h2>";
        $html .= "</td></tr>";
        $html .= "</table>";
        $html .= "<table class='padding_table' style='border-collapse:collapse;width:100%;font-family:Helvetica;'>";
        $html .= '<tr>';
        $logotdcolSpan = 2;
        $logotdStyle = "width:35%;border-collpase:collpased;padding:4px;border-left:1px solid black;border-right:1px solid black;border-top:1px solid black;border-color: black;border-width: thin;";
        if(file_exists($logo_path.'/'.$garageDetail['logo_path'])) {
            $html .= '<td rowspan="2" valign="top" style="width:15%;border-collpase:collpased;padding : 4px;border-bottom:1px solid black;border-left:1px solid black;border-top:1px solid black;border-color: black;border-width: thin;">';
            $html .= '<img src="'.$logo_path.'/'.$garageDetail['logo_path'].'">';
            $html .= '</td>';
            $logotdcolSpan = 0;
            $logotdStyle = "width:35%;border-collpase:collpased;padding : 4px;border-bottom:1px solid black;border-right:1px solid black;border-top:1px solid black;border-color: black;border-width: thin;";
        }

        $html .= '<td rowspan="2" colspan="'.$logotdcolSpan.'" valign="top" style="'.$logotdStyle.'">';
        $html .= '<span class="bold-item"><b>'.strtoupper($garageDetail['garage_name']).'</b></span><br>';
        $html .= '<span class="non-bold-item">'.strtoupper($garageDetail['address']).'</span><br>';

        if($garageDetail['gstin_no'] !="" && $is_tax == 'Y') {
            $html .= '<span class="non-bold-item">GSTIN : '.$garageDetail['gstin_no'].'.</span><br>';
        }
        $html .= '<span class="non-bold-item">';
        if($garageDetail['contact_no'] != "") {
            $html .= 'PH: '.$garageDetail['contact_no'];
        }

        if($garageDetail['alternate_contact'] != "" && $garageDetail['contact_no'] != "") {
            $html .= ', '.$garageDetail['alternate_contact'];
        } else if($garageDetail['alternate_contact'] != "" && $garageDetail['contact_no'] == "") {
            $html .= $garageDetail['alternate_contact'];
        }
        $html .= '</span>';
        $html .= '</td>';
        $html .= '<td style="height:20px;width:25%;border-collpase:collpased;padding : 4px;border:1px solid black;border-color: black;border-width: thin;border-style: solid" valign="top"><span class="fsize14">Invoice No  <br><b>'.$jobcard['invoice_no'].'</b> </span></td>';
        $html .= '<td style="height:20px;width:25%;padding : 4px;border:1px solid black;border-color: black;border-width: thin;border-style: solid" valign="top"><span class="fsize14">Date <br><b> '.date('d-m-Y', strtotime($jobcard['jobcard_date'])).'</b></span></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td valign="top" style="height:20px;padding : 4px;border:1px solid black;border-color: black;border-width: thin;border-style: solid">';
        $html .= '<span class="fsize14">Jobcard No <br><b>'.$jobcard['jobcard_no'].'</b></span>';
        $html .= '</td>';
        $html .= '<td valign="top" style="height:20px;padding : 4px;border:1px solid black;border-color: black;border-width: thin;border-style: solid">';
        $html .= '<span class="fsize14">Mode/Terms of Payment <br> <b>'.$paid_channel.'</b> </span>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td colspan="2" valign="top" style="padding : 4px;border:1px solid black;border-color: black;border-width: thin;border-style: solid">';
        $html .= '<span class="fsize14">Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </span>';
        $html .= '<span>'.strtoupper($jobcard['name']).'</span><br>';
        $html .= '<span class="fsize14">Contact No &nbsp;&nbsp;&nbsp;: ';
        if($jobcard['mobile_no'] != "") {
            $html .= $jobcard['mobile_no'];
        } 
        $html .= '</span><br>';
        $html .= '<span class="fsize14">Email &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ';
        $html .= strtoupper($jobcard['email']).'</span><br>';
        $html .= '<span class="fsize14">Address &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ';
        $html .= strtoupper($jobcard['billing_address']).'</span>';
        
        $html .= '</td>';
        $html .= '<td colspan="2" valign="top" style="height:35px;padding : 4px;border:1px solid black;border-color: black;border-width: thin;border-style: solid">';
        $html .= '<span class="fsize14">';
        $html .= 'Reg. No &nbsp;&nbsp;&nbsp;: ';
        $html .= strtoupper($jobcard['reg_no']).'</span><br>';
        $html .= '<span class="fsize14">Model &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ';
        $html .= strtoupper($jobcard['make_name'].' '.$jobcard['model_name']).'</span><br>';
        $html .= '<span class="fsize14">Year &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ';
        $html .= $jobcard['year'].'</span><br>';
        $html .= '<span class="fsize14">Odometer &nbsp;: ';
        $html .= $jobcard['odometer'].'</span>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        
        $html .= '<div style="float:right;width:100%;text-align:right;font-family:Helvetica;">';
        $html .= '<table border="1" style="width:100%;border-collapse:collapse;font-family:Helvetica;font-size:90%;">';
        $html .= '<tr>';
        $html .= '<th style="text-align:left;padding:4px;">Description</th>';
        if($is_tax == 'Y') {
        $html .= '<th style="text-align:right;padding:4px;width:10%;">HSN/SAC</th>';
        }
        $html .= '<th style="text-align:right;padding:4px;width:5%;">Qty</th>';
        $html .= '<th style="text-align:right;padding:4px;width:10%;">Rate</th>';
        
        if($is_disc == 'Y') {
            $html .= '<th style="text-align:right;padding:4px;width:8%;">Discount</th>';
        }
        if($is_tax == 'Y') {
            $html .= '<th style="text-align:right;padding:4px;width:8%;">Tax</th>';
            $html .= '<th style="text-align:right;padding:4px;width:4%;">%</th>';
        }
        $html .= '<th style="text-align:right;padding:4px;width:10%;">Amount</th>';
        $html .= '</tr>';

        foreach ($jobcard_item as $i => $v) {
            $description = $v['description'];
            $html .= '<tr>';
            $html .= '<td class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:3px;text-align:left;">'.strtoupper($v['item_name']).'<br><small>'.$description.'</small></td>';
            if($is_tax == 'Y') {
            $hsn_sac_code = $v['hsn_sac_code'] != "" && $v['hsn_sac_code'] != 0 ? $v['hsn_sac_code'] : '';
            $html .= '<td class="no-b non-bold-item" style="border-collapse:collapse;padding:5px;text-align:right;">'.$hsn_sac_code.'</td>';
            }
            $qty = $v['qty'] != "" && $v['qty'] != 0 ? $v['qty'] : '';
            $html .= '<td  class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:5px;text-align:right;">'.$qty.'</td>';
            $html .= '<td  class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:5px;text-align:right;">'.$this->moneyFormatIndia($v['unit_price']).'</td>';

            if($is_disc == 'Y') {
                $disc = $v['discount_value'] != "" && $v['discount_value'] != 0 ? $this->moneyFormatIndia($v['discount_value']) : '';
                $html .= '<td class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:5px;text-align:right;">'.$disc.'</td>';
            }
            if($is_tax == 'Y') {
                $tax = $v['tax_amount'] != "" && $v['tax_amount'] != 0 ? $this->moneyFormatIndia($v['tax_amount']) : '';
                $tax_rate = $v['tax_rate'] != "" && $v['tax_rate'] != 0 ? '('.$this->moneyFormatIndia($v['tax_rate']).'%)' : '';
                $html .= '<td class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:5px;text-align:right;">'.$tax.'</td>';
                $html .= '<td class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:5px;text-align:right;">'.$tax_rate.'</td>';
                if($v['tax_rate'] != 0 && $v['tax_rate'] != '') {
                    $taxSummary[$hsn_sac_code][$v['tax_rate']] += $tax;
                    $taxAbleAmt[$v['tax_rate']] += $v['taxable_amount'];
                }
            }   
            $html .= '<td class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:5px;text-align:right;">'.$this->moneyFormatIndia($v['total']).'</td>';
            $html .= '</tr>';
        }
        
        for ($i=count($jobcard_item); $i <=(150 - (count($jobcard_item) * 7)); $i++) {
            $html .= '<tr class="no-b">';
            $html .= '<td class="no-b"></td>';
            if($is_tax == 'Y') {
            $html .= '<td class="no-b"></td>';
            }
            $html .= '<td class="no-b"></td>';
            $html .= '<td class="no-b"></td>';
            if($is_disc == 'Y') {
                $html .= '<td class="no-b"></td>';
            }
            if($is_tax == 'Y') {
                $html .= '<td class="no-b"></td>';
                $html .= '<td class="no-b"></td>';
            }
            $html .= '<td class="no-b"></td>';
            $html .= '</tr>';
    	}

        $html .= "<tr>";
        $html .= "<td colspan=".$grandTotalrowspan." valign='top' style='border:1px solid black;border-collapse:collapse;font-weight:bold;'>Grand Total</td>";
        if($is_disc == 'Y') {
            $html .= "<td style='text-align:right;border:1px solid black;border-collapse:collapse;font-weight:bold;'>".$this->moneyFormatIndia($jobcard['total_discount'])."</td>";
        }
        if($is_tax == 'Y') {
            $html .= "<td style='text-align:right;border:1px solid black;border-collapse:collapse;font-weight:bold;'>".$this->moneyFormatIndia($jobcard['total_tax'])."</td>";
            $html .= "<td style='text-align:right;border:1px solid black;border-collapse:collapse;font-weight:bold;'></td>";
        }
        $html .= "<td style='text-align:right;border:1px solid black;border-collapse:collapse;font-weight:bold;'>".$this->moneyFormatIndia($jobcard['grand_total'])."</td>";
        $html .= "</tr>";
        $total_paid = $total_paid['total_paid'] != null ? $total_paid['total_paid'] : 0;
        $html .= "<tr>";
        $html .= "<td colspan=".$paidTotalrowspan." valign='top' style='border:1px solid black;border-collapse:collapse;font-weight:bold;'>Paid</td>";
        $html .= "<td style='text-align:right;border:1px solid black;border-collapse:collapse;font-weight:bold;'>".$this->moneyFormatIndia($total_paid)."</td>";
        $html .= "</tr>";
        $balance = $jobcard['grand_total'] - $total_paid;
        $html .= "<tr>";
        $html .= "<td colspan=".$paidTotalrowspan." valign='top' style='border:1px solid black;border-collapse:collapse;font-weight:bold;'>Balance</td>";
        $html .= "<td style='text-align:right;border:1px solid black;border-collapse:collapse;font-weight:bold;'>".$this->moneyFormatIndia($balance)."</td>";
        $html .= "</tr>";
        $html .= "<tr>";
        $html .= "<td colspan=".($paidTotalrowspan + 1)." valign='top' style='border:1px solid black;border-collapse:collapse;'>";
        $html .= "Amount Chargeable (in words)";
        $html .= "<br><b>";
        $html .= $invoiceAmtInWords.' Only';
        $html .= "</b></td>";
        $html .= "</tr>";
        $html .= '</table>';

        if($is_tax == 'Y' && !empty($taxSummary)) {
            $html .= "<table style='margin-top:2px;border:1px solid black;border-collapse:collapse;width:100%;font-family:Arial;font-size:90%;'>";
            $html .= "<tr>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;' rowspan='2'>HSN/SAC</td>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;' rowspan='2'>Taxable Amt</td>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;' colspan='2'>CGST</td>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;' colspan='2'>SGST</td>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;' rowspan='2'>Total Tax</td>";
            $html .= '</tr>';
            $html .= "<tr>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;'>Rate</td>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;'>Amount</td>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;'>Rate</td>";
            $html .= "<td style='text-align:center;border:1px solid black;border-collapse:collapse;'>Amount</td>";
            $html .= '</tr>';

            foreach($taxSummary as $k => $v) {
            foreach($v as $k1 => $v1) {
                $html .= '<tr>';
                $html .= '<td style="text-align:center;border:1px solid black;border-collapse:collapse;">'.$k.'</td>';
                $html .= '<td style="text-align:center;border:1px solid black;border-collapse:collapse;">'.$taxAbleAmt[$k1].'</td>';
                $html .= '<td style="text-align:center;border:1px solid black;border-collapse:collapse;">'.sprintf('%0.2f', $k1/2).'</td>';
                $html .= '<td style="text-align:center;border:1px solid black;border-collapse:collapse;">'.($v1/2).'</td>';
                $html .= '<td style="text-align:center;border:1px solid black;border-collapse:collapse;">'.sprintf('%0.2f', $k1/2).'</td>';
                $html .= '<td style="text-align:center;border:1px solid black;border-collapse:collapse;">'.($v1/2).'</td>';
                $html .= '<td style="text-align:center;border:1px solid black;border-collapse:collapse;">'.$this->moneyFormatIndia($v1).'</td>';
                $html .= '</tr>';
            }
            }
            $html .= "</table>";
        }


        $html  .= "<table border='1' style='margin-top:1px;border:1px solid black;border-collapse:collapse;width:100%;font-family:Arial;font-size:90%;'>";
        if($garageDetail['invoice_notes'] != "") {
            $html .= '<tr>';
            $html .= '<td colspan="2" style="border: 1px solid black;">';
            $html .= "<span style='text-decoration: underline;'><b>Terms & Conditions :</b></span>";
            $html .= $garageDetail['invoice_notes'];
            $html .= '</td>';
            $html .= '</tr>';
        }
        $html .= '<tr>';
        $html .= "<td style='border: 1px solid black;text-align:left;'>";
        $html .= "<span> <br><br> Customer Signature</span>";
        $html .= '</td>';
        $html .= "<td style='text-align:right;border: 1px solid black;'>";
        $html .= "<span><b>for ".strtoupper($garageDetail['garage_name'])."</b> <br><br> Authorised Signatory</span>";
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '</div>';
        $html .= '<style> .fsize14 { font-size: 14px;} .bold-item { font-size: 14px;} .non-bold-item { font-size: 12px;}  h2 { font-size: 16px;font-weight:bolder;font-family:Helvetica;} span { font-size : 12px;font-family:Helvetica;}  .no-b{ border:1px solid black;border-top:0px !important;border-bottom: 0px !important; } @page { margin: 35px 25px 25px 25px; }</style>';
        
        $flName = 'Estimate-'.$jobcard['jobcard_no'].'-'.strtoupper($jobcard['name']).'-'.date('d-m-Y',strtotime($jobcard['date'])).'.pdf';
        
        //$mpdf->cacheTables = true;
        $mpdf->SetTitle($flName);
        $mpdf->WriteHTML($html);
        $file_name = $file_name.'.pdf';
        $mpdf->Output($file_name,'F');

        header('Content-type:application/pdf');
        header('Content-disposition: inline; filename="'.$flName.'"');
        @ readfile($file_name);
    }
    public function generateRepairOrder($jobcard_id) {
    	$logo_path = LOGO_DOCUMENT_ROOT.'thumbs/garage_'.$_SESSION['setting']->garage_id;
        require_once DOCUMENT_ROOT."uploads/mpdf/mpdf.php";
        
        $mpdf = new mPDF('c','A4');
        
        // jobcard and jobcard items.
	    $this->db->select('tbl_invoices.invoice_no,tbl_jobcard.*,tbl_jobcard.date as jobcard_date,tbl_customer.*,tbl_customer_vehicle.reg_no,tbl_make.make_id,tbl_make.name as make_name,tbl_model.model_id,tbl_model.name as model_name');
        $this->db->from('tbl_jobcard');
        $this->db->join('tbl_invoices','tbl_invoices.item_id=tbl_jobcard.jobcard_id AND item_type="job_invoice"','left');
        $this->db->join('tbl_customer','tbl_customer.customer_id=tbl_jobcard.customer_id','left');
        $this->db->join('tbl_customer_vehicle','tbl_customer_vehicle.vehicle_id=tbl_jobcard.vehicle_id','left');
        $this->db->join('tbl_make','tbl_make.make_id=tbl_customer_vehicle.make_id','left');
        $this->db->join('tbl_model','tbl_model.model_id=tbl_customer_vehicle.model_id','left');
        $this->db->where('jobcard_id',$jobcard_id);
        $jobcard = $this->db->get()->row_array();
        
        // garage detail.
        $this->db->select('*,tbl_garage.name as garage_name');
        $this->db->from('tbl_garage');
        $this->db->where('tbl_garage.garage_id',$jobcard['garage_id']);
        $garageDetail = $this->db->get()->row_array();

        $cust_concerns = '';
        if($jobcard['customer_concern'] != "") {
            $cust_concernsArray = explode(",",$jobcard['customer_concern']);
            $cust_concerns .= '<ul>';
            foreach($cust_concernsArray as $v) {
                $cust_concerns .= '<li>'.$v.'</li>';
            }
            $cust_concerns .= '</ul>';
        }
        
        $file_name = DOCUMENT_ROOT.'uploads/estimate/Estimate-'.$jobcard['jobcard_no'].'-'.date('d-m-Y');
        $is_disc = $jobcard['is_disc_applicable'];
        $is_tax  = $jobcard['is_tax_applicable'];
        $grandTotalrowspan = 3;
        $paidTotalrowspan = 3;
        
        if($is_disc == 'Y') {
            $paidTotalrowspan++;
        }
        
        if($is_tax == 'Y') {
            $grandTotalrowspan = 4;
            $paidTotalrowspan++;
            $paidTotalrowspan++;
            $paidTotalrowspan++;
        }
		
        $this->db->select('tbl_jobcard_item.*,tbl_items.item_name,tbl_items.hsn_sac_code');
        $this->db->from('tbl_jobcard_item');
        $this->db->join('tbl_items','tbl_items.item_id = tbl_jobcard_item.item_id','left');
        $this->db->where('jobcard_id',$jobcard_id);
        $jobcard_item = $this->db->get()->result_array();
        
        $this->load->model('SettingModel');
        $invoiceAmtInWords = $this->SettingModel->getIndianCurrencyInWords($jobcard['grand_total']);
        $html .= "<table style='width:100%;'>";
        $html .= "<tr><td style='text-align:center;'>";
        $html .= "<h2>JOBCARD / REPAIR ORDER</h2>";
        $html .= "</td></tr>";
        $html .= "</table>";
        $html .= "<table class='padding_table' style='border-collapse:collapse;width:100%;font-family:Helvetica;'>";
        $html .= '<tr>';
        $logotdcolSpan = 2;
        $logotdStyle = "width:35%;border-collpase:collpased;padding:4px;border-left:1px solid black;border-right:1px solid black;border-top:1px solid black;border-color: black;border-width: thin;";
        if(file_exists($logo_path.'/'.$garageDetail['logo_path'])) {
            $html .= '<td rowspan="2" valign="top" style="width:15%;border-collpase:collpased;padding : 4px;border-bottom:1px solid black;border-left:1px solid black;border-top:1px solid black;border-color: black;border-width: thin;">';
            $html .= '<img src="'.$logo_path.'/'.$garageDetail['logo_path'].'">';
            $html .= '</td>';
            $logotdcolSpan = 0;
            $logotdStyle = "width:35%;border-collpase:collpased;padding : 4px;border-bottom:1px solid black;border-right:1px solid black;border-top:1px solid black;border-color: black;border-width: thin;";
        }

        $html .= '<td rowspan="2" colspan="'.$logotdcolSpan.'" valign="top" style="'.$logotdStyle.'">';
        $html .= '<span class="bold-item"><b>'.strtoupper($garageDetail['garage_name']).'</b></span><br>';
        $html .= '<span class="non-bold-item">'.strtoupper($garageDetail['address']).'</span><br>';

        if($garageDetail['gstin_no'] !="" && $is_tax == 'Y') {
            $html .= '<span class="non-bold-item">GSTIN : '.$garageDetail['gstin_no'].'.</span><br>';
        }
        $html .= '<span class="non-bold-item">';
        if($garageDetail['contact_no'] != "") {
            $html .= 'PH: '.$garageDetail['contact_no'];
        }

        if($garageDetail['alternate_contact'] != "" && $garageDetail['contact_no'] != "") {
            $html .= ', '.$garageDetail['alternate_contact'];
        } else if($garageDetail['alternate_contact'] != "" && $garageDetail['contact_no'] == "") {
            $html .= $garageDetail['alternate_contact'];
        }
        $html .= '</span>';
        $html .= '</td>';
        $html .= '<td style="height:20px;width:25%;border-collpase:collpased;padding : 4px;border:1px solid black;border-color: black;border-width: thin;border-style: solid" valign="top"><span class="fsize14">Invoice No  <br><b>'.$jobcard['invoice_no'].'</b> </span></td>';
        $html .= '<td style="height:20px;width:25%;padding : 4px;border:1px solid black;border-color: black;border-width: thin;border-style: solid" valign="top"><span class="fsize14">Date <br><b> '.date('d-m-Y', strtotime(date('Y-m-d'))).'</b></span></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td valign="top" style="height:20px;padding : 4px;border:1px solid black;border-color: black;border-width: thin;border-style: solid">';
        $html .= '<span class="fsize14">Jobcard No <br><b>'.$jobcard['jobcard_no'].'</b></span>';
        $html .= '</td>';
        $html .= '<td valign="top" style="height:20px;padding : 4px;border:1px solid black;border-color: black;border-width: thin;border-style: solid">';
        $html .= '<span class="fsize14">Mode/Terms of Payment <br> <b>'.$paid_channel.'</b> </span>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td colspan="2" valign="top" style="padding : 4px;border:1px solid black;border-color: black;border-width: thin;border-style: solid">';
        $html .= '<span class="fsize14">Name &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </span>';
        $html .= '<span>'.strtoupper($jobcard['name']).'</span><br>';
        $html .= '<span class="fsize14">Contact No &nbsp;&nbsp;&nbsp;: ';
        if($jobcard['mobile_no'] != "") {
            $html .= $jobcard['mobile_no'];
        } 
        $html .= '</span><br>';
        $html .= '<span class="fsize14">Email &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ';
        $html .= strtoupper($jobcard['email']).'</span><br>';
        $html .= '<span class="fsize14">Address &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ';
        $html .= strtoupper($jobcard['billing_address']).'</span>';
        
        $html .= '</td>';
        $html .= '<td colspan="2" valign="top" style="height:35px;padding : 4px;border:1px solid black;border-color: black;border-width: thin;border-style: solid">';
        $html .= '<span class="fsize14">';
        $html .= 'Reg. No &nbsp;&nbsp;&nbsp;: ';
        $html .= strtoupper($jobcard['reg_no']).'</span><br>';
        $html .= '<span class="fsize14">Model &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ';
        $html .= strtoupper($jobcard['make_name'].' '.$jobcard['model_name']).'</span><br>';
        $html .= '<span class="fsize14">Year &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ';
        $html .= $jobcard['year'].'</span><br>';
        $html .= '<span class="fsize14">Odometer &nbsp;: ';
        $html .= $jobcard['odometer'].'</span>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        if($cust_concerns != "") {
            $html .= '<div style="float:right;width:100%;text-align:right;font-family:Helvetica;">';
            $html .= '<table border="1" style="width:100%;border-collapse:collapse;font-family:Helvetica;font-size:90%;">';
            $html .= '<tr>';
            $html .= '<td>';
            $html .= 'Customer Complaints/Concern';
            $html .= '</td>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<td>';
            $html .= $cust_concerns;
            $html .= '</td>';
            $html .= '</tr>';
            $html .= '</table>';
            $html .= '</div>';
        }
        $html .= '<div style="float:right;width:100%;text-align:right;font-family:Helvetica;">';
        $html .= '<table border="1" style="width:100%;border-collapse:collapse;font-family:Helvetica;font-size:90%;">';
        $html .= '<tr>';
        $html .= '<th style="text-align:left;padding:4px;">Description</th>';
        $html .= '<th style="text-align:right;padding:4px;width:5%;">Qty</th>';
        $html .= '</tr>';

        foreach ($jobcard_item as $i => $v) {
            $description = $v['description'];
            $html .= '<tr>';
            $html .= '<td class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:3px;text-align:left;">'.strtoupper($v['item_name']).'<br><small>'.$description.'</small></td>';
            $qty = $v['qty'] != "" && $v['qty'] != 0 ? $v['qty'] : '';
            $html .= '<td  class="no-b non-bold-item" style="vertical-align:top;border-collapse:collapse;padding:5px;text-align:right;">'.$qty.'</td>';
            $html .= '</tr>';
        }
        
        for ($i=count($jobcard_item); $i <=(150 - (count($jobcard_item) * 7)); $i++) {
            $html .= '<tr class="no-b">';
            $html .= '<td class="no-b"></td>';
            $html .= '<td class="no-b"></td>';
            $html .= '</tr>';
    	}

        if($jobcard['jobcard_notes'] != "") {
            $html .= '<tr>';
            $html .= '<td colspan="2">Remarks</td>';
            $html .= '</tr>';

            $html .= '<tr>';
            $html .= '<td colspan="2"><ul><li>'.$jobcard['jobcard_notes'].'</li></ul></td>';
            $html .= '</tr>';
        } else {
            $html .= '<tr>';
            $html .= '<td style="border-top: 0px i !important;" colspan="2"></td>';
            $html .= '</tr>';
        }
        
        $html .= '</table>';
        $html .= '</div>';
        $html .= '<style> .fsize14 { font-size: 14px;} .bold-item { font-size: 14px;} .non-bold-item { font-size: 12px;}  h2 { font-size: 16px;font-weight:bolder;font-family:Helvetica;} span { font-size : 12px;font-family:Helvetica;}  .no-b{ border:1px solid black;border-top:0px !important;border-bottom: 0px !important; } @page { margin: 35px 25px 25px 25px; }</style>';
        
        $flName = 'Jobcard-'.$jobcard['jobcard_no'].'-'.strtoupper($jobcard['name']).'-'.date('d-m-Y',strtotime($jobcard['jobcard_date'])).'.pdf';
        //$mpdf->cacheTables = true;
        $mpdf->SetTitle($file_name);
        $mpdf->WriteHTML($html);
        $file_name = $file_name.'.pdf';
        $mpdf->Output($file_name,'F');
        
        header('Content-type:application/pdf');
        header('Content-disposition: inline; filename="'.$flName.'"');
        @ readfile($file_name);
	}
	public function getItems() {
            $result = array();

			$this->db->select('*');
            $this->db->from('tbl_items');
            $this->db->like('item_name',$_REQUEST['term']);
            $this->db->where('tbl_items.garage_id',$_SESSION['setting']->garage_id);
            $this->db->limit(50);
            $result['items'] = $this->db->get()->result_array();
            
	    	return $result;
	}
        public function gettemplate() {
            $this->db->select('*');
            $this->db->from('tbl_template');
            $this->db->where('garage_id',$_SESSION['setting']->garage_id);
            $this->db->where('event_action',$_REQUEST['event_action']);
            return $this->db->get()->row_array();
        }
        public function getdetailforpo() {
            $result = array();
            
            $this->db->select('*');
            $this->db->from('tbl_customer_vehicle');
            $this->db->where('vehicle_id',$_REQUEST['vehicle_id']);
            $vehicle = $this->db->get()->row_array();
                
            $this->db->select('tbl_make.make_id,tbl_make.name as make_name,tbl_model.model_id,tbl_model.name as model_name');
            $this->db->from('tbl_model');
            $this->db->join('tbl_make','tbl_model.make_id = tbl_make.make_id','left');
            $this->db->where('tbl_model.model_id',$vehicle['model_id']);

            $result['make_model'] = $this->db->get()->row_array();
            $result['variant'] = array();

            $this->db->select('*');
            $this->db->from('tbl_variant');
            if($vehicle['variant_id'] != 0) {
                $this->db->where('variant_id',$vehicle['variant_id']);
            } else {
                $this->db->where('model_id',$vehicle['model_id']);
            }
            $result['variant'] = $this->db->get()->result_array();
            
            return $result;
        }
        public function viewpoPdf($po_id) {
            $file_name = DOCUMENT_ROOT.'uploads/jobcards/jobcard.pdf';
            $logo_path = LOGO_DOCUMENT_ROOT.'thumbs/garage_'.$_SESSION['setting']->garage_id;
            require_once DOCUMENT_ROOT."uploads/mpdf/mpdf.php";
            
            $mpdf = new mPDF('c','A4');

            if($po_id && $po_id != "") {
            // po and po items.
            $this->db->select('tbl_vendor_bills.*,tbl_cities.name as vendor_city,tbl_vendor_bills.notes as order_note,tbl_vendor.*,tbl_make.name as make_name,tbl_model.name as model_name,tbl_variant.name as variant_name');
            $this->db->from('tbl_vendor_bills');
            $this->db->join('tbl_vendor','tbl_vendor.vendor_id = tbl_vendor_bills.vendor_id','left');
            $this->db->join('tbl_cities','tbl_cities.id = tbl_vendor.city','left');
            $this->db->join('tbl_make','tbl_make.make_id = tbl_vendor_bills.make_id','left');
            $this->db->join('tbl_model','tbl_model.model_id = tbl_vendor_bills.model_id','left');
            $this->db->join('tbl_variant','tbl_variant.variant_id = tbl_vendor_bills.variant_id','left');
            $this->db->where('po_id',$po_id);
            $po = $this->db->get()->row_array();

            // vendor contact..
            $this->db->select('*');
            $this->db->from('tbl_vendor_contacts');
            $this->db->where('vendor_id',$po['vendor_id']);
            $vendor_contacts = $this->db->get()->result_array();

            $this->db->select('*');
            $this->db->from('tbl_vendor_bill_item');
            $this->db->where('po_id',$po_id);
            $po_items = $this->db->get()->result_array();

            // garage detail.
            $this->db->select('*,tbl_garage.name as garage_name');
            $this->db->from('tbl_garage');
            $this->db->where('tbl_garage.garage_id',$po['garage_id']);
            $garageDetail = $this->db->get()->row_array();

            $html = "<table style='width:100%;'>";
            $html .= "<tr><td style='text-align:center;'>";
            $html .= "<h2>Purchase Order</h2>";
            $html .= "</td></tr>";
            $html .= "</table>";
            $html .= "<table class='padding_table' style='border-collapse:collapse;width:100%;font-family:Helvetica;'>";
            $html .= '<tr>';
            $logotdcolSpan = 2;

            if($garageDetail['show_logo'] == 'Y') {
                $html .= '<td rowspan="2" valign="top" style="width:15%;border-collpase:collpased;padding : 4px;border-bottom:1px solid black;border-left:1px solid black;border-top:1px solid black;border-color: black;border-width: thin;">';
                $html .= '<img src="'.$logo_path.'/'.$garageDetail['logo_path'].'">';
                $html .= '</td>';
                $logotdcolSpan = 0;
                $logotdStyle = "width:35%;border-collpase:collpased;padding : 4px;border-bottom:1px solid black;border-right:1px solid black;border-top:1px solid black;border-color: black;border-width: thin;";
            } else {
                $logotdStyle = "width:50%;border-collpase:collpased;padding:4px;border-left:1px solid black;border-right:1px solid black;border-top:1px solid black;border-color: black;border-width: thin;";
            }
    
            $html .= '<td colspan="'.$logotdcolSpan.'" valign="top" style="'.$logotdStyle.'">';
            $html .= '<span class="bold-item"><b>'.strtoupper($garageDetail['garage_name']).'</b></span><br>';
            $html .= '<span class="non-bold-item">'.$garageDetail['address'].'</span><br>';
    
            if($garageDetail['gstin_no'] !="" && $is_tax == 'Y') {
                $html .= '<span class="non-bold-item">GSTIN : '.$garageDetail['gstin_no'].'.</span><br>';
            }
            $html .= '<span class="non-bold-item">';
            if($garageDetail['contact_no'] != "") {
                $html .= 'PH: '.$garageDetail['contact_no'];
            }
    
            if($garageDetail['alternate_contact'] != "" && $garageDetail['contact_no'] != "") {
                $html .= ', '.$garageDetail['alternate_contact'];
            } else if($garageDetail['alternate_contact'] != "" && $garageDetail['contact_no'] == "") {
                $html .= $garageDetail['alternate_contact'];
            }

            $html .= '</span><br>';
            $html .= '<span class="non-bold-item">';
            if($garageDetail['email'] != "") {
                $html .= 'Email: '.$garageDetail['email'];
            }
            $html .= '</span><br>';
            $html .= '</td>';
            $html .= '<td colspan="2" style="width:50%;border-collpase:collpased;padding:4px;border-left:1px solid black;border-right:1px solid black;border-top:1px solid black;border-color: black;border-width: thin;" valign="top">';
            $html .= '<span class="bold-item"><b>'.strtoupper($po['company_name']).'</b></span><br>';
            $html .= '<span class="non-bold-item">'.$po['address1'];
            if ($po['address2'] != "") {
                $html .= ' , '.$po['address2'].'';
            }
            $html .= '</span><br>';
            if($po['vendor_city'] != "") {
                $html .= '<span style="display:table-row;" class="non-bold-item">';
                $html .= $po['vendor_city'];
                if($po['pincode'] != "") {
                    $html .= ' - ';
                    $html .= $po['pincode'];
                }
                $html .= '</span><br>';
            }
    
            $html .= '<span class="non-bold-item">';
            if($vendor_contacts && !empty($vendor_contacts)) {
                $html .= 'Phone: '.$vendor_contacts[0]['contact_no'];
            }
    
            $html .= '</td>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<td style="border:1px solid thin;" colspan="2">Vehicle:<br></td>';
            $html .= '<td style="border:1px solid thin;width:25%;">Order No: <br></td>';
            $html .= '<td style="border:1px solid thin;width:25%;">Order Date: <br></td>';
            $html .= '</tr>';
            $html .= '</table>';

            $html .= "<table style='border-collapse:collapse;width:100%;width:100%;' border='1'>";
            $html .= '<tr>';
            $html .= '<th style="width:5%;">Sr. No</th>';
            $html .= '<th style="width:50%;">Description</th>';
            $html .= '<th style="width:14%;">Ordered Qty</th>';
            $html .= '</tr>';
            $srno = 1;
            foreach($po_items as $p => $item) {
                $html .= '<tr>';
                $html .= '<td style="text-align:center;">'.$srno.'</td>';
                $html .= '<td>'.$item['description'].'</td>';
                $html .= '<td style="text-align:center;">'.$item['qty'].'</td>';
                $html .= '</tr>';
                $srno++;
            }
            $html .= '<tr>';
            $html .= '<td colspan="3"><b>Notes:</b><br><span>'.$po['order_note'].'</span></td>';
            $html .= '</tr>';
            $html .= '</table>';
            $html .= '<style> .bold-item { font-size: 14px;} .non-bold-item { font-size: 12px;}  h2 { font-size: 16px;font-weight:bolder;font-family:Helvetica;} span { font-size : 12px;font-family:Helvetica;}  .no-b{ border-top:0px !important;border-bottom: 0px !important; } @page { margin: 35px 25px 25px 25px; }</style>';

            $mpdf->WriteHTML($html);
            $mpdf->Output($file_name,'F');
            
            header('Content-type:application/pdf');
            header('Content-disposition: inline; filename="'.$file_name.'"');
            @ readfile($file_name);
        }
        }

        public function getSelectedPackageDetail() {
            if(isset($_REQUEST) && !empty($_REQUEST['package_ids'])) {
                $packageItem = array();
                $pakage_ids = explode(",",$_REQUEST['package_ids']);
                $this->db->select('tbl_package_item.*,tbl_items.item_name');
                $this->db->from('tbl_package_item');
                $this->db->join('tbl_items','tbl_items.item_id=tbl_package_item.item_id','left');
                $this->db->where_in('package_id',$pakage_ids);
                $packageItem = $this->db->get()->result_array();
                return json_encode(array('status' => 200,'data'=> $packageItem,'message' => 'success'));
            } else {
                return json_encode(array('status' => 300,'message' => 'please select at-least one package.'));
            }
        }
        public function getVendorInvoiceDetail($invoice_id) {
                $result = array();
                $this->db->select('*');
                $this->db->from('tbl_vendor');
                $this->db->where('garage_id',$_SESSION['setting']->garage_id);
                $result['vendors'] = $this->db->get()->result_array();
                return $result;
        }
        public function getPODetailsByPOID($po_id) {
                $result['po'] = array();
                $result['po_items'] = array();

                $this->db->select('*');
                $this->db->from('tbl_vendor');
                $this->db->where('garage_id',$_SESSION['setting']->garage_id);
                $result['vendors'] = $this->db->get()->result_array();
            if($po_id != "") {
                $this->db->select('*');
                $this->db->from('tbl_vendor_bills');
                $this->db->where('po_id',$po_id);
                $result['po'] = $this->db->get()->row_array();
                
                $this->db->select('*');
                $this->db->from('tbl_vendor_bill_item');
                $this->db->where('po_id',$po_id);
                $result['po_items'] = $this->db->get()->result_array();

                $result['po']['order_date'] = $result['po']['order_date'] != ""  && $result['po']['order_date'] != '0000-00-00' ?  date('d-m-Y',strtotime($result['po']['order_date'])) : '';
                $result['po']['due_date'] = $result['po']['due_date'] != ""  && $result['po']['due_date'] != '0000-00-00' ?  date('d-m-Y',strtotime($result['po']['due_date'])) : '';
            }
            return $result;
        }
        public function sendCommunication($template_id) {
            // first get email/sms template.
            $template = array();

            $this->db->select('*');
            $this->db->from('tbl_template');
            $this->db->where('template_id',$template_id);
            $template = $this->db->get()->row_array();

            if(!empty($template)) {  // now find receiptant.
                
            }
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
                    if($num_array[1]) {
                        $thecash = $thecash.'.'.$num_array[1];
                    }
                    return ($thecash);
        }
        public function convertMessage($idArray) {
            if($idArray && $idArray['jobcard_id'] != "") {
                $selectedTeplate = array();
                $this->db->select('*');
                $this->db->from('tbl_template');
                $this->db->where('order_no',$idArray['order_no']);
                $this->db->where('garage_id',$_SESSION['setting']->garage_id);
                $selectedTeplate = $this->db->get()->row_array();
                
                $jobcard_id = base64_decode($idArray['jobcard_id']);
                $this->db->select('*,tbl_jobcard.garage_id,tbl_customer.name as customer_name,tbl_jobcard.customer_id as job_customer_id,tbl_jobcard.vehicle_id as job_vehicle_id, tbl_garage.name as garage_name,tbl_invoices.date as invoice_date,tbl_jobcard.date as job_date,tbl_make.name as make_name,tbl_model.name as model_name,tbl_variant.name as variant_name,tbl_garage.email as gar_email,tbl_customer.billing_address,tbl_customer.email as cust_email,tbl_customer.mobile_no as cust_mobile_no');
                $this->db->from('tbl_jobcard');
                $this->db->join('tbl_invoices','tbl_invoices.item_id=tbl_jobcard.jobcard_id and tbl_invoices.item_type="job_invoice"','left');
                $this->db->join('tbl_garage','tbl_garage.garage_id=tbl_jobcard.garage_id','left');
                $this->db->join('tbl_customer','tbl_customer.customer_id=tbl_jobcard.customer_id','left');
                $this->db->join('tbl_customer_vehicle','tbl_customer_vehicle.vehicle_id=tbl_jobcard.vehicle_id','left');
                $this->db->join('tbl_make','tbl_make.make_id=tbl_customer_vehicle.make_id','left');
                $this->db->join('tbl_model','tbl_model.model_id=tbl_customer_vehicle.model_id','left');
                $this->db->join('tbl_variant','tbl_variant.variant_id=tbl_customer_vehicle.variant_id','left');
                $this->db->where('tbl_jobcard.jobcard_id',$jobcard_id);
                $jobData = $this->db->get()->row_array();

                $job_invoice = array();
                $this->db->select('*');
                $this->db->from('tbl_invoices');
                $this->db->where('item_id', $jobcard_id);
                $this->db->where('item_type', 'job_invoice');
                $job_invoice = $this->db->get()->row_array();
                
                $invoice_amount = 0;
                $invoice_payment_made = 0;
                $current_paid = 0;
                $total_due_invoice = 0;
                
                if($job_invoice && !empty($job_invoice)) {
                    $invoice_amount = $job_invoice['amount'];
                    $all_payment_made = array();
    
                    $this->db->select('*');
                    $this->db->from('tbl_transaction');
                    $this->db->join('tbl_payment_type','tbl_payment_type.payment_type=tbl_transaction.payment_type','left');
                    $this->db->where('tbl_transaction.customer_id', $job_invoice['customer_id']);
                    $this->db->where('tbl_transaction.item_id', $job_invoice['invoice_id']);
                    $this->db->where('tbl_transaction.transaction_type', 'customer_payment');
                    $all_payment_made = $this->db->get()->result_array();
                    
                    if ($all_payment_made && !empty($all_payment_made)) {
                        foreach ($all_payment_made as $p) {
                            $invoice_payment_made += $p['amount'];
                            $current_paid = $p['amount'];
                        }
                    }
                }
	
                include_once(DOCUMENT_ROOT.'assets/Shortener.php');
                $shortener = new Shortener();

                $invoice_link = FRONT_ROOT.$shortener->urlToShortCode(BASE_URL.'booking/viewInvoicePdf?job_id='.base64_encode($jobData['jobcard_id']));
                $estimate_link = FRONT_ROOT.$shortener->urlToShortCode(BASE_URL.'booking/viewEstimatePdf?job_id='.base64_encode($jobData['jobcard_id']));
                $total_due_invoice = $invoice_amount - $invoice_payment_made;
                
                $vars = array(
                "{{garage_name}}" => $jobData['garage_name'] != "" ? $jobData['garage_name'] : "",
                "{{garage_address}}" => $jobData['address'] != "" ? $jobData['address'] : "",
                "{{garage_email}}" => $jobData['gar_email'] != "" ? $jobData['gar_email'] : "",
                "{{garage_website}}" => $jobData['web'] != "" ? $jobData['web'] : "",
                "{{garage_contact_no}}" => $jobData['contact_no'] != "" ? $jobData['contact_no'] : "",
                "{{garage_contact_person}}" => $jobData['contact_person_name'] != "" ? $jobData['contact_person_name'] : "",
                "{{customer_name}}" => $jobData['customer_name'] != "" ? $jobData['customer_name'] : "",
                "{{customer_address}}" => $jobData['billing_address'] != "" ? $jobData['billing_address'] : "",
                "{{customer_contact_no}}" => $jobData['mobile_no'] != "" ? $jobData['mobile_no'] : $jobData['home_phone'] != "" ? $jobData['home_phone'] : $jobData['work_phone'],
                "{{customer_email}}" => $jobData['cust_email'] != "" ? $jobData['cust_email'] : "",
                "{{customer_vehicle_no}}" => $jobData['reg_no'] != "" ? $jobData['reg_no'] : "",
                "{{customer_vehicle_make}}" => $jobData['make_name'] != "" ? $jobData['make_name'] : "",
                "{{customer_vehicle_model}}" => $jobData['model_name'] != "" ? $jobData['model_name'] : "",
                "{{customer_vehicle_variant}}" => $jobData['variant_name'] != "" ? $jobData['variant_name'] : "",
                "{{sender_name}}" => $jobData['email_sender_name'] != "" ? $jobData['email_sender_name'] : "",
                "{{sender_contact_no}}" => $jobData['email_sender_contact_no'] != "" ? $jobData['email_sender_contact_no'] : "",
                "{{sender_email}}" => $jobData['email_sender_email'] != "" ? $jobData['email_sender_email'] : "",
                "{{jobcard_no}}" => $jobData['jobcard_no'] != "" ? $jobData['jobcard_no'] : "",
                "{{jobcard_date}}" => $jobData['job_date'] != "" ? date("d-m-Y",strtotime($jobData['job_date'])) : "",
                "{{jobcard_odometer}}" => $jobData['odometer'] != "" ? $jobData['odometer'] : "",
                "{{jobcard_total_amount}}" => $jobData['grand_total'] != "" ? $jobData['grand_total'] : "",
                "{{estimate_no}}" => $jobData['jobcard_no'] != "" ? $jobData['jobcard_no'] : "",
                "{{estimate_date}}" => $jobData['job_date'] != "" ? date("d-m-Y",strtotime($jobData['job_date'])) : "",
                "{{estimate_link}}" => $estimate_link,
                "{{estimate_total_amount}}" => $jobData['grand_total'] != "" ? $jobData['grand_total'] : "",
                "{{invoice_no}}" => $jobData['invoice_no'] != "" ? $jobData['invoice_no'] : "",
                "{{invoice_date}}" => $jobData['invoice_date'] != "" ? date('d-m-Y',strtotime($jobData['invoice_date'])) : "",
                "{{invoice_due_date}}" => $jobData['due_date'] != "" ? date('d-m-Y',strtotime($jobData['due_date'])) : "",
                "{{invoice_link}}" => $invoice_link,
                "{{invoice_total_amount}}" => $invoice_amount,
                "{{invoice_total_paid}}" => $invoice_payment_made,
                "{{invoice_paid}}" => $current_paid,
                "{{invoice_paid_by}}" => $invoice_paid_by,
                "{{invoice_total_due}}" => $total_due_invoice
                );
                
                if($idArray['isSave'] && $idArray['isSave'] == 'Y') {
                    $email_subject = strtr($selectedTeplate['email_subject'], $vars);
                    $email_body = strtr($selectedTeplate['email_body'], $vars);
                    $sms_body = strtr($selectedTeplate['sms_body'], $vars);

                    
                    $emailSMSBuffer = array();
                    $emailSMSBuffer['garage_id'] = $jobData['garage_id'];
                    $emailSMSBuffer['item_type'] = $selectedTeplate['name'];
                    $emailSMSBuffer['item_id'] = $jobcard_id;
                    $emailSMSBuffer['template_id'] = $selectedTeplate['template_id'];
                    $emailSMSBuffer['customer_id'] = $jobData['customer_id'];
                    $emailSMSBuffer['vehicle_id'] = $jobData['vehicle_id'];
                    $emailSMSBuffer['reminder_id'] = 0;
                    $emailSMSBuffer['contact_no'] = $jobData['cust_mobile_no'];
                    $emailSMSBuffer['email_id'] = $jobData['cust_email'];
                    $emailSMSBuffer['is_email'] = 'N';
                    $emailSMSBuffer['is_sms'] = 'Y';
                    $emailSMSBuffer['email_subject'] = $email_subject;
                    $emailSMSBuffer['email_body'] = $email_body;
                    $emailSMSBuffer['sms_body'] = $sms_body;
                    $emailSMSBuffer['date'] = date('Y-m-d H:i:s');
                    $emailSMSBuffer['sms_sent_date'] = date('Y-m-d H:i:s');
                    $emailSMSBuffer['sms_sent_status'] = 'pending';
                    $this->InsertData('tbl_email_sms_buffer', $emailSMSBuffer);
                }
                return $replace_content = strip_tags(strtr($idArray['message'], $vars));
            }
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
            return $this->db->insert_id();
        }
        
}
