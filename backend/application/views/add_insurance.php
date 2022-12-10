<form class="form-material form" id="add_insurance_form" method="post">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="name" value="<?php if(isset($insurance['name'])) { echo $insurance['name'];} ?>" autocomplete="off">
                <label class="floating-label required">Insurance Company Name</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="contact_name" value="<?php if(isset($insurance['contact_name'])) { echo $insurance['contact_name'];} ?>" autocomplete="off">
                <label class="floating-label">Contact Person Name</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="mobile_no" value="<?php if(isset($insurance['mobile_no'])) { echo $insurance['mobile_no'];} ?>" autocomplete="off">
                <label class="floating-label">Contact No.</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="gst_no" value="<?php if(isset($insurance['gst_no'])) { echo $insurance['gst_no'];} ?>" autocomplete="off">
                <label class="floating-label">GST No.</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="address" value="<?php if(isset($insurance['address'])) { echo $insurance['address'];} ?>" autocomplete="off">
                <label class="floating-label">Address</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php if(!isset($_REQUEST['id'])) { ?>
            <button type="button" onclick="insert_data('tbl_insurance',this.form,'insurance')" class="btn btn-sm btn-success btn-outline btn-1e pull-right"><i class="fa fa-save"></i> Save</button>
            <?php } else { ?>
            <button type="button" onclick="update_data('tbl_insurance','insurance_id','<?php echo $insurance['insurance_id'];?>','insurance',this.form)" class="btn btn-sm btn-success btn-outline btn-1e pull-right"><i class="fa fa-save"></i> Update</button>
            <?php } ?>
            <a href="inventory" class="btn btn-sm btn-info btn-outline btn-1e pull-right"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>
</form>
<script>
$(document).ready(function() {
    
    $(".part_drp").select2({
        'placeholder' : 'Select Parts'
    });
    $(".service_drp").select2({
        'placeholder' : 'Select Labor'
    });
    $(document).on('change','.drp' , function() {
        var elem_value = this.value;
        var elem_name = '';
        var elem_id = $(this).attr('id').split('_');
        var elem_index = elem_id[2]; 
        if(elem_id[0] == 'part') {
            elem_name = 'parts';
        } else {
            elem_name = 'service';
        }
        
        $.ajax({
            type: 'POST',
            url: 'kit/getinventorysearch',
            data:{'elem_value' : elem_value,'elem_name' : elem_name},
            success:function(result) {
                if(elem_id[0] == 'part') { 
                    $('#service_no_'+elem_index).select2('val','');
                } else {
                    $('#part_no_'+elem_index).select2('val','');
                }
                var ResultParse = JSON.parse(result);
                if(ResultParse.list_price_A != "") {
                    $('#list_price_'+elem_index).val(ResultParse.list_price_A);
                } else {
                    $('#list_price_'+elem_index).val('');
                    $('#list_price_'+elem_index).focus();
                }
                $('#quantity_'+elem_index).val('1');
                
                $('#list_price_'+elem_index).addClass('dirty');
                $('#quantity_'+elem_index).addClass('dirty');
            }
        });
    });
});
function add_new_item() {
    var part_no = $('#part_no_1').html();
    var service_no = $('#service_no_1').html();
    var next_index = parseInt($('.add_item').length) + 1;
    
    var html = "<div class='row add_item'>";
        html += "<div class='col-md-3'>";
        html += "<div class='form-group floating-label'>";
        html += "<select class='form-control input-sm drp' name='parts[]' id='part_no_"+next_index+"'>";
        html += part_no;    
        html += "</select>";
        html += "</div>";
        html += "</div>";
        html += "<div class='col-md-3'>";
        html += "<div class='form-group floating-label'>";
        html += "<select class='form-control input-sm drp' name='service[]' id='service_no_"+next_index+"'>";
        html += service_no;    
        html += "</select>";
        html += "</div>";
        html += "</div>";
        html += "<div class='col-md-1'>";
        html += "<div class='form-group floating-label'>";
        html += "<input type='text' class='form-control input-sm' id='quantity_"+next_index+"' name='quantity[]'>";
        html += "<label for='regular2'>Quantity</label>";
        html += "</div>";
        html += "</div>";
        html += "<div class='col-md-1'>";
        html += "<div class='form-group floating-label'>";
        html += "<input type='text' class='form-control input-sm' id='list_price_"+next_index+"' name='list_price[]'>";
        html += "<label for='regular2'>List Price</label>";
        html += "</div>";
        html += "</div>";
        html += "</div>";
        $('.add_item:last').after(html);
        $("#part_no_"+next_index+"").select2({
            'placeholder' : 'Select Parts'
        });
        $("#service_no_"+next_index+"").select2({
            'placeholder' : 'Select Labor'
        });
        $("#part_no_"+next_index+"").select2('val','');
        $("#service_no_"+next_index+"").select2('val','');
            
 }
</script>
