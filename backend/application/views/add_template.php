<?php
$GLOBALS['title_left'] = '<button type="button" onclick="window.history.back();" class="btn btn-sm btn-info btn-outline btn-1e">Back</button>';
?>
<form class="form-material form" id="add_template_form" name="add_template_form" method="post">
    <div class="row">
        <div class="col-lg-3">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" name="name" class="form-control input-sm" value="<?php
                                                                                    if (isset($template['name'])) {
                                                                                        echo $template['name'];
                                                                                    } ?>">
                <label class="floating-label required">Template Name</label>
            </div>
        </div>
        <div class="col-lg-7"></div>
        <div class="col-lg-2" style="text-align: right;"><br>
            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" href="#placeholder_modal">PlaceHolders</button>
        </div>
    </div>

    <ul class="nav nav-tabs customtab nav-tabs-line" role="tablist">
        <li role="presentation" class="nav-item active" data-id="parts">
            <a href="#tab1" role="tab" data-toggle="tab" aria-expanded="true" class="nav-link active"><span class="hidden-xs">EMail Template</span></a>
        </li>
        <li role="presentation" class="nav-item" data-id="service" data-id="service">
            <a href="#tab2" role="tab" class="nav-link" data-toggle="tab" aria-expanded="false"><span class="hidden-xs">SMS Template</span></a>
        </li>
    </ul>
    <div class="tab-content" style="width: 100%;">
        <input type="hidden" name="template_id" value="<?php echo $_REQUEST['id']; ?>">
        <div id="tab1" class="tab-pane active">
            <table style="width: 100%;">
                <tr>
                    <td style="width:10%;"><label class="floating-label">Subject : </label></td>
                    <td style="width:90%;">
                        <div class="form-group form-material floating" data-plugin="formMaterial">
                            <input type="text" class="form-control input-sm" value="<?php echo $template['email_subject']; ?>" name="email_subject">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top;">Body : <br></td>
                    <td>
                        <textarea class="body" name="email_body"><?php echo $template['email_body']; ?></textarea> <br>
                    </td>
                </tr>
            </table>
        </div>
        <div id="tab2" class="tab-pane">
            <br>
            <table style="width: 100%;">
                <tr>
                    <td style="vertical-align: top;">SMS Body : <br></td>
                    <td>
                        <textarea class="body" name="sms_body"><?php echo $template['sms_body']; ?></textarea> <br>
                    </td>
                <tr>
            </table>
        </div>
    </div>
    <table style="width: 100%;text-align: right;">
        <tr>
            <td>
                <button type="button" onclick="save_template()" class="btn btn-sm btn-success btn-outline btn-1e">
                    <?php if (isset($_REQUEST['id'])) { ?>
                        Update Template
                    <?php } else {  ?>
                        Create Template
                    <?php } ?>
                </button>
            </td>
        </tr>
    </table>
</form>
<div id="placeholder_modal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 5px !important;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title" id="ap_model_header">Placeholder</h4>
            </div>
            <div class="modal-body">
             <span><ul><li>Copy the placeholder which you want to use in template. </li><li>Paste into the template where you want to replace with respective values. </li></ul>   </span>
            <div class="nav-tabs-vertical d-flex" data-plugin="tabs">
                  <ul class="nav nav-tabs nav-tabs-solid" role="tablist">
                    <li class="nav-item" role="presentation"><a class="nav-link active" data-toggle="tab" href="#tab_1"  role="tab" aria-selected="true">Garage</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" href="#tab_2"  role="tab" aria-selected="false">Customer</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" href="#tab_3" role="tab" aria-selected="false">Sender</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" href="#tab_4"  role="tab" aria-selected="false">Jobcard</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" href="#tab_5"  role="tab" aria-selected="false">Estimate</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" href="#tab_6"  role="tab" aria-selected="false">Invoice</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" href="#tab_8"  role="tab" aria-selected="false">Vendor</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" href="#tab_9"  role="tab" aria-selected="false">Service Reminder</a></li>
                  </ul>
                  <div class="tab-content" style="width:100%;">
                    <div class="tab-pane active" id="tab_1" role="tabpanel">
                        <ul>
                            <li>{{garage_name}}</li>
                            <li>{{garage_address}}</li>
                            <li>{{garage_email}}</li>
                            <li>{{garage_website}}</li>
                            <li>{{garage_contact_no}}</li>
                            <li>{{garage_contact_person}}</li>
                        </ul>
                    </div>
                    <div class="tab-pane" id="tab_2" role="tabpanel">
                    <ul>
                            <li>{{customer_firstname}}</li>
                            <li>{{customer_lastname}}</li>
                            <li>{{customer_fullname}}</li>
                            <li>{{customer_address}}</li>
                            <li>{{customer_contact_no}}</li>
                            <li>{{customer_email}}</li>
                            <li>{{customer_vehicle_no}}</li>
                            <li>{{customer_vehicle_make}}</li>
                            <li>{{customer_vehicle_model}}</li>
                            <li>{{customer_vehicle_variant}}</li>
                    </ul>
                    </div>
                    <div class="tab-pane" id="tab_3" role="tabpanel">
                    <ul>
                            <li>{{sender_name}}</li>
                            <li>{{sender_contact_no}}</li>
                            <li>{{sender_email}}</li>
                    </ul>
                    </div>
                    <div class="tab-pane" id="tab_4" role="tabpanel">
                    <ul>
                            <li>{{jobcard_no}}</li>
                            <li>{{jobcard_date}}</li>
                            <li>{{jobcard_delivery_date}}</li>
                            <li>{{jobcard_odometer}}</li>
                            <li>{{jobcard_total_amount}}</li>
                    </ul>
                    </div>
                    <div class="tab-pane" id="tab_5" role="tabpanel">
                    <ul>
                            <li>{{estimate_no}}</li>
                            <li>{{estimate_date}}</li>
                            <li>{{estimate_link}}</li>
                            <li>{{estimate_total_amount}}</li>
                    </ul>
                    </div>
                    <div class="tab-pane" id="tab_6" role="tabpanel">
                    <ul>
                            <li>{{invoice_no}}</li>
                            <li>{{invoice_date}}</li>
                            <li>{{invoice_due_date}}</li>
                            <li>{{invoice_link}}</li>
                            <li>{{invoice_total_amount}}</li>
                            <li>{{invoice_total_paid}}</li>
                            <li>{{invoice_total_due}}</li>
                            <li>{{invoice_paid_by}}</li>
                    </ul>
                    </div>
                    <div class="tab-pane" id="tab_8" role="tabpanel">
                    <ul>
                            <li>{{vendor_company_name}}</li>
                            <li>{{vendor_address}}</li>
                            <li>{{vendor_contact_no}}</li>
                            <li>{{vendor_email}}</li>
                            <li>{{vendor_contact_person}}</li>
                            <li>{{vendor_contact_person_no}}</li>
                    </ul>
                    </div>
                    <div class="tab-pane" id="tab_9" role="tabpanel">
                    <ul>
                            <li>{{service_due_date}}</li>
                            <li>{{service_due_vehicle_no}}</li>
                            <li>{{last_service_date}}</li>
                    </ul>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    h1 {
        margin-left: 15px;
        margin-bottom: 20px;
    }

    @media (min-width: 768px) {

        .brand-pills>li>a {
            border-top-right-radius: 0px;
            border-bottom-right-radius: 0px;
        }

        li.brand-nav.active a:after {
            content: " ";
            display: block;
            width: 0;
            height: 0;
            border-top: 20px solid transparent;
            border-bottom: 20px solid transparent;
            border-left: 9px solid #428bca;
            position: absolute;
            top: 50%;
            margin-top: -20px;
            left: 100%;
            z-index: 2;
        }
    }
</style>
<script>
    $(function() {
        $('textarea').summernote();
    });
    function save_template() {
        $.ajax({
            type: 'POST',
            url: 'Transcation/InsertOperation',
            data: $('#add_template_form').serialize() + '&table_name=tbl_template',
            success: function(result) {
                var res = JSON.parse(result);
                if (res.status == '200') {
                    toastr.success(res.message, '');
                    setTimeout(function() {
                        window.location = 'template';
                    }, 1000);
                } else {
                    toastr.error(res.message, '');
                }
            }
        });
    }
</script>