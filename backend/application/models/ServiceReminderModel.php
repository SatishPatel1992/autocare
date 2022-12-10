<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ServiceReminderModel extends CI_Model {

    public function getReminders() {
        $result = array();
        if(isset($_REQUEST['d'])) {
            $dateArray = explode(" - ",$_REQUEST['d']);
            $start_date = date('Y-m-d',strtotime($dateArray[0]));
            $end_date = date('Y-m-d',strtotime($dateArray[1]));
        } else {
            $start_date = date('Y-m-d',strtotime('-10 days'));
            $end_date = date('Y-m-d',strtotime('+15 days'));    
        }
        $result['reminder_count'] = array();
        $total_reminder_sent = 0;
        $total_vehicle_received = 0;


        $this->db->select('tbl_email_sms_buffer.vehicle_id,count(*) as total_reminder_sent');
        $this->db->from('tbl_email_sms_buffer');
        $this->db->join('tbl_service_reminder','tbl_service_reminder.serv_remider_id = tbl_email_sms_buffer.reminder_id','left');
        $this->db->where('tbl_email_sms_buffer.garage_id',$_SESSION['setting']->garage_id);
        $sent_date_where = "date_format(email_sent_date,'%Y-%m-%d') between '".$start_date."' and '".$end_date."'";
        $this->db->where($sent_date_where);
        $sms_sent_date_where = "date_format(email_sent_date,'%Y-%m-%d') between '".$start_date."' and '".$end_date."'";
        $this->db->where($sms_sent_date_where);
        $this->db->where('email_sent_status','sent');
        $this->db->where('sms_sent_status','sent');
        $this->db->group_by('tbl_email_sms_buffer.vehicle_id');
        $reminder_sent_history = $this->db->get()->result_array();

        // check if any vehicle is received or not.
        $this->db->select('customer_id,vehicle_id');
        $this->db->from('tbl_jobcard');
        $date_where = "date between '".$start_date."' and '".$end_date."'";
        $this->db->where($date_where);
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
        $allJobcardCreated = $this->db->get()->result_array();

        $allJobcards = array();
        if(!empty($allJobcardCreated)) {
            foreach($allJobcardCreated as $k => $v) {
                $allJobcards[$v['vehicle_id']] = $v;
            }
        }

        
        if(!empty($reminder_sent_history)) {
            foreach($reminder_sent_history as $k => $v) {
                $result['reminder_count'][$v['vehicle_id']] = $v['total_reminder_sent'];
                if($v['total_reminder_sent'] != 0) {
                    $total_reminder_sent++;
                }
                if($allJobcards && isset($allJobcards[$v['vehicle_id']]) && !empty($allJobcards[$v['vehicle_id']])) {
                    $total_vehicle_received++;
                }
            }
        }

        $this->db->select("tbl_make.name as make_name,tbl_model.name as model_name,tbl_customer.mobile_no,tbl_customer.email,tbl_customer.name,tbl_customer_vehicle.reg_no,tbl_service_reminder.*,DATEDIFF(STR_TO_DATE(reminder_date, '%Y-%m-%d'),CURDATE()) AS days");
        $this->db->from("tbl_service_reminder");
        $this->db->join('tbl_customer','tbl_customer.customer_id=tbl_service_reminder.customer_id','left');
        $this->db->join('tbl_customer_vehicle','tbl_customer_vehicle.vehicle_id=tbl_service_reminder.vehicle_id','left');
        $this->db->join('tbl_make','tbl_make.make_id=tbl_customer_vehicle.make_id','left');
        $this->db->join('tbl_model','tbl_model.model_id=tbl_customer_vehicle.model_id','left');
        $date_where = "reminder_date between '".$start_date."' and '".$end_date."'";
        $this->db->where($date_where);
        $this->db->where('tbl_service_reminder.garage_id',$_SESSION['setting']->garage_id);
        $this->db->where('tbl_customer_vehicle.is_active','1');
        $result['due_remiders'] = $this->db->get()->result_array();
        
        $result['total_reminder_sent'] = $total_reminder_sent;
        $result['total_vehicle_received'] = $total_vehicle_received;
        
        return $result;
    }
    public function sendComunication($reminder_ids,$is_email,$is_sms) {
        $this->db->select('*');
        $this->db->from('tbl_template');
        $this->db->where('garage_id',$_SESSION['setting']->garage_id);
        $this->db->where('order_no',1);
        $selectedTemplate =$this->db->get()->row_array();
        
        if($selectedTemplate && !empty($selectedTemplate)) { // if get templates.
        foreach($reminder_ids as $k => $v) {
        $this->db->select('tbl_make.name as make_name,tbl_model.name as model_name,tbl_variant.name as variant_name,tbl_garage.name as garage_name,tbl_garage.address,tbl_garage.email as gar_email,tbl_customer.*,tbl_customer_vehicle.reg_no ,tbl_service_reminder.*,tbl_customer.email as cust_email,tbl_customer.mobile_no as cust_mobile_no');
        $this->db->from('tbl_service_reminder');
        $this->db->join('tbl_garage','tbl_garage.garage_id=tbl_service_reminder.garage_id','left');
        $this->db->join('tbl_customer','tbl_customer.customer_id=tbl_service_reminder.customer_id','left');
        $this->db->join('tbl_customer_vehicle','tbl_customer_vehicle.vehicle_id=tbl_service_reminder.vehicle_id','left');
        $this->db->join('tbl_make','tbl_make.make_id=tbl_customer_vehicle.make_id','left');
        $this->db->join('tbl_model','tbl_model.model_id=tbl_customer_vehicle.model_id','left');
        $this->db->join('tbl_variant','tbl_variant.variant_id=tbl_customer_vehicle.variant_id','left');
        $this->db->where('serv_remider_id',$v);
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
                "{{service_due_vehicle_no}}" => $reminder['service_due_vehicle_no'] != "" ? $reminder['service_due_vehicle_no'] : "",
                );
                $email_subject = strtr($selectedTemplate['email_subject'], $vars);
                $email_body = strtr($selectedTemplate['email_body'], $vars);
                $sms_body = strtr($selectedTemplate['sms_body'], $vars);

                $emailSMSBuffer = array();
                $emailSMSBuffer['garage_id'] = $_SESSION['setting']->garage_id;
                $emailSMSBuffer['item_type'] = 'service_reminder';
                $emailSMSBuffer['item_id'] = $reminder['job_id'];
                $emailSMSBuffer['template_id'] = $selectedTemplate['template_id'];
                $emailSMSBuffer['reminder_id'] = $reminder['serv_remider_id'];
                $emailSMSBuffer['contact_no'] = $reminder['cust_mobile_no'];
                $emailSMSBuffer['email_id'] = $reminder['cust_email'];
                $emailSMSBuffer['is_email'] = $is_email;
                $emailSMSBuffer['is_sms'] = $is_sms;
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
    }
    public function InsertData($TableName, $data) {
        $DataArray = array();
        foreach ($data as $key => $value) {
            if ($key != 'table_name' && $key != 'transcation') {
                $DataArray[$key] = addslashes($value);
            }
        }
        $this->db->insert($TableName, $DataArray);
        //echo $this->db->last_query();exit;
        return $this->db->insert_id();
    }
}
?>
