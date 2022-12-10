<?php 
$GLOBALS['title_left'] = '<a href="customer" class="btn btn-sm btn-info btn-outline btn-1e pull-right"><i class="fa fa-arrow-left"></i> Back</a>';
?>

        <input type="hidden" id="cust_id" value="<?php if(isset($_REQUEST['id'])) { echo base64_decode($_REQUEST['id']); } ?>">
        <form class="form-material form" id="add_customer_form" method="post">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                                    <input type="text" class="form-control input-sm" name="name" required id="customer_name" value="<?php if(isset($customer['name'])) { echo $customer['name'];} ?>">
                                                    <label class="floating-label required">Name</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                                    <input type="text" class="form-control input-sm" name="mobile_no" value="<?php if(isset($customer['mobile_no'])) { echo $customer['mobile_no'];} ?>">
                                                    <label class="floating-label">Mobile</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                                    <input type="text" class="form-control input-sm" name="email" value="<?php if(isset($customer['email'])) { echo $customer['email'];} ?>">
                                                    <label class="floating-label">Email</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                                    <input type="text" class="form-control input-sm" name="opening_balance" value="<?php if(isset($customer['opening_balance'])) { echo $customer['opening_balance'];} ?>">
                                                    <label class="floating-label">Opening Balance</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                                    <input type="text" class="form-control input-sm" name="billing_address" value="<?php if(isset($customer['opening_balance'])) { echo $customer['billing_address'];} ?>">
                                                    <label class="floating-label">Billing Address</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                            <div class="form-group form-material floating" data-plugin="formMaterial" style="display:inline-flex;width:inherit;">
                        <input type="text" class="form-control input-sm" name="credit_period" autocomplete="off" maxlength="3" value="<?php if (isset($customer['credit_period'])) {
                                                                                                                            echo $customer['credit_period'];
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
                                                    <input type="text" class="form-control input-sm" name="gst_no" value="<?php echo $customer['gst_no']; ?>">
                                                    <label class="floating-label">GSTIN (Optional)</label>
                                                </div>
                                            </div>
                               </div>
                                        </form>
                                        <hr>
                                        <form id="add_vehicle_form" name="add_vehicle_form">
                                        <div class="row vehicle_div">
                                            <div class="col-lg-12 col-sm-12">
                                                <table style="width: 100%;" class='vehicle_details' id="vehicle_list_table">
                                                    <tr>
                                                        <td style="width: 20%;">
                                                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                                                <select class="form-control input-sm make_drp" name="vehicle[0][make_id]" id="make_1">
                                                                    <option value="*">Select</option>
                                                                    <?php foreach ($make as $mk) { ?>
                                <option <?php if (isset($autos[0]['make_id']) && ($autos[0]['make_id'] == $mk['make_id'])) {
        echo 'selected';
    } ?> value="<?php echo $mk['make_id']; ?>"><?php echo $mk['name']; ?></option>
<?php } ?>
                                                                </select>
                                                                <label class="floating-label required">Select Make</label>
                                                            </div>
                                                        </td>
                                                        <td style="width: 20%;">
                                                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                                                <select class="form-control input-sm model_drp" name="vehicle[0][model_id]" id="model_1">
                                                                    <option value="*">Select</option>
                                                                    <?php foreach ($autoload['model_1'] as $a => $m) { ?>
                                <option <?php if (isset($autos[0]['model_id']) && ($autos[0]['model_id'] == $m['model_id'])) {
        echo "selected";
    } ?> value="<?php echo $m['model_id']; ?>"><?php echo $m['name']; ?></option>
                            <?php } ?>
                                                                </select>
                                                                <label class="floating-label required">Select Model</label>
                                                            </div>
                                                        </td>
                                                        <td style="width: 20%;">
                                                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                                                <select class="form-control input-sm" name="vehicle[0][variant_id]" id="variant_1">
                                                                    <option  value="*">Select</option>
                                                                    <?php foreach ($autoload['variant_1'] as $a => $m) { ?>
                                <option <?php if (isset($autos[0]['variant_id']) && ($autos[0]['variant_id'] == $m['variant_id'])) {
        echo "selected";
    } ?> value="<?php echo $m['variant_id']; ?>"><?php echo $m['name']; ?></option>
                            <?php } ?>
                                                                </select>
                                                                <label class="floating-label">Select Variant</label>
                                                            </div>
                                                            
                                                        </td>
                                                        <td style="width: 20%;">
                                                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                                                <input type="text" class="form-control input-sm" name="vehicle[0][reg_no]" id="reg_1" value="<?php  if(isset($autos[0]['reg_no'])) { echo $autos[0]['reg_no']; } ?>">
                                                                <label class="floating-label required">Vehicle No.</label>
                                                            </div>
                                                        </td>
                                                        <td style="width: 15%;">
                                                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                                                <select class="form-control input-sm" name="vehicle[0][fuel_type]" id="fuel_type_1">
                                                                    <option <?php if($autos[0]['fuel_type'] == 'Petrol') { echo 'selected'; } ?> value="Petrol">Petrol</option>
                                                                    <option <?php if($autos[0]['fuel_type'] == 'Diesel') { echo 'selected'; } ?> value="Diesel">Diesel</option>
                                                                    <option <?php if($autos[0]['fuel_type'] == 'CNG') { echo 'selected'; } ?> value="CNG">CNG</option>
                                                                    <option <?php if($autos[0]['fuel_type'] == 'Electric') { echo 'selected'; } ?> value="Electric">Electric</option>
                                                                </select>
                                                                <label class="floating-label required">Fuel Type</label>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <button type="button" class="btn btn-primary btn-xs" onclick="add_vehicle()">+</button>
                                                        </td>
                                                    </tr>
                                                    <?php 
                                            if (isset($autos) && !empty($autos)) {
                    foreach ($autos as $k => $v) {
                        if ($k == 0) {
                            continue;
                        }
                        ?>
                                                    <tr id="row_<?php echo $k; ?>">
                                <td style="width: 20%;"> 
                                    <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <select class="form-control input-sm make_drp" name="vehicle[<?php echo $k; ?>][make_id]" id="make_<?php echo $k + 1; ?>">
                                    <option value="">Select Make</option>
        <?php foreach ($make as $mk) { ?>
                                        <option <?php if ($v['make_id'] == $mk['make_id']) {
                echo 'selected';
            } ?> value="<?php echo $mk['make_id']; ?>"><?php echo $mk['name']; ?></option>
        <?php } ?>
                                    </select>
                                    </div>
                                </td>
                                <td style="width: 20%;">
                                    <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <select class="form-control input-sm model_drp" name="vehicle[<?php echo $k; ?>][model_id]" id="model_<?php echo $k + 1; ?>">
                                        <option value="">Select Model</option>
                                        <?php foreach ($autoload['model_' . ($k + 1)] as $a => $m) { ?>
                                        <option <?php if ($v['model_id'] == $m['model_id']) { echo "selected"; } ?> value="<?php echo $m['model_id']; ?>"><?php echo $m['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                    </div>
                                </td>
                                <td style="width: 20%;">
                                    <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <select class="form-control input-sm" name="vehicle[<?php echo $k; ?>][variant_id]" id="variant_<?php echo $k + 1; ?>">
                                        <option value="">Select Variant</option>
                                        <?php foreach ($autoload['variant_' . ($k + 1)] as $a => $m) { ?>
                                        <option <?php if ($v['variant_id'] == $m['variant_id']) {
                                                echo "selected";
                                            } ?> value="<?php echo $m['variant_id']; ?>"><?php echo $m['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                    </div>
                                </td>
                                <td style="width: 20%;">
                                                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                                                <input type="text" class="form-control input-sm" name="vehicle[<?php echo $k; ?>][reg_no]" id="reg_<?php echo $k +1; ?>" value="<?php echo $v['reg_no']; ?>">
                                                                <label class="floating-label required">Vehicle No.</label>
                                                            </div>
                                                        </td>
                                                        <td style="width: 15%;">
                                                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                                                <input type="text" class="form-control input-sm" name="vehicle[<?php echo $k; ?>][year]" id="year_<?php echo $k +1; ?>" value="<?php echo $v['year']; ?>">
                                                                <label class="floating-label">Year</label>
                                                            </div>
                                                        </td>
                                                        <td style="width: 5%;">
                                                            <button type="button" class="btn btn-primary btn-xs" onclick="remove_vehicle(<?php echo $k; ?>)">X</button>
                                                        </td>
                                                    </tr>
                                                    <?php } } ?>
                                                </table>
                                            </div>
                                            </div>
                                        <div class="row">
                                            <div class="col-md-6"></div>
                                            <div class="col-md-6" style="text-align: right;">
                                                <button type="button" class="btn btn-success btn-sm waves-effect waves-classic pull-right" onclick="save_customer()"><i class="fa fa-save"></i> Save</button>
                                            </div>
                                        </div>
                                        </form>
<style>
#add_payment_modal .form-group {
  margin-bottom: 2px;
}
</style>
<script>
    function add_vehicle_detail() {
        $("#cust_vehicle_tab").trigger('click');
    }
    $(document).ready(function() {
        $(document).on('change', 'select.make_drp', function() {
            var id = $(this).attr('id').split('_');
            var make = this.value;
            $('#model_' + id[1]).val('');
            $.ajax({
                method: 'GET',
                url: 'customer/getModelByMake?make_id=' + make,
                success: function(result) {
                    var option_html = '<option value="*">Select Model</option>';
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
                    var option_html = '<option value="*">Select Variant</option>';
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
        $.ajax({
            type: 'POST',
            url: 'Transcation/InsertOperation',
            data: {
                'customer_detail': jQuery('#add_customer_form').serialize(),
                'vehicle_detail': jQuery('#add_vehicle_form').serialize(),
                'cust_id': $('#cust_id').val(),
                'table_name': 'tbl_customer'
            },
            success: function(result) {
                var res = JSON.parse(result);
                if (res != undefined && res.status == '200') {
                    toastr.success(res.message, '');
                } else {
                    toastr.error(res.message, '');
                }
                setTimeout(function() {
                    window.location = 'customer';
                }, 500);
            }
        });
    }
    function remove_vehicle(row_id) {
        $('table#vehicle_list_table tbody tr#row_'+row_id).remove();
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
        html += "<select class='form-control input-sm make_drp' name='vehicle["+cnt+"][make_id]' id='make_" + cnt + "'>";
        html += make_select;
        html += "</select>";
        html += "</div>";
        html += "</td>";
        html += "<td style='width: 20%;'>";
        html += "<div class='form-group form-material floating' data-plugin='formMaterial'>";
        html += "<select class='form-control input-sm model_drp' name='vehicle["+cnt+"][model_id]' id='model_" + cnt + "'>";
        html += "<option value=''>Select Model</option>";
        html += "</select>";
        html += "</div>";
        html += "</td>";
        html += "<td style='width: 20%;'>";
        html += "<div class='form-group form-material floating' data-plugin='formMaterial'>";
        html += "<select class='form-control input-sm' name='vehicle["+cnt+"][variant_id]' id='variant_" + cnt + "'>";
        html += "<option value=''>Select Variant</option>";
        html += "</select>";
        html += "</div>";
        html += "</td>";
        html += "<td style='width: 20%;'>";
        html += "<div class='form-group form-material floating' data-plugin='formMaterial'>";
        html += "<input type='text' class='form-control input-sm' name='vehicle["+cnt+"][reg_no]' id='reg_" + cnt + "' onblur=check_duplication('tbl_customer_vehicle','reg_no',this,'vehicle_id',0,'Duplicate Licence Plate found. please enter another.')>";
        html += "<label class='floating-label'>Vehicle No.</label>";
        html += "</div>";
        html += "</td>";
        html += "<td style='width: 15%;'>";
        html += "<div class='form-group form-material floating' data-plugin='formMaterial'>";
        html += "<input type='text' class='form-control input-sm' name='vehicle["+cnt+"][year]' id='year_" + cnt + "'>";
        html += "<label class='floating-label'>Year</label>";
        html += "</div>";
        html += "</td>";
        html += "<td style='width: 5%;'>";
        html += "</td>";
        html += "</tr>";
        html += "</table>";
        html += "</div>";
        $('.vehicle_div:last').after(html);
        $('select').select2();
    }
</script>
