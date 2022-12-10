<?php
$GLOBALS['title_left'] = '';
$is_logo=0;
?>
<div class="row">
    <div class="col-xl-12 col-md-12">
        <div class="nav-tabs-horizontal" data-plugin="tabs">
            <ul class="nav nav-tabs nav-tabs-line" role="tablist">
                <li class="nav-item" role="presentation"><a id="garageInfLink" class="nav-link active" data-toggle="tab" href="#tab1"  role="tab">Garage Information</a></li>
                <li class="nav-item" role="presentation"><a id="jobinvoiceLink" class="nav-link" data-toggle="tab" href="#tab4" role="tab">Jobcard / Invoice</a></li>
                <li class="nav-item" role="presentation"><a id="EMALSMSLink" class="nav-link" data-toggle="tab" href="#tab7" role="tab">Reminders</a></li>
            </ul>
            <div class="tab-content pt-20">
                <div class="tab-pane active" id="tab1" role="tabpanel">
                    <form autocomplete="off" class="form" id="garage_information_form" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-lg-3 col-md-3 col-xs-12">
                                <div class="imgUp">
                                    <div <?php
                                            if (file_exists('uploads/logos/garage_' . $setting['garage_id'] . '/' . $setting['logo_path']) && $setting['logo_path'] != "") { $is_logo = 1; ?> style="background-image: url('<?php echo UPLOAD_PATH_URL . 'logos/garage_' . $setting['garage_id'] . '/' . $setting['logo_path']; ?>')" <?php } ?> class="imagePreview">
                                    </div>
                                    <label class="btn btn-primary btn-sm uploadlogobtn">
                                        <input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;" name="filename">
                                        <span><?php if($is_logo == 1) { ?> Change Logo <?php } else { ?> Upload Logo  <?php } ?></span>
                                    </label>
                                    <input type="hidden" name="logo_path" value="<?php echo $setting['logo_path']; ?>">
                                    <label class="btn btn-primary btn-sm removelogbtn" onclick="removeLogo();" style="display: <?php if($is_logo == 1) {  echo 'inline-block'; } else { echo 'none'; } ?>">
                                        Remove
                                    </label>
                                </div>
                            </div>
                            <div class="col-lg-9 col-md-9 col-xs-12">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-xs-12">
                                        <div class="form-group form-material floating" data-plugin="formMaterial">
                                            <input type="text" name="name" class="form-control input-sm" value="<?php if (isset($setting['name'])) {
                                                                                                                    echo $setting['name'];
                                                                                                                } ?>">
                                            <label class="floating-label required">Name</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12">
                                        <div class="form-group form-material floating" data-plugin="formMaterial">
                                            <input type="text" name="contact_no" class="form-control input-sm" required value="<?php
                                                                                                                                if (isset($setting['contact_no'])) {
                                                                                                                                    echo $setting['contact_no'];
                                                                                                                                } ?>">
                                            <label class="floating-label required">Contact No. </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-xs-12">
                                        <div class="form-group form-material floating" data-plugin="formMaterial">
                                            <input type="text" name="address" class="form-control input-sm" required value="<?php
                                                                                                                                if (isset($setting['address'])) {
                                                                                                                                    echo $setting['address'];
                                                                                                                                } ?>">
                                            <label class="floating-label required">Address </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12">
                                        <div class="form-group form-material floating" data-plugin="formMaterial">
                                            <input type="text" name="email" class="form-control input-sm" required value="<?php if (isset($setting['email'])) {
                                                                                                                                echo $setting['email'];
                                                                                                                            } ?>">
                                            <label class="floating-label">Email</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12">
                                        <div class="form-group form-material floating" data-plugin="formMaterial">
                                            <input type="text" name="web" class="form-control input-sm" required value="<?php
                                                                                                                        if (isset($setting['web'])) {
                                                                                                                            echo $setting['web'];
                                                                                                                        } ?>">
                                            <label class="floating-label">Website</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12">
                                        <div class="form-group form-material floating" data-plugin="formMaterial">
                                            <input type="text" name="contact_person_name" class="form-control input-sm" required value="<?php
                                                                                                                                        if (isset($setting['contact_person_name'])) {
                                                                                                                                            echo $setting['contact_person_name'];
                                                                                                                                        } ?>">
                                            <label class="floating-label required">Contact Person Name</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-xs-12">
                                        <div class="form-group form-material floating" data-plugin="formMaterial">
                                            <input type="text" name="alternate_contact" class="form-control input-sm" required value="<?php
                                                                                                                                        if (isset($setting['alternate_contact'])) {
                                                                                                                                            echo $setting['alternate_contact'];
                                                                                                                                        } ?>">
                                            <label class="floating-label">Alternate Contact No.</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-lg-12" style="text-align: right;">
                            <button type="button" class="btn btn-primary btn-sm pull-right" onclick="saveSetting()"><i class="fa fa-save"></i> Save & Next <i class="fa fa-arrow-right"></i></button>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab3" role="tabpanel">
                    <form autocomplete="off" class="form" id="payment_type_form" method="post">
                        <table id="payment_type_table" style="width: 40%;" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 90%;">Name</th>
                                    <th style="width: 10%;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="form-group form-material floating" data-plugin="formMaterial">
                                            <input type="text" name="name[]" class="form-control input-sm" value="<?php echo isset($payment_methods[0]['name']) ? $payment_methods[0]['name'] : ''; ?>">
                                        </div>
                                    </td>
                                    <td style="text-align: center;" onclick="addNewPaymentType()"><i class="fa fa-plus"></i></td>
                                </tr>
                                <?php foreach ($payment_methods as $kpm => $pm) {
                                    if ($kpm == 0) {
                                        continue;
                                    } ?>
                                    <tr>
                                        <td>
                                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                                <input type="text" name="name[]" class="form-control input-sm" value="<?php echo $pm['name']; ?>">
                                            </div>
                                        </td>
                                        <td style="text-align: center;" class="paymentTypeTd"><i class="fa fa-trash"></i></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </form>
                    <div class="row">
                        <div class="col-lg-12" style="text-align: right;">
                            <button type="button" class="btn btn-primary btn-sm pull-right" onclick="goToPrevious(1)"><i class="fa fa-arrow-left"></i> Previous</button>
                            <button type="button" class="btn btn-primary btn-sm pull-right" onclick="saveOtherThanFirstTab(3)"><i class="fa fa-save"></i> Save & Next <i class="fa fa-arrow-right"></i></button>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab4" role="tabpanel">
                    <form autocomplete="off" class="form" id="invoice_jobcard_form" method="post">
                        <div class="row">
                            <div class="col-lg-2 col-md-2 col-xs-6">
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <input type="text" name="jobcard_no_start" class="form-control input-sm" value="<?php echo $setting['jobcard_no_start']; ?>" />
                                    <label class="floating-label">Jobcard No. Start From</label>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-xs-6">
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <input type="text" name="invoice_no_start" class="form-control input-sm" value="<?php echo $setting['invoice_no_start']; ?>" />
                                    <label class="floating-label">Invoice No. Start From</label>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-xs-6">
                                <br>
                                <label class="floating-label">GST Applicable ?</label>
                                <span style="float: right;">
                                    <span>N</span>
                                    <input type="checkbox" id="gst_applicable" data-plugin="switchery" <?php if($setting['gst_applicable'] == 'Y') { echo "checked"; } ?> />
                                    <input type="hidden" name="gst_applicable" value="<?php echo $setting['gst_applicable']; ?>">
                                    <span>Y</span>
                                </span>
                            </div>
                            <div class="col-lg-2 col-md-2 col-xs-6" id="gstin_no_div">
                                <div class="form-group floating-label">
                                    <div class="form-group form-material floating" data-plugin="formMaterial">
                                        <input type="text" name="gstin_no" class="form-control input-sm" required value="<?php echo $setting['gstin_no']; ?>">
                                        <label class="floating-label">GSTIN No</label>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-lg-2 col-md-2 col-xs-6">
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                        <input type="text" name="invoice_due_after_days" class="form-control input-sm" value="<?php echo $setting['invoice_due_after_days']; ?>">
                                        <label class="floating-label">Invoice due after days</label>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-xs-6">
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                        <select class="form-control input-sm" name="send_feedback">
                                            <option <?php echo $setting['send_feedback'] == 0 ? 'selected': ''; ?> value="0">Do not send</option>
                                            <option <?php echo $setting['send_feedback'] == 1 ? 'selected': ''; ?> value="1">After 1 days of service</option>
                                            <option <?php echo $setting['send_feedback'] == 2 ? 'selected': ''; ?> value="2">After 2 days of service</option>
                                            <option <?php echo $setting['send_feedback'] == 3 ? 'selected': ''; ?> value="3">After 3 days of service</option>
                                            <option <?php echo $setting['send_feedback'] == 4 ? 'selected': ''; ?> value="4">After 4 days of service</option>
                                            <option <?php echo $setting['send_feedback'] == 5 ? 'selected': ''; ?> value="5">After 5 days of service</option>
                                            <option <?php echo $setting['send_feedback'] == 10 ? 'selected': ''; ?> value="10">After 10 days of service</option>
                                            <option <?php echo $setting['send_feedback'] == 15 ? 'selected': ''; ?> value="15">After 15 days of service</option>
                                            <option <?php echo $setting['send_feedback'] == 20 ? 'selected': ''; ?> value="20">After 20 days of service</option>
                                            <option <?php echo $setting['send_feedback'] == 30 ? 'selected': ''; ?> value="30">After 30 days of service</option>
                                        </select>
                                        <label class="floating-label">Send feedback SMS/Email</label>
                                </div>
                            </div> -->
                            </div>
                            <br>
                            <br>
                            <div class="row">
                            <div class="col-lg-6 col-md-6 col-xs-12">
                                <label class="floating-label" style="font-weight: bold;">Estimate Terms and Condition</label>
                                <div class="form-group form-material" data-plugin="formMaterial">
                                    <textarea class="form-control textarea" name="estimate_notes" rows="5"><?php echo $setting['estimate_notes']; ?></textarea>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-xs-12">
                                <label class="floating-label" style="font-weight: bold;">Invoice Terms and Condition</label>
                                <div class="form-group form-material" data-plugin="formMaterial">
                                    <textarea class="form-control textarea" rows="5" name="invoice_notes"><?php echo $setting['invoice_notes']; ?></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12" style="text-align: right;">
                                <button type="button" class="btn btn-primary btn-sm pull-right" onclick="goToPrevious(1)"><i class="fa fa-arrow-left"></i> Previous</button>
                                <button type="button" class="btn btn-primary btn-sm pull-right" onclick="saveOtherThanFirstTab(2)"><i class="fa fa-save"></i> Save & Next <i class="fa fa-arrow-right"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="tab-pane" id="tab7" role="tabpanel">
                                <!-- <div class="row">
                            <div class="col-lg-3 col-md-3 col-xs-12">
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <input type="text" class="form-control" name="email_sender_name" value="<?php echo $setting['email_sender_name']; ?>">
                                    <label class="floating-label">Sender Name</label>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3 col-xs-12">
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <input type="text" class="form-control" name="email_sender_contact_no" value="<?php echo $setting['email_sender_contact_no']; ?>">
                                    <label class="floating-label">Sender Contact No.</label>
                                </div>
                            </div>
                               <div class="col-lg-3 col-md-3 col-xs-12">
                                       <label class="floating-label">SMS Balance</label>
                                       <span style="float:right;font-weight:bold;"><?php echo $setting['sms_balance']; ?></span>
                               </div>
                            </div> -->
            <div class="row">
            <div class="col-lg-4">
               <div class="card border border-primary">
               <div class="card-block">
                <h5 class="card-title">Payment reminders to customer<hr></h5>
                <table style="width:100%">
               <tr>
                <td style="width:95%;">5 days before due date</td>
                <td style="width:5%;"><input id="5days_before_payment" type="checkbox" <?php if(isset($setting['5days_before_payment']) && $setting['5days_before_payment'] == 'Y') { echo "checked"; }?>></td>
               </tr>
               <tr>
                <td>3 days before due date</td>
                <td><input id="3days_before_payment" type="checkbox" <?php if(isset($setting['3days_before_payment']) && $setting['3days_before_payment'] == 'Y') { echo "checked"; }?>></td>
               </tr>
               <tr>
                <td>on due date</td>
                <td><input id="on_due_date_payment" type="checkbox" <?php if(isset($setting['on_due_date_payment']) && $setting['on_due_date_payment'] == 'Y') { echo "checked"; }?>></td>
               </tr>
               </table>
               <hr>
               <span style="font-weight:bold;">Sample SMS</span><hr>
               <small>Hello Srit,<br> Just a gentle reminder - your payment of Rs. 4,500 against the invoice is due on today. Request you to make payment.<br>- CarToll service center  </small><br>
               <small>view your invoice here: <span style="color:blue;">http://makemyrepair.com/gtdhr</span></small>
              </div>
            </div>
               </div>
               <div class="col-lg-4">
               <div class="card border border-primary">
              <div class="card-block">
                <h5 class="card-title">Service reminder to customer<hr></h5>
                <table style="width:100%;">
               <tr>
                <td style="width:95%;">5 days before due date</td>
                <td style="width:5%;"><input id="5days_before_service" type="checkbox" <?php if(isset($setting['5days_before_service']) && $setting['5days_before_service'] == 'Y') { echo "checked"; }?>></td>
               </tr>
               <tr>
                <td>3 days before due date</td>
                <td><input id="3days_before_service" type="checkbox" <?php if(isset($setting['3days_before_service']) && $setting['3days_before_service'] == 'Y') { echo "checked"; }?>></td>
               </tr>
               <tr>
                <td>on due date</td>
                <td><input id="on_due_date_service" type="checkbox" <?php if(isset($setting['on_due_date_service']) && $setting['on_due_date_service'] == 'Y') { echo "checked"; }?>></td>
               </tr>
               </table>
               <hr>
               <span style="font-weight:bold;">Sample SMS</span><hr>
               <small>Hello Srit,<br> Just to remind your vehicle GJ04AA1000 is due for service on 14 March 2020. please call us on 999912345 to book your service<br>- CarToll service center</small>
              </div>
            </div>
               </div>
                </div><br>
                <div class="row">
                        <div class="col-lg-12" style="text-align: right;">
                            <button type="button" class="btn btn-primary btn-sm pull-right" onclick="goToPrevious(2)"><i class="fa fa-arrow-left"></i> Previous</button>
                            <button type="button" class="btn btn-primary btn-sm pull-right" onclick="saveOtherThanFirstTab(5)"><i class="fa fa-save"></i> Save</button>
                        </div>
                    </div>
        </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    #tax_rate_table .form-material.floating,
    #payment_type_table .form-material.floating {
        margin-top: 0px;
        margin-bottom: 0px;
    }
    .alert-message {
        margin: 10px 0;
        padding: 5px;
        border-left: 3px solid #eee;
    }
    .alert-message h4 {
        margin-top: 0;
        margin-bottom: 5px;
    }
    .alert-message p:last-child {
        margin-bottom: 0;
    }
    .alert-message-info {
        background-color: #f4f8fa;
        border-color: #5bc0de;
    }
    .alert-message-info h4
    {
        color: #5bc0de;
    }
</style>
<script>
function getTemplateDetails(reminder_id) {
        $.ajax({
             method: 'POST',
             url: 'common/commonFunc',
             data: {'do': 'get_reminder_body', 'id': reminder_id},
             success: function(result) {
               var data = JSON.parse(result);
               if(result && data['data']) {
                 console.log(data['result']);  
                 $('#view_email_sms').modal('show');
                 $('#email_body_html').html(data['data']['email_body']);
                 $('#sms_body_html').html(data['data']['sms_body']);
               } 
             }
        });
    }
    function getTemplateBody(value) {
        $.ajax({
                method: 'POST',
                url: 'common/commonFunc',
                data: {'do': 'get_template_body', 'id': value},
                success: function(result) {
                    var res = JSON.parse(result);
                    $('#email_sub').html(res.data.email_subject);
                    $('#email_body').html(res.data.email_body);
                    $("#sms_body").html(res.data.sms_body);
                    $('.emptmessage').css('display','none');

                }
        });
    }
    $('#inp_gstinno_switch').on('change', function() {
        if ($(this).prop('checked')) {
            $('#gstin_no_div').css('display', 'block');
        } else {
            $('#gstin_no_div').css('display', 'none');
        }
    });
    $("#state_drp").on('change', function() {
        if (this.value != "*") {
            $.ajax({
                type: 'GET',
                url: 'CountryStateCity/getCityByState?state_id=' + this.value,
                success: function(result) {
                    var html = '<option value="*">Select City</option>';
                    $.each(result, function(i, v) {
                        html += '<option value="' + v.id + '">' + v.name + '</option>';
                    });
                    $("#city_drp").html(html);
                    singleSelect2.updateLabel("Select City");
                    singleSelect2.reload();
                }
            });
        } else {
            var html = '<option value="*">Select City</option>';
            $("#city_drp").html(html);
            singleSelect2.updateLabel("Select City");
            singleSelect2.reload();
        }
    });
    $("#state_drp_default").on('change', function() {
        if (this.value != "*") {
            $.ajax({
                type: 'GET',
                url: 'CountryStateCity/getCityByState?state_id=' + this.value,
                success: function(result) {
                    var html = '<option value="*">Select City</option>';
                    $.each(result, function(i, v) {
                        html += '<option value=' + v.id + '>' + v.name + '</option>';
                    });
                    $("#city_drp_default").html(html);
                    singleSelect4.updateLabel("Select City");
                    singleSelect4.reload();
                }
            });
        } else {
            var html = '<option value="*">Select City</option>';
            $("#city_drp").html(html);
            singleSelect4.updateLabel("Select City");
            singleSelect4.reload();
        }
    });
    function saveOtherThanFirstTab(tabIndex) {
        var data = '';
        var table_name = '';
        var nextTabId = '';
        var message = '';
        if(tabIndex == 2) {
            var dis_col = $('#show_discount_column').prop('checked') ? 'Y' : 'N';
            $('input[name=show_discount_column]').val(dis_col);
            var gst_col = $('#gst_applicable').prop('checked') ? 'Y' : 'N';
            $('input[name=gst_applicable]').val(gst_col);
            data = $('#invoice_jobcard_form').serialize();
            message = 'Invoice jobcard settings saved successfully.';
            nextTabId = 'EMALSMSLink';
        } else {
            save_reminder_setting();
            return false;
        }

        $.ajax({
            type: "POST",
            url: 'Transcation/InsertOperation',
            data: {'data': data,'table_name':'tbl_settings','tabIndex':tabIndex},
            success: function(data) {
                toastr.success(message);
                if(nextTabId != "") {
                    $('#'+nextTabId).trigger('click');
                }
            },
            error: function(e) {
                toastr.error('Error occured while saving data.');
            }
        });
    }
    function save_reminder_setting() {
        var obj = {
            '5days_before_payment': $("#5days_before_payment").prop('checked') ? 'Y' : 'N',
            '3days_before_payment': $("#3days_before_payment").prop('checked') ? 'Y' : 'N',
            'on_due_date_payment': $("#on_due_date_payment").prop('checked') ? 'Y' : 'N',
            '5days_before_service': $("#5days_before_service").prop('checked') ? 'Y' : 'N',
            '3days_before_service': $("#3days_before_service").prop('checked') ? 'Y' : 'N',
            'on_due_date_service': $("#on_due_date_service").prop('checked') ? 'Y' : 'N'
        };
    
        $.ajax({
                method: 'POST',
                url: 'Transcation/InsertOperation',
                data: {'data': obj,'table_name': 'tbl_service_reminder_setting'},
                success: function(result) {
                    toastr.success("Setting saved successfully !");
                }
        });
    }
    function goToPrevious(tabIndex) {
        if(tabIndex == 1) {
            $('#garageInfLink').trigger('click');
        } else if(tabIndex == 2) {
            $('#jobinvoiceLink').trigger('click');
        }
    }
    function saveSetting() {
        var garage_inf_form = $('#garage_information_form')[0];
        var data = new FormData(garage_inf_form);

        data.append('table_name', 'tbl_garage');

        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: 'Transcation/InsertOperation',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function(data) {
                toastr.success('Setting saved successfully.');
                $('#jobinvoiceLink').trigger('click');
            },
            error: function(e) {
                toastr.error('Error occured while saving data.');
            }
        });
    }
    $(document).ready(function() {
        $('textarea').summernote();
        $(document).on('click', '.paymentTypeTd', function() {
            $(this).parent('tr').remove();
        });
        $(document).on('click', '.taxRowTd', function() {
            $(this).parent('tr').remove();
        });
    });
    $(document).on("click", "i.del", function() {
        $(this).parent().remove();
    });
    function removeLogo() {
        $(".imgUp").find('.imagePreview').css("background", "url(assets/photos/default-logo.png)").css('background-size','cover');
        $('.removelogbtn').css('display','none');
        $('input[name=logo_path]').val('');
    }
    $(function() {
        $(document).on("change", ".uploadFile", function() {
            var uploadFile = $(this);
            var files = !!this.files ? this.files : [];
            if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
            if (/^image/.test(files[0].type)) { // only image file
                var reader = new FileReader(); // instance of the FileReader
                reader.readAsDataURL(files[0]); // read the local file
                if (files[0].size > 2000000) {
                    toastr.error('Only Image file having size 2MB or less is allowed.');
                    $('input[name=logo_path]').val('');
                    return;
                }
                reader.onloadend = function() { // set image data as background of div
                    uploadFile.closest(".imgUp").find('.imagePreview').css("background-image", "url(" + this.result + ")");
                    $('.removelogbtn').css('display','inline');
                    $('.uploadlogobtn span').text('Change Logo');
                    $('input[name=logo_path]').val(files[0].name);
                }
            } else {
                toastr.error('Only Image file having size 2MB or less is allowed.');
            }
        });
    });



    function addNewPaymentType() {
        var html = '<tr>';
        html += '<td>';
        html += '<div class="form-group form-material floating" data-plugin="formMaterial">';
        html += '<input type="text" name="name[]" class="form-control input-sm">';
        html += '</div>';
        html += '</td>';
        html += '<td style="text-align: center;" class="paymentTypeTd"><i class="fa fa-trash"></i></td>';
        html += '</tr>';
        $(html).insertAfter('#payment_type_table tbody tr:last');
    }
</script>
