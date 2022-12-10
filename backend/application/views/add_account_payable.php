<div class="row">
    <div class="col-md-12">
        <a href="accountpayable" class="btn btn-sm btn-info btn-outline btn-1e pull-right">Back <i class="ti-back-left"></i></a>
    </div>
</div>	
<form class="floating-labels" id="add_account" name="add_account" method="post">
    <input type="hidden" name="company_id" value="<?php echo $_SESSION['data']->company_id;?>">
    <div class="row">
        <div class="col-lg-3 col-md-3">
            <div class="form-group">
                <select class="form-control input-sm">
                    <optgroup label="Select Vendor">
                        <?php  foreach ($drpdata['vendors'] as $k=>$v) { ?>
                          <option value="<?php echo $v['vendor_id'];?>"><?php echo $v['name'];?></option>
                        <?php } ?>
                    </optgroup>
                </select>
            </div>
        </div>
        <div class="col-lg-3 col-md-3">
            <div class="form-group">
                <input type="text" class="form-control input-sm" required="">
                <label class="control-label">Address</label>
            </div>
        </div>
        <div class="col-lg-3 col-md-3">
            <div class="form-group">
                <input type="text" class="form-control input-sm" required="">
                <label class="control-label">City</label>
            </div>
        </div>
        <div class="col-lg-3 col-md-3">
            <div class="form-group">
                <input type="text" class="form-control input-sm" required="">
                <label class="control-label">Zip</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-2 col-md-2">
            <div class="form-group">
                <input type="text" class="form-control input-sm" required="">
                <label class="control-label">Invoice No</label>
            </div>
        </div>
        <div class="col-lg-2 col-md-2">
            <div class="form-group">
                <input type="text" class="form-control input-sm" required="">
                <label class="control-label">Invoice Date</label>
            </div>
        </div>
        <div class="col-lg-2 col-md-2">
            <div class="form-group">
                <input type="text" class="form-control input-sm" required="">
                <label class="control-label">PO Number</label>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="form-group">
                <div class="radio-list">
                    <label class="radio-inline p-0">
                        <div class="radio radio-success">
                            <input type="radio" name="acc_type" id="radio1" value="Asset" <?php if(!empty($data) && $data['acc_type'] == 'Asset') {     echo 'checked'; }?> required="" >
                            <label for="radio1">Invoice</label>
                        </div>
                    </label>
                    <label class="radio-inline">
                        <div class="radio radio-info">
                            <input type="radio" name="acc_type" id="radio2" value="Liability" <?php if(!empty($data) && $data['acc_type'] == 'Liability') {     echo 'checked'; }?> required="">
                            <label for="radio2">Credit Memo</label>
                        </div>
                    </label>
                    <label class="radio-inline">
                        <div class="radio radio-warning">
                            <input type="radio" name="acc_type" id="radio3" value="Capital" <?php if(!empty($data) && $data['acc_type'] == 'Capital') { echo 'checked'; }?> required="">
                            <label for="radio3">Debit Memo</label>
                        </div>
                    </label>
                </div>
                <label class="control-label">Trans Type.</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-3 col-sm-3">
            <div class="form-group">
                <input type="text" class="form-control input-sm" id="gross_amount" required="">
                <label class="control-label">Gross</label>
            </div>
        </div>
        <div class="col-lg-3 col-sm-3">
            <div class="form-group">
                <input type="text" class="form-control input-sm" id="freight_amount" required="">
                <label class="control-label">Freight</label>
            </div>
        </div>
        <div class="col-lg-3 col-sm-3">
            <div class="form-group">
                <input type="text" class="form-control input-sm" id="net_amount" required="">
                <label class="control-label">Net</label>
            </div>
        </div>
        <div class="col-lg-3 col-sm-3">
        <div class="form-group">
                <div class="radio-list">
                    <label class="radio-inline">
                        <div class="radio radio-info">
                            <input type="radio" name="line_item" id="radio5" value="Y" <?php if(!empty($data) && $data['line_item'] == 'Y') {     echo 'checked'; }?> required="">
                            <label for="radio5">Yes.</label>
                        </div>
                    </label>
                    <label class="radio-inline">
                        <div class="radio radio-warning">
                            <input type="radio" name="line_item" id="radio4" value="N" <?php if(!empty($data) && $data['line_item'] == 'N') { echo 'checked'; } else { echo 'checked'; }?> required="">
                            <label for="radio4">No.</label>
                        </div>
                    </label>
                </div>
                <label class="control-label">Line Detail.</label>
            </div>
            </div>
    </div>
    <div class="row hide" id="line_details_div">
        <div class="col-lg-12 col-sm-12">
            <div class="panel panel-success">
                <div class="panel-heading"> Line Detail.
                    <div class="pull-right">
                        <a href="#" data-perform="panel-collapse">
                            <i class="ti-minus"></i>
                        </a>
                    </div>
                </div>
                <div class="panel-wrapper collapse in" aria-expanded="true" style="">
                    <div class="panel-body" style="border: 1px dotted;">
                        <div class="row row_div">
                            <div class="col-lg-1 col-md-1">
                                <div class="form-group floating-label">
                                    <p> 1.</p>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-3">
                                <div class="form-group">
                                    <select class="form-control input-sm" name="default_expense_acc" id="accounts_select" required="">
                                    <optgroup label="Account">
                                        <?php  foreach ($drpdata['accounts'] as $k=>$v) { ?>
                                        <option value="<?php echo $v['ch_acc_id'];?>"><?php echo $v['acc_desc'];?></option>
                                        <?php } ?>
                                    </optgroup>
                                </select>
                            </div>
                            </div>
                            <div class="col-lg-5 col-md-5">
                                <div class="form-group">
                                    <input type="text" class="form-control input-sm" required="">
                                    <label class="control-label">Description</label>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2">
                                <div class="form-group">
                                    <input type="text" class="form-control input-sm line_amount" required="">
                                    <label class="control-label">Amount</label>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <br>
                                <button type="button" class="btn btn-primary btn-xs" title="Add Row" data-toggle="tooltip" onclick="add_row()">+</button>
                            </div>
                        </div>
                        <div id="add_new_rows"></div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php if(!isset($_REQUEST['id'])) { ?>
            <input type="button" class="btn btn-sm btn-success btn-outline btn-1e pull-right" value="Save" onclick="insert_data('tbl_chart_of_account',this.form,'chartofaccount')">
            <?php } else { ?>
            <button type="button" onclick="update_data('tbl_chart_of_account','ch_acc_id','<?php echo $data['ch_acc_id'];?>','chartofaccount',this.form)" class="btn btn-sm btn-success btn-outline btn-1e pull-right"> Update <i class="fa fa-save"></i> </button>
            <?php } ?>
        </div>
    </div>
     </form>
<style>
    .floating-labels label {
        position: inherit;
    }
</style>
<script>
 $('document').ready(function() {
     $('select').select2();
     $('#purchase_date').datepicker({
        'format':'yyyy-mm-dd',
        autoclose:true
     });
 });
 function add_row() {
     var row_length = ($('.row_div').length + 1);
     var get_select_option = $('#accounts_select').html();
     var html_row  = "<div class='row row_div'>";
         html_row += '<div class="col-lg-1 col-md-1">';
         html_row += '<div class="form-group floating-label">';
         html_row += '<p> '+row_length++ +'.</p>';
         html_row += '</div>';
         html_row += '</div>';
         html_row += '<div class="col-lg-3 col-md-3">';
         html_row += '<div class="form-group">';
         html_row += '<select class="form-control input-sm" name="default_expense_acc" id="acc_'+row_length+'">';
         html_row += get_select_option;
         html_row += '</select>';
         html_row += '</div>';
         html_row += '</div>';
         html_row += '<div class="col-lg-5 col-md-5">';
         html_row += '<div class="form-group">';
         html_row += '<input type="text" class="form-control input-sm" required>';
         html_row += '<label class="control-label">Description</label>';
         html_row += '</div>';
         html_row += '</div>';
         html_row += '<div class="col-lg-2 col-md-2">';
         html_row += '<div class="form-group">';
         html_row += '<input type="text" class="form-control input-sm line_amount" required>';
         html_row += '<label class="control-label">Amount</label>';
         html_row += '</div>';
         html_row += '</div>';
         html_row += '</div>';
         $('#add_new_rows').append(html_row);
         $('#acc_'+row_length).select2();
 }
 $(document).on('change' , '.line_amount', function() { 
     var total_gross = 0;
     $('.line_amount').each(function(i,v) {
         total_gross += parseFloat(this.value);
     });
     $('#gross_amount').val(total_gross);
     
     var fright = $('#freight_amount').val() != "" ? $('#freight_amount').val() : 0;
     var net_amount = parseFloat(total_gross) + parseFloat(fright);
     $("#net_amount").val(net_amount);
 });
 $("input[name=line_item]").on('change',function() {
     if(this.value == 'Y') {
         $('#line_details_div').removeClass('hide');
     } else {
         $('#line_details_div').addClass('hide');
     }
});
</script>