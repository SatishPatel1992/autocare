<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config = array(
    'protocol' => 'smtp', // 'mail', 'sendmail', or 'smtp'
    'smtp_host' => 'localhost', 
    'smtp_port' => 25,
    'smtp_user' => 'satish@makemyrepair.com',
    'smtp_pass' => 'S@t!sh@123',
    'smtp_crypto' => '', //can be 'ssl' or 'tls' for example
    'mailtype' => 'html', //plaintext 'text' mails or 'html'
    'smtp_timeout' => '50', //in seconds
    'charset' => 'iso-8859-1',
    'wordwrap' => TRUE
);