<?php
$GLOBALS['title_left'] = '<a onclick="add_new_transaction()" href="javascript:void(0)" class="btn btn-sm btn-success btn-outline btn-1e pull-right"><i class="fa fa-plus"></i> Add Transaction</a>
                          <a onclick=window.location="accounts" href="javascript:void(0)" class="btn btn-sm btn-info btn-outline btn-1e pull-right"><i class="fa fa-arrow-left"></i> Back</a>';
?>
<div class="row">
    <div class="col-lg-2">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                    <input type="text" name="duration" class="form-control input-sm customer_list" value="<?php echo $_REQUEST['d']; ?>">
                    <label class="floating-label">Duration</label>
            </div>
    </div>
    <div class="col-lg-2">
            <input type="button" style="margin-top:15px;" class="btn btn-sm btn-info" value="Filter" onclick="filter()">
    </div>
</div>
    <div class="row">
    <div class="col-lg-12">
    <table style="width: 100%;" class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th style="background-color: lavender;text-align: left;">Tx ID.</th>
                        <th style="background-color: lavender;text-align: left;">Date</th>
                        <th style="background-color: lavender;text-align: left;">Type</th>
                        <th style="background-color: lavender;text-align: left;">Due Date</th>
                        <th style="background-color: lavender;text-align: left;">Name</th>
                        <th style="background-color: lavender;text-align: left;">Description</th>
                        <th style="background-color: lavender;text-align: left;">Category</th>
                        <th style="background-color: lavender;text-align: right;">Amount (<i class="fa fa-rupee"></i>)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(count($transactions) > 0) {
                    $total_amount = 0;
                    foreach($transactions as $k => $v) { ?>
                    <tr>
                        <td><?php echo $v['transaction_id']; ?></td>
                        <td><?php echo date("d-m-Y",strtotime($v['date'])); ?></td>
                        <td><?php echo str_replace('_',' ',ucwords($v['transaction_type'],'_')); ?></td>
                        <td><?php echo $v['due_date'] != "0000-00-00" ? date("d-m-Y",strtotime($v['due_date'])) : ''; ?></td>
                        <td><?php echo $v['name'] != "" ? strlen($v['name']) > 25 ? substr($v['name'],0,25).'...' : $v['name'] : $v['company_name']; ?></td>
                        <td><?php echo $v['description']; ?></td>
                        <td><?php echo $v['name']; ?></td>
                        <td style="text-align:right;"></td>
                    </tr>
                    <?php } ?>
                    <tr>
                            <td style="text-align:right;font-weight:bold;" colspan="7">Balance</td>
                            <td style="text-align:right;font-weight:bold;"><?php echo $total_amount;?></td>
                    </tr>
                    <?php } else { ?>
                        <tr>
                            <td colspan="8">No any transactions found.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
    </div>
</div>
<div id="add_trans_modal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:35%;">
        <div class="modal-content" style="border-radius: 5px !important;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title" id="myModalLabel">Add New Transaction</h4>
            </div>
            <div class="modal-body">
                <form name="transcation_form" id="transcation_form">
                    <table style="width: 100%;">
                        <tr>
                            <td style="vertical-align:bottom;">Transaction Type</td>
                            <td>
                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                <select class="form-control input-sm" name="transaction_type" onchange="onChangeTransType(this.value)">
                                    <option value="bill">Vendor Bill</option>
                                    <option value="bill_payment">Bill Payment</option>
                                    <option value="deposit" >Bank Deposit</option>
                                    <option value="customer_payment">Customer Payment</option>
                                    <option value="other_income">Other Income</option>
                                    <option value="other_expense">Other Expense</option>
                                </select>
                            </div>
                            </td>
                            <td></td>
                        </tr>
                        <tr class="customer_tr">
                            <td style="vertical-align:bottom;">Customer</td>
                            <td>
                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                <select class="form-control input-sm" name="customer_id" id="customer_id">
                                        <option value="">Select</option>
                                         <?php foreach ($customers as $k => $v) { ?>
                                        <option value="<?php echo $v['customer_id'].'_'.$v['vehicle_id']; ?>"><?php echo $v['name'].'-'.$v['reg_no']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            </td>
                            <td style="cursor:pointer;width:5%;text-align: right;vertical-align: bottom;"><i class="fa fa-plus" onclick="add_customer()"></i></td>
                        </tr>
                        <tr class="vendor_tr">
                            <td style="vertical-align:bottom;">Vendor</td>
                            <td>
                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                <select class="form-control input-sm" name="vendor_id" id="vendor_id">
                                        <option value="">Select</option>
                                         <?php foreach ($vendors as $k => $v) { ?>
                                        <option value="<?php echo $v['vendor_id'] ?>"><?php echo $v['company_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            </td>
                            <td style="cursor:pointer;width:5%;text-align: right;vertical-align: bottom;"><i class="fa fa-plus" onclick="add_vendor()"></i></td>
                        </tr>
                        <tr id="category_tr" style="display:none;">
                            <td style="vertical-align:bottom;">Category</td>
                            <td>
                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                <select class="form-control input-sm" name="category_id">
                                     <option value="">Select</option>
                                    <?php foreach ($category as $k => $v) { ?>
                                      <option data-type="<?php echo $v['transaction_type']; ?>" value="<?php echo $v['category_id'] ?>"><?php echo $v['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            </td>
                            <td style="cursor:pointer;width:5%;text-align: right;vertical-align: bottom;"><i class="fa fa-plus" onclick="add_category()"></i></td>
                        </tr>
                        <tr>
                            <td style="vertical-align:bottom;">Date</td>
                            <td>
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <input type="text" class="form-control input-sm" name="date" value="<?php echo date('d-m-Y'); ?>">
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="vertical-align:bottom;">Amount (<i class="fa fa-rupee"></i>)</td>
                            <td>
                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                <input type="number" class="form-control input-sm" name="amount">
                            </div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="vertical-align:bottom;">Description</td>
                            <td>
                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                <input type="text" class="form-control input-sm" name="description">
                            </div>
                            </td>
                            <td></td>
                        </tr>
                        <tr class="paid_from_tr">
                            <td class="paid_from_tr_label" style="vertical-align:bottom;">Paid from</td>
                            <td>
                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                <select class="form-control input-sm" name="paid_from" id="paid_from">
                                    <?php foreach ($paid_accounts as $k => $v) { ?>
                                        <option value="<?php echo $v['account_id'] ?>"><?php echo $v['account_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            </td>
                            <td></td>
                        </tr>
                        <tr class="deposit_to_tr">
                            <td class="deposit_to_tr_label" style="vertical-align:bottom;">Deposit to</td>
                            <td>
                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                <select class="form-control input-sm" name="deposit_to" id="deposit_to">
                                    <?php foreach ($deposit_accounts as $k => $v) { ?>
                                        <option value="<?php echo $v['account_id'] ?>"><?php echo $v['account_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            </td>
                            <td></td>
                        </tr>
                        <tr class="APAccount_tr">
                            <td style="vertical-align:bottom;">A/P Account</td>
                            <td>
                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                <select class="form-control input-sm" name="ap_account">
                                    <?php foreach ($ap_account as $k => $v) { ?>
                                        <option value="<?php echo $v['account_id'] ?>"><?php echo $v['account_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            </td>
                            <td></td>
                        </tr>
                        <tr class="ARAccount_tr">
                            <td style="vertical-align:bottom;">A/R Account</td>
                            <td>
                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                <select class="form-control input-sm" name="ar_account">
                                    <?php foreach ($ar_account as $k => $v) { ?>
                                        <option value="<?php echo $v['account_id'] ?>"><?php echo $v['account_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="vertical-align:bottom;">Due Date</td>
                            <td>
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <input type="text" class="form-control input-sm" name="due_date">
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: right;">
                                <br>
                                <button type="button" onclick="save_transcation()" class="btn btn-sm btn-info"><i class="fa fa-save"></i> Save Transcation</button>
                            </td>
                            <td></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="add_category_modal" class="modal fade" role="dialog">
<div class="modal-dialog modal-lg" style="width:40%;">
        <div class="modal-content" style="border-radius: 5px !important;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title">Category</h4>
            </div>
            <div class="modal-body" style="padding: 0px 10px 10px 10px;">
                <div class="row">
                    <div class="col-lg-5">
                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                <input type="text" class="form-control input-sm" placeholder="Category Name" name="category_name">
                            </div>
                    </div>
                    <div class="col-lg-5">
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                        <select class="form-control input-sm" name="trans_type">
                            <option value="Income">Income</option>
                            <option value="Expense">Expense</option>
                        </select>
                    </div>
                    </div>
                    <div class="col-lg-2"><br>
                    <button type="button" onclick="save_category()" class="btn btn-sm btn-info">Save</button>
                    </div>
                </div>
                <div class="row">
                   <div class="col-lg-12">
                      <table class="table table-bordered" id="category_list">
                        <thead>
                        <tr>
                           <th>Name</th>
                           <th>Type</th>
                           <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                           <?php 
                           if(!empty($category)) {
                           foreach ($category as $k => $v) { ?>
                           <tr>
                             <td><?php echo $v['name']; ?></td>
                             <td><?php echo $v['transaction_type']; ?></td>
                             <td>
                                <a class="btn btn-xs btn-danger" href="javascript:void(0);" onclick="delete_category(<?php echo $v['category_id']; ?>)"><i class="fa fa-trash"></i></a>
                             </td>
                           </tr>
                           <?php } } else { ?>
                            <tr>
                                <td colspan="3">No category founds</td>
                            </tr>
                           <?php } ?>
                        </tbody>
                      </table>
                   </div>
                </div>
            </div>
        </div>
</div>
</div>
<div id="add_customer_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 5px !important;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title">Add Customer</h4>
            </div>
            <div class="modal-body" style="padding: 0px 10px 10px 10px;">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                                        <form class="form-material form" id="add_customer_form" method="post">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                                    <input type="text" class="form-control input-sm" name="first_name" required>
                                                    <label class="floating-label required">First Name</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                                    <input type="text" class="form-control input-sm" name="last_name">
                                                    <label class="floating-label">Last Name</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                                    <input type="text" class="form-control input-sm" name="mobile_no" required>
                                                    <label class="floating-label required">Mobile</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                                    <input type="text" class="form-control input-sm" name="email">
                                                    <label class="floating-label">Email</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                                    <input type="text" class="form-control input-sm" name="address_1">
                                                    <label class="floating-label">Address Line 1</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                                    <input type="text" class="form-control input-sm" name="address_2">
                                                    <label class="floating-label">Address Line 2</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                                    <select class="form-control input-sm" name="state" id="state_drp">
                                                    <option value="*">Select</option>
                                                    <?php foreach ($state as $st) { ?>
                                                        <option <?php if($_SESSION['setting']->default_state == $st['id']) { echo 'selected'; } ?> value="<?php echo $st['id'] ?>"><?php echo $st['name'] ?></option>
                                                    <?php } ?>
                                                    </select>
                                                    <label class="floating-label">Select State</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                                    <select class="form-control input-sm" name="city" id="city_drp">
                                                    <option value="*">Select</option>
                                                    <?php foreach ($city as $ct) { ?>
                                                        <option <?php if($_SESSION['setting']->default_city == $ct['id']) { echo 'selected'; } ?> value="<?php echo $ct['id'] ?>"><?php echo $ct['name'] ?></option>
                                                    <?php } ?>
                                                    </select>
                                                    <label class="floating-label">Select City</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                                    <input type="text" class="form-control input-sm" name="pincode">
                                                    <label class="floating-label">Pincode</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                                    <input type="text" class="form-control input-sm" name="home_phone">
                                                    <label class="floating-label">Home Phone</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                                    <input type="text" class="form-control input-sm" name="work_phone">
                                                    <label class="floating-label">Work Phone</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                                    <input type="text" class="form-control input-sm" name="notes">
                                                    <label class="floating-label">Notes</label>
                                                </div>
                                            </div>
                                        </div>
                                        </form>
                                        <div style="border-top: 1px solid lightgray;border-bottom: 1px solid lightgray;padding: 5px;background-color: aliceblue;">
                                            <span><i class="fa fa-car" aria-hidden="true"></i> Vehicle details</span>
                                        </div>
                                        <form id="add_vehicle_form" name="add_vehicle_form">
                                        <div class="row vehicle_div">
                                            <div class="col-lg-12 col-sm-12">
                                                <table style="width: 100%;" class='vehicle_details'>
                                                    <tr>
                                                        <td style="width: 20%;">
                                                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                                                <select class="form-control input-sm make_drp" name="vehicle[0][make_id]" id="make_1">
                                                                    <option value="*">Select</option>
                                                                    <?php foreach ($make as $mk) { ?>
                                                                        <option value="<?php echo $mk['make_id'] ?>"><?php echo $mk['name'] ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                                <label class="floating-label required">Select Make</label>
                                                            </div>
                                                        </td>
                                                        <td style="width: 20%;">
                                                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                                                <select class="form-control input-sm model_drp" name="vehicle[0][model_id]" id="model_1">
                                                                    <option value="*">Select</option>
                                                                </select>
                                                                <label class="floating-label required">Select Model</label>
                                                            </div>
                                                        </td>
                                                        <td style="width: 20%;">
                                                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                                                <select class="form-control input-sm" name="vehicle[0][variant_id]" id="variant_1">
                                                                    <option value="*">Select</option>
                                                                </select>
                                                                <label class="floating-label">Select Variant</label>
                                                            </div>
                                                            
                                                        </td>
                                                        <td style="width: 20%;">
                                                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                                                <input type="text" class="form-control input-sm" name="vehicle[0][reg_no]" id="reg_1" onblur="check_duplication('tbl_customer_vehicle','reg_no',this,'vehicle_id',0,'Reg No. is already exists. please enter another !')">
                                                                <label class="floating-label required">Vehicle No.</label>
                                                            </div>
                                                        </td>
                                                        <td style="width: 15%;">
                                                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                                                <input type="text" class="form-control input-sm" name="vehicle[0][year]" id="year_1">
                                                                <label class="floating-label">Year</label>
                                                            </div>
                                                        </td>
                                                        <!-- <td style="width: 5%;">
                                                            <button type="button" class="btn btn-primary btn-xs" onclick="add_vehicle()">+</button>
                                                        </td> -->
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6"></div>
                                            <div class="col-md-6" style="text-align: right;">
                                                <button type="button" class="btn btn-success btn-sm waves-effect waves-classic pull-right" onclick="save_customer_details()"><i class="fa fa-save"></i> Save</button>
                                                <button type="button" class="btn btn-warning btn-sm waves-effect waves-classic pull-right" data-dismiss="modal" aria-hidden="true">Close</button>
                                            </div>
                                        </div>
                                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<div id="add_vendor_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 5px !important;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title">Add Vendor</h4>
            </div>
            <div class="modal-body" style="padding: 0px 10px 10px 10px;">
            <form class="form-material form" id="add_vendor_form" method="post">
            <div class="row">
                <div class="col-lg-3">
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                        <input type="text" class="form-control input-sm" name="company_name" autocomplete="off">
                        <label class="floating-label required">Vendor/Company Name</label>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                        <input type="text" class="form-control input-sm" name="address1" autocomplete="off">
                        <label class="floating-label">Address 1</label>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                        <input type="text" class="form-control input-sm" name="address2" autocomplete="off">
                        <label class="floating-label">Address 2</label>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                        <select class="form-control input-sm" name="state" id="state_drp_vendor">
                            <option value="*">Select</option>
                            <?php foreach ($state as $st) { ?>
                                <option <?php if($_SESSION['setting']->default_state == $st['id']) { echo 'selected'; } ?> value="<?php echo $st['id'] ?>"><?php echo $st['name'] ?></option>
                            <?php } ?>
                        </select>
                        <label class="floating-label">Select State</label>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                        <select class="form-control input-sm" name="city" id="city_drp_vendor">
                            <option value="*">Select</option>
                            <?php foreach ($city as $ct) { ?>
                                <option <?php if($_SESSION['setting']->default_city == $ct['id']) { echo 'selected'; } ?> value="<?php echo $ct['id'] ?>"><?php echo $ct['name'] ?></option>
                            <?php } ?>
                        </select>
                        <label class="floating-label">Select City</label>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                        <input type="text" class="form-control input-sm" name="pincode" autocomplete="off" value="<?php if(isset($_SESSION['setting']->default_pincode) && $_SESSION['setting']->default_pincode != 0) { echo $_SESSION['setting']->default_pincode; } ?>">
                        <label class="floating-label">Pincode</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                        <input type="text" class="form-control input-sm" name="account_no" autocomplete="off">
                        <label class="floating-label">Account No.</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                        <input type="text" class="form-control input-sm" name="ifsc_code" autocomplete="off">
                        <label class="floating-label">IFSC Code</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                        <input type="text" class="form-control input-sm" name="email" autocomplete="off">
                        <label class="floating-label">Email</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                        <select class="form-control input-sm" name="payment_term" id="vendor_payment_term">
                            <option value="0">Due on receipt</option>
                            <option value="7">7 Days</option>
                            <option value="14">14 Days</option>
                            <option value="20">20 Days</option>
                            <option value="21">21 Days</option>
                            <option value="30">30 Days</option>
                            <option value="45">45 Days</option>
                            <option value="60">60 Days</option>
                            <option value="90">90 Days</option>
                        </select>
                        <label class="floating-label">Payment Terms</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                        <input type="text" class="form-control input-sm" name="notes" autocomplete="off" value="<?php if (isset($vendor['notes'])) {
                                                                                                                    echo $vendor['notes'];
                                                                                                                } ?>">
                        <label class="floating-label">Notes</label>
                    </div>
                </div>
            </div>
            </form>
            <div style="border-top: 1px solid lightgray;border-bottom: 1px solid lightgray;padding: 5px;background-color: aliceblue;">
                <span><i class="fa fa-user" aria-hidden="true"></i> Contact Person details</span>
            </div>
            <form class="form-material form" id="add_vendor_contact_form" method="post">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                        <input type="text" class="form-control input-sm" name="vendor_contact[0][person_name]" id="vendor_contact_name_1" autocomplete="off">
                        <label class="floating-label required">Contact Person 1</label>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                        <input type="text" class="form-control input-sm" name="vendor_contact[0][contact_no]" id="vendor_contact_no_1" autocomplete="off">
                        <label class="floating-label required">Contact No.</label>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                        <input type="text" class="form-control input-sm" name="vendor_contact[0][position]" autocomplete="off">
                        <label class="floating-label">Position</label>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                        <input type="text" class="form-control input-sm" name="vendor_contact[0][email]" autocomplete="off">
                        <label class="floating-label">Email</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                        <input type="text" class="form-control input-sm" name="vendor_contact[1][person_name]" autocomplete="off">
                        <label class="floating-label">Contact Person 2</label>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                        <input type="text" class="form-control input-sm" name="vendor_contact[1][contact_no]" autocomplete="off">
                        <label class="floating-label">Contact No.</label>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                        <input type="text" class="form-control input-sm" name="vendor_contact[1][position]" autocomplete="off">
                        <label class="floating-label">Position</label>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                        <input type="text" class="form-control input-sm" name="vendor_contact[1][email]" autocomplete="off">
                        <label class="floating-label">Email</label>
                    </div>
                </div>
            </div>
            <div class="row" style="text-align: right;">
                <div class="col-md-12">
                    <button type="button" onclick="save_vendor_details()" class="btn btn-sm btn-success btn-outline btn-1e"><i class="fa fa-save"></i> Save</button>
                    <button type="button" class="btn btn-warning btn-sm waves-effect waves-classic pull-right" data-dismiss="modal" aria-hidden="true">Close</button>
                </div>
            </div>
            </form>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="date_range" value="<?php echo $_REQUEST['d']; ?>">
<input type="hidden" id="type_id" value="<?php echo $_REQUEST['type']; ?>">
<style>
form#add_new_account_form .form-material.floating {
    margin-top: 0px;
    margin-bottom: 0px ;
}
form#transcation_form .form-material.floating {
    margin-top: 0px;
    margin-bottom: 0px ;
}
.select2-container--default .select2-results__option[aria-disabled=true] {
    display: none;
}
</style>
<script>
    function filter() {
        window.location.href = 'transactions?type='+$('#type_id').val()+'&d='+$('input[name=duration]').val();
    }
    $(document).on('change', 'select.make_drp', function() {
            var id = $(this).attr('id').split('_');
            var make = this.value;
            $('#model_' + id[1]).val('');
            $.ajax({
                method: 'GET',
                url: 'customer/getModelByMake?make_id=' + make,
                success: function(result) {
                    var option_html = '<option value="">Select Model</option>';
                    $(result).each(function(i, v) {
                        option_html += '<option value="' + v.model_id + '">' + v.name + '</option>';
                    });
                    $('#model_' + id[1]).html(option_html);
                }
            });
    });
    $(document).on('change', 'select.model_drp', function() {
            var id = $(this).attr('id').split('_');
            var model = this.value;
            $('#variant_' + id[1]).val('');
            $.ajax({
                method: 'GET',
                url: 'customer/getVariantByModel?model_id=' + model,
                success: function(result) {
                    var option_html = '<option value="">Select Variant</option>';
                    $(result).each(function(i, v) {
                        option_html += '<option value="' + v.variant_id + '">' + v.name + '</option>';
                    });
                    $('#variant_' + id[1]).html(option_html);
                }
            });
    });
    function add_vehicle() {
        var make_select = $('select#make_1').html();
        var cnt = $(".vehicle_div").length + 1;
        var html = "<div class='row vehicle_div' id='row_"+cnt+"'>";
        html += "<div class='col-lg-12 col-sm-12'>";
        html += "<table style='width:100%;' class='vehicle_details'>";
        html += "<tr>";
        html += "<td style='width: 20%;'>";
        html += "<div class='form-group form-material floating' data-plugin='formMaterial'>";
        html += "<select class='form-control input-sm make_drp' name='vehicle["+cnt+"][make]' id='make_" + cnt + "'>";
        html += make_select;
        html += "</select>";
        html += "<label class='floating-label required'>Select Make</label>";
        html += "</div>";
        html += "</td>";
        html += "<td style='width: 20%;'>";
        html += "<div class='form-group form-material floating' data-plugin='formMaterial'>";
        html += "<select class='form-control input-sm model_drp' name='vehicle["+cnt+"][model]' id='model_" + cnt + "'>";
        html += "<option value=''>Select</option>";
        html += "</select>";
        html += "<label class='floating-label required'>Select Model</label>";
        html += "</div>";
        html += "</td>";
        html += "<td style='width: 20%;'>";
        html += "<div class='form-group form-material floating' data-plugin='formMaterial'>";
        html += "<select class='form-control input-sm' name='vehicle["+cnt+"][variant]' id='variant_" + cnt + "'>";
        html += "<option value=''>Select</option>";
        html += "</select>";
        html += "<label class='floating-label'>Select Variant</label>";
        html += "</div>";
        html += "</td>";
        html += "<td style='width: 20%;'>";
        html += "<div class='form-group form-material floating' data-plugin='formMaterial'>";
        html += "<input type='text' class='form-control input-sm' name='vehicle["+cnt+"][reg_no]' id='reg_" + cnt + "' onblur=check_duplication('tbl_customer_vehicle','reg_no',this,'vehicle_id',0,'Reg No. is already exists. please enter another.')>";
        html += "<label class='floating-label required'>Vehicle No.</label>";
        html += "</div>";
        html += "</td>";
        html += "<td style='width: 15%;'>";
        html += "<div class='form-group form-material floating' data-plugin='formMaterial'>";
        html += "<input type='text' class='form-control input-sm' name='vehicle["+cnt+"][year]' id='year_" + cnt + "'>";
        html += "<label class='floating-label'>Year</label>";
        html += "</div>";
        html += "</td>";
        html += "<td style='width: 5%;'>";
        html += "<i class='btn btn-primary btn-xs fa fa-trash' onclick='removeVehicleRow("+cnt+")'></i>";
        html += "</td>";
        html += "</tr>";
        html += "</table>";
        html += "</div>";
        $('.vehicle_div:last').after(html);
        $('select').select2();
    }
    function removeVehicleRow(row_id) {
        $('#row_'+row_id).remove();
    }
    function save_customer_details() {
        if($('input[name=first_name]').val() == "") {
            toastr.warning("Customer firstname is required !");
            $('input[name=first_name]').focus();
            return false;
        } else if($('input[name=mobile_no]').val() == "") {
            toastr.warning("Customer mobile no is required !");
            $('input[name=mobile_no]').focus();
            return false;
        }
        var is_valid = 'Y';
        var at_least_one = 'N';
        $('table.vehicle_details tbody tr').each(function(vehicle) {
            var make = $(this).find("td:eq(0) select").val();
            var model = $(this).find("td:eq(1) select").val();
            var variant = $(this).find("td:eq(2) select").val();
            var reg_no = $(this).find("td:eq(3) input").val();
            if((make != "*") && (make == "*" || model == "*" || reg_no =="")) {
                is_valid = 'N';
            }
            if((make != "*" && model != "*" && reg_no !="")) {
                at_least_one = 'Y';
            }
        });
        if(at_least_one == 'N') {
            toastr.warning("Please select at-lease one vehicle detail properly !");
            return false;
        }
        if(is_valid == 'N') {
              toastr.warning("Please select correct vehicle details");
              return false;
        }
        
        $.ajax({
            method: 'POST',
            url: 'Transcation/InsertOperation',
            data: {'table_name': 'tbl_customer_ajax','customer_detail': $('#add_customer_form').serialize(),'vehicle_detail': $('#add_vehicle_form').serialize()},
            success: function(result) {
                    var returnJSON = JSON.parse(result);
                    var data = JSON.parse(returnJSON['data']);
                    if(returnJSON && returnJSON['status'] == 200) {
                    if ($("#customer_id").find("option[value='" + data['customer_id'] + "']").length) {
                        $("#customer_id").val(data['customer_id']).trigger("change");
                    } else {
                        var newOpt = new Option(data['customer'],data['id'], true, true);
                        $("#customer_id").append(newOpt).trigger('change');
                    }
                    toastr.success("Customer created successfully !");
                    $('#add_customer_modal').modal('hide');
                }
            }
        });
    }
    function save_vendor_details() {
        if($('input[name=company_name]').val() == "") {
            toastr.warning("Vendor/Company name is required.");
            $('input[name=company_name]').focus();
            return false;
        } else if($("#vendor_contact_name_1").val() == "") {
            toastr.warning("Name of contact person 1 is required.");
            $("#vendor_contact_name_1").focus();
            return false;
        }  else if($("#vendor_contact_no_1").val() == "") {
            toastr.warning("Contact no of contact person 1 is required.");
            $("#vendor_contact_no_1").focus();
            return false;
        }
        $.ajax({
            method: 'POST',
            url: 'Transcation/InsertOperation',
            data: {'table_name': 'tbl_vendor_ajax','vendor_detail': $('#add_vendor_form').serialize(),'vendor_contacts': $('#add_vendor_contact_form').serialize()},
            success: function(result) {
                var returnJSON = JSON.parse(result);
                var data = JSON.parse(returnJSON['data']);
                if(returnJSON && returnJSON['status'] == 200) {
                if ($("#vendor_id").find("option[value='" + data['vendor_id'] + "']").length) {
                    $("#vendor_id").val(data['vendor_id']).trigger("change");
                } else {
                    var newOpt = new Option(data['company_name'],data['vendor_id'], true, true);
                    $("#vendor_id").append(newOpt).trigger('change');
                }
                toastr.success("Vendor created successfully !");
                $('#add_vendor_modal').modal('hide');
                }
            }
        });
    }
    function add_vendor() {
        $('#add_vendor_modal').modal('show');
        setTimeout(function() {
            $('input[name=company_name]').focus();
        },500);
    }
    function add_category() {
        $('#add_category_modal').modal('show');
        // setTimeout(function() {
        //     $('input[name=company_name]').focus();
        // },500);
    }
    function add_customer() {
        $('#add_customer_modal').modal('show');
        setTimeout(function() {
            $('input[name=first_name]').focus();
        },500);
    }
    function save_transcation() {
        if($('input[name=date]').val() == "") {
            toastr.warning('Transaction date is required !');
            $('input[name=date]').focus();
            return false;
        } else if($('input[name=amount]').val() == "") {
            toastr.warning('Amount  is required !');
            $('input[name=amount]').focus();
            return false;
        } else if($('select[name=transaction_type]').val() == 'bill' && $('select[name=vendor_id]').val() == "") {
            toastr.warning('Vendor is required for vendor bill transaction !');
            return false;
        } else if($('select[name=transaction_type]').val() == 'bill' && $('select[name=ap_account]').val() == "") {
            toastr.warning('A/P account required for vendor bill transaction !');
            return false;
        } else if($('select[name=transaction_type]').val() == 'customer_payment' && $('select[name=customer_id]').val() == "") {
            toastr.warning('Customer is required for customer payment transaction !');
            return false;
        } else if(($('select[name=transaction_type]').val() == 'other_income' || $('select[name=transaction_type]').val() == 'other_expense')  && $('select[name=category_id]').val() == null) {
            toastr.warning('Category is required !');
            return false;
        }

        if($('select[name=transaction_type]').val() == 'bill') {
            $("select[name=ar_account]").val("");
            $("select[name=deposit_to]").val("");
            $("select[name=paid_from]").val("");
        } else if($('select[name=transaction_type]').val() == 'bill_payment') {
            $("select[name=ar_account]").val("");
            $("select[name=deposit_to]").val("");
        } else if($('select[name=transaction_type]').val() == 'customer_payment') {
            $("select[name=ap_account]").val("");
            $("select[name=paid_from]").val("");
        } else if($('select[name=transaction_type]').val() == 'other_income') {
            $("select[name=ap_account]").val("");
            $("select[name=ar_account]").val("");
            $("select[name=paid_from]").val("");
        } else if($('select[name=transaction_type]').val() == 'other_expense') {
            $("select[name=ap_account]").val("");
            $("select[name=ar_account]").val("");
            $("select[name=deposit_to]").val("");
        } else if($('select[name=transaction_type]').val() == 'deposit') {
            $("select[name=ar_account]").val("");
            $("select[name=ap_account]").val("");
        }

        $.ajax({
            method: 'POST',
            url: 'Transcation/InsertOperation',
            data: {'table_name': 'tbl_transaction','data': $('#transcation_form').serialize()},
            success: function(res) {
                var result = JSON.parse(res);
                if(result && result['status'] == 200) {
                    toastr.success("Transaction created successfully !");
                    setTimeout(function() {
                       window.location.reload();     
                    },1000);
                }
            }
        });
    }
    function delete_category(category_id) {
        $.ajax({
            method: 'POST',
            url: 'Transcation/InsertOperation',
            data: {'table_name': 'tbl_category','category_id': category_id,'action':'delete'},
            success: function(result) {
                var returnJSON = JSON.parse(result);
                var data = JSON.parse(returnJSON['data']);
                if(returnJSON && returnJSON['status'] == 200) {
                    toastr.success("Category deleted successfully !");
                }
                if(data) {
                    var category_table = '';
                    data['category_list'].forEach(element => {
                        category_table += '<tr>';
                        category_table += '<td>'+element.name+'</td>';
                        category_table += '<td>'+element.transaction_type+'</td>';
                        category_table += '<td>';
                        category_table += '<a class="btn btn-xs btn-danger" href="javascript:void(0);" onclick="delete_category('+element.category_id+')"><i class="fa fa-trash"></i></a>';
                        category_table += '</td>';
                        category_table += '</tr>';
                    });
                    $('table#category_list tbody').html(category_table);
                }
            }
        });
    }
    function save_category() {
        if($('input[name=category_name]').val() =="") {
            toastr.success("Category name is required !");
            return false;
        }
        
        $.ajax({
            method: 'POST',
            url: 'Transcation/InsertOperation',
            data: {'table_name': 'tbl_category','name': $('input[name=category_name]').val(),'transaction_type': $('select[name=trans_type]').val(),'action':'insert'},
            success: function(result) {
                var returnJSON = JSON.parse(result);
                var data = JSON.parse(returnJSON['data']);
                if(returnJSON && returnJSON['status'] == 200) {
                    toastr.success("Category saved successfully !");
                }
                if(data) {
                    var category_table = '';
                    var category_drp = '';
                    data['category_list'].forEach(element => {
                        category_table += '<tr>';
                        category_table += '<td>'+element.name+'</td>';
                        category_table += '<td>'+element.transaction_type+'</td>';
                        category_table += '<td>';
                        category_table += '<a class="btn btn-xs btn-danger" href="javascript:void(0);" onclick="delete_category('+element.category_id+')"><i class="fa fa-trash"></i></a>';
                        category_table += '</td>';
                        category_table += '</tr>';
                    });
                    $('table#category_list tbody').html(category_table);
                    if ($("select[name=category_id]").find("option[value='" + data['added_category']['category_id'] + "']").length) {
                        $("select[name=category_id]").val(data['added_category']['category_id']).trigger("change");
                    } else {
                        var newOpt = new Option(data['added_category']['name'],data['added_category']['category_id'], false, false);
                        $("select[name=category_id]").append(newOpt).trigger('change');
                    }
                    $("select[name=category_id] option[value="+data['added_category']['category_id']+"]").attr('data-type',data['added_category']['transaction_type']);
                    if($('select[name=transaction_type]').val() == 'other_income' && data['added_category']['transaction_type'] != 'Income') {
                        $("select[name=category_id] option[value="+data['added_category']['category_id']+"]").prop("disabled", true);
                    }
                    if($('select[name=transaction_type]').val() == 'other_expense' && data['added_category']['transaction_type'] != 'Expense') {
                        $("select[name=category_id] option[value="+data['added_category']['category_id']+"]").prop("disabled", true);
                    }
                }
                $('#add_category_modal').modal('hide');
            }
        });
    }
    function onChangeTransType(value) {
        $("select[name=category_id]").val("").trigger("change");
        $("select[name=customer_id]").val("").trigger("change");
        $("select[name=vendor_id]").val("").trigger("change");

        if(value == 'bill') {
           $('.paymenttype_tr').css('display','none');
           $('#category_tr').css('display','none');
           $('.vendor_tr').css('display','table-row');
           $('.customer_tr').css('display','none');
           $('.paid_from_tr').css('display','none');
           $('.deposit_to_tr').css('display','none');
           $('.APAccount_tr').css('display','table-row');
           $('.ARAccount_tr').css('display','none');
        } else if(value == 'bill_payment') {
           $('.paymenttype_tr').css('display','none');
           $('#category_tr').css('display','none');
           $('.vendor_tr').css('display','table-row');
           $('.customer_tr').css('display','none');
           $('.paid_from_tr').css('display','table-row');
           $('.deposit_to_tr').css('display','none');
           $('.APAccount_tr').css('display','table-row');
           $('.ARAccount_tr').css('display','none');
        } else if(value == 'deposit') {
           $('.paymenttype_tr').css('display','none');
           $('#category_tr').css('display','none');
           $('.vendor_tr').css('display','none');
           $('.customer_tr').css('display','none');
           $('.paid_from_tr').css('display','table-row');
           $('.deposit_to_tr').css('display','table-row');
           $('.APAccount_tr').css('display','none');
           $('.ARAccount_tr').css('display','none');
        } else if(value == 'customer_payment') {
           $('.paymenttype_tr').css('display','table-row');
           $('#category_tr').css('display','none');
           $('.vendor_tr').css('display','none');
           $('.customer_tr').css('display','table-row');
           $('.paid_from_tr').css('display','none');
           $('.deposit_to_tr').css('display','table-row');
           $('.APAccount_tr').css('display','none');
           $('.ARAccount_tr').css('display','table-row');
        } else if(value == 'other_income') {
           $('.paymenttype_tr').css('display','none');
           $('#category_tr').css('display','table-row');
           $('.vendor_tr').css('display','none');
           $('.customer_tr').css('display','table-row');
           $('.paid_from_tr').css('display','none');
           $('.deposit_to_tr').css('display','table-row');
           $('.APAccount_tr').css('display','none');
           $('.ARAccount_tr').css('display','none');
        } else if(value == 'other_expense') {
           $('.paymenttype_tr').css('display','table-row');
           $('#category_tr').css('display','table-row');
           $('.vendor_tr').css('display','table-row');
           $('.customer_tr').css('display','none');
           $('.paid_from_tr').css('display','table-row');
           $('.deposit_to_tr').css('display','none');
           $('.APAccount_tr').css('display','none');
           $('.ARAccount_tr').css('display','none');
        }

        if(value == 'other_income' || value == 'other_expense') {
            $('select[name=category_id]').find("option").each(function() {
                if(value == 'other_income' && $(this).data('type') == 'Income') {
                    $(this).prop('disabled',false);
                } else if(value == 'other_expense' && $(this).data('type') == 'Expense') {
                    $(this).prop('disabled',false);
                } else {
                    $(this).prop('disabled',true);
                }
            });
        }
        $('select[name=ap_account] option').length == 2  ? $("select[name=ap_account]").select2("val",$("select[name=ap_account] option:last").val()) : '';
        $('select[name=ar_account] option').length == 2  ? $("select[name=ar_account]").select2("val",$("select[name=ar_account] option:last").val()) : '';

    }
    function add_new_transaction() {
        $("#add_trans_modal").modal('show');
    }
    var intial_load = 0;
    $(document).ready(function() {
        $(document).on('change', '#state_drp_vendor', function() {
            if (this.value != "") {
                $.ajax({
                    type: 'GET',
                    url: 'CountryStateCity/getCityByState?state_id=' + this.value,
                    success: function(result) {
                        $('#city_drp_vendor').val();
                        var html = '<option value="">Select</option>';
                        $.each(result, function(i, v) {
                            html += '<option value=' + v.id + '>' + v.name +
                                '</option>';
                        });
                        $('#city_drp_vendor').html(html);
                    }
                });
            } else {
                $('#city_drp_vendor').html('<option value="">Select</option>');
                $('#city_drp_vendor').val();
            }
        });
        $(document).on('change', '#state_drp', function() {
            if (this.value != "") {
                $.ajax({
                    type: 'GET',
                    url: 'CountryStateCity/getCityByState?state_id=' + this.value,
                    success: function(result) {
                        $('#city_drp').val();
                        var html = '<option value="">Select</option>';
                        $.each(result, function(i, v) {
                            html += '<option value=' + v.id + '>' + v.name +
                                '</option>';
                        });
                        $('#city_drp').html(html);
                    }
                });
            } else {
                $('#city_drp').html('<option value="">Select</option>');
                $('#city_drp').val();
            }
        });
        onChangeTransType('bill');
        $('input[name=as_on_date]').datepicker({
            format: 'dd-mm-yyyy',
            autoclose:true
        });
        $('input[name=date]').datepicker({
            format: 'dd-mm-yyyy',
            autoclose:true
        });
        $('input[name=due_date]').datepicker({
            format: 'dd-mm-yyyy',
            autoclose:true
        });
        var currentMonth = new Date().getMonth();
        var financialYearStartMonth = 3;
        var financialYearStartDate = moment().month(financialYearStartMonth).startOf('month');
        if (currentMonth < financialYearStartMonth) {
            financialYearStartDate = financialYearStartDate.subtract(1, 'year');
        }
        $('input[name=duration]').daterangepicker({
            locale: {
                format: 'DD-MM-YYYY'
            },
            ranges: {
                "All Time": [],
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'This Financial Year': [moment(financialYearStartDate), moment()]
            },
            startDate: moment(financialYearStartDate),
            endDate: moment()
        });
        <?php if(isset($_REQUEST['d']) && $_REQUEST['d'] != "") { ?>
            var dates = $('#date_range').val().split(" - ");
            $("input[name=duration]").data('daterangepicker').setStartDate(dates[0]);
            $("input[name=duration]").data('daterangepicker').setEndDate(dates[1]);
        <?php } ?>
    });
</script>