<?php
if($data['jobcard']['status'] == 'estimate_created') {
    $bages = 'badge-primary';
} else if($data['jobcard']['status'] == 'close') {
    $bages = 'badge-success';
} else if($data['jobcard']['status'] == 'payment_due') {
    $bages = 'badge-danger';
} else {
    $bages = 'badge-info';
}
$GLOBALS['title_left'] = '<span style="float:left;font-size:15px;margin-top:5px;" class="badge '.$bages.'">' .str_replace('_', ' ', $data['jobcard']['status'] == 'estimate_created' ? 'Estimate' :$data['jobcard']['status']). '</span> <a href="javascript:void(0)" onclick=window.location="jobcards" class="btn btn-sm btn-info btn-outline btn-1e pull-right"><i class="fa fa-arrow-left"></i> Back</a>';
?>
<input type="hidden" id="settingArray" value='<?php echo json_encode($data['setting']); ?>'>
<input type="hidden" id="tax_applicable" value='<?php echo $data['is_tax_applicable']; ?>'>
<input type="hidden" id="discount_applicable" value='Y'>
<div class="panel panel-primary panel-line" style="border:1px solid lavender;margin-bottom: 1.143rem;margin-bottom: 10px;">
    <div class="panel-heading">
        <h3 class="panel-title" style="font-size:15px;color:#757575;padding: 5px 5px;border-bottom: 1px solid lavender;background-color: ghostwhite;"><i class="fa fa-user" aria-hidden="true"></i> Customer / <i class="fa fa-car" aria-hidden="true"></i> Vehicle Detail</h3>
    </div>
    <div class="panel-body">
        <?php if (!isset($_REQUEST['job_id'])) { ?>
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <table style="border-bottom:1px solid lavender;">
                        <tr>
                            <td style="width: 25%;"></td>
                            <td style="width: 40%;">
                                <div class="form-group has-search" style="margin-bottom: 10px;">
                                    <span class="fa fa-search form-control-feedback"></span>
                                    <input type="text" class="form-control" placeholder="Search customer by Vehicle No, Name OR Contact No." id="auto_vehicle_no">
                                </div>
                            </td>
                            <td style="width: 5%;text-align:center;"><label style="font-weight: bold;">OR</label> </td>
                            <td style="width: 10%;">
                                <div class="form-group" style="margin-bottom: 10px;">
                                    <button type="button" onclick="addEditCust()" href="javascript:void(0);" class="btn btn-info btn-outline btn-sm"><span id="addEditCustLabel"><i class="fa fa-plus"></i> Add New Customer</span></button>
                                </div>
                            </td>
                            <td style="width: 20%;"></td>
                        </tr>
                    </table>
                </div>
            </div>
        <?php } ?>

        <div class="row">
            <div class="col-md-2 col-lg-2 col-sm-6 col-xs-6">
                <span style="color:#BDBDB9">Name</span>
            </div>
            <div class="col-md-4 col-lg-4 col-sm-6 col-xs-6">
                : <span id="span_cust_name"><?php echo isset($data['jobcard']['name']) ? $data['jobcard']['name'] : 'N/A'; ?></span>
            </div>
            <div class="col-md-2 col-lg-2 col-sm-6 col-xs-6">
                <span style="color:#BDBDB9">Vehicle No </span>
            </div>
            <div class="col-md-4 col-lg-4 col-sm-12 col-xs-12">
               : <span id="span_vehicle_no"><?php echo isset($data['jobcard']['reg_no']) ?  $data['jobcard']['reg_no'] : 'N/A'; ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 col-lg-2 col-sm-6 col-xs-6">
                <span style="color:#BDBDB9">Contact No</span>
            </div>
            <div class="col-md-4 col-lg-4 col-sm-6 col-xs-6">
                : <span id="span_contact_no"><?php echo isset($data['jobcard']['mobile_no']) ? $data['jobcard']['mobile_no'] : 'N/A'; ?></span>
            </div>
            <div class="col-md-2 col-lg-2 col-sm-6 col-xs-6">
                <span style="color:#BDBDB9">Make </span>
            </div>
            <div class="col-md-4 col-lg-4 col-sm-6 col-xs-6">
                : <span id="span_make"><?php echo isset($data['jobcard']['make_name']) ? $data['jobcard']['make_name'] : 'N/A'; ?></span>
            </div>
        </div>
        <input type="hidden" id="customer_id" value="<?php echo isset($data['jobcard']['customer_id']) ? $data['jobcard']['customer_id'] : ''; ?>">
        <input type="hidden" id="vehicle_id" value="<?php echo isset($data['jobcard']['vehicle_id']) ? $data['jobcard']['vehicle_id'] : ''; ?>">
        <input type="hidden" id="make_model_variant_id" value="<?php echo isset($data['jobcard']['make_id']) ? $data['jobcard']['variant_id'].'_'.$data['jobcard']['model_id'].'_'.$data['jobcard']['make_id'] : ''; ?>">
        <input type="hidden" id="cust_model_id" value="<?php echo isset($data['jobcard']['cust_model_id']) ? $data['jobcard']['cust_model_id'] : ''; ?>">
        <input type="hidden" id="job_id" value="<?php echo isset($data['jobcard']['jobcard_id']) ? base64_encode($data['jobcard']['jobcard_id']) : ''; ?>">
        <div class="row">
            <div class="col-md-2 col-lg-2 col-sm-6 col-xs-6">
                <span style="color:#BDBDB9">Email</span>
            </div>
            <div class="col-md-4 col-lg-4 col-sm-6 col-xs-6">
                : <span id="span_email"><?php echo isset($data['jobcard']['email']) ? $data['jobcard']['email'] : 'N/A'; ?></span>
            </div>
            <div class="col-md-2 col-lg-2 col-sm-6 col-xs-6">
                <span style="color:#BDBDB9">Model </span>
            </div>
            <div class="col-md-4 col-lg-4 col-sm-6 col-xs-6">
                : <span id="span_model"><?php echo isset($data['jobcard']['model_name']) ? $data['jobcard']['model_name'] : 'N/A'; ?></span>
            </div>
        </div>

        <div class="row">
            <div class="col-md-2 col-lg-2 col-sm-6 col-xs-6">
                <span style="color:#BDBDB9">Address</span>
            </div>
            <div class="col-md-4 col-lg-4 col-sm-6 col-xs-6">
                : <span id="span_address"><?php echo isset($data['jobcard']['billing_address']) ? $data['jobcard']['billing_address'] : 'N/A'; ?></span>
            </div>
            <div  class="col-md-2 col-lg-2 col-sm-6 col-xs-6">
                <span style="color:#BDBDB9">Variant</span>
            </div>
            <div class="col-md-4 col-lg-4 col-sm-6 col-xs-6">
                : <span id="span_variant"><?php echo isset($data['jobcard']['variant_name']) ? $data['jobcard']['variant_name'] : 'N/A'; ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2 col-lg-2 col-sm-6 col-xs-6">
                <span style="color:#BDBDB9">GST No.</span>
            </div>
            <div class="col-md-4 col-lg-4 col-sm-6 col-xs-6">
                : <span id="span_gst_no"><?php echo isset($data['jobcard']['gst_no']) ? $data['jobcard']['gst_no'] : 'N/A'; ?></span>
            </div>
            <div  class="col-md-2 col-lg-2 col-sm-6 col-xs-6">
                <span style="color:#BDBDB9">Fuel Type</span>
            </div>
            <div class="col-md-4 col-lg-4 col-sm-6 col-xs-6">
                : <span id="span_fuel_type"><?php echo isset($data['jobcard']['fuel_type']) ? $data['jobcard']['fuel_type'] : 'N/A'; ?></span>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12">
        <ul class="nav nav-tabs nav-tabs-line" role="tablist">
            <li class="nav-item active"><a href="#tab2" class="nav-link active" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-user"></i></span> <span class="hidden-xs" style="font-weight: bold;"><i class="fa fa-tasks" aria-hidden="true"></i> Job Detail</span></a></li>
            <li class="nav-item"><a href="#tab6" class="nav-link" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-user"></i></span> <span class="hidden-xs" style="font-weight: bold;"><i class="fa fa-history" aria-hidden="true"></i> Service History <span id="attach_badges_hist"></span></span></a></li>
        </ul>
        <div class="tab-content pt-20" style="width: 100%;">
            <div id="tab2" class="tab-pane active">
                <div class="panel panel-primary panel-line" style="border:1px solid lavender;margin-bottom: 10px;">
                    <div class="panel-heading">
                        <h3 class="panel-title" style="font-size:15px;color:#757575;padding: 5px 5px;border-bottom: 1px solid lavender;font-weight: bold;background-color: ghostwhite;">Job Assignment</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-2 col-lg-2 col-sm-12 col-xs-12">
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <input type="text" class="form-control input-sm" readonly id="job_no" name="jobcard_no" value="<?php if (isset($data['jobcard']) && !empty($data['jobcard']['jobcard_no'])) {
                                                                                                                                    echo $data['jobcard']['jobcard_no'];
                                                                                                                                } else {
                                                                                                                                    echo 'Auto Generated';
                                                                                                                                } ?>" >
                                    <label class="floating-label">Jobcard No.</label>
                                </div>
                            </div>
                            <div class="col-md-2 col-lg-2 col-sm-12 col-xs-12">
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <input type="text" class="form-control input-sm" id="date" name="date" value="<?php if (isset($data['jobcard']) && !empty($data['jobcard']['date'])) {
                                                                                                                        echo date('d-m-Y', strtotime($data['jobcard']['date']));
                                                                                                                    } ?>">
                                    <label class="floating-label">Date</label>
                                </div>
                            </div>
                            <div class="col-md-2 col-lg-2 col-sm-12 col-xs-12">
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <input type="text" class="form-control input-sm" id="expt_delivery_date" name="expt_delivery_date" value="<?php if (isset($data['jobcard']) && !empty($data['jobcard']['expt_delivery_date'])) {
                                                                                                                                                    echo date('d-m-Y', strtotime($data['jobcard']['expt_delivery_date']));
                                                                                                                                                } ?>">
                                    <label class="floating-label">Expected Delivery Date</label>
                                </div>
                            </div>
                            <div class="col-md-2 col-lg-2 col-sm-12 col-xs-12">
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <input type="text" class="form-control input-sm" name="odometer" id="odometer" value="<?php if (isset($data['jobcard']) && !empty($data['jobcard']['odometer'])) {
                                                                                                                                echo $data['jobcard']['odometer'];
                                                                                                                            } ?>">
                                    <label class="floating-label">Odometer</label>
                                </div>
                            </div>
                            <div class="col-md-2 col-lg-2 col-sm-12 col-xs-12">
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <select class="form-control input-sm singleSelect" id="mechanic" name="mechanic">
                                        <option value="_">Select</option>
                                        <?php foreach ($data['assign_tos'] as $d => $a) { ?>
                                            <option <?php if ((!isset($data['jobcard']['mechanic']) && $data['jobcard']['mechanic'] == "") && $data['setting']['default_mechanic'] == $a['user_id']) {
                                                        echo 'selected';
                                                    } else if ($data['jobcard']['mechanic'] == $a['user_id']) {
                                                        echo 'selected';
                                                    } ?> value="<?php echo $a['user_id']; ?>"><?php echo $a['first_name'] . ' ' . $a['last_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <label class="floating-label">Mechanic</label>
                                </div>
                            </div>
                            <div class="col-md-2 col-lg-2 col-sm-12 col-xs-12">
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <select class="form-control input-sm singleSelect" id="advisor" name="advisor">
                                        <option value="_">Select</option>
                                        <?php foreach ($data['assign_tos'] as $d => $a) { ?>
                                            <option <?php if (!isset($data['jobcard']['advisor']) && $data['setting']['default_advisor'] == $a['user_id']) {
                                                        echo 'selected';
                                                    } else if ($data['jobcard']['advisor'] == $a['user_id']) {
                                                        echo 'selected';
                                                    } ?> value="<?php echo $a['user_id']; ?>"><?php echo $a['first_name'] . ' ' . $a['last_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <label class="floating-label">Advisor</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-primary panel-line" style="border:1px solid lavender;margin-bottom: 10px">
                    <div class="panel-heading">
                        <h3 class="panel-title" style="font-size:15px;color:#757575;padding: 5px 5px;border-bottom: 1px solid lavender;font-weight: bold;background-color: ghostwhite;">Customer Complaints / Concerns</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12 col-lg-12">
                                <div style="width:100%;" class="form-group form-material floating" data-plugin="formMaterial">
                                    <input type="text" value="<?php if (isset($data['jobcard']) && !empty($data['jobcard']['customer_concern'])) {
                                                                    echo $data['jobcard']['customer_concern'];
                                                                } ?>" class="form-control input-sm" placeholder="Enter Customer Concern / Complaints. (i.e General service etc..)" data-role="tagsinput" name="customer_concern" id="customer_concern" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel panel-primary panel-line" style="border:1px solid lavender;margin-bottom: 10px">
                    <div class="panel-heading">
                        <h3 class="panel-title" style="font-size:15px;color:#757575;padding: 5px 5px;border-bottom: 1px solid lavender;font-weight: bold;background-color: ghostwhite;">Insurance Details</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-3 col-lg-3">
                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                <select class="form-control" id="insurance_id" onChange="onChangeInsurance(this.value)" name="insurance_company_id">
                                        <option value="*">Select Insurance</option>
                                        <?php foreach($data['insurance'] as $ins) { ?>
                                        <option <?php if($data['jobcard']['insurance_id'] == $ins['insurance_id']) { echo 'selected'; } ?> value="<?php echo $ins['insurance_id']; ?>"><?php echo $ins['name']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <label class="floating-label">Insurance Company</label>
                                </div>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="panel panel-primary panel-line" style="border:1px solid lavender;margin-bottom: 10px">
                    <div class="panel-heading">
                        <h3 class="panel-title" style="font-size:15px;color:#757575;padding: 5px 5px;border-bottom: 1px solid lavender;font-weight: bold;background-color: ghostwhite;">Job Item Details
                            <div class="material-switch" style="float:right;">
                                <input id="isGstBill" type="checkbox" <?php if($data['jobcard']['is_gst_bill'] == 'Y') { echo 'checked'; } else if(!isset($data['jobcard']['is_gst_bill'])) { echo 'checked'; } ?> />
                                GST Bill: &nbsp;&nbsp;&nbsp; N &nbsp;<label for="isGstBill" class="label-info"></label> &nbsp; Y
                            </div>
                        </h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-8 col-lg-8 col-xs-8" style="margin-top: 10px;">
                                <div class="form-group has-search" style="margin-bottom: 10px;display:flex;">
                                    <i class="fa fa-search form-control-feedback"></i>
                                    <input type="text" class="form-control" placeholder="Search Part/Labor Item..." id="search_item">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-info" onclick="add_new_item()"><i class="fa fa-plus"></i></button>
                                    </span>
                                </div>
                            </div>
                            <div class="col-md-1 col-lg-1" style="margin-top: 10px;text-align: center;">
                                <label style="font-weight: bold;">OR</label>
                            </div>
                            <div class="col-md-3 col-lg-3">
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <select class="form-control input-sm no-select customMultiSelect" multiple="" onchange="getpackagedetail()" placeholder="Package Selected" id="package_drp">
                                        <?php foreach ($data['packages'] as $pac) { ?>
                                            <option value="<?php echo $pac['package_id']; ?>"><?php echo $pac['package_name']; ?></option>
                                        <?php } ?>
                                    </select>
                                    <label class="floating-label">Select Package</label>
                                </div>
                            </div>
                        </div>
                        <table class="table table-bordered booking_item_table" data-plugin="floatThead" style="width: 100%;" id="jobTable">
                            <tr>
                                <td style="width: 3%;text-align: center;background-color: lavender;">
                                <button class="btn btn-xs btn-info jobActionOption dropdown-toggle" type="button" id="sendToButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-arrows" aria-hidden="true"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="sendToButton">
                                            <a class="dropdown-item" onclick="itemAction(1)">Delete Selected</a>
                                        </div>
                                </td>
                                <td style="width: <?php echo $data['setting']['total_enable'] == 5 ? '55%' : $data['setting']['total_enable'] == 6 ? '45%' : $data['setting']['total_enable'] == 7 ? '30%' : '30%'; ?>;background-color: lavender;">Items</td>
                                <td style="width: 5%;background-color: lavender;text-align:center;">Qty</td>
                                <td style="width: 10%;background-color: lavender;text-align:center;">Unit Price (&#8377)</td>
                                <td style="width: 12%;background-color: lavender;text-align:center;">Discount</td>
                                <td class='insurance_field' style="width: 10%;background-color: lavender;text-align:center;">Insurance Payable</td>
                                <td class='insurance_field' style="width: 10%;background-color: lavender;text-align:center;">Customer Payable</td>
                                <?php if (isset($data['is_tax_applicable']) && $data['is_tax_applicable'] == "Y") { ?>
                                    <td style="width: 12%;background-color: lavender;text-align:center;">Tax (&#8377 / %)</td>
                                <?php } ?>
                                <td style="width: 10%;background-color: lavender;text-align:center;">Line Total</td>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4" style="text-align: right;font-weight: bold;">Sub Total</td>
                                    <td style="text-align: right;font-size:15px;"><i class="fa fa-rupee"></i> <span id="sub_total_discount">0</span></td>
                                    <td class='insurance_field' style="text-align: right;font-size:15px;"><i class="fa fa-rupee"></i> <span id="total_insurance_pay">0</span></td>
                                    <td class='insurance_field' style="text-align: right;font-size:15px;"><i class="fa fa-rupee"></i> <span id="total_customer_pay">0</span></td>
                                    <?php if (isset($data['is_tax_applicable']) && $data['is_tax_applicable'] == "Y") { ?>
                                        <td style="text-align: right;font-size:15px;"><i class="fa fa-rupee"></i> <span id="sub_total_tax">0</span></td>
                                    <?php } ?>
                                    <td style="text-align: right;font-size:15px;"><i class="fa fa-rupee"></i> <span id="grand_total">0</span></td>
                                </tr>
                                <!-- <tr>
                                    <td colspan="<?php echo $data['setting']['total_enable'] - 1; ?>" style="text-align: left;font-weight: bold;">Paid</td>
                                    <td style="text-align: center;font-size:15px;"><i class="fa fa-rupee"></i> <span id="total_paid_amount"><?php echo $data['payments']['total_paid']; ?></span></td>
                                </tr>
                                <tr>
                                    <td colspan="<?php echo $data['setting']['total_enable'] - 1; ?>" style="text-align: left;font-weight: bold;">Balance</td>
                                    <td style="font-size: 18px;font-weight:bold;text-align: center;"><i class="fa fa-rupee"></i> <span id="total_balance_amount">0</span></td>
                                </tr> -->
                            </tbody>
                        </table>
                        <input type="hidden" id="tax_type">
                        <table class="table table-bordered" style="width: 100%;" id="taxable_amt_summary">
                                <tr>
                                    <th style="width:70%"></th>
                                    <th style="width:30%">
                                        <span style="text-align: left;">Taxable Amount</span>
                                        <span style="float: right;font-size:15px;"><i class="fa fa-rupee"></i> <span id="total_taxable_amount">0</span>
                                    </th>
                                </tr>
                                <tr class="scgst_tr">
                                    <th style="width:70%"></th>
                                    <th style="width:30%">
                                        <span style="text-align: left;">SGST</span>
                                        <span style="float: right;font-size:15px;"><i class="fa fa-rupee"></i> <span id="total_sgst">0</span>
                                    </th>
                                </tr>
                                <tr class="scgst_tr">
                                    <th style="width:70%"></th>
                                    <th style="width:30%">
                                        <span style="text-align: left;">CGST</span>
                                        <span style="float: right;font-size:15px;"><i class="fa fa-rupee"></i> <span id="total_cgst">0</span>
                                    </th>
                                </tr>
                                <tr class="igst_tr" style="display: none;">
                                    <th style="width:70%"></th>
                                    <th style="width:30%">
                                        <span style="text-align: left;">IGST</span>
                                        <span style="float: right;font-size:15px;"><i class="fa fa-rupee"></i> <span id="total_igst">0</span>
                                    </th>
                                </tr>
                                <tr>
                                    <th style="width:70%"></th>
                                    <th style="width:30%">
                                        <span style="color:#3f51b5;text-align: left;font-weight:bold;">Total Amount</span>
                                        <span style="color:#3f51b5;float: right;font-weight:bold;font-size:15px;"><i class="fa fa-rupee"></i> <span id="total_amount">0</span>
                                    </th>
                                </tr>
                                <tr>
                                    <th style="width:70%"></th>
                                    <th style="width:30%">
                                        <?php $total_paid = $data['payments']['total_paid'] != null ? $data['payments']['total_paid'] : 0; ?>
                                        <span style="color:#4caf50;text-align: left;">Paid Amount</span>
                                        <span style="color:#4caf50;float: right;font-size:15px;"><i class="fa fa-rupee"></i> <span id="received_amount"><?php echo $total_paid;?></span>
                                    </th>
                                </tr>
                                <tr>
                                    <th style="width:70%"></th>
                                    <th style="width:30%">
                                        <span style="color:red;text-align: left;">Balance Amount</span>
                                        <span style="color:red;float: right;font-size:15px;"><i class="fa fa-rupee"></i> <span id="balance_amount">0</span>
                                    </th>
                                </tr>
                        </table>
                        <div class="row">
                            <div class="col-lg-12">
                                <textarea placeholder="Jobcard Notes" id="jobcard_notes" class="form-control input-sm" rows="5"><?php echo $data['jobcard']['jobcard_notes']; ?></textarea>
                            </div>
                        </div><br>
                        <div class="row">
                            <div class="col-lg-6" style="text-align:left;">
                            <?php if (isset($_REQUEST) && $_REQUEST['job_id'] != "") { ?>
                                    <a class="btn btn-sm btn-warning waves-effect waves-classic" href="javascript:void(0)" onclick="deleteJobcard()"><i class="fa fa-trash"></i></a>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-info dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-print" aria-hidden="true"></i> Print
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" target="__blank" href="booking/viewEstimatePdf?job_id=<?php echo base64_encode($data['jobcard']['jobcard_id']); ?>">Estimate / Quotation</a>
                                            <a class="dropdown-item" target="__blank" href="booking/viewJobcardPdf?job_id=<?php echo base64_encode($data['jobcard']['jobcard_id']); ?>">Jobcard</a>
                                            <?php if (isset($data['invoices']) && !empty($data['invoices'])) { ?>
                                            <?php if($data['jobcard']['insurance_id']) { ?>
                                                <a class="dropdown-item" target="__blank" href="booking/viewCustomerInvoicePdf?job_id=<?php echo base64_encode($data['jobcard']['jobcard_id']); ?>">Customer Invoice</a>
                                                <a class="dropdown-item" target="__blank" href="booking/viewInsuranceInvoicePdf?job_id=<?php echo base64_encode($data['jobcard']['jobcard_id']); ?>">Insurance Invoice</a>
                                            <?php } else { ?>
                                                <a class="dropdown-item" target="__blank" href="booking/viewInvoicePdf?job_id=<?php echo base64_encode($data['jobcard']['jobcard_id']); ?>">Invoice</a>
                                            <?php } } ?>
                                        </div>
                                    </div>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-success dropdown-toggle" type="button" id="sendToButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-commenting-o" aria-hidden="true"></i> SMS
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="sendToButton">
                                            <a class="dropdown-item" href="#" onclick="sendSMSToCustomer(3)">Estimate / Quatation</a>
                                            <?php if (isset($data['invoices']) && !empty($data['invoices'])) { ?>
                                                <a class="dropdown-item" onclick="sendSMSToCustomer(4)">Invoice</a>
                                                <a class="dropdown-item" onclick="sendSMSToCustomer(5)">Payment Received</a>
                                                <a class="dropdown-item" onclick="sendSMSToCustomer(6)">Payment Due</a>
                                                <!-- <a class="dropdown-item" onclick="sendSMSToCustomer(7)">Review / Feedback</a> -->
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-success dropdown-toggle" type="button" id="sendToButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-whatsapp" aria-hidden="true"></i> Whatsapp
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="sendToButton">
                                            <a class="dropdown-item" href="#" onclick="sendToCustomer(3)">Estimate / Quatation</a>
                                            <?php if (isset($data['invoices']) && !empty($data['invoices'])) { ?>
                                                <a class="dropdown-item" onclick="sendToCustomer(4)">Invoice</a>
                                                <a class="dropdown-item" onclick="sendToCustomer(5)">Payment Received</a>
                                                <a class="dropdown-item" onclick="sendToCustomer(6)">Payment Due</a>
                                                <a class="dropdown-item" onclick="sendToCustomer(7)">Review / Feedback</a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                            <div class="col-lg-6" style="text-align:right;">
                                <?php if (!isset($data['jobcard']['status']) || $data['jobcard']['status'] == '') { ?>
                                    <a class="btn btn-sm btn-warning waves-effect waves-classic" href="javascript:void(0)" onclick="save_jobItem('estimate_created')">Create Estimate</a>
                                <?php } ?>
                                <?php if (($data['jobcard']['status'] == 'payment_due' || $data['jobcard']['status'] == 'partial_paid') && isset($_REQUEST) && $_REQUEST['job_id'] != "") { ?>
                                    <a class="btn btn-sm btn-primary waves-effect waves-classic" href="javascript:void(0)" onclick="save_jobItem('add_payment')">Add Payment</a>
                                <?php } ?>
                                <?php if (isset($data['jobcard']['status']) && $data['jobcard']['status'] == 'invoice') { ?>
                                    <a class="btn btn-sm btn-success waves-effect waves-classic" href="javascript:void(0)" onclick="save_jobItem('create_invoice')">Create Invoice</a>
                                <?php } ?>
                                <?php if((isset($_REQUEST) && $_REQUEST['job_id'] != "" ) && ($data['jobcard']['status'] == 'waiting_for_parts' || $data['jobcard']['status'] == 'work_in_progress' || $data['jobcard']['status'] == 'estimate_created'))  { ?>
                                    <a class="btn btn-sm btn-info waves-effect waves-classic" href="javascript:void(0)" onclick="save_jobItem('')">Update Jobcard</a>
                                    <a class="btn btn-sm btn-primary waves-effect waves-classic" href="javascript:void(0)" onclick="save_jobItem('job_done')">Job Done</a>
                                <?php } ?>
                                <!-- <?php if($data['jobcard']['status'] == 'close') { ?>
                                    <label style="font-weight:bold;vertical-align:sub;"><b>Change Status : </b></label> &nbsp;&nbsp;
                                    <select  style="width:30%;float:right;" class="no-select form-control input-sm" id="post_status_change">
                                        <option value="close">Close</option>
                                        <option value="partial_paid">Partial Paid</option>
                                        <option value="payment_due">Payment Due</option>
                                    </select>
                                <?php } ?> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="tab6" class="tab-pane">
                <div class="row">
                    <div class="col-lg-6"></div>
                    <div class="col-lg-6">
                    <div class="card-footer" style="padding:5px 5px !important;background-color:lavender;">
              <div class="row no-space">
                <div class="col-4" style="border-right: 1px solid gray;">
                    <div class="px-20">
                      <span>TOTAL INVOICE</span>
                    </div>
                    <div class="px-20 font-size-20">
                        <i class="fa fa-rupee"></i> <span id="serv_his_invoiced"><?php echo $data['hist_summary']['total_invoiced'] ? $data['hist_summary']['total_invoiced'] : 0; ?></span>
                    </div>
                </div> 
                <div class="col-4" style="border-right: 1px solid gray;">
                <div class="px-20">
                      <span>TOTAL PAID</span>
                    </div>
                    <div class="px-20 font-size-20">
                        <i class="fa fa-rupee"></i> <span id="serv_his_paid"><?php echo $data['hist_summary']['total_paid'] ? $data['hist_summary']['total_paid'] : 0; ?></span>
                    </div>
                </div>
                <div class="col-4">
                <div class="px-20">
                      <span>TOTAL DUE</span>
                    </div>
                    <div class="px-20 font-size-20">
                        <i class="fa fa-rupee"></i> <span id="serv_his_due"><?php
                        $tdue = $data['hist_summary']['total_invoiced'] - $data['hist_summary']['total_paid'];
                        echo $tdue ? $tdue : 0; ?></span>
                    </div>
                </div>
              </div>
            </div>
                    </div>
                </div>
                <table class="table table-bordered" id="service_his_tab">
                    <thead>
                        <tr>
                            <th style="font-weight:bold;background-color:lavender;">Jobcard No.</th>
                            <th style="font-weight:bold;background-color:lavender;">Invoice No.</th>
                            <th style="font-weight:bold;background-color:lavender;">Date</th>
                            <th style="font-weight:bold;background-color:lavender;">Odometer</th>
                            <th style="font-weight:bold;background-color:lavender;">Invoice</th>
                            <th style="font-weight:bold;background-color:lavender;">Paid</th>
                            <th style="font-weight:bold;background-color:lavender;">Due</th>
                            <th style="font-weight:bold;background-color:lavender;">View</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                         if(!empty($data['service_hist'])) {
                         foreach($data['service_hist'] as $s => $h) { ?>
                            <tr>
                                <td><?php echo $h['jobcard_no']; ?></td>
                                <td><?php echo $h['invoice_no']; ?></td>
                                <td><?php echo $h['date']; ?></td>
                                <td><?php echo $h['odometer']; ?></td>
                                <td><i class="fa fa-rupee"></i> <?php echo $h['total_invoiced']; ?></td>
                                <td><i class="fa fa-rupee"></i> <?php echo $h['total_paid']; ?></td>
                                <td><i class="fa fa-rupee"></i> <?php echo $h['total_invoiced'] - $h['total_paid']; ?></td>
                                <td> <a class="btn btn-xs btn-default" target="__blank" href="job-view?job_id=<?php echo $h['jobcard_id']; ?>"><i class="fa fa-external-link" aria-hidden="true"></i></a></td>
                            </tr>
                        <?php } } else { ?>
                        <tr>
                            <td style="text-align: center;" colspan="8">No any past service history found.</td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
<div id="add_payment_modal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 5px !important;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title" id="ap_model_header">Payment Details</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="invoice_id" value="<?php echo base64_encode($data['invoices']['invoice_id']); ?>">
                <input type="hidden" id="payable_amt">
                <table style="width: 100%;" id="add_payment_table">
                    <tr>
                        <td style="width:35%;"><span><i class="fa fa-money"></i> Payment Status  </span></td>
                        <td style="width:65%;">
                            <input type="radio" name="invoice_status" checked value="paid"> Fully Paid &nbsp;&nbsp;
                            <input type="radio" name="invoice_status" value="partial_paid"> Partial Paid &nbsp;&nbsp;
                            <span id="paymnt_due_opt"><input type="radio" name="invoice_status" value="due"> Due</span>
                        </td>
                    </tr>
                    <tr class="hideOnDue">
                        <td><span><i class="fa fa-rupee"></i> Amount to be paid </span></td>
                        <td id="amt_fully_paid">
                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                <input type="number" name="amount" class="form-control input-sm" id="amount">
                            </div>
                        </td>
                        <td id="amt_partial_paid">
                            <div class="form-group form-material floating" data-plugin="formMaterial" style="display:flex;">
                                <input type="text" name="amount" class="form-control input-sm" id="paid_amount">
                                <input type="text" style="margin-left:5px;" class="text-center form-control input-sm" id="remaning_amount" placeholder="Unpaid Amt" readonly>
                                <select class="no-select form-control input-sm text-center" style="margin-left:5px;" id="paym_status">
                                    <option value="partial_paid">Partial Paid</option>
                                    <option value="paid">Paid & Close</option>
                                </select>
                            </div>
                        </td>                        
                    </tr>
                    <tr class="processInvoice">
                        <td><i class="fa fa-calendar"></i> Next Reminder Date</td>
                        <td>
                            <div class="form-group form-material floating" data-plugin="formMaterial" style="display:flex;">
                                <select class='no-select form-control input-sm next_reminder_date_change'>
                                    <option value=''>No Reminder</option>
                                    <option value='3'>After 3 Months</option>
                                    <option selected value='6'>After 6 Months</option>
                                    <option value='9'>After 9 Months</option>
                                    <option value='12'>After 12 Months</option>
                                    <option value='15'>After 15 Months</option>
                                    <option value='18'>After 18 Months</option>
                                    <option value='24'>After 24 Months</option>
                                </select>
                                <input type="text" style="margin-left:15px;" class="form-control input-sm" name="date" id="next_service_date">
                            </div>
                        </td>
                    </tr>
                    <tr class="processInvoice">
                        <td colspan="2">
                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                <textarea rows="3" class="form-control input-sm" name="notes" placeholder="Invoice Notes" id="notes"></textarea>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm btn-outline" style="display: none;" onclick="save_payment('payment')" id="ap_payment">Add Payment</button>
                <button type="button" class="btn btn-success btn-sm btn-outline" style="display: none;" onclick="save_invoice('invoice')" id="ap_invoice">Process Invoice</button>
                <button class="btn btn-info btn-sm btn-outline" data-dismiss="modal" aria-hidden="true">Close</button>

            </div>
        </div>
    </div>
</div>
<input type="hidden" id="btn_type">
<input type="hidden" id="selected_packages">
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
                    <div class="col-md-3">
                                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                                    <input type="text" class="form-control input-sm" name="gst_no">
                                                    <label class="floating-label">GSTIN (Optional)</label>
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
                                                                <select class="form-control input-sm" name="vehicle[0][fuel_type]" id="fuel_type_1">
                                                                    <option value="Petrol">Petrol</option>
                                                                    <option value="Diesel">Diesel</option>
                                                                    <option value="CNG">CNG</option>
                                                                    <option value="Electric">Electric</option>
                                                                </select>
                                                                <label class="floating-label required">Fuel Type</label>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12" style="text-align: right;">
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
<div id="send_to_customer_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="text-align: -webkit-center;">
        <div class="modal-content" style="border-radius: 5px !important;width: 60%;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title" id="myModalLabel">Send EMAIL/SMS To Customer</h4>
            </div>
            <div class="modal-body">
               <div class="row">
                   <div class="col-lg-12">
                        <form id="sent_form" name="send_form">
                        <input type="hidden" name="item_type" value="jobcard">
                        <input type="hidden" name="item_id" value="<?php echo $_REQUEST['job_id']; ?>">
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 40%;">Mobile No. </td>
                                <td style="width: 60%;" colspan="2">
                                    <div class="form-group form-material" data-plugin="formMaterial">
                                        <input type="text" class="form-control input-sm" name="contact_no" id="mobile_to_be_send">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Email ID</td>
                                <td colspan="2">
                                    <div class="form-group form-material" data-plugin="formMaterial">
                                        <input type="text" class="form-control input-sm" name="email_address" id="email_to_be_send">
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>Send by</td>
                                <td colspan="2">
                                    <span style="float: left;"> <input checked type="checkbox" id="email_chk"> Email </span>
                                    <span style="float: right;"> <input checked type="checkbox" id="sms_chk"> SMS</span>
                                </td>
                            </tr>
                            <tr>
                                <td style="width: 40%;">Template </td>
                                <td style="width: 55%;">
                                    <div class="form-group form-material floating" data-plugin="formMaterial">
                                        <select class="form-control input-sm" name="template_id" id="template_to_be_send">
                                            <option value="">Select Template</option>
                                        </select>
                                    </div>
                                </td>
                                <td style="width: 5%;" valign="bottom"> 
                                    <a class="btn btn-xs btn-default" target="__blank" href="#" id="view_template"><i class="fa fa-eye"></i></a>
                                </td>
                            </tr>
                            <tr>
                              <td colspan="3" style="text-align: right;">
                                 <button type="button" class="btn btn-info btn-sm" onclick="sendTemplate()"><i class="fa fa-send"></i> Send</button>
                              </td>
                            </tr>
                        </table>
                        </form>
                   </div>
               </div>
            </div>
        </div>
    </div>
</div>
<div id="add_new_item_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 5px !important;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title">Add Item</h4>
            </div>
            <div class="modal-body">
            <div class="row">
            <div class="col-lg-4 col-md-4">
                <label>Item Type</label><br>
                <label><input type="radio" checked name="item_type" value="P" onclick="toggleProductService('product')"> Product</label>
                <label class="float-right"><input type="radio" name="item_type" value="S" onclick="toggleProductService('service')"> Service</label>
            </div>

            </div>
            <form class="form-material form" id="add_product_form" name="add_part" method="post">
            <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="form-group form-material floating" data-plugin="formMaterial">
                    <input type="text" class="form-control input-sm" id="item_name_product" name="item_name">
                    <label class="floating-label required">Item Name</label>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
            <div class="form-group form-material floating" data-plugin="formMaterial" style="display:inline-flex;width:inherit;">
                <input type="text" class="form-control input-sm" name="sell_price" id="sell_price">
                <label class="floating-label">Sell Price</label>
                <div class="input-group" style="width:auto;">
            <div class="bs-dropdown-to-select-group">
                <button type="button" class="btn btn-default dropdown-toggle as-is bs-dropdown-to-select" data-toggle="dropdown">
                    <span data-bind="bs-drp-sel-label">With Tax</span>
                    <input type="hidden" name="sell_price_tax" data-bind="bs-drp-sel-value" value="with_tax">
                </button>
                <ul class="dropdown-menu" role="menu" >
                    <li data-value="with_tax"><a href="#">With Tax</a></li>
                    <li data-value="without_tax"><a href="#">Without Tax</a></li>
                </ul>
            </div>
            </div>
            </div>
        </div>
        </div>
        <div class="row">
        <div class="col-lg-6 col-md-6">
                <div class="form-group form-material floating" data-plugin="formMaterial">
                    <input type="text" class="form-control input-sm" name="item_code">
                    <label class="floating-label">Item Code</label>
                </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="form-group form-material floating" data-plugin="formMaterial" style="display:inline-flex;width:inherit;">
                <input type="text" class="form-control input-sm" name="purchase_price" autocomplete="off" id="purchase_price">
                <label class="floating-label">Purchase Price</label>
                <div class="input-group" style="width:auto;">
            <div class="bs-dropdown-to-select-group">
                <button type="button" class="btn btn-default dropdown-toggle as-is bs-dropdown-to-select" data-toggle="dropdown">
                    <span data-bind="bs-drp-sel-label">With Tax</span>
                    <input type="hidden" name="purchase_price_tax" data-bind="bs-drp-sel-value" value="with_tax">
                </button>
                <ul class="dropdown-menu" role="menu" >
                    <li data-value="with_tax"><a href="#">With Tax</a></li>
                    <li data-value="without_tax"><a href="#">Without Tax</a></li>
                </ul>
            </div>
            </div>
            </div>            
        </div>
    </div>
        <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                 <input type="text" class="form-control input-sm" name="hsn_sac_code">
                <label class="floating-label">HSN Code</label>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <select class="form-control input-sm" name="tax_rate">
                    <option value="0" >None</option>
                    <option value="0">GST @ 0%</option>
                    <option value="0.1">GST @ 0.1%</option>
                    <option value="0.25">GST @ 0.25%</option>
                    <option value="3">GST @ 3%</option>
                    <option value="5">GST @ 5%</option>
                    <option value="12">GST @ 12%</option>
                    <option value="18">GST @ 18%</option>
                    <option value="28">GST @ 28%</option>
                </select>
                <label class="floating-label">GST Tax Rate(%)</label>
            </div>
        </div>
    </div>
    <div class="row">
    <div class="col-lg-12 col-md-12">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" id="description" name="description">
                <label class="floating-label">Remarks</label>
            </div>
        </div>
        </div>
    <div class="row">
        <div class="col-lg-6 col-md-6">
                <div class="form-group form-material floating" data-plugin="formMaterial">
                    <input type="text" class="form-control input-sm" name="opening_stock">
                    <label class="floating-label">Opening Stock</label>
                </div>
        </div>         
        <div class="col-lg-6 col-md-6">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="date">
                <label class="floating-label">As of Date</label>
            </div>
        </div>
        </div>
        <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                    <br>
                    <div class="material-switch">
                            <input type="hidden" name="low_stock_warning" value="N">
                            <input id="enableLowStockCheck" type="checkbox"/>
                            Enable Low Stock Warning &nbsp;&nbsp;&nbsp;<label for="enableLowStockCheck" class="label-primary"></label>
                    </div>                    
            </div>
        </div>
        <div class="col-lg-6 col-md-6" id="lowStockInputDiv" style="display:none;">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="low_stock_unit">
                <label class="floating-label">Low Stock Units</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12" style="text-align: right;">
            <button type="button" class="btn btn-success btn-sm btn-outline btn-1e pull-right" onclick="save_product()"><i class="fa fa-floppy-o"></i> Save Product</button>
        </div>
    </div>
</form>
<form class="form-material form" id="add_service_form" name="add_service" method="post" style="display:none;">
    <div class="row">
    <div class="col-lg-6 col-md-6">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="item_name" id="item_name_service">
                <label class="floating-label required">Service Name</label>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="form-group form-material floating" data-plugin="formMaterial" style="display:inline-flex;width:inherit;">
                <input type="text" class="form-control input-sm" name="sell_price">
                <label class="floating-label">Sells Price</label>
                <div class="input-group" style="width:auto;">
            <div class="bs-dropdown-to-select-group">
                <button type="button" class="btn btn-default dropdown-toggle as-is bs-dropdown-to-select" data-toggle="dropdown">
                    <span data-bind="bs-drp-sel-label">With Tax</span>
                    <input type="hidden" name="sell_price_tax" data-bind="bs-drp-sel-value" value="with_tax">
                </button>
                <ul class="dropdown-menu" role="menu" >
                    <li data-value="with_tax"><a href="#">With Tax</a></li>
                    <li data-value="without_tax"><a href="#">Without Tax</a></li>
                </ul>
            </div>
            </div>
            </div>
        </div>
        </div>
    <div class="row">
    <div class="col-lg-6 col-md-6">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="item_code">
                <label class="floating-label">Service Code</label>
            </div>
        </div>
    <div class="col-lg-6 col-md-6">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="description">
                <label class="floating-label">Remarks</label>
            </div>
    </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                 <input type="text" class="form-control input-sm" name="hsn_sac_code">
                <label class="floating-label">SAC Code</label>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="form-group form-material floating" data-plugin="formMaterial">
            <select class="form-control input-sm" name="tax_rate">
                    <option value="0" >None</option>
                    <option value="0">GST @ 0%</option>
                    <option value="0.1">GST @ 0.1%</option>
                    <option value="0.25">GST @ 0.25%</option>
                    <option value="3">GST @ 3%</option>
                    <option value="5">GST @ 5%</option>
                    <option value="12">GST @ 12%</option>
                    <option value="18">GST @ 18%</option>
                    <option value="28">GST @ 28%</option>
                </select>
                <label class="floating-label">GST Tax Rate(%)</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12" style="text-align: right;">
            <button type="button" class="btn btn-success btn-sm btn-outline btn-1e pull-right" onclick="save_product()"><i class="fa fa-floppy-o"></i> Save Service</button>
        </div>
    </div>
     </form>
         
        </div>
    </div>
</div>
<style type="text/css">
    .ui-autocomplete {
        z-index: 2150000000;
    }
    #add_payment_modal .form-control:disabled,#add_payment_modal .form-control[readonly] {
        background-color: #eee !important;
    }
    .booking_item_table td,
    .booking_item_table th
    {
        vertical-align: bottom;
    }

    .legend-scale ul {
        margin: 0;
        margin-bottom: 5px;
        padding: 0;
        float: left;
        list-style: none;
    }

    .legend-scale ul li {
        font-size: 80%;
        list-style: none;
        margin-left: 0;
        line-height: 18px;
        margin-bottom: 2px;
    }

    ul.legend-labels li span {
        display: block;
        float: left;
        height: 16px;
        width: 30px;
        margin-right: 5px;
        margin-left: 0;
        border: 1px solid #999;
    }


    #sent_form .form-group,
    #add_purchase_order_form .form-group
    {
        margin-bottom: 2px;
    }

    #part_list_tbl tbody tr td input:not([name='part_desc[]']) {
        text-align: center;
    }

    .panel-heading {
        border-top-color: lightsteelblue !important;
    }
    .jobActionOption::after {
        content: none;
    }
</style>
<script>
    var gst_applicable = $('#tax_applicable').val();
    var show_discount_column = $('#discount_applicable').val();
    $('input[name=applicable_to]').change(function() {
        if (this.value == 'spec') {
            $('#option_specific').css('display', 'block');
        } else {
            $('#option_specific').css('display', 'none');
        }
    });

    function closeinvoicemodal() {
        // var job_id = $("#job_id").val();
        // window.location.href = "job-view?job_id=" + job_id;
    }

    function template_setting_modal() {
        $('#template_setting_modal').modal('show');
    }
    function onChangeInsurance(event) {
        if ($('#vehicle_id').val() == "" || $('#customer_id').val() == "") {
            toastr.warning('Please select customer first.');
            $('#auto_vehicle_no').focus();
            return false;
        }
        if(event != '*') {
            $('.insurance_field').css('display','');
        } else {
            $('.insurance_field').css('display','none');
        }

        $('.item_tr').remove();
        itemArray.forEach(element => {
            var Index = $('.item_tr').length + 1;
            Index = checkIndexAvailable('item_tr',Index);
            fillValue(Index,element);
        });
        do_calculation();
        
    }
    function sendSMSToCustomer(order_no) {
        $.ajax({
                method: 'POST',
                url: 'common/commonFunc',
                data: {
                    'do': 'send_sms_to_customer',
                    'order_no': order_no,
                    'job_id': $("#job_id").val()
                },
                success: function(result) {
                    toastr.success("SMS sent successfully !");
                }
        });
    }

    function sendToCustomer(order_no) {
            $.ajax({
                method: 'POST',
                url: 'common/commonFunc',
                data: {
                    'do': 'send_to_customer_whatsapp',
                    'order_no': order_no,
                    'job_id': $("#job_id").val()
                },
                success: function(result) {
                    var returnResponse = JSON.parse(result);
                    window.open(
                        'https://web.whatsapp.com/send?text='+returnResponse['data'],
                        '_blank'
                    );
                }
            });
    }
    function view_sms_email(buffer_id) {
        $.ajax({
            method: 'POST',
            url: 'common/commonFunc',
            data: {
                'do': 'get_reminder_body',
                'id': buffer_id
            },
            success: function(result) {
                var json = JSON.parse(result);
                $('#view_sms_email_modal').modal('show');
                $('#email_body').html(json.data.email_body);
                $('#sms_body').html(json.data.sms_body);
            }
        });
    }
    function sendTemplate() {
        var email_chk = $('#email_chk').prop('checked') ? 'Y' : 'N';
        var sms_chk = $('#sms_chk').prop('checked') ? 'Y' : 'N';

        $.ajax({
            method: 'POST',
            url: 'Transcation/InsertOperation',
            data: {
                'form': $('#sent_form').serialize(),
                'table_name': 'tbl_email_sms_buffer',
                'email_chk': email_chk,
                'sms_chk': sms_chk,
            },
            success: function(result) {
                var res = JSON.parse(result);
                if(res && res['status'] == 200) {
                    toastr.success(res.message);
                } else {
                    toastr.error(res.message);
                }
                $('#send_to_customer_modal').modal('hide');
            }
        });
    }
    function getTemplateBody(value) {
        $.ajax({
            method: 'POST',
            url: 'common/commonFunc',
            data: {
                'do': 'get_template_body',
                'id': value
            },
            success: function(result) {
                var res = JSON.parse(result);
                $('#email_sub').html(res.data.email_subject);
                $('#email_body').html(res.data.email_body);
                $("#sms_body").html(res.data.sms_body);
                $('.emptmessage').css('display', 'none');

            }
        });
    }



    function add_vehicle_detail() {
        $("#cust_vehicle_tab").trigger('click');
    }

    function getpackagedetail() {
        var selectedItems = $('.customMultiSelect').val();
        $.ajax({
            method: 'GET',
            url: 'booking/getpackageDetail?package_ids=' + selectedItems,
            success: function(result) {
                var packageRes = JSON.parse(result);
                var prev_pack_selected = $('#selected_packages').val();
                prevPackLength = prev_pack_selected != "" ? prev_pack_selected.split(',') : [];
                selectedItemLength = selectedItems != "" ? selectedItems.length : [];

                if (prevPackLength.length > selectedItemLength) {
                    if (confirm("Would you like to remove the item of this package ?")) {
                        var removePackId = prevPackLength.filter(x => selectedItems.indexOf(x) === -1);
                        $('.pack_'+removePackId).remove();
                    } else {
                        $('.customMultiSelect').val(prev_pack_selected);
                    }
                }
                $('#selected_packages').val(selectedItems);
                if (packageRes && packageRes.status == '200' && packageRes.data.length > 0) {
                    $.each(packageRes.data, function(i, v) {
                        var Index = ($('.item_tr').length + 1);
                        Index = checkIndexAvailable('item_tr',Index);
                        itemArray.push(v);
                        fillValue(Index,v,'','pack_'+v.package_id);
                    });
                }
                do_calculation();
            }
        });
    }

    function getLabel(result) {
        if (result.item_code != "") {
            return result.item_code + "-" + result.item_name
        } else {
            return result.item_name;
        }
    }

    function deleteJobcard() {
        if ($('#job_id').val() != "") {
            if (confirm('Are you sure want to delete ? Please note that all the details associated with this jobcard will be deleted (i.e jobcard,Estimate,Payment etc..)')) {
                $.ajax({
                    method: 'POST',
                    url: 'common/commonFunc',
                    data: {
                        'jobcard_id': $('#job_id').val(),
                        'do': 'delete_jobcard'
                    },
                    success: function(result) {
                        var parseRes = JSON.parse(result);
                        toastr.success(parseRes.message);
                        window.location.href = 'jobcards';
                    }
                });
            }
        }
    }
    $('#post_status_change').change(function() { 
        if (confirm('Are you sure want to change Jobcard status ? ')) {
                $.ajax({
                    method: 'POST',
                    url: 'common/commonFunc',
                    data: {
                        'jobcard_id': $('#job_id').val(),
                        'status' : this.value,
                        'do': 'change_jobcard_status'
                    },
                    success: function(result) {
                        toastr.success('Jobcard status change successfully !');
                        setTimeout(function() {
                            window.location.reload();
                        },800);
                    }
                });
            }
    });
    $('.next_reminder_date_change').change(function() {
        setNextReminderDate(this.value);
    });
    $('input[name=invoice_status]').change(function() {
        if(this.value == 'paid') {
            $('#amount').val($("#payable_amt").val());
            $('#amount').attr('readonly',true);
            $(".hideOnDue").css('display','table-row');
            $('#amt_partial_paid').css('display','none');
            $('#amt_fully_paid').css('display','block');
        } else if(this.value == 'partial_paid') {
            $('#amt_partial_paid').css('display','table-row');
            $('#amt_fully_paid').css('display','none');
            $('#paid_amount').val('').focus();
            $('#paid_amount').attr('readonly',false);
            $(".hideOnDue").css('display','table-row');
        } else if(this.value == 'due') {
            $('#amount').attr('readonly',false);
            $(".hideOnDue").css('display','none');
        } 
    })
    $('#paid_amount').keyup(function() {
        var paid_amount = this.value != "" ? this.value : 0;
        var payable_amt = $("#payable_amt").val() != "" ? $("#payable_amt").val() : 0; 
        var upaid_amt = parseFloat(payable_amt) - parseFloat(paid_amount);
        $('#remaning_amount').val(upaid_amt);

        if(upaid_amt <= 0) {
            $('#paym_status').val('paid');
        } else {
            $('#paym_status').val('partial_paid');
        }
    })
    function save_invoice(action) {
        var dataArray = do_calculation();
        var paid_amount = 0;
        var status = '';
        if($('input[name=invoice_status]:checked').val() == 'paid') {
            paid_amount = $('#amount').val();
            status = 'paid';
        } else if($('input[name=invoice_status]:checked').val() == 'partial_paid') {
            paid_amount = $('#paid_amount').val();
            status = $('#paym_status').val();
        } else {
            paid_amount = 0;
            status = 'payment_due';
        }
        
        var obj = {
            'paid_amount': paid_amount,
            'refrence_no': $('#refrence_no').val(),
            'invoice_status': status,
            'notes': $('#notes').val(),
            'next_service_date': $('#next_service_date').val(),
            'jobcard_id': $('#job_id').val(),
            'customer_id': $('#customer_id').val(),
            'vehicle_id': $('#vehicle_id').val(),
            'invoice_payment_sms': $('#invoice_payment_sms').prop('checked') ? 'Y' : 'N',
            'invoice_payment_email': $('#invoice_payment_email').prop('checked') ? 'Y' : 'N'
        };
        $.ajax({
            method: 'POST',
            url: 'Transcation/InsertOperation',
            data: {
                'data': obj,
                'table_name': 'tbl_payment',
                'action': action,
                'mainItem': dataArray.main,
                'rowItem': dataArray.rows,
            },
            success: function(result) {
                var parseRes = JSON.parse(result);
                toastr.success(parseRes.message, '');
                
                if($('#insurance_id').val() != "*" && $('#insurance_id').val() != "") {
                    window.open('booking/viewCustomerInvoicePdf?job_id=' + parseRes['data']['jobcard_id'], '_blank');
                } else {
                    window.open('booking/viewInvoicePdf?job_id=' + parseRes['data']['jobcard_id'], '_blank');
                }
                setTimeout(function() {
                    window.location.href = "job-view?job_id=" + parseRes['data']['jobcard_id'];
                }, 2000);
            }
        });
    }

    function save_payment(action) {
        var paid_amount = 0;
        var status = '';
        if($('input[name=invoice_status]:checked').val() == 'paid') {
            paid_amount = $('#amount').val();
            status = 'paid';
        } else if($('input[name=invoice_status]:checked').val() == 'partial_paid') {
            paid_amount = $('#paid_amount').val();
            status = $('#paym_status').val();
        }

        var obj = {
            'payment_type_id': $('#payment_type_id').val(),
            'paid_amount': paid_amount,
            'refrence_no': $('#refrence_no').val(),
            'invoice_status': status,
            'notes': $('#notes').val(),
            'next_service_date': $('#next_service_date').val(),
            'jobcard_id': $('#job_id').val(),
            'customer_id': $('#customer_id').val(),
            'vehicle_id': $('#vehicle_id').val(),
            'invoice_id' : $('input[name=invoice_id]').val(),
            'payment_sms': $('#payment_sms').prop('checked') ? 'Y' : 'N',
            'payment_email': $('#payment_email').prop('checked') ? 'Y' : 'N',
        };
        $.ajax({
            method: 'POST',
            url: 'Transcation/InsertOperation',
            data: {
                'data': obj,
                'table_name': 'tbl_payment',
                'action': action
            },
            success: function(result) {
                var parseRes = JSON.parse(result);
                toastr.success(parseRes.message, '');
                setTimeout(function() {
                    window.location.reload();
                }, 1000);
            }
        });
    }



    function save_jobItem(action) {
        if ($('#customer_id').val() == "" || $('#vehicle_id').val() == "") {
            toastr.warning('Please select customer first.');
            $("#auto_vehicle_no").focus();
            return false;
        }
        var jobcard_id = $('#job_id').val();
        var dataArray = do_calculation();
        
        $.ajax({
            method: 'POST',
            url: 'Transcation/InsertOperation',
            data: {
                'mainItem': dataArray.main,
                'rowItem': dataArray.rows,
                'table_name': 'tbl_jobcard',
                'jobcard_id': jobcard_id,
                'action': action
            },
            success: function(result) {
                var parseRes = JSON.parse(result);
                if (action == 'add_payment') {
                    $('#add_payment_modal').modal('show');
                    $('#ap_payment').css('display', 'block');
                    $('#ap_invoice').css('display', 'none');
                    $("#ap_model_btn").text('Add Payment');
                    $(".processInvoice").css('display', 'none');
                    $(".sendPaymentEmailSMS").css('display', 'table-row');
                    $('#payable_amt').val(parseRes.data.difference);
                    $("#paymnt_due_opt").css('display', 'none');
                    $('#amount').prop('readonly',true);
                    if (parseFloat(parseRes.data.difference) > 0) {
                        $('#amount').val(parseFloat(parseRes.data.difference));
                    }
                } else if (action == 'create_invoice') {
                    $('#add_payment_modal').modal('show');
                    var settingRes = JSON.parse($('#settingArray').val());

                    var next_srv_month = 6;
                    var CurrentDate = new Date();
                    CurrentDate.setMonth(CurrentDate.getMonth() + parseFloat(next_srv_month));
                    $('#next_service_date').datepicker("setDate", CurrentDate);
                    
                    $('#ap_payment').css('display', 'none');
                    $('#ap_invoice').css('display', 'block');
                    $("#ap_model_btn").text('Process Invoice');
                    $("#paymnt_due_opt").css('display', 'inline');
                    $(".processInvoice").css('display', 'table-row');
                    $(".sendPaymentEmailSMS").css('display', 'none');
                    var parseData = parseRes.data;

                    $('#payable_amt').val(parseData.difference);
                    $('#amount').prop('readonly',true);
                    if (parseFloat(parseData.difference) > 0) {
                        $('#amount').val(parseFloat(parseData.difference));

                    }
                } else {
                    toastr.success(parseRes.message, '');
                    setTimeout(function() {
                        window.location = "job-view?job_id=" + parseRes.data.jobcard_id;
                    }, 1000);
                }
            }
        });
    }

    function addEditCust() {
        $('#add_customer_modal').modal('show');
        $.ajax({
            method: 'GET',
            url: 'booking/getcustomerdetail?customer_id=' + $('#customer_id').val() + "&vehicle_id=" + $('#vehicle_id').val(),
            success: function(result) {
                $.each(result.customer, function(k, v) {
                    if ($("input[name=" + k + "]").length > 0) {
                        $("input[name=" + k + "]").val(v).trigger('change');
                    }
                });

                var make = '<option value="*">Select</option>';
                var model = '';
                var variant = '';
                $.each(result.make, function(k, makeArray) {
                    make += '<option value=' + makeArray.make_id + '>' + makeArray.name + '</option>';
                });
                $('#make_1').html(make);
                $.each(result.autoload, function(k, autoloadArray) {
                    if (autoloadArray.length > 0) {
                        var indexSplit = k.split('_');
                        if ($('#model_' + indexSplit[1]).length == 0) {
                            add_vehicle();
                        }
                        $.each(autoloadArray, function(k1, autoload) {
                            if (indexSplit[0] == 'model') {
                                model += '<option value=' + autoload.model_id + '>' + autoload.name + '</option>';
                            } else {
                                variant += '<option value=' + autoload.variant_id + '>' + autoload.name + '</option>';
                            }
                        });
                        $('#make_' + indexSplit[1]).html(make);
                        $('#model_' + indexSplit[1]).html(model);
                        $('#variant_' + indexSplit[1]).html(variant);
                    }
                });
                $.each(result.autos, function(key, autos) {
                    $('#make_' + (key + 1)).val(autos.make_id);
                    $('#model_' + (key + 1)).val(autos.model_id);
                    $('#variant_' + (key + 1)).val(autos.variant_id);
                    $('#reg_' + (key + 1)).val(autos.reg_no).trigger('change');
                    $('#year_' + (key + 1)).val(autos.year).trigger('change');
                });

            }
        });
    }
    function autocompltCust(data) {
        data.name != undefined && data.name != "" ? $('#span_cust_name').text(data.name) : $('#span_cust_name').text('N/A');
        data.email != undefined && data.email != "" ? $('#span_email').text(data.email) : $('#span_email').text('N/A');
        data.mobile_no != undefined && data.mobile_no != "" ? $('#span_contact_no').text(data.mobile_no) : $('#span_contact_no').text('N/A');
        data.billing_address != undefined && data.billing_address != "" ? $('#span_address').text(data.billing_address) : $('#span_address').text('N/A');
        data.gst_no != undefined && data.gst_no != "" ? $('#span_gst_no').text(data.gst_no) : $('#span_gst_no').text('N/A');
        data.reg_no != undefined && data.reg_no != "" ? $('#span_vehicle_no').text(data.reg_no) : $('#span_vehicle_no').text('N/A');
        data.make_name != undefined && data.make_name != "" ? $('#span_make').text(' ' + data.make_name) : $('#span_make').text('N/A');
        data.model_name != undefined && data.model_name != "" ? $('#span_model').text(' ' + data.model_name) : $('#span_model').text('N/A');
        data.variant_name != undefined && data.variant_name != "" ? $('#span_variant').text(' ' + data.variant_name) : $('#span_variant_name').text('N/A');
        data.fuel_type != undefined && data.fuel_type != "" ? $('#span_fuel_type').text(' ' + data.fuel_type) : $('#span_fuel_type').text('N/A');
    }
    function formatDate(date) {
        var yyyy = date.getFullYear().toString();
        var mm = (date.getMonth()+1).toString(); // getMonth() is zero-based
        var dd  = date.getDate().toString();
        return (dd[1]?dd:"0"+dd[0])  + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + yyyy; // padding
    }  
    function loadJobItem(id) {
        $.ajax({
            method: 'POST',
            url: 'common/commonFunc',
            data: { 'id': id,'do':'get_job_item'},
            success: function(result) {
                var data = JSON.parse(result);
                if(data.data.customer_data) {
                    selectedCustomer.push(data.data.customer_data);
                }
                if(data.data.job_items && data.data.job_items.length > 0) {
                    data.data.job_items.forEach(row => {
                        var Index = ($('.item_tr').length + 1);
                        Index = checkIndexAvailable('item_tr',Index);
                        itemArray.push(row);
                        fillValue(Index,row);
                    });
                }

            }
            });
    }
    $(document).on('change','.row_chk', function() {
        do_calculation();
    });
    itemArray = [];
    selectedCustomer = [];
    $(document).ready(function() {
        if($('#job_id').val() != "") {
          loadJobItem($('#job_id').val());
        }
        multiSelect = tail.select(".customMultiSelect", {
            search: true,
            searchMarked: true,
            searchFocus: true,
            multiSelectGroup: true
        });
        $('#next_service_date').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy'
        });
        $('#date').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy'
        });
        $('#expt_delivery_date').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy'
        });
        $('input[name=order_date]').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy'
        });
        $('input[name=due_date]').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy'
        });
        if($('#insurance_id').val() == '*' || $('#insurance_id').val() == '') {
            $('.insurance_field').css('display','none');
        }

        var vdtx = getUrlQueryString()["vdtx"];
        if(vdtx != "" && vdtx != undefined) {
            var decrypted = CryptoJS.AES.decrypt(vdtx, "Secret");
            var job_date = decrypted.toString(CryptoJS.enc.Utf8);
            $('#date').datepicker("setDate", formatDate(new Date(job_date)));
            var settingRes = JSON.parse($('#settingArray').val());
            if (settingRes && settingRes['default_delivery_date']) {
                var exptDelDate = settingRes['default_delivery_date'];
                var CurrentDate = new Date(job_date);
                CurrentDate.setDate(CurrentDate.getDate() + parseInt(exptDelDate));
                $('#expt_delivery_date').datepicker("setDate", formatDate(new Date(CurrentDate)));
            } else {
                $('#expt_delivery_date').datepicker("setDate", formatDate(new Date(job_date)));
            }
        }
        $(document).on('click','.bs-dropdown-to-select-group .dropdown-menu li', function( event ) {
            var $target = $(event.currentTarget);
    		$target.closest('.bs-dropdown-to-select-group')
			.find('[data-bind="bs-drp-sel-value"]').val($target.attr('data-value'))
			.end()
			.children('.dropdown-toggle').dropdown('toggle');
	    	    $target.closest('.bs-dropdown-to-select-group')
    		.find('[data-bind="bs-drp-sel-label"]').text($(this).data('value')).end().find('input[type=hidden]').val($(this).data('value'));
		    return false;
	    });
        if ($("#auto_vehicle_no").length == 1) {
            $("#auto_vehicle_no").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "booking/getcustomerByRegNo",
                        dataType: "json",
                        data: {
                            term: request.term
                        },
                        success: function(data) {
                            response($.map(data, function(result) {
                                return {
                                    label: result.reg_no,
                                    value: result.reg_no,
                                    data: result
                                }
                            }));
                        }
                    });
                },
                select: function(event, ui) {
                    setTimeout(function() {
                        $('#auto_vehicle_no').val('');
                    }, 100);

                    var data = ui.item.data;
                    $('#vehicle_id').val(data.vehicle_id);
                    $('#customer_id').val(data.customer_id);
                    $('#make_model_variant_id').val(data.variant_id+'_'+data.model_id+'_'+data.make_id); 
                    $('#cust_id').val(data.customer_id);
                    $('#cust_model_id').val(data.model_id);
                    selectedCustomer.push(data);
                    autocompltCust(data);
                    $('#addEditCustLabel').html('<i class="fa fa-pencil-square-o"></i> Edit Customer');

                    $.ajax({
                        method: 'GET',
                        url: "booking/getCustomerHistoryData?customer_id=" + data.customer_id + '&vehicle_id=' + data.vehicle_id,
                        success: function(data) {
                            var html = '';
                            if (data && data.service_hist && data.service_hist.length > 0) {
                                data.service_hist.forEach(element => {
                                    html += '<tr>';
                                    html += '<td>' + element['jobcard_no'] + '</td>';
                                    html += '<td>' + element['invoice_no'] + '</td>';
                                    html += '<td>' + element['date'] + '</td>';
                                    html += '<td>' + element['odometer'] + '</td>';
                                    html += '<td><i class="fa fa-rupee"></i> ' + element['total_invoiced'] + '</td>';
                                    html += '<td><i class="fa fa-rupee"></i> ' + element['total_paid'] + '</td>';
                                    html += '<td><i class="fa fa-rupee"></i> ' + (parseFloat(element['total_invoiced']) - parseFloat(element['total_paid'])) + '</td>';
                                    html += '<td> <a class="btn btn-xs btn-info" target="__blank" href="job-view?job_id='+ element['jobcard_id']+'"><i class="fa fa-external-link" aria-hidden="true"></i></a></td>';
                                    html += '</tr>';
                                });
                            } else {
                                    html += '<tr>';
                                    html += '<td style="text-align: center;" colspan="8">No any past service history found.</td>';
                                    html += '</tr>';
                            }
                            $("#service_his_tab tbody").html(html);
                            if(data && data.hist_summary) {
                                $('#serv_his_invoiced').text(data.hist_summary.total_invoiced);
                                $('#serv_his_paid').text(data.hist_summary.total_paid);
                                $('#serv_his_due').text(data.hist_summary.total_due);
                            } else {
                                $('#serv_his_invoiced').text(0);
                                $('#serv_his_paid').text(0);
                                $('#serv_his_due').text(0);
                            }
                        }
                    });
                },
                change: function(event, ui) {
                    // provide must match checking if what is in the input is in the list of results. HACK!
                    var source = $(this).val();
                    var found = false;
                    var item_type = $('#item_type').val();
                    $('.ui-autocomplete li').filter(function() {
                        if (ui.item != null) {
                            if (ui.item.label.toString() == source.toString()) {
                                found = true;
                                return;
                            }
                        }
                    });
                    if (found == false) {
                        $(this).val('');
                        $('#auto_vehicle_no').trigger('change');
                        autocompltCust({});
                    } else {
                        $(this).val('');
                        $('#vehicle_id').val(ui.item.data.vehicle_id);
                        $('#customer_id').val(ui.item.data.customer_id);
                        $('#make_model_variant_id').val(ui.item.data.variant_id+'_'+ui.item.data.model_id+'_'+ui.item.data.make_id);
                        $("#cust_id").val(ui.item.data.customer_id);
                        $('#cust_model_id').val(ui.item.data.model_id);
                    }
                }
            }).autocomplete("instance")._renderItem = function(ul, item) {
                var name = item.data.name;
                var vehicle = item.data.make_name + " " + item.data.model_name;
                var balance = item.data.balance > 0 ? item.data.balance+' (<i style="color:red;" class="fa fa-arrow-up"></i>)' : item.data.balance  < 0 ? item.data.balance+' (<i style="color:green;" class="fa fa-arrow-up"></i>)' : item.data.balance == 0 ? item.data.balance : '';
                return $("<li>")
                    .append("<div> Name : " + name + " <br><span>Vehicle : " + vehicle + "</span><span style='float:right;'>Reg. No : " + item.data.reg_no + "</span><br>Mobile No : " + item.data.mobile_no + "<span style='float:right;'>Balance: <i class='fa fa-rupee'></i> "+balance+"</span></div>")
                    .appendTo(ul);
            };
        }
        $("#search_item").autocomplete({
            source: function(request, response) {
                if ($('#vehicle_id').val() == "" || $('#customer_id').val() == "") {
                    toastr.warning('Please select customer first.');
                    $('#auto_vehicle_no').focus();
                    return false;
                }
                $.ajax({
                    url: "booking/getItemByName",
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        if (data.items.length > 0) {
                            response($.map(data.items, function(result) {
                                return {
                                    label: result.item_name,
                                    value: result.item_id,
                                    data: result
                                }
                            }));
                        }
                         else {
                            var noData = [{
                                'label': 'Add Item',
                                'value': '1'
                            }];
                            response($.map(noData, function(result) {
                                return {
                                    label: '<a onclick="openItemModel()" class="btn btn-success btn-sm btn-block"><i class="fa fa-plus"></i> Add Item</a>',
                                    value: '-10'
                                }
                            }));
                        }
                    }
                });
            },
            focus: function(event, ui) {
                if (ui.item.label == '<a onclick="openItemModel()" class="btn btn-success btn-sm btn-block"><i class="fa fa-plus"></i> Add Item</a>') {
                    return false;
                } else {
                    $('#search_item').val(ui.item.label);
                }
                // Prevent the default focus behavior.
                event.preventDefault();
                // or return false;
            },
            select: function(event, ui) {
                if(ui.item.value != -10) {
                    var data = ui.item.data;
                    var Index = ($('.item_tr').length + 1);
                    Index = checkIndexAvailable('item_tr',Index);
                    itemArray.push(data);
                    fillValue(Index,data,'default');
                    setTimeout(function() {
                        $('#search_item').val('');
                    }, 200);
                } else {
                    openItemModel();
                }

            },
            change: function(event, ui) {
                // provide must match checking if what is in the input is in the list of results. HACK!
                var source = $(this).val();
                var found = false;
                var item_type = $('#item_type').val();
                $('.ui-autocomplete li').filter(function() {
                    if (ui.item != null) {
                        if (ui.item.label.toString() == source.toString()) {
                            found = true;
                            return;
                        }
                    }
                });
                if (found == false) {
                    $(this).val('');
                    $('#idvalue').val('');
                } else {
                    $(this).val(ui.item.label);
                    $('#idvalue').val(ui.item.value);
                }
            }
        });
        $("#search_item_po").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "booking/getItemByName",
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        if (data.items.length > 0) {
                            response($.map(data.items, function(result) {
                                return {
                                    label: result.item_name,
                                    value: result.item_id,
                                    data: result
                                }
                            }));
                        } 
                        // else {
                        //     var noData = [{
                        //         'label': 'Add Item',
                        //         'value': '1'
                        //     }];
                        //     response($.map(noData, function(result) {
                        //         return {
                        //             label: '<a class="btn btn-success btn-sm btn-block"><i class="fa fa-plus"></i> Add Item</a>',
                        //             value: '12'
                        //         }
                        //     }));
                        // }
                    }
                });
            },
            focus: function(event, ui) {
                if (ui.item.label == '<a class="btn btn-success btn-sm btn-block"><i class="fa fa-plus"></i> Add Item</a>') {
                    return false;
                } else {
                    $('#search_item_po').val(ui.item.label);
                }
                // Prevent the default focus behavior.
                event.preventDefault();
                // or return false;
            },
            select: function(event, ui) {
                var data = ui.item.data;
                var Index = ($('.porow_tr').length + 1);
                Index = checkIndexAvailable('porow_tr',Index);
                purchaseOrderRow(Index,data);
                setTimeout(function() {
                    $('#search_item_po').val('');
                }, 200);
            },
            change: function(event, ui) {
                // provide must match checking if what is in the input is in the list of results. HACK!
                var source = $(this).val();
                var found = false;
                $('.ui-autocomplete li').filter(function() {
                    if (ui.item != null) {
                        if (ui.item.label.toString() == source.toString()) {
                            found = true;
                            return;
                        }
                    }
                });
                if (found == false) {
                    $(this).val('');
                    $('#idvalue').val('');
                } else {
                    $(this).val(ui.item.label);
                    $('#idvalue').val(ui.item.value);
                }
            }
        });
    });
    function openItemModel() {
        $('#item_name_product').val($('#search_item').val());
        $('#item_name_product').removeClass('empty');
        setTimeout(function() {
            $('#search_item').val('');
        }, 200);
        $('#add_new_item_modal').modal('show');
    }
    function checkIndexAvailable(className,index) {
        var IndexArray = [];
        $('.'+className).each(function(e) {
            var eleIdSplit = $(this).attr('id').split('_')[2];
            IndexArray.push(parseInt(eleIdSplit));
        });
        if (IndexArray.indexOf(index) > -1) {
            return checkIndexAvailable(className,index + 1);
        }
        return index;
    }
    function save_product() {
        var data = '';
        if($("input[name=item_type]:checked").val() =='P') {
            if($('#item_name_product').val() == "") {
                $('#item_name_product').focus();
                toastr.warning("Item name is required !");
                return false;
            }
            data = $('#add_product_form').serialize();
        } else {
            if($('#item_name_service').val() == "") {
                $('#item_name_service').focus();
                toastr.warning("Item name is required !");
                return false;
            }
            data = $('#add_service_form').serialize();
        }
        $.ajax({
            method: 'POST',
            url: 'Transcation/InsertOperation',
            data: {
                'data': data,
                'item_type': $("input[name=item_type]:checked").val(),
                'table_name': 'tbl_items'
            },
            success: function(result) {
                toastr.success('Item added successfully !');
                var retunData = JSON.parse(result);
                var Index = ($('.item_tr').length + 1);
                Index = checkIndexAvailable('item_tr',Index);
                itemArray.push(retunData['data']);
                fillValue(Index,retunData['data'],'default');
                $('#add_new_item_modal').modal('hide');
                $('#add_product_form').trigger("reset");
                $('#add_service_form').trigger("reset");
                $("#add_product_form").each(function(){
                    $(this).find(':input').addClass('empty');
                });
                $("#add_service_form").each(function(){
                    $(this).find(':input').addClass('empty');
                });
            }
        });
     }
    $('#enableLowStockCheck').on('change',function() {
        if($(this).prop('checked')) {
            $('input[name=low_stock_warning]').val('Y');
            $('#lowStockInputDiv').css('display','block');
            $('input[name=low_stock_unit]').focus();
        } else {
            $('input[name=low_stock_warning]').val('N');
            $('#lowStockInputDiv').css('display','none');
        }
    });
    $('#isGstBill').on('change',function() {
        if($(this).prop('checked')) {
            itemArray.forEach(element => {
                $('.tax_rt_'+element.item_id).val(element.tax_rate);
                do_calculation();
            });
        } else {
            itemArray.forEach(element => {
                $('.tax_rt_'+element.item_id).val('0');
                do_calculation();
            });
        }
    });
    function toggleProductService(type) {
        if(type=='product') {
            $('#add_product_form').css('display','block');
            $('#add_service_form').css('display','none');
        } else {
            $('#add_product_form').css('display','none');
            $('#add_service_form').css('display','block');
        }
    }
    function add_new_item() {
        $('#add_new_item_modal').modal('show');
    }
    function fillValue(vacant_index, data,type='',trID='') {
        var is_tax_enable = $('#tax_applicable').val();
        var is_discount_enable = $('#discount_applicable').val();
        var is_insurance_applicable = $('#insurance_id').val() != "*" ? 'Y' : 'N';
        var description = '';
        var qty = '';
        var unit_price = 0;
        var purchase_price = 0;
        var discount_value = 0;
        var discount_type = 'F';
        var tax_value = 0;
        var tax_rate = '';
        var tax_type = '';
        var line_total = 0;
        if(data && data != '' && type == 'default') {
            qty = data.item_type=='P' ? 1 : '';
            unit_price = data.sell_price;
            purchase_price = data.purchase_price;
            purchase_price_tax = data.purchase_price_tax;
            tax_type = data.sell_price_tax;
            tax_rate = data.tax_rate;
            if(tax_type == 'with_tax' && tax_rate != 0 && unit_price != 0 && is_tax_enable == 'Y') {
                tax_value = ((parseFloat(unit_price) * parseFloat(tax_rate)) / (100 + parseFloat(tax_rate))).toFixed(2);
                unit_price = parseFloat(unit_price) - parseFloat(tax_value);
            } else if(tax_type == 'without_tax' && tax_rate != 0 && unit_price != 0 && is_tax_enable == 'Y') {
                tax_value = (parseFloat(unit_price) * parseFloat(tax_rate) / 100).toFixed(2);
            }
            line_total = parseFloat(unit_price) + parseFloat(tax_value);
        } else if(data && data != '') {
            description = data.description;
            qty = data.qty != '' && data.qty != 0 ? data.qty : '';
            unit_price = data.unit_price != ''  && data.unit_price != 0 ? data.unit_price : '';
            purchase_price = data.purchase_price;
            purchase_price_tax = data.purchase_price_tax;
            discount_type = data.discount_type;
            discount_value = data.discount_type == 'F' ? data.discount_value : data.discount_percentage;
            discount_amount = discount_value;
            tax_value = data.tax_amount;
            tax_rate = data.tax_rate;
            line_total = data.total;
        }
        var html = '';
        html += '<tr class="item_tr '+trID+'" id="item_tr_'+vacant_index+'">';
        html += '<td>';
        html += '<div class="checkbox-custom checkbox-info"><input type="checkbox" id="chk_'+vacant_index+'" class="row_chk"><label style="padding-left:unset;">&nbsp;</label></div>';
        html += '</td>';
        html += '<td>';
        html += '<span id="item_name_'+vacant_index+'">'+data['item_name']+'</span>';
        html += '<div class="form-group form-material floating" data-plugin="formMaterial">';
        html += '<input type="text" style="height:22px;font-size:12px;" name="item_description[]" class="form-control input-sm" value="'+description+'" placeholder="Enter Description (optional)" id="item_description_'+vacant_index+'">';
        html += '<input type="hidden" name="item_id[]" id="item_id_'+vacant_index+'" value="'+data['item_id']+'">';
        html += '<input type="hidden" name="item_type[]" id="item_type_'+vacant_index+'" value="'+data['item_type']+'">';
        html += '</div>';
        html += '</td>';
        html += '<td>';
        html += '<div class="form-group form-material floating" data-plugin="formMaterial">';
        html += '<input type="text"  name="qty[]" id="qty_'+vacant_index+'" style="text-align:right;" class="form-control input-sm do_calculation" value="'+qty+'">';
        html += '</div>';
        html += '</td>';
        html += '<td>';
        html += '<div class="form-group form-material floating" data-plugin="formMaterial">';
        html += '<input type="text" name="unit_price[]" id="unit_price_'+vacant_index+'" style="text-align:right;" data-pPrice="'+purchase_price+'" data-pTaxType="'+purchase_price_tax+'" class="form-control input-sm do_calculation" value="'+unit_price+'">';
        html += '</div>';
        html += '</td>';
        if(is_discount_enable == 'Y') {
            html += '<td>';
            html += '<div class="form-group form-material floating" data-plugin="formMaterial">';
            html += '<span style="display: inline-flex;">';
            html += '<span style="width: 60%;">';
            html += '<input type="text" style="text-align:right;" id="discount_value_'+vacant_index+'" class="form-control input-sm do_calculation" value='+discount_value+' name="discount_value[]">';
            html += '</span>';
            html += '<span style="width: 2%;"></span>';
            html += '<span style="width: 38%;">';
            html += '<select style="text-align-last:center;" class="form-control no-select input-sm do_calculation_drp" id="discount_type_'+vacant_index+'" name="discount_type[]">';
            html += '<option value="P">%</option>';
            html += '<option value="F">&#8377</option>';
            html += '</select>';
            html += '</span>';
            html += '</span>';
            html += '</div>';
            html += '</td>';
        }

        if(is_insurance_applicable == 'Y') {
            html += '<td>';
            html += '<div class="form-group form-material floating" data-plugin="formMaterial">';
            html += '<span style="display: inline-flex;">';
            html += '<span style="width: 60%;">';
            html += '<input type="text" style="text-align:right;" id="insurance_payable_'+vacant_index+'"  class="form-control input-sm do_calculation" name="insurance_payable[]" value='+(data.insurance_payable ? data.insurance_payable : '')+'>';
            html += '</span>';
            html += '<span style="width: 2%;"></span>';
            html += '<span style="width: 38%;">';
            html += '<select style="text-align-last:center;" class="form-control input-sm no-select do_calculation_drp" name="insurance_pay_type[]" id="insurance_pay_type_'+vacant_index+'">';
            html += '<option value="P">%</option>';
            html += '<option value="R">&#8377;</option>';
            html += '</select>';
            html += '</span>';
            html += '</span>';
            html += '</div>';
            html += '</td>';

            html += '<td>';
            html += '<div class="form-group form-material floating" data-plugin="formMaterial">';
            html += '<span style="width: 60%;">';
            html += '<input type="text" readonly style="text-align:right;" id="customer_payable_'+vacant_index+'"  class="form-control input-sm do_calculation" name="customer_payable[]" value='+(data?.customer_payable ? data?.customer_payable : '')+'>';
            html += '</span>';
            html += '</div>';
            html += '</td>';
        }
        if(is_tax_enable == 'Y') {
            html += '<td>';
            html += '<div class="form-group form-material floating" data-plugin="formMaterial">';
            html += '<span style="display: inline-flex;">';
            html += '<span style="width: 60%;">';
            html += '<input type="text" readonly style="text-align:right;" id="tax_value_'+vacant_index+'"  class="form-control input-sm do_calculation" name="tax_value[]" value='+tax_value+'>';
            html += '</span>';
            html += '<span style="width: 2%;"></span>';
            html += '<span style="width: 38%;">';
            html += '<input type="hidden" id="tax_type_'+vacant_index+'" value='+tax_type+'>';
            html += '<select style="text-align-last:center;" class="form-control input-sm no-select do_calculation_drp tax_rt_'+data.item_id+'" name="tax_rate[]" id="tax_rate_'+vacant_index+'">';
            html += '<option value="0">(0%)</option>';
            html += '<option value="0.1">(0.1%)</option>';
            html += '<option value="0.25">(0.25%)</option>';
            html += '<option value="3">(3%)</option>';
            html += '<option value="5">(5%)</option>';
            html += '<option value="12">(12%)</option>';
            html += '<option value="18">(18%)</option>';
            html += '<option value="28">(28%)</option>';
            html += '</select>';
            html += '</span>';
            html += '</span>';
            html += '</div>';
            html += '</td>';
        }
        html += '<td>';
        html += '<div class="form-group form-material floating" data-plugin="formMaterial">';
        html += '<input type="text" style="text-align:right;" readonly id="line_total_'+vacant_index+'" value='+line_total+' name="line_total" class="form-control input-sm do_calculation_drp">';
        html += '</div>';
        html += '</td>';
        html += '</tr>';
        if($('.item_tr').length == 0) {
            $(html).insertAfter('#jobTable > tbody > tr:first');
        } else {
            $(html).insertAfter('#jobTable tbody tr.item_tr:last');
        }
        if($('#isGstBill').prop('checked')) {
            $('#tax_rate_'+vacant_index).val(tax_rate);
        }
        $('#discount_type_'+vacant_index).val(discount_type);
        $('#insurance_pay_type_'+vacant_index).val(data.insurance_pay_type && data.insurance_pay_type !='0' ? data.insurance_pay_type : 'P');
        do_calculation();
    }
    var taxableItems = [];
    function do_calculation() {
        var is_tax_enable = $('#tax_applicable').val();
        var is_discount_enable = $('#discount_applicable').val();
        var total_discount = 0;
        var total_tax = 0;
        var total_line = 0;
        var grand_total = 0;
        var total_taxable_amount = 0;
        var row_item = [];
        var total_insurance_payable = 0;
        var total_customer_payable = 0;
        taxableItems = [];
        var total
        $('.item_tr').each(function(e) {
            var line_total = 0;
            var taxable_amount = 0;
            var discount_amount = 0;
            var index = $(this).attr('id').split('_')[2];
            
            var qty = $('#qty_'+index).val() != "" ? parseFloat($('#qty_'+index).val()) : 0;
            qty = $('#item_type_'+index).val() == 'S' && $('#qty_'+index).val() == 0 ? 1 : qty;
            var unit_price = $('#unit_price_'+index).val() != "" ? parseFloat($('#unit_price_'+index).val()) : 0;
            line_total = parseFloat(qty) * parseFloat(unit_price);
            if(is_discount_enable == 'Y') {
                var discount_type = $('#discount_type_'+index).val();
                var discount_value = $('#discount_value_'+index).val() != "" ? $('#discount_value_'+index).val() : 0;
                var discount_amount = discount_value;
                if(discount_value != 0 && discount_type == 'P') {
                    discount_amount = parseFloat(line_total) * parseFloat(discount_value) / 100;
                }
                total_discount = parseFloat(total_discount) + parseFloat(discount_amount);
                line_total = parseFloat(line_total) - parseFloat(discount_amount);
            }
        
            var insurance_amount = 0;
            if($('#insurance_pay_type_'+index).val() == 'P') {
                var insurance_percentage = $('#insurance_payable_'+index).val() ? $('#insurance_payable_'+index).val() : 0;
                insurance_amount =  (parseFloat(insurance_percentage) * parseFloat(line_total)) / 100;
                var customer_amount = parseFloat(line_total) - parseFloat(insurance_amount);
                $('#customer_payable_'+index).val(customer_amount);
            } else if($('#insurance_pay_type_'+index).val() == 'R') {
                insurance_amount = $('#insurance_payable_'+index).val() ? $('#insurance_payable_'+index).val() : 0;
                var customer_amount = parseFloat(line_total) - parseFloat(insurance_amount);
                $('#customer_payable_'+index).val(customer_amount);
            }

            taxable_amount = line_total;
            if(is_tax_enable == 'Y') {
                total_taxable_amount = parseFloat(total_taxable_amount) + parseFloat(taxable_amount);
                var tax_value = 0;
                var tax_rate = $('#tax_rate_'+index).val() != 0 ? $('#tax_rate_'+index).val() : 0;
                var tax_type = $('#tax_type_'+index).val() != 0 ? $('#tax_type_'+index).val() : '';
                tax_value = (parseFloat(line_total) * parseFloat(tax_rate)/ 100).toFixed(2);
                $('#tax_value_'+index).val(tax_value);
                total_tax = parseFloat(total_tax) + parseFloat(tax_value);
                line_total = parseFloat(line_total) + parseFloat(tax_value);
            }
            grand_total = parseFloat(grand_total) + parseFloat(line_total);
            $('#line_total_'+index).val(line_total.toFixed(2));

            total_insurance_payable += parseFloat(insurance_amount);
            total_customer_payable += parseFloat($('#customer_payable_'+index).val() ? $('#customer_payable_'+index).val() : 0);

            row_item.push({
                item_type: $('#item_type_'+index).val(),
                item_id: $('#item_id_'+index).val(),
                description: $('#item_description_'+index).val(),
                qty: $('#qty_'+index).val(),
                unit_price: $('#unit_price_'+index).val(),
                insurance_payable: $('#insurance_payable_'+index).val() ? $('#insurance_payable_'+index).val() : 0,
                insurance_pay_type: $('#insurance_pay_type_'+index).val() ? $('#insurance_pay_type_'+index).val() : 0,
                customer_payable: $('#customer_payable_'+index).val() ? $('#customer_payable_'+index).val() : 0,
                discount_type : $('#discount_type_'+index).val(),
                discount_value : discount_amount,
                discount_percentage : $('#discount_type_'+index).val() == 'P' ? $('#discount_value_'+index).val() : 0,
                taxable_amount : taxable_amount,
                tax_rate: $('#tax_rate_'+index).val(),
                tax_amount: $('#tax_value_'+index).val(),
                total: line_total
            });
            taxableItems.push({'tax_rate' : $('#tax_rate_'+index).val(),'taxable_amount' : taxable_amount});
        });
        var settings = JSON.parse($('#settingArray').val());
        if(settings['gstin_no']) {
            updateTaxDetails(total_taxable_amount,settings['gstin_no']);
        }
        var jobdetails = {
            'customer_id' : $('#customer_id').val(),
            'vehicle_id' : $('#vehicle_id').val(),
            'customer_id' : $('#customer_id').val(),
            'is_gst_bill' : $('#isGstBill').prop('checked') ? 'Y' : 'N',
            'odometer' : $('#odometer').val(),
            'date' : $('#date').val(),
            'expt_delivery_date' : $('#expt_delivery_date').val(),
            'customer_concern' : $('#customer_concern').val(),
            'mechanic' : $('#mechanic').val(),
            'advisor' : $('#advisor').val(),
            'insurance_id' : $('#insurance_id').val() != '*' ? $('#insurance_id').val() : '',
            'insurance_payable' : total_insurance_payable,
            'customer_payable' : total_customer_payable,
            'taxable_amount': total_taxable_amount,
            'tax_type' : $('#tax_type').val(),
            'total_discount': total_discount.toFixed(0),
            'total_tax': total_tax.toFixed(0),
            'grand_total': grand_total.toFixed(0),
            'jobcard_notes': $('#jobcard_notes').val()
        }
        $('#sub_total_discount').text(total_discount.toFixed(2));
        $('#sub_total_tax').text(total_tax.toFixed(2));
        $('#total_insurance_pay').text(total_insurance_payable.toFixed(2));
        $('#total_customer_pay').text(total_customer_payable.toFixed(2));
        $('#grand_total').text(grand_total.toFixed(2));
        $('#total_amount').text(grand_total.toFixed(0));
        var total_balance_amount = parseFloat(grand_total) - parseFloat($('#received_amount').text());
        $('#balance_amount').text(total_balance_amount.toFixed(0));
        return {'main': jobdetails,'rows':row_item};
    }
    function updateTaxDetails(total_taxable_amount,companyGSTIN) {
        var customerGSTIN = selectedCustomer[0]['gst_no'];

        var totalTaxAmount = 0;
        var totalGST = 0;
        taxableItems.forEach(element => {
            totalTaxAmount += parseFloat(element['taxable_amount']);
            totalGST += parseFloat((element['taxable_amount'] * element['tax_rate']) / 100);
        });
        $('#total_taxable_amount').text(totalTaxAmount.toFixed(2));

        if(companyGSTIN && companyGSTIN != '') {
            custStateCode = '';
            if(customerGSTIN) {
                custStateCode =  customerGSTIN.substr(0, 2);                
            }
            compStateCode =  companyGSTIN.substr(0, 2);

            if(customerGSTIN && customerGSTIN != '' && custStateCode == compStateCode) { // CGST-SGST Applicable.
                var scgst = (totalGST / 2).toFixed(2);
                $('.igst_tr').css('display','none');
                $('.scgst_tr').css('display','');
                $('#total_sgst').text(scgst);
                $('#total_cgst').text(scgst);
                $('#tax_type').val('scgst');
            } else if(customerGSTIN && customerGSTIN != '' && custStateCode != compStateCode) { // IGST Applicable
                $('.igst_tr').css('display','');
                $('.scgst_tr').css('display','none');
                $('#total_taxable_amount').text(totalTaxAmount);
                $('#total_igst').text((totalGST).toFixed(0));
                $('#tax_type').val('igst');
            } else {
                var scgst = (totalGST / 2).toFixed(2);
                $('.igst_tr').css('display','none');
                $('.scgst_tr').css('display','');
                $('#total_sgst').text(scgst);
                $('#total_cgst').text(scgst);
                $('#tax_type').val('scgst');
            }
        }
    }
    function save_po_details() {
        if($('select[name=vendor_id]').val()== '') {
            toastr.warning("Please select vendor name !");
            return false;
        }
        var jobcard_id = $('#job_id').val();
        var dataArray = purchaseOrderCalculation();
        
        $.ajax({
            method: 'POST',
            url: 'Transcation/InsertOperation',
            data: {
                'mainItem': dataArray.main,
                'rowItem': dataArray.rows,
                'table_name': 'tbl_vendor_bills',
                'jobcard_id': jobcard_id
            },
            success: function(result) {
                var parseRes = JSON.parse(result);
            }
        });
    }
    function addDaysToDate(date,days) {
        if(date != "") {
            var CurrentDate = new Date(date.split("-")[2]+'-'+date.split("-")[1]+'-'+date.split("-")[0]);
            CurrentDate.setDate(CurrentDate.getDate() + parseFloat(days));
            return CurrentDate;
        }
    }
    function setNextReminderDate(months) {
        var CurrentDate = new Date();
        CurrentDate.setMonth(CurrentDate.getMonth() + parseFloat(months));
        $('#next_service_date').datepicker("setDate", CurrentDate);
    }
    $(document).on('change', 'select[name=vendor_id]',function() {
        var days = $(this).find(':selected').data('payterms');
        $('#po_payment_term').val(days);
        var order_date = $('input[name=order_date]').val();
        if(order_date) {
            $('input[name=due_date]').datepicker("setDate", addDaysToDate(order_date,days));
        }
    });
    $(document).on('keyup', '#po_payment_term',function() {
        var days = this.value != "" ? this.value : 0;
        var order_date = $('input[name=order_date]').val();
        if(order_date) {
            $('input[name=due_date]').datepicker("setDate", addDaysToDate(order_date,days));
        }
    });
    function purchaseOrderRow(vacant_index, data) {
        var is_tax_enable = $('#tax_applicable').val();
        var is_discount_enable = $('#discount_applicable').val();
        var item_name = '';
        var description = '';
        var qty = 1;
        var purchase_price = 0;
        var tax_value = 0;
        var tax_rate = '';
        var tax_type = '';
        var line_total = 0;
        if(data && data != '') {
            description = data.description != "" ? data.description : '';
            item_name = data.item_name;
            qty = data.qty && data.qty != "" ? data.qty : 1;
            purchase_price = data.purchase_price;
            tax_type = data.purchase_price_tax;
            tax_rate = data.tax_rate;
            if(tax_type == 'with_tax' && tax_rate != 0 && purchase_price != 0 && is_tax_enable == 'Y') {
                tax_value = ((parseFloat(purchase_price) * parseFloat(tax_rate)) / (100 + parseFloat(tax_rate))).toFixed(2);
                purchase_price = parseFloat(purchase_price) - parseFloat(tax_value);
            } else if(tax_type == 'without_tax' && tax_rate != 0 && purchase_price != 0 && is_tax_enable == 'Y') {
                tax_value = (parseFloat(purchase_price) * parseFloat(tax_rate) / 100).toFixed(2);
            }
            line_total = parseFloat(purchase_price) + parseFloat(tax_value);
        }

        var html = '';
        html += '<tr class="porow_tr" id="porow_tr_'+vacant_index+'">';
        html += '<td>';
        html += '<i class="fa fa-trash"></i>';
        html += '</td>';
        html += '<td>';
        html += '<span>'+item_name+'</span>';
        html += '<div class="form-group form-material floating" data-plugin="formMaterial">';
        html += '<input type="text" style="height:22px;font-size:12px;" name="description[]" class="form-control input-sm" value="'+description+'" placeholder="Enter Description (optional)" id="po_item_description_'+vacant_index+'">';
        html += '<input type="hidden" name="item_id[]" id="po_item_id_'+vacant_index+'" value="'+data['item_id']+'">';
        html += '</div>';
        html += '</td>';
        html += '<td>';
        html += '<div class="form-group form-material floating" data-plugin="formMaterial">';
        html += '<input type="text"  name="qty[]" id="po_qty_'+vacant_index+'" style="text-align:right;" class="form-control input-sm poRowChange" value='+qty+'>';
        html += '</div>';
        html += '</td>';
        html += '<td>';
        html += '<div class="form-group form-material floating" data-plugin="formMaterial">';
        html += '<input type="text" name="unit_price[]" id="po_purchase_price_'+vacant_index+'" style="text-align:right;" class="form-control input-sm poRowChange" value='+purchase_price+'>';
        html += '</div>';
        html += '</td>';
        if(is_discount_enable == 'Y') {
            html += '<td>';
            html += '<div class="form-group form-material floating" data-plugin="formMaterial">';
            html += '<span style="display: inline-flex;">';
            html += '<span style="width: 60%;">';
            html += '<input type="text" style="text-align:right;" id="po_discount_value_'+vacant_index+'" class="form-control input-sm poRowChange" name="discount_value[]">';
            html += '</span>';
            html += '<span style="width: 2%;"></span>';
            html += '<span style="width: 38%;">';
            html += '<select style="text-align-last:center;" class="form-control no-select input-sm poRowChangeDrp" id="po_discount_type_'+vacant_index+'" name="discount_type[]">';
            html += '<option value="F">&#8377</option>';
            html += '<option value="P">%</option>';
            html += '</select>';
            html += '</span>';
            html += '</span>';
            html += '</div>';
            html += '</td>';
        }
        if(is_tax_enable == 'Y') {
            html += '<td>';
            html += '<div class="form-group form-material floating" data-plugin="formMaterial">';
            html += '<span style="display: inline-flex;">';
            html += '<span style="width: 60%;">';
            html += '<input type="text" readonly style="text-align:right;" id="po_tax_value_'+vacant_index+'"  class="form-control input-sm poRowChange" name="tax_value[]" value='+tax_value+'>';
            html += '</span>';
            html += '<span style="width: 2%;"></span>';
            html += '<span style="width: 38%;">';
            html += '<input type="hidden" id="po_tax_type_'+vacant_index+'" value='+tax_type+'>';
            html += '<select style="text-align-last:center;" class="form-control input-sm no-select poRowChangeDrp" name="tax_rate[]" id="po_tax_rate_'+vacant_index+'">';
            html += '<option value="0">(0%)</option>';
            html += '<option value="0.1">(0.1%)</option>';
            html += '<option value="0.25">(0.25%)</option>';
            html += '<option value="3">(3%)</option>';
            html += '<option value="5">(5%)</option>';
            html += '<option value="12">(12%)</option>';
            html += '<option value="18">(18%)</option>';
            html += '<option value="28">(28%)</option>';
            html += '</select>';
            html += '</span>';
            html += '</span>';
            html += '</div>';
            html += '</td>';
        }
        html += '<td>';
        html += '<div class="form-group form-material floating" data-plugin="formMaterial">';
        html += '<input type="text" style="text-align:center;" id="po_line_total_'+vacant_index+'" value='+line_total+' name="total" class="form-control input-sm">';
        html += '</div>';
        html += '</td>';
        html += '</tr>';
        if($('.porow_tr').length == 0) {
            $(html).insertAfter('#purchaseTable > tbody > tr:first');
        } else {
            $(html).insertAfter('#purchaseTable tbody tr.porow_tr:last');
        }
        $('#po_tax_rate_'+vacant_index).val(tax_rate);
        purchaseOrderCalculation();
    }
    function purchaseOrderCalculation() {
        var is_tax_enable = $('#tax_applicable').val();
        var is_discount_enable = $('#discount_applicable').val();
        var total_discount = 0;
        var total_tax = 0;
        var total_line = 0;
        var grand_total = 0;
        var total_taxable_amount = 0;
        var row_item = [];
        $('.porow_tr').each(function(e) {
            var discount_value = 0;
            var discount_amount = 0;
            var discount_type = 'F';
            var line_total = 0;
            var taxable_amount = 0;
            var index = $(this).attr('id').split('_')[2];
            //if($('#chk_'+index).prop('checked')) {
            var qty = $('#po_qty_'+index).val() != "" ? parseFloat($('#po_qty_'+index).val()) : 0;
            var purchase_price = $('#po_purchase_price_'+index).val() != "" ? parseFloat($('#po_purchase_price_'+index).val()) : 0;
            line_total = parseFloat(qty) * parseFloat(purchase_price);
            taxable_amount = line_total;
        
            if(is_discount_enable == 'Y') {
                var discount_type = $('#po_discount_type_'+index).val() != "" ? $('#po_discount_type_'+index).val() : 'F';
                var discount_value = $('#po_discount_value_'+index).val() != "" ? $('#po_discount_value_'+index).val() : 0;
                var discount_amount = discount_value;
                if(discount_value != 0 && discount_type == 'P') {
                    discount_amount = parseFloat(line_total) * parseFloat(discount_value) / 100;
                }
                total_discount = parseFloat(total_discount) + parseFloat(discount_amount);
                line_total = parseFloat(line_total) - parseFloat(discount_amount);
                taxable_amount = parseFloat(taxable_amount) - parseFloat(discount_amount);
            }
            if(is_tax_enable == 'Y') {
                total_taxable_amount = parseFloat(total_taxable_amount) + parseFloat(taxable_amount);
                var tax_value = 0;
                var tax_rate = $('#po_tax_rate_'+index).val() != 0 ? $('#po_tax_rate_'+index).val() : 0;
                var tax_type = $('#po_tax_type_'+index).val() != 0 ? $('#po_tax_type_'+index).val() : '';
                tax_value = (parseFloat(line_total) * parseFloat(tax_rate)/ 100).toFixed(2);
                $('#po_tax_value_'+index).val(tax_value);
                total_tax = parseFloat(total_tax) + parseFloat(tax_value);
                line_total = parseFloat(line_total) + parseFloat(tax_value);
            }
            grand_total = parseFloat(grand_total) + parseFloat(line_total);
            $('#po_line_total_'+index).val(line_total.toFixed(2));

            row_item.push({
                item_id: $('#po_item_id_'+index).val(),
                description: $('#po_item_description_'+index).val(),
                qty: $('#po_qty_'+index).val(),
                unit_price: $('#po_purchase_price_'+index).val(),
                discount_type : $('#po_discount_type_'+index).val(),
                discount_value : discount_amount,
                discount_percentage : $('#po_discount_type_'+index).val() == 'P' ? $('#po_discount_value_'+index).val() : 0,
                taxable_amount : taxable_amount,
                tax_rate: $('#po_tax_rate_'+index).val(),
                tax_value: $('#po_tax_value_'+index).val(),
                total: line_total                
            });
            //}
        });
        var podetails = {
            'vendor_id' : $('select[name=vendor_id]').val(),
            'order_date' : $('input[name=order_date]').val(),
            'payment_term' : $('input[name=po_payment_term]').val(),
            'due_date' : $('input[name=due_date]').val(),
            'taxable_amount' : total_taxable_amount,
            'total_discount' : total_discount.toFixed(0),
            'total_tax' : total_tax.toFixed(0),
            'grand_total' : grand_total.toFixed(0),
        }
        $('#po_total_discount').text(total_discount.toFixed(0));
        $('#po_total_tax').text(total_tax.toFixed(0));
        $('#po_grand_total').text(grand_total.toFixed(0));
        return {'main': podetails,'rows':row_item};
    }
    function itemAction(selected_value) {
            var isSelected = 'N'; // check at-least one selected..
            var selectedItem = 0;
            $(".row_chk").each(function(i, v) {
                if ($(this).prop('checked')) {
                    isSelected = 'Y';
                    selectedItem++;
                }
            });
            if (isSelected == 'N') {
                toastr.warning("Please select at-least one item !");
                return false;
            }
            if (selected_value == 1) { // delete section
                $(".row_chk").each(function(i, v) {
                    if ($(this).prop('checked')) {
                        var index = $(this).attr('id').split('_')[1];
                        $('#item_tr_' + index).remove();
                    }
                });
                do_calculation();
            } else if (selected_value == 2) { // create purchase order section....
                if ($('#vehicle_id').val() == "" || $('#customer_id').val() == "") {
                    toastr.warning('Please Select Customer.');
                    $('#auto_vehicle_no').focus();
                    return false;
                }
                $('#add_purchase_order').modal('show');
                $('#purchaseTable tbody tr.porow_tr').remove();
                var days = $('select[name=vendor_id]').find(':selected').data('payterms');
                if($('input[name=order_date]').val() != "") {
                    $('input[name=due_date]').datepicker("setDate", addDaysToDate($('input[name=order_date]').val(),days));
                }
                $('.item_tr').each(function(e) {
                    var row_index = $(this).attr('id').split('_')[2];
                    var data = {
                        'qty': $('#qty_'+row_index).val(),
                        'item_id': $('#item_id_'+row_index).val(),
                        'item_name': $('#item_name_'+row_index).text(),
                        'description': $('#item_description_'+row_index).val(),
                        'purchase_price' : $('#unit_price_'+row_index).data('pprice'),
                        'purchase_price_tax': $('#unit_price_'+row_index).data('ptaxtype'),
                        'tax_rate' : $('#tax_rate_'+row_index).val()
                    };
                    var Index = ($('.porow_tr').length + 1);
                    Index = checkIndexAvailable('porow_tr',Index);
                    purchaseOrderRow(Index,data);
                });
            }
    }
    $(function() {
        $(document).on('change', 'select.do_calculation_drp', function() {
            do_calculation();
        });
        $(document).on('keyup', '.do_calculation', function() {
            do_calculation();
        });
        $(document).on('change', 'select.poRowChangeDrp', function() {
            purchaseOrderCalculation();
        });
        $(document).on('keyup', '.poRowChange', function() {
            purchaseOrderCalculation();
        });
        
        $(document).on('keyup', '.total_paid', function(i, v) {
            var total_paid = $(this).val() != "" ? parseFloat($(this).val()) : 0;
            var ele_index = $(this).attr('id').split('_');
            var total_payment = $('#total_payment_' + ele_index[2]).val() != "" ? parseFloat($('#total_payment_' + ele_index[2]).val()) : 0;
            var total_due = total_payment - total_paid;
            $('#total_due_' + ele_index[2]).val(total_due);
        });
        $(document).on('change', '#item_type', function() {
            $('#search_item').val('');
        })
        $(document).on('change', 'select.make_drp', function() {
            var id = $(this).attr('id').split('_');
            var make = this.value;
            $('#model_' + id[1]).val('');
            $.ajax({
                method: 'GET',
                url: 'customer/getModelByMake?make_id=' + make,
                success: function(result) {
                    var option_html = '<option value="*">Select</option>';
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
                    var option_html = '<option value="*">Select</option>';
                    $(result).each(function(i, v) {
                        option_html += '<option value="' + v.variant_id + '">' + v.name + '</option>';
                    });
                    $('#variant_' + id[1]).html(option_html);
                }
            });

        });
    });

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
        
        var vehicle_id = $('#vehicle_id').val();
        var customer_id = $('#customer_id').val();

        $.ajax({
            type: 'POST',
            url: 'Transcation/InsertOperation',
            data: {
                'vehicle_id': vehicle_id,
                'cust_id': customer_id,
                'customer_detail': jQuery('#add_customer_form').serialize(),
                'vehicle_detail': jQuery('#add_vehicle_form').serialize(),
                'table_name': 'tbl_customer'
            },
            success: function(result) {
                var res = JSON.parse(result);
                if (res != undefined && res.status == '200') {
                    toastr.success(res.message, '');
                    if (res.data[0] != undefined && res.data[0] != '') {
                        autocompltCust(res.data[0]);
                        selectedCustomer.push(res.data[0]);
                        $('#vehicle_id').val(res['data'][0]['vehicle_id']);
                        $('#customer_id').val(res['data'][0]['customer_id']);
                    }
                    $('#add_customer_modal').modal('hide');
                    $('#addEditCustLabel').html('<i class="fa fa-pencil-square-o"></i> Edit Customer');
                } else {
                    toastr.error(res.message, '');
                }
            }
        });
    }

    function add_vehicle() {
        var make_select = $('select#make_1').html();
        var cnt = $(".vehicle_div").length + 1;
        var html = "<div class='row vehicle_div'>";
        html += "<div class='col-lg-12 col-sm-12'>";
        html += "<table style='width:100%;'>";
        html += "<tr>";
        html += "<td style='width: 20%;'>";
        html += "<div class='form-group form-material floating' data-plugin='formMaterial'>";
        html += "<select class='form-control input-sm make_drp' name='make[]' id='make_" + cnt + "'>";
        html += make_select;
        html += "</select>";
        html += "</div>";
        html += "</td>";
        html += "<td style='width: 20%;'>";
        html += "<div class='form-group form-material floating' data-plugin='formMaterial'>";
        html += "<select class='form-control input-sm model_drp' name='model[]' id='model_" + cnt + "'>";
        html += "<option value=''>Select Model</option>";
        html += "</select>";
        html += "</div>";
        html += "</td>";
        html += "<td style='width: 20%;'>";
        html += "<div class='form-group form-material floating' data-plugin='formMaterial'>";
        html += "<select class='form-control input-sm' name='variant[]' id='variant_" + cnt + "'>";
        html += "<option value=''>Select Variant</option>";
        html += "</select>";
        html += "</div>";
        html += "</td>";
        html += "<td style='width: 20%;'>";
        html += "<div class='form-group form-material floating' data-plugin='formMaterial'>";
        html += "<input type='text' class='form-control input-sm' name='reg_no[]' id='reg_" + cnt + "' onblur=check_duplication('tbl_customer_vehicle','reg_no',this,'vehicle_id',0,'Duplicate Licence Plate found. please enter another.')>";
        html += "<label class='floating-label'>Vehicle No.</label>";
        html += "</div>";
        html += "</td>";
        html += "<td style='width: 15%;'>";
        html += "<div class='form-group form-material floating' data-plugin='formMaterial'>";
        html += "<input type='text' class='form-control input-sm' name='year[]' id='year_" + cnt + "'>";
        html += "<label class='floating-label'>Year</label>";
        html += "</div>";
        html += "</td>";
        html += "<td style='width: 5%;'>";
        html += "</td>";
        html += "</tr>";
        html += "</table>";
        html += "</div>";
        $('.vehicle_div:last').after(html);
    }

    function opencreateTicketModal() {
        $('.createBtn').css('display', 'block');
        $('.updateBtn').css('display', 'none');
        $("#add_ticket_modal").modal('show');
        $('#ticket_log_form textarea[name=description]').val('');
        $('#ticket_log_form select[name=status]').val('');
        $('#ticket_log_form select[name=assign_to]').val('');
    }

    function createTicket() {
        if ($("#job_id").val() != "") {
            $.ajax({
                method: 'POST',
                url: 'Transcation/InsertOperation',
                data: $('#ticket_log_form').serialize() + '&jobcard_id=' + $("#job_id").val() + '&table_name=tbl_ticket',
                success: function(result) {
                    toastr.success('Complain registered successfully !!');        
                    setTimeout(function() {
                        window.location.reload();
                    },1000);
                }
            });
        } else {
            toastr.warning('Without jobcard compalint can not be log.');
        }
    }

    function updateTicket(ticketID) {
        if ($("#job_id").val() != "") {
            $.ajax({
                method: 'POST',
                url: 'Transcation/InsertOperation',
                data: $('#ticket_log_form').serialize() + '&table_name=tbl_ticket',
                success: function(result) {
                    window.location.reload();
                }
            });
        } else {
            toastr.warning('Without jobcard compalint can not be log.');
        }
    }

    function getTicketDetail(ticketID) {
        if (ticketID != "") {
            $('.createBtn').css('display', 'none');
            $('.updateBtn').css('display', 'block');
            $.ajax({
                method: 'GET',
                url: 'Ticket/getTicketDetail?ticket_id=' + ticketID,
                success: function(result) {
                    if (result['ticketData']) {
                        $('#ticket_log_form input[name=ticket_id]').val(result['ticketData']['ticket_id']);
                        $('#ticket_log_form textarea[name=description]').val(result['ticketData']['description']);
                        $('#ticket_log_form select[name=status]').val(result['ticketData']['status']);
                        $('#ticket_log_form select[name=assign_to]').val(result['ticketData']['assign_to']);
                        $("#add_ticket_modal").modal('show');
                    }
                }
            });
        } else {
            toastr.warning('Ticket Id not found! please try after sometime.');
        }
    }

    function deleteTicket() {
        if ($('#ticket_log_form input[name=ticket_id]').val() != '') {
            $.ajax({
                method: 'GET',
                url: 'Ticket/deleteTicket?ticket_id=' + $('#ticket_log_form input[name=ticket_id]').val(),
                success: function(result) {
                    window.location.reload();
                }
            });
        } else {
            toastr.warning('Ticket Id not found! please try after sometime.');
        }
    }
</script>
