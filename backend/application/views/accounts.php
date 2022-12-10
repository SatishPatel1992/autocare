<div class="row">
    <div class="col-lg-2">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                    <input type="text" name="duration" class="form-control input-sm customer_list" value="<?php echo $_REQUEST['d']; ?>">
                    <label class="floating-label">Duration</label>
            </div>
    </div>
    <div class="col-lg-2" id="vendor_select">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <select id="vendor_id" class="form-control input-sm">
                <option value="*">Select All</option>
                <?php foreach($vendors as $k => $v) { ?>
                    <option <?php if($_REQUEST['vd'] && $_REQUEST['vd'] == $v['vendor_id']) { echo "selected"; } ?> value="<?php echo $v['vendor_id']; ?>"><?php echo $v['company_name']; ?></option>
                <?php } ?>
                </select>
                <label class="floating-label">Filter by vendor</label><br>
            </div>
    </div>
    <div class="col-lg-2">
            <input type="button" style="margin-top:15px;" class="btn btn-sm btn-info" value="Filter" onclick="filter()">
    </div>
    <div class="col-lg-6" style="text-align:right;font-size:15px;">
        <span style="color:#3f51b5;"> Total Expenses : <i class="fa fa-rupee"></i> <span style="font-weight:bold;" id="total_expenses">0</span></span> | <span style="color:green;"> Paid : <i class="fa fa-rupee"></i> <span style="font-weight:bold;color:green;" id="total_paid">0</span></span> | <span style="color: red;">Payable : <i class="fa fa-rupee"></i> <span style="font-weight:bold;" id="total_balance">0</span></span>
    </div>
</div>
<div class="nav-tabs-horizontal" data-plugin="tabs">
<ul class="nav nav-tabs nav-tabs-line" role="tablist">
    <li role="presentation" class="nav-item active" tab-id="model_tab">
        <a href="#income_tab" class="nav-link active" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-user"></i></span> <span class="hidden-xs">Income</span></a>
    </li>
    <li role="presentation" class="nav-item" tab-id="model_tab">
        <a href="#expenses_tab" class="nav-link" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-user"></i></span> <span class="hidden-xs">Expenses</span></a>
    </li>    
    <li role="presentation" class="nav-item" tab-id="varient_tab">
        <a href="#payments_tab" class="nav-link" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-user"></i></span> <span class="hidden-xs">Payments</span></a>
    </li>
</ul>
<div class="tab-content" style="margin-top:10px;">
<div class="tab-pane active" id="income_tab">
<div class="row" style="text-align:right;">
            <div class="col-lg-12">
                <a data-toggle="modal" href="javascript:void(0)" onclick="add_expense_trans()" class="btn btn-sm btn-info btn-outline btn-1e pull-right"><i class="fa fa-plus"></i> Add Income</a>
            </div>
        </div>
    <div class="row" style="margin-top:10px;">
    <div class="col-lg-12">
    <table style="width: 100%;" class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th style="width:10%;background-color: lavender;text-align: left;">Income Head</th>
                        <th style="width:20%;background-color: lavender;text-align: left;">Received Name</th>
                        <th style="width:10%;background-color: lavender;text-align: left;">Bill No.</th>
                        <th style="width:10%;background-color: lavender;text-align: left;">Bill Date</th>
                        <th style="width:12%;background-color: lavender;text-align: right;">Total Receivable (<i class="fa fa-rupee"></i>)</th>
                        <th style="width:12%;background-color: lavender;text-align: right;">Received Amount (<i class="fa fa-rupee"></i>)</th>
                        <th style="width:10%;background-color: lavender;text-align: right;">Receivable (<i class="fa fa-rupee"></i>)</th>
                        <th style="width:25%;background-color: lavender;text-align: left;">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if(count($expenses) > 0) {
                    $srno=1;
                    foreach($expenses as $k => $v) { ?>
                    <tr>
                        <td><?php 
                            if($v['po_id'] != 0) {
                                echo 'Stock';
                            } else {
                              echo $v['category_name'];  
                            } 
                         ?></td>
                        <td><?php echo $v['company_name']; ?></td>
                        <td><?php echo $v['bill_no']; ?></td>
                        <td><?php 
                            if($v['po_id'] != 0) { 
                                echo date('d-m-Y', strtotime($v['invoice_date'])); 
                            } else { 
                                echo date('d-m-Y', strtotime($v['date'])); 
                            } 
                         ?></td>
                        <td style="text-align:right;"><?php 
                            if($v['po_id'] != 0) {
                                echo $v['grand_total'];
                            } else { 
                                echo $v['amount'];
                            } ?>
                        </td>
                        <td style="text-align:right;"><?php echo $v['payment_made'] ? $v['payment_made'] : 0; ?></td>
                        <td style="text-align:right;"><?php
                            if($v['po_id'] != 0) {
                                echo $v['grand_total'] - $v['payment_made']; 
                            } else {
                                echo $v['amount'] - $v['payment_made']; 
                            }
                         ?></td>
                        <td><?php 
                            if($v['po_id'] != 0) {
                                echo $v['notes'];
                            } else {
                                echo $v['remarks'];
                            }
                          ?></td>
                    </tr>
                    <?php } } else { ?>
                        <tr>
                            <td colspan="8">No any expenses found.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
    </div>
</div>
</div>
<div class="tab-pane" id="expenses_tab">
<div class="row" style="text-align:right;">
            <div class="col-lg-12">
                <a data-toggle="modal" href="javascript:void(0)" onclick="add_expense_trans()" class="btn btn-sm btn-info btn-outline btn-1e pull-right"><i class="fa fa-plus"></i> Add Expense</a>
            </div>
        </div>
    <div class="row" style="margin-top:10px;">
    <div class="col-lg-12">
    <table style="width: 100%;" class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th style="width:10%;background-color: lavender;text-align: left;">Expense Head</th>
                        <th style="width:20%;background-color: lavender;text-align: left;">Vendor Name</th>
                        <th style="width:10%;background-color: lavender;text-align: left;">Bill No.</th>
                        <th style="width:10%;background-color: lavender;text-align: left;">Bill Date</th>
                        <th style="width:12%;background-color: lavender;text-align: right;">Invoice Amount (<i class="fa fa-rupee"></i>)</th>
                        <th style="width:10%;background-color: lavender;text-align: right;">Paid Amount (<i class="fa fa-rupee"></i>)</th>
                        <th style="width:10%;background-color: lavender;text-align: right;">Payable (<i class="fa fa-rupee"></i>)</th>
                        <th style="width:25%;background-color: lavender;text-align: left;">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if(count($expenses) > 0) {
                    $srno=1;
                    foreach($expenses as $k => $v) { ?>
                    <tr>
                        <td><?php 
                            if($v['po_id'] != 0) {
                                echo 'Stock';
                            } else {
                              echo $v['category_name'];  
                            } 
                         ?></td>
                        <td><?php echo $v['company_name']; ?></td>
                        <td><?php echo $v['bill_no']; ?></td>
                        <td><?php 
                            if($v['po_id'] != 0) { 
                                echo date('d-m-Y', strtotime($v['invoice_date'])); 
                            } else { 
                                echo date('d-m-Y', strtotime($v['date'])); 
                            } 
                         ?></td>
                        <td style="text-align:right;"><?php 
                            if($v['po_id'] != 0) {
                                echo $v['grand_total'];
                            } else { 
                                echo $v['amount'];
                            } ?>
                        </td>
                        <td style="text-align:right;"><?php echo $v['payment_made'] ? $v['payment_made'] : 0; ?></td>
                        <td style="text-align:right;"><?php
                            if($v['po_id'] != 0) {
                                echo $v['grand_total'] - $v['payment_made']; 
                            } else {
                                echo $v['amount'] - $v['payment_made']; 
                            }
                         ?></td>
                        <td><?php 
                            if($v['po_id'] != 0) {
                                echo $v['notes'];
                            } else {
                                echo $v['remarks'];
                            }
                          ?></td>
                    </tr>
                    <?php } } else { ?>
                        <tr>
                            <td colspan="8">No any expenses found.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
    </div>
</div>
</div>
<div class="tab-pane" id="payments_tab">
    <div class="row" style="text-align:right;">
            <div class="col-lg-12">
                <a data-toggle="modal" href="javascript:void(0)" data-target="#add_payment_modal" class="btn btn-sm btn-info btn-outline btn-1e pull-right"><i class="fa fa-plus"></i> Add Payment</a>
            </div>
    </div>
    <div class="row" style="margin-top:10px;">
    <div class="col-lg-12">
    <table style="width: 100%;" class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th style="width:15%;background-color: lavender;text-align: left;">Vendor Name</th>
                        <th style="width:10%;background-color: lavender;text-align: left;">Bill No.</th>
                        <th style="width:10%;background-color: lavender;text-align: left;">Bill Date</th>
                        <th style="width:10%;background-color: lavender;text-align: left;">Payment Date</th>
                        <th style="width:12%;background-color: lavender;text-align: right;">Invoice Amount (<i class="fa fa-rupee"></i>)</th>
                        <th style="width:10%;background-color: lavender;text-align: right;">Paid Amount (<i class="fa fa-rupee"></i>)</th>
                        <th style="width:25%;background-color: lavender;text-align: left;">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if(count($payments) > 0) {
                    $srno=1;
                    foreach($payments as $k => $v) { ?>
                    <tr>
                        <td><?php echo $v['company_name']; ?></td>
                        <td><?php echo $v['bill_no']; ?></td>
                        <td><?php echo date('d-m-Y', strtotime($v['invoice_date'])); ?></td>
                        <td><?php echo date('d-m-Y', strtotime($v['payment_date'])); ?></td>
                        <td style="text-align:right;"><?php echo $v['grand_total']; ?></td>
                        <td style="text-align:right;"><?php echo $v['total_paid']; ?></td>
                        <td><?php echo $v['payment_notes']; ?></td>
                    </tr>
                    <?php } } else { ?>
                        <tr>
                            <td colspan="7">No any payments found.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
    </div>
</div>
</div>

</div>
</div>
<div id="add_income_modal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:35%;">
        <div class="modal-content" style="border-radius: 5px !important;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title" id="myModalLabel">Add Income</h4>
            </div>
            <div class="modal-body">
                <form name="income_form" id="income_form">
                    <table style="width: 100%;">
                        <tr>
                            <td style="vertical-align:bottom;">Income Head</td>
                            <td>
                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                <select class="form-control input-sm" name="category_id">
                                    <?php foreach($category as $c) { if($c['head_type'] == 'Income') { ?>
                                        <option value="<?php echo $c['category_id']?>"><?php echo $c['name']; ?></option>
                                    <?php } } ?>
                                </select>
                            </div>
                            </td>
                            <td style="cursor:pointer;width:5%;text-align: right;vertical-align: bottom;"><i class="fa fa-plus" onclick="add_category()"></i></td>
                        </tr>
                        <tr>
                            <td style="vertical-align:bottom;">Received From</td>
                            <td>
                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                <input type="text" class="form-control input-sm" name="received_from">
                            </div>
                            </td>
                            <td></td>
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
                                    <input type="text" class="form-control input-sm" name="amount">
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="vertical-align:bottom;">Remarks</td>
                            <td>
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <input type="text" class="form-control input-sm" name="remarks">
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: right;">
                                <br>
                                <input type="hidden" name="transaction_type" value="expense">
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
<div id="add_expense_modal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:35%;">
        <div class="modal-content" style="border-radius: 5px !important;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title" id="myModalLabel">Add Expense</h4>
            </div>
            <div class="modal-body">
                <form name="expense_form" id="expense_form">
                    <table style="width: 100%;">
                        <tr>
                            <td style="vertical-align:bottom;">Expense Head</td>
                            <td>
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <select class="form-control input-sm" name="category_id">
                                        <?php foreach($category as $c) { if($c['head_type'] == 'Expense') { ?>
                                            <option value="<?php echo $c['category_id']?>"><?php echo $c['name']; ?></option>
                                        <?php } } ?>
                                    </select>
                                </div>
                            </td>
                            <td style="cursor:pointer;width:5%;text-align: right;vertical-align: bottom;"><i class="fa fa-plus" onclick="add_category()"></i></td>
                        </tr>
                        <tr>
                            <td style="vertical-align:bottom;">Vendor</td>
                            <td>
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <select class="form-control input-sm" name="vendor_id">
                                        <?php foreach($vendors as $c) { ?>
                                            <option value="<?php echo $c['vendor_id']?>"><?php echo $c['company_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </td>
                            <td style="cursor:pointer;width:5%;text-align: right;vertical-align: bottom;"><i class="fa fa-plus" onclick="add_vendor()"></i></td>
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
                            <td style="vertical-align:bottom;">Bill No.</td>
                            <td>
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <input type="text" class="form-control input-sm" name="bill_no">
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="vertical-align:bottom;">Amount (<i class="fa fa-rupee"></i>)</td>
                            <td>
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <input type="text" class="form-control input-sm" name="amount">
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="vertical-align:bottom;">Paid (<i class="fa fa-rupee"></i>)</td>
                            <td>
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <input type="text" class="form-control input-sm" name="paid">
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="vertical-align:bottom;">Remarks</td>
                            <td>
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <input type="text" class="form-control input-sm" name="remarks">
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: right;">
                                <br>
                                <input type="hidden" name="transaction_type" value="expense">
                                <button type="button" onclick="save_expense()" class="btn btn-sm btn-info"><i class="fa fa-save"></i> Save Transcation</button>
                            </td>
                            <td></td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="add_payment_modal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width:100%;">
        <div class="modal-content" style="border-radius: 5px !important;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title" id="myModalLabel">Add Payment</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-4">
                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <select class="form-control input-sm" name="vendor_id" onchange="getVendorBillDetails(this.value)">
                                        <option value="*">Select</option>
                                        <?php foreach($vendors as $c) { ?>
                                            <option value="<?php echo $c['vendor_id']?>"><?php echo $c['company_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <label class="floating-label required">Select Vendor</label>
                            </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                    <table style="width: 100%;" class="table table-bordered" id="vendor_bill_table">
                        <thead>
                            <tr>
                            <th> </th>
                            <th>Vendor Name</th>
                            <th>Bill Date </th>
                            <th>Bill No. </th>
                            <th>Invoice Amount (<i class="fa fa-rupee"></i>) </th>
                            <th>Amount Paid (<i class="fa fa-rupee"></i>)</th>
                            <th>Payable (<i class="fa fa-rupee"></i>) </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="6"> Please select vendor to show data.</td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <h4>Total Payable : <span id="vendor_payable">0</span></h4>
                    </div>
                </div>
                <form id="payment_form">
                    <input type="hidden" name="selectedBills">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group form-material floating" data-plugin="formMaterial">
                            <input type="text" class="form-control input-sm" name="amount_paid" id="amount_paid" autocomplete="off">
                            <label class="floating-label required">Amount Paid</label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group form-material floating" data-plugin="formMaterial">
                            <select class="form-control input-sm singleSelect" name="payment_type">
                                <?php foreach($payment_type as $ptype) {?>
                                    <option value="<?php echo $ptype['payment_type'];?>"><?php echo $ptype['name'];?></option>
                                <?php } ?>                                
                            </select>
                            <label class="floating-label required">Payment Mode</label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group form-material floating" data-plugin="formMaterial">
                            <input type="text" class="form-control input-sm" name="date" id="date" autocomplete="off" value="<?php echo date('d-m-Y'); ?>">
                            <label class="floating-label required">Date</label>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group form-material floating" data-plugin="formMaterial">
                            <input type="text" class="form-control input-sm" name="notes" id="notes" autocomplete="off">
                            <label class="floating-label required">Remarks</label>
                        </div>
                    </div>
                    <div class="col-lg-8" style="text-align:right;">
                        <br>
                    <button type="button" onclick="add_payment()" class="btn btn-sm btn-info"><i class="fa fa-plus"></i> Save Payment</button>
                    </div>
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
                             <td><?php echo $v['head_type']; ?></td>
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
                                                    <input type="text" class="form-control input-sm" name="name" id="customer_name" required>
                                                    <label class="floating-label required">Name</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                                    <input type="text" class="form-control input-sm" name="mobile_no">
                                                    <label class="floating-label">Mobile</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                                    <input type="text" class="form-control input-sm" name="email">
                                                    <label class="floating-label">Email</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                                    <input type="text" class="form-control input-sm" name="opening_balance">
                                                    <label class="floating-label">Opening Balance</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                                    <input type="text" class="form-control input-sm" name="billing_address">
                                                    <label class="floating-label">Billing Address</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                            <div class="form-group form-material floating" data-plugin="formMaterial" style="display:inline-flex;width:inherit;">
                        <input type="text" class="form-control input-sm" name="credit_period" autocomplete="off" maxlength="3" value="<?php if (isset($vendor['credit_period'])) {
                                                                                                                            echo $vendor['credit_period'];
                                                                                                                        } else { echo 30; } ?>">
                        <label class="floating-label">Credit Period</label>
                        <div class="input-group" style="width:auto;">
                        <div class="bs-dropdown-to-select-group">
                            <button type="button" class="btn btn-default">
                                <span data-bind="bs-drp-sel-label">Days</span>
                            </button>
                        </div>
                        </div>
                        </div>
                    </div>

                                        </div>

                                        </div>
                                        </div>
                                        </form>
                                        <hr>
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
                                                                <input type="text" class="form-control input-sm" name="vehicle[0][reg_no]" id="reg_1">
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
                                                <button type="button" class="btn btn-success btn-sm waves-effect waves-classic pull-right" onclick="save_customer()"><i class="fa fa-save"></i> Save</button>
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
<div id="add_vendor_modal" class="modal fade mymodal" role="dialog">
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
                        <input type="text" class="form-control input-sm" name="company_name" id="company_name" autocomplete="off">
                        <label class="floating-label required">Vendor Name</label>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                        <input type="text" class="form-control input-sm" name="mobile_no" autocomplete="off">
                        <label class="floating-label">Mobile No.</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                        <input type="text" class="form-control input-sm" name="email" autocomplete="off">
                        <label class="floating-label">Email</label>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                        <input type="text" class="form-control input-sm" name="opening_balance" autocomplete="off">
                        <label class="floating-label">Opening Balance</label>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                        <input type="text" class="form-control input-sm" name="billing_address" autocomplete="off">
                        <label class="floating-label">Billing Address</label>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                        <input type="text" class="form-control input-sm" name="gst_no" autocomplete="off">
                        <label class="floating-label">GSTIN</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group form-material floating" data-plugin="formMaterial" style="display:inline-flex;width:inherit;">
                        <input type="text" class="form-control input-sm" name="credit_period" autocomplete="off" maxlength="3">
                        <label class="floating-label">Credit Period</label>
                        <div class="input-group" style="width:auto;">
                        <div class="bs-dropdown-to-select-group">
                            <button type="button" class="btn btn-default">
                                <span data-bind="bs-drp-sel-label">Days</span>
                            </button>
                        </div>
                        </div>
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
<style>
form#add_new_account_form .form-material.floating {
    margin-top: 0px;
    margin-bottom: 0px ;
}
form#income_form .form-material.floating {
    margin-top: 0px;
    margin-bottom: 0px ;
}
form#expense_form .form-material.floating {
    margin-top: 0px;
    margin-bottom: 0px ;
}
.select2-container--default .select2-results__option[aria-disabled=true] {
    display: none;
}
</style>
<script>
    function add_category() {
        window.location = 'income-expense-head';
    }
    function filter() {        
        window.location.href = 'accounts?d='+$('input[name=duration]').val()+'&vd='+$('#vendor_id').val();
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
    
    function updatePayableAmt() {
        var total_payable = 0;
        var po_ids = [];
        $('.chk').each(function(e) {
            var index = $(this).attr('id').split('_');
            if($(this).prop('checked')) {
                var payable = $('#payable_'+index[1]).text() ? $('#payable_'+index[1]).text() : 0;
                total_payable += parseFloat(payable);
                po_ids.push(index[2]);
            }
         });
         $('input[name=selectedBills]').val(po_ids.join(','));
        $('#vendor_payable').text(total_payable);
    }
    function getVendorBillDetails(vendor_id) {
        $.ajax({
                method: 'GET',
                url: 'common/commonFunc?vendor_id=' + vendor_id + '&do=getVendorBillDetails',
                success: function(result) {
                    var vendor_bills = JSON.parse(result);
                    var html = '';
                    debugger;
                    if(vendor_bills.data.length > 0) {
                        var index = 0;
                        vendor_bills.data.forEach(element => {
                            html += '<tr>';
                            if(element.po_id) {
                                html += '<td><input type="checkbox" onchange="updatePayableAmt()" class="chk" id="chk_'+index+'_po-'+element.po_id+'"></td>';
                            } else {
                                html += '<td><input type="checkbox" onchange="updatePayableAmt()" class="chk" id="chk_'+index+'_tras-'+element.transcation_id+'"></td>';
                            }
                            html += '<td>'+element.company_name+'</td>';
                            html += '<td>'+element.invoice_date+'</td>';
                            html += '<td>'+element.bill_no+'</td>';
                            html += '<td>'+(element.grand_total ? element.grand_total : 0)+'</td>';
                            html += '<td>'+(element.paid ? element.paid : 0)+'</td>';
                            html += '<td id="payable_'+index+'">'+(element.payable ? element.payable : 0)+'</td>';
                            html += '</tr>';
                            index++;
                        });
                    } else {
                        html += '<tr><td colspan="6"> Please select vendor to show data.</td></tr>';
                    }
                    $('#vendor_bill_table tbody').html(html);
                }
        });
    }
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
    function save_customer() {
        if($('#customer_name').val() == "") {
            toastr.warning("Customer name is required !");
            $('#customer_name').focus();
            return false;
        }
        if($('#make_1').val() == "*") {
            toastr.warning("Please select make !");
            return false;
        }
        if($('#model_1').val() == "*") {
            toastr.warning("Please select model !");
            return false;
        }
        if($('#reg_1').val() == "") {
            toastr.warning("Vehicle No. is required !");
            $('#reg_1').focus();
            return false;
        }
        
        $.ajax({
            type: 'POST',
            url: 'Transcation/InsertOperation',
            data: {
                'customer_detail': jQuery('#add_customer_form').serialize(),
                'vehicle_detail': jQuery('#add_vehicle_form').serialize(),
                'table_name': 'tbl_customer_ajax'
            },
            success: function(result) {
                var returnJSON = JSON.parse(result);
                var data = JSON.parse(returnJSON['data']);
                if(returnJSON && returnJSON['status'] == 200) {
                    if($("#customer_id").find("option[value='" + data['customer_id'] + "']").length) {
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
        if($('#expense_form select[name=vendor_id]').val() == "") {
            toastr.warning("Vendor name is required.");
            $('select[name=company_name]').focus();
            return false;
        }
        $.ajax({
            method: 'POST',
            url: 'Transcation/InsertOperation',
            data: {'table_name': 'tbl_vendor_ajax','vendor_detail': $('#add_vendor_form').serialize()},
            success: function(result) {
                var returnJSON = JSON.parse(result);
                var data = JSON.parse(returnJSON['data']);

                if(returnJSON && returnJSON['status'] == 200) {
                    var newOpt = new Option(data['company_name'],data['vendor_id'], true, true);
                    $('#expense_form select[name=vendor_id]').append(newOpt).trigger('change');
                    
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
        if($('#income_form input[name=date]').val() == "") {
            toastr.warning('Transaction date is required !');
            $('input[name=date]').focus();
            return false;
        } else if($('#income_form input[name=amount]').val() == "") {
            toastr.warning('Amount  is required !');
            $('input[name=amount]').focus();
            return false;
        }

        $.ajax({
            method: 'POST',
            url: 'Transcation/InsertOperation',
            data: {'table_name': 'tbl_transaction','data': $('#income_form').serialize()},
            success: function(result) {
                var returnJSON = JSON.parse(result);
                if(returnJSON && returnJSON['status'] == 200) {
                    toastr.success("Transaction saved successfully !");
                    setTimeout(function() {
                       //window.location.reload();     
                    },1000);
                }
            }
        });
    }
    function save_expense() {
        if($('#expense_form select[name=vendor_id]').val() == "") {
            toastr.warning('Vendor is required !');
            return false;
        } else if($('#expense_form input[name=date]').val() == "") {
            toastr.warning('Transaction date is required !');
            $('#expense_form input[name=date]').focus();
            return false;
        } else if($('#expense_form input[name=amount]').val() == "") {
            toastr.warning('Amount  is required !');
            $('#expense_form input[name=amount]').focus();
            return false;
        }

        $.ajax({
            method: 'POST',
            url: 'Transcation/InsertOperation',
            data: {'table_name': 'tbl_transaction','data': $('#expense_form').serialize()},
            success: function(result) {
                var returnJSON = JSON.parse(result);
                if(returnJSON && returnJSON['status'] == 200) {
                    toastr.success("Transaction saved successfully !");
                    setTimeout(function() {
                       window.location.reload();     
                    },1000);
                }
            }
        });
    }
    function add_payment() {
        debugger;
        if($('#payment_form input[name=amount_paid]').val() == "") {
            toastr.warning('Enter Amount to be Paid !');
            $('#payment_form input[name=amount_paid]').focus();
            return false;
        } else if($('#payment_form input[name=date]').val() == "") {
            toastr.warning('Date is required !');
            $('#payment_form input[name=date]').focus();
            return false;
        } else if($('input[name=selectedBills]').val() == "") {
            toastr.warning('Please select at-least one Bill for payment !');
            return false;
        }

        $.ajax({
            method: 'POST',
            url: 'Transcation/InsertOperation',
            data: {'table_name': 'tbl_account_payment','data': $('#payment_form').serialize()},
            success: function(result) {
                var returnJSON = JSON.parse(result);
                if(returnJSON && returnJSON['status'] == 200) {
                    toastr.success("Payment saved successfully !");
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
                        category_table += '<td>'+element.head_type+'</td>';
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
            data: {'table_name': 'tbl_category','name': $('input[name=category_name]').val(),'head_type': $('select[name=trans_type]').val(),'action':'insert'},
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
                        category_table += '<td>'+element.head_type+'</td>';
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
                    $("select[name=category_id] option[value="+data['added_category']['category_id']+"]").attr('data-type',data['added_category']['head_type']);
                    if($('select[name=head_type]').val() == 'other_income' && data['added_category']['head_type'] != 'Income') {
                        $("select[name=category_id] option[value="+data['added_category']['category_id']+"]").prop("disabled", true);
                    }
                    if($('select[name=head_type]').val() == 'other_expense' && data['added_category']['head_type'] != 'Expense') {
                        $("select[name=category_id] option[value="+data['added_category']['category_id']+"]").prop("disabled", true);
                    }
                }
                $('#add_category_modal').modal('hide');
            }
        });
    }
    function gotostatement(account_id) {
         window.location = 'transactions?type='+account_id;
    }
    function add_new_account() {
        $('input[name=account_id]').val(0);
        $('#add_account_label').text("Add Account");
        $("#add_new_account_modal").modal('show');
    }
    function add_expense_trans() {
        $("#add_expense_modal").modal('show');
    } 
    function add_new_transaction() {
        $("#add_income_modal").modal('show');
    }
    function edit_account_id(id) {
        $.ajax({
            method: 'POST',
            url: 'accounts/getAccountDetailsByID',
            data: {
                'account_id': id
            },
            success: function(result) {
                if(result) {
                    $('input[name=account_name]').attr('readonly',true);
                    $('select[name=account_type]').attr('readonly',true);
                    $('input[name=account_id]').val(id);
                    $('#add_account_label').text("Edit Account");
                    $("#add_new_account_modal").modal('show');
                    Object.keys(result).forEach(function(key) {
                        if(key == 'account_type' || key == 'status') {
                            $('select[name='+key+']').select2("val",result[key]);
                        } else if(result[key]) {
                            $('input[name='+key+']').val(result[key]);
                        }
                    });
                }
            }
        });
    }
    var intial_load = 0;
    $(document).ready(function() {
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
        $('input[name=date]').datepicker({
            format: 'dd-mm-yyyy',
            autoclose:true
        });
        $('input[name=due_date]').datepicker({
            format: 'dd-mm-yyyy',
            autoclose:true
        });
    });
    
    function save_new_account() {
        if($('input[name=account_name]').val() == "") {
            toastr.error("Account name is required !");
            $('input[name=account_name]').focus();
            return false;
        }
        $.ajax({
            method: 'POST',
            url: 'Transcation/InsertOperation',
            data: {
                'data': $('#add_new_account_form').serialize(),
                'table_name': 'tbl_accounts'
            },
            success: function(result) {
                var parseRes = JSON.parse(result);
                if(parseRes) {
                    toastr.success(parseRes.message);
                    setTimeout(function() {
                        window.location.reload();
                    },1000);
                }
            }
        });
    }
</script>
