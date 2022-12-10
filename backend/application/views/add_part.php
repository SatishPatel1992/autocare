<?php
$GLOBALS['title_left'] = '<a href="parts" class="btn btn-sm btn-info btn-outline btn-1e pull-right"><i class="fa fa-arrow-left"></i> Back</a>';
?>
<form class="form-material form" id="add_part" name="add_part" method="post">
    <input type="hidden" name="part_id" value="<?php echo $_REQUEST['id']; ?>">
    <!-- <div style="border-top: 1px solid lightgray;border-bottom: 1px solid lightgray;padding: 5px;background-color: aliceblue;">
        <span><i class="fa fa-car" aria-hidden="true"></i> General details</span>
    </div> -->
    <div class="row">
    <div class="col-lg-2 col-md-2">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="part_number" value="<?php if (!empty($part)) { echo $part['part_number']; } ?>" onblur="check_duplication('tbl_parts','part_number',this,'part_id',<?php echo isset($_REQUEST['id']) ? base64_decode($_REQUEST['id']) : 0; ?>,'Part Number Already Exist.')">
                <label class="floating-label">Item Code</label>
            </div>
        </div>
        <div class="col-lg-3 col-md-3">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" id="part_name" name="part_name" value="<?php if (!empty($part)) { echo $part['part_name']; } ?>">
                <label class="floating-label required">Item Name</label>
            </div>
        </div>
        <div class="col-lg-3 col-md-3">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" id="part_name" name="brand" value="<?php if (!empty($part)) { echo $part['brand']; } ?>">
                <label class="floating-label">Description</label>
            </div>
        </div>
    </div>
    <div style="border-top: 1px solid lightgray;border-bottom: 1px solid lightgray;padding: 5px;background-color: aliceblue;">
        <span> Pricing Details</span>
    </div>
    <div class="row">
        <div class="col-lg-2 col-md-2">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm calcSellingPriceAmt" name="cost_price" autocomplete="off" value="<?php if (!empty($part)) { echo $part['cost_price']; } ?>" id="purchase_price">
                <label class="floating-label">Purchase Price</label>
            </div>
        </div>
        <div class="col-lg-2 col-md-2">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="sell_price" value="<?php if (!empty($part)) { echo $part['sell_price'];
} ?>" id="sell_price">
                <label class="floating-label">Sell Price</label>
            </div>
        </div>
        <div class="col-lg-2 col-md-2">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                 <input type="text" class="form-control input-sm" value="<?php if (!empty($part)) { echo $part['HSN'];
} ?>" id="hsn_code">
                <label class="floating-label">HSN Code</label>
            </div>
        </div>
        <div class="col-lg-2 col-md-2">
            <div class="form-group form-material floating" data-plugin="formMaterial">
            <select class="form-control input-sm" name="tax_rate">
                    <option value="Inclusive" <?php if (!empty($part) && $part['tax_type'] == 'Inclusive') { echo 'selected'; } ?>>None</option>
                    <option value="Exclusive" <?php if (!empty($part) && $part['tax_type'] == 'Exclusive') { echo 'selected'; } ?>>Exempted</option>
                    <option value="Exclusive" <?php if (!empty($part) && $part['tax_type'] == 'Exclusive') { echo 'selected'; } ?>>GST @ 0%</option>
                    <option value="Exclusive" <?php if (!empty($part) && $part['tax_type'] == 'Exclusive') { echo 'selected'; } ?>>GST @ 0.1%</option>
                    <option value="Exclusive" <?php if (!empty($part) && $part['tax_type'] == 'Exclusive') { echo 'selected'; } ?>>GST @ 0.25%</option>
                    <option value="Exclusive" <?php if (!empty($part) && $part['tax_type'] == 'Exclusive') { echo 'selected'; } ?>>GST @ 3%</option>
                    <option value="Exclusive" <?php if (!empty($part) && $part['tax_type'] == 'Exclusive') { echo 'selected'; } ?>>GST @ 5%</option>
                    <option value="Exclusive" <?php if (!empty($part) && $part['tax_type'] == 'Exclusive') { echo 'selected'; } ?>>GST @ 12%</option>
                    <option value="Exclusive" <?php if (!empty($part) && $part['tax_type'] == 'Exclusive') { echo 'selected'; } ?>>GST @ 18%</option>
                    <option value="Exclusive" <?php if (!empty($part) && $part['tax_type'] == 'Exclusive') { echo 'selected'; } ?>>GST @ 28%</option>
                </select>
                <label class="floating-label">GST Tax Rate(%)</label>
            </div>
        </div>
    </div>
    <div style="border-top: 1px solid lightgray;border-bottom: 1px solid lightgray;padding: 5px;background-color: aliceblue;">
        <span>Stock Details</span>
    </div>
    <div class="row">
        <div class="col-lg-2 col-md-2">
                <div class="form-group form-material floating" data-plugin="formMaterial">
                    <input type="text" <?php if(isset($_REQUEST['id'])) { echo 'disabled'; } ?> class="form-control input-sm" name="current_stock" value="<?php if (!empty($part) && isset($part['current_stock'])) { echo $part['current_stock']; } ?>">
                    <label class="floating-label">Opening Stock</label>
                </div>
        </div>         
        <div class="col-lg-2 col-md-2">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="as_on_date">
                <label class="floating-label">As of Date</label>
            </div>
        </div>
        <div class="col-lg-3 col-md-3">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                    <br>
                    <div class="material-switch">
                            <input id="someSwitchOptionSuccess" name="someSwitchOption001" type="checkbox"/>
                            Enable Low Stock Warning &nbsp;&nbsp;&nbsp;<label for="someSwitchOptionSuccess" class="label-primary"></label>
                    </div>                    
            </div>
        </div>
        <div class="col-lg-2 col-md-2">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="low_stock_units">
                <label class="floating-label">Low Stock Units</label>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-lg-12" style="text-align: right;">
            <button type="button" class="btn btn-success btn-sm btn-outline btn-1e pull-right" onclick="save_item()"><i class="fa fa-floppy-o"></i> <?php if(isset($_REQUEST['id'])) { echo 'Update Part'; } else { echo 'Save Part';}?></button>
        </div>
    </div>
</form>
<script>
    $('input[name=applicable_to]').change(function() {
            if (this.value == 'spec') {
                $('#option_specific').css('display','block');
            } else {
                $('#option_specific').css('display','none');
            }
    });
    $('document').ready(function() {
        $('input[name=as_on_date]').datepicker({
            format: 'dd-mm-yyyy',
            autoclose:true
        });
        $(document).on('change', '#manage_inventory',function() {
            if($(this).prop('checked')) {
                $('.manage_inv_div').css('display','block');
            } else {
                $('.manage_inv_div').css('display','none');
            }
        });
        $(document).on('change','select.make_drp', function() {
            var make = this.value;
            $('#model').val();
            $.ajax({
                method:'GET',
                url:'customer/getModelByMake?make_id='+make,
                success:function(result) {
                    var option_html = '<option value="">Select Model</option>';
                    $(result).each(function(i,v) {
                        option_html += '<option value="'+v.model_id+'">'+v.name+'</option>';
                    });
                    $('#model').html(option_html);
                }
            });
        });
        $(document).on('change','select.model_drp', function() {
            var model = this.value;
            $('#variant').val();
            $.ajax({
                method:'GET',
                url:'customer/getVariantByModel?model_id='+model,
                success:function(result) {
                    console.log(result);
                    var option_html = '<option value="">Select Variant</option>';
                    $(result).each(function(i,v) {
                        option_html += '<option value="'+v.variant_id+'">'+v.name+'</option>';
                    });
                    $('#variant').html(option_html);
                }
            });
        });
        $(document).on('change','#tax_id', function() {
            var hsn = parseFloat($(this).find(':selected').data('hsn'));
            var rate = parseFloat($(this).find(':selected').data('rate'));
            $('#hsn_code').val(hsn);
            $('#tax_rate').val(rate);
            $('#tax_rate').addClass('dirty');
            $('#tax_rate').removeClass('empty');
            $('#hsn_code').focus();
            $('#hsn_code').addClass('dirty');
            $('#hsn_code').removeClass('empty');
        });
    }); 
    function save_item() {
        if($('#part_name').val() == "") {
            toastr.warning("Part name cann't be blank.",'');
            $('#part_name').focus();
            return false;
        } else if($('#applicable_to').val() == 'spec' && $('#model').val() == "") {
            toastr.warning("Please select model for this part to be applied.",'');
            return false;
        }
        var manage_inv = $('#manage_inventory').prop('checked') ? 'Y' : 'N';
        $.ajax({
           method:'POST',
           url:'Transcation/InsertOperation',
           data:jQuery('#add_part').serialize()+"&table_name=tbl_parts&manage_inventory="+manage_inv,
           success:function(result) {
              var res = JSON.parse(result);
              toastr.success(res.message,'');
              setTimeout(function() {
                  window.location = 'parts';
              },1000)
           }
        });
    }
    $(document).on('change', '.calcSellingPrice', function() {
        calculateSellingPrice();
    });
    $(document).on('keyup', '.calcSellingPriceAmt', function() {
        calculateSellingPrice();
    });
    function calculateSellingPrice() {
        var margin_type = $('select[name=margin_type]').val();
        var margin_value = $('input[name=margin_value]').val();
        var purchase_price = $('input[name=cost_price]').val();

        if(purchase_price != '' && margin_value != "" && margin_type != "") {
            var selling_price = 0;
            var margin_amount = parseFloat(margin_value);
            if(margin_type == '%') {
                margin_amount = parseFloat(purchase_price) * parseFloat(margin_value) / 100;
            }
            selling_price = parseFloat(purchase_price) + parseFloat(margin_amount);
            $('input[name=sell_price]').val(selling_price);
            $('input[name=sell_price]').addClass('dirty');
            $('input[name=sell_price]').removeClass('empty');
        }
    }
</script>
