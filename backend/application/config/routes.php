<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['account-payable'] = 'reports/account_payable';
$route['account-receivable'] = 'reports/account_receivable';
$route['account-statement'] = 'reports/account_statement';
$route['accounts'] = 'accounts';
$route['income-expense-head'] = 'incomeExpenseHead';
$route['add-income-expense-head'] = 'incomeExpenseHead/addIncExpHead';
$route['parts-purchase'] = 'reports/parts_purchase';
$route['jobcard-reports'] = 'reports/jobcard_reports';
$route['invoice-reports'] = 'reports/invoice_reports';
$route['ledger'] = 'reports/ledger';
$route['daybook'] = 'reports/daybook';
$route['outstanding'] = 'reports/outstanding';
$route['gstr-1'] = 'reports/gstr_1';
$route['gstr-2'] = 'reports/gstr_2';
$route['bill_wise_profit'] = 'reports/bill_wise_profit';
$route['stock-reports'] = 'reports/stock_reports';
$route['profit-loss'] = 'reports/profit_loss';
$route['transactions'] = 'reports/transactions';
$route['sales-summary'] = 'reports/sales_summary';
$route['items'] = 'inventory';
$route['add-customer'] = 'customer/add';
$route['add-vendor'] = 'vendor/add';
$route['add-user'] = 'users/addUser';
$route['add-template'] = 'template/addTemplate';
$route['add-purchase'] = 'purchase/add_purchase'; 
$route['job-view'] = 'booking/BookingView';
$route['add-package'] = 'packages/addPackage';
$route['jobcards'] = 'booking/index';
$route['service-reminder'] = 'ServiceReminder/index';
$route['add-campaign'] = 'Campaign/addCampaign';
$route['insurance'] = 'insurance';
$route['add-insurance'] = 'insurance/addInsurance';
$route['vehicle'] = 'vehicle';
$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['email'] = 'Sendingemail_Controller';
