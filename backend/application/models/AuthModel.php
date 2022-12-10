<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AuthModel extends CI_Model {
    public function check_login($username,$password) {
        $query  = $this->db->select('*')->from('tbl_users')->where('mobile_no',$username)->get()->row();
        $settings = $this->db->select('*')->from('tbl_garage')->where('garage_id',$query->garage_id)->get()->row();
        if($query == "") {
            return array('status' => 1,'message' => 'Username not found.');
        } else {
            $hashed_password = $query->password;
            $user_id  = $query->user_id;
	        if(md5($password) == $hashed_password) {
                $last_login = date('Y-m-d H:i:s');
                $token = crypt(substr( md5(rand()), 0, 7));
                $this->db->where('user_id',$user_id);
                $this->db->update('tbl_users',array('last_login'=>$last_login));
                if($this->db->trans_status() === FALSE) {
                  return array('status' => 1,'message' => 'Internal server error.');
                } else {
                  return array('status' => 0,'message' => 'Successfully login.','data' => $query, 'token' => $token,'setting' => $settings);
                }
            } else {
                return array('status' => 1,'message' => 'Wrong password.');
            }
        }
    }
    public function logout() {
        $users_id  = $this->input->get_request_header('User-ID', TRUE);
        $token     = $this->input->get_request_header('Authorization', TRUE);
        $this->db->where('users_id',$users_id)->where('token',$token)->delete('users_authentication');
        return array('status' => 200,'message' => 'Successfully logout.');
    }
}
