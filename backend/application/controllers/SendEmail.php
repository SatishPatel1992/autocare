<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SendEmail extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('ServiceReminderModel');
    }
    public function send() {
        $this->load->library("PhpMailerLib");
        $mail = $this->phpmailerlib->load();
        // SMTP configuration
        //$mail->isSMTP();
        $mail->Host     = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'makemyrepairs@gmail.com';
        $mail->Password = 'Satish@123';
        $mail->SMTPSecure = 'tls';
        $mail->Port     = 587;
        $mail->SMTPOptions = array(
          'ssl' => array(
          'verify_peer' => false,
          'verify_peer_name' => false,
          'allow_self_signed' => true
        ));

        $templates = array();
        $this->db->select('tbl_email_sms_buffer.*,tbl_garage.sms_balance');
        $this->db->from('tbl_email_sms_buffer');
        $this->db->join('tbl_garage','tbl_garage.garage_id=tbl_email_sms_buffer.garage_id','left');
        $where = "sms_sent_status='pending'";
        $this->db->where($where);
        $templates = $this->db->get()->result_array();
        
        if($templates && !empty($templates)) {
            foreach($templates as $k => $v) {
                if($v['email_id'] != "" && $v['is_email'] == 'Y' && $v['email_sent_status'] == 'pending') {
                        $mail->setFrom('makemyrepairs@gmail.com', 'Make My Repair');
                        $mail->addAddress($v['email_id']);     // Add a recipient
                  
                        $mail->isHTML(true);                                  // Set email format to HTML
                        $mail->Subject = $v['email_subject'];
                        $mail->Body    = $v['email_body'];
                        print_r($mail->send());
                        if($mail->send()) {
                            $this->db->where('buffer_id',$v['buffer_id']);
                            $this->db->update('tbl_email_sms_buffer',array('email_sent_status' => 'sent','email_sent_date'=>date('Y-m-d H:i:s')));
                        } else {
                            echo "Mailer Error: " . $mail->ErrorInfo;
                            $this->db->where('buffer_id',$v['buffer_id']);
                            $this->db->update('tbl_email_sms_buffer',array('email_sent_status' => 'failed','email_sent_date'=>date('Y-m-d H:i:s')));
                        }
                }
                if($v['sms_balance'] > 0 && $v['contact_no'] != "" && $v['is_sms'] == 'Y' && $v['sms_sent_status'] == 'pending') {
                   $this->sendSMS($v['sms_body'],$v['contact_no'],$v['buffer_id'],$v['garage_id']);
                }
            }
        }
    }
    public function convertPaymentRemider() {

    }
    public function sendServiceReminder() {
        $this->db->select("*");
        $this->db->from('tbl_garage');
        $this->db->where('is_active',1);
        $allGarages = $this->db->get()->result_array();
        
        foreach($allGarages as $garageInfo) {
            $no_of_days_occure = array();
            $is_proceeds = 'N';
            if($garageInfo['5days_before_service'] == 'Y') {
                array_push($no_of_days_occure, '5');
                $is_proceeds = 'Y';
            }
            if($garageInfo['3days_before_service'] == 'Y') {
                array_push($no_of_days_occure, '3');
                $is_proceeds = 'Y';
            }
            if($garageInfo['on_due_date_service'] == 'Y') {
                array_push($no_of_days_occure, '0');
                $is_proceeds = 'Y';
            }
            
            if($is_proceeds == 'Y') {
            $start_date = date('Y-m-d',strtotime('-15 days'));
            $end_date = date('Y-m-d',strtotime('+15 days'));    
                // check if any vehicle is received or not.
            $this->db->select('customer_id,vehicle_id');
            $this->db->from('tbl_jobcard');
            $date_where = "date between '".$start_date."' and '".$end_date."'";
            $this->db->where($date_where);
            $this->db->where('garage_id',$garageInfo['garage_id']);
            $allJobcardCreated = $this->db->get()->result_array();

            $allJobcards = array();
            if(!empty($allJobcardCreated)) {
                foreach($allJobcardCreated as $k => $v) {
                    $allJobcards[$v['vehicle_id']] = $v;
                }
            }
            
            $this->db->select('*');
            $this->db->from('tbl_template');
            $this->db->where('garage_id',$garageInfo['garage_id']);
            $this->db->where('order_no',1);
            $reminderTemplate = $this->db->get()->row_array();

            $this->db->select("tbl_service_reminder.vehicle_id,serv_remider_id,DATEDIFF(STR_TO_DATE(reminder_date, '%Y-%m-%d'),CURDATE()) AS days");
            $this->db->from("tbl_service_reminder");
            $this->db->where('tbl_service_reminder.garage_id',$garageInfo['garage_id']);
            $having = "days IN (".implode(",",$no_of_days_occure).")";
            $this->db->having($having);
            $allRemindertobeSend = $this->db->get()->result_array();
            
            foreach($allRemindertobeSend as $reminder) {
                if(!$allJobcards[$reminder['vehicle_id']] && empty($allJobcards[$reminder['vehicle_id']])) {
                    $this->replacePlaceholder($reminderTemplate,$reminder['serv_remider_id'],'Y','Y');
                }
            }
        }
      }
    }
    public function replacePlaceholder($selectedTemplate,$reminder_id,$is_email,$is_sms) {
        $this->db->select('tbl_garage.address,tbl_garage.email_sender_name,tbl_garage.email_sender_contact_no,tbl_garage.email_sender_email,tbl_make.name as make_name,tbl_model.name as model_name,tbl_variant.name as variant_name,tbl_garage.name as garage_name,tbl_garage.email as gar_email,tbl_customer.*,tbl_customer_vehicle.reg_no,tbl_service_reminder.*,tbl_customer.email as cust_email,tbl_customer.mobile_no as cust_mobile_no');
        $this->db->from('tbl_service_reminder');
        $this->db->join('tbl_garage','tbl_garage.garage_id=tbl_service_reminder.garage_id','left');
        $this->db->join('tbl_customer','tbl_customer.customer_id=tbl_service_reminder.customer_id','left');
        $this->db->join('tbl_customer_vehicle','tbl_customer_vehicle.vehicle_id=tbl_service_reminder.vehicle_id','left');
        $this->db->join('tbl_make','tbl_make.make_id=tbl_customer_vehicle.make_id','left');
        $this->db->join('tbl_model','tbl_model.model_id=tbl_customer_vehicle.model_id','left');
        $this->db->join('tbl_variant','tbl_variant.variant_id=tbl_customer_vehicle.variant_id','left');
        $this->db->where('serv_remider_id',$reminder_id);
        $reminder = $this->db->get()->row_array();

        if($reminder) {
            $vars = array(
                "{{garage_name}}" => $reminder['garage_name'] != "" ? $reminder['garage_name'] : "",
                "{{garage_address}}" => $reminder['address'] != "" ? $reminder['address'] : "",
                "{{garage_email}}" => $reminder['gar_email'] != "" ? $reminder['gar_email'] : "",
                "{{garage_website}}" => $reminder['web'] != "" ? $reminder['web'] : "",
                "{{garage_contact_no}}" => $reminder['contact_no'] != "" ? $reminder['contact_no'] : "",
                "{{garage_contact_person}}" => $reminder['contact_person_name'] != "" ? $reminder['contact_person_name'] : "",
                "{{customer_name}}" => $reminder['name'] != "" ? $reminder['name'] : "",
                "{{customer_address}}" => $reminder['billing_address'] != "" ? $reminder['billing_address'] : "",
                "{{customer_contact_no}}" => $reminder['mobile_no'] != "" ? $reminder['mobile_no'] : $reminder['home_phone'] != "" ? $reminder['home_phone'] : $reminder['work_phone'],
                "{{customer_email}}" => $reminder['cust_email'] != "" ? $reminder['cust_email'] : "",
                "{{customer_vehicle_no}}" => $reminder['reg_no'] != "" ? $reminder['reg_no'] : "",
                "{{customer_vehicle_make}}" => $reminder['make_name'] != "" ? $reminder['make_name'] : "",
                "{{customer_vehicle_model}}" => $reminder['model_name'] != "" ? $reminder['model_name'] : "",
                "{{customer_vehicle_variant}}" => $reminder['variant_name'] != "" ? $reminder['variant_name'] : "",
                "{{sender_name}}" => $reminder['email_sender_name'] != "" ? $reminder['email_sender_name'] : "",
                "{{sender_contact_no}}" => $reminder['email_sender_contact_no'] != "" ? $reminder['email_sender_contact_no'] : "",
                "{{sender_email}}" => $reminder['email_sender_email'] != "" ? $reminder['email_sender_email'] : "",
                "{{last_service_date}}" => $reminder['service_date'] != "" ? date('d-m-Y',strtotime($reminder['service_date'])) : "",
                "{{service_due_date}}" => $reminder['reminder_date'] != "" ? date('d-m-Y',strtotime($reminder['reminder_date'])) : "",
                "{{service_due_vehicle_no}}" => $reminder['reg_no'] != "" ? $reminder['reg_no'] : "",
                );
                $email_subject = strtr($selectedTemplate['email_subject'], $vars);
                $email_body = strtr($selectedTemplate['email_body'], $vars);
                $sms_body = strtr($selectedTemplate['sms_body'], $vars);

                $emailSMSBuffer = array();
                $emailSMSBuffer['garage_id'] = $reminder['garage_id'];
                $emailSMSBuffer['item_type'] = 'service_reminder';
                $emailSMSBuffer['item_id'] = $reminder['job_id'];
                $emailSMSBuffer['template_id'] = $selectedTemplate['template_id'];
                $emailSMSBuffer['customer_id'] = $reminder['customer_id'];
                $emailSMSBuffer['vehicle_id'] = $reminder['vehicle_id'];
                $emailSMSBuffer['reminder_id'] = $reminder['serv_remider_id'];
                $emailSMSBuffer['contact_no'] = $reminder['cust_mobile_no'];
                $emailSMSBuffer['email_id'] = $reminder['cust_email'];
                $emailSMSBuffer['is_email'] = $is_email;
                $emailSMSBuffer['is_sms'] = $is_sms;
                $emailSMSBuffer['email_subject'] = $email_subject;
                $emailSMSBuffer['email_body'] = $email_body;
                $emailSMSBuffer['sms_body'] = $sms_body;
                $emailSMSBuffer['date'] = date('Y-m-d H:i:s');
                $emailSMSBuffer['sms_sent_date'] = date('Y-m-d H:i:s');
                $emailSMSBuffer['sms_sent_status'] = 'pending';
                $this->InsertData('tbl_email_sms_buffer', $emailSMSBuffer);
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
    public function sendSMS($sms,$contactno,$buffer_id,$garage_id) {
            // Account details
            $apiKey = urlencode('g7hGQO4ny20-gU7LMONSZZ7hrtN5RUf6tiivVBjptn');
	
            // Message details
            $numbers = urlencode($contactno);
            $sender = urlencode('CARRPR');
            $message = rawurlencode($sms);
         
            // Prepare data for POST request
            $data = 'apikey=' . $apiKey . '&numbers=' . $numbers . "&sender=" . $sender . "&message=" . $message;
         
            // Send the GET request with cURL
            $ch = curl_init('https://api.textlocal.in/send/?' . $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            
            $this->db->where('buffer_id',$buffer_id);
            $this->db->update('tbl_email_sms_buffer',array('sms_sent_status' => 'sent','sms_sent_date' => date("Y-m-d H:i:s")));
            
            $this->updateSMSBalance($garage_id);

    }
    public function updateSMSBalance($garage_id) {
        $this->db->where('garage_id', $garage_id);
        $this->db->set('sms_balance', 'sms_balance-1', FALSE);
        $this->db->update('tbl_garage');
    }
}
