<div class="row">
    <div class="col-md-12">
        <a href="AccountStatement" class="fcbtn btn btn-info btn-outline btn-1e pull-right">Back <i class="ti-back-left"></i></a>
    </div>
</div>
<form class="form-material form" id="account_statment_add" method="post">
    <input type="hidden" name="company_id" value="<?php echo $_SESSION['data']->company_id;?>">
    <div class="row">
        <div class="col-md-9"></div>
        <div class="col-md-2">
           <div class="form-group">
               <input type="text" class="form-control input-sm" name="date" id="date" required=""><span class="highlight"></span> <span class="bar"></span>
                <label for="input1">Date</label>
            </div>
        </div>
    </div>
    <div class="row" id="acc">
        <div class="col-md-3">
           <div class="form-group">
                <select class="form-control input-sm select2 cust_dropdown" name="customer_id[]">
                      <option>Select Customer</option>
                      <?php foreach ($data['customer'] as $value) { ?>
                      <option value="<?php echo $value['customer_id'];?>"><?php echo $value['first_name'].' '.$value['last_name'];?></option>
                      <?php } ?>
                </select>
            </div>
        </div>
        <div class="col-md-4">
           <div class="form-group floating-label">
               <input type="text" class="form-control input-sm" name="description[]" required=""><span class="highlight"></span> <span class="bar"></span>
                <label for="input1">Description</label>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group floating-label">
                <input type="text" class="form-control input-sm credit" name="credit[]" id="credit" onkeyup="calc_total()">
               <label for="input1">Credit</label>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group floating-label">
               <input type="text" class="form-control input-sm debit" name="debit[]" id="debit" onkeyup="calc_total()">
               <label for="input1">Debit</label>
            </div>
        </div>
        <div class="col-md-1">
            <div class="form-group">
                <i class="mdi mdi-plus fa-fw" onclick="AddNewRow()"></i>
        </div>
        </div>
    </div>
    <div id="add_new_row"></div>
    <div class="row">
        <div class="col-md-5">
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <input type="text" class="form-control input-sm" name="opening_balance" id="opening_balance" value="<?php if(isset($data['opening_bal'])) { echo $data['opening_bal']['opening_balance'];} else{ echo 0; }?>" readonly="">
               <label for="input1">Opening Balance</label>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <input type="text" class="form-control input-sm" id="total_credit" onkeyup="calc_total()" readonly="">
                <label for="input1">Total Credit</label>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <input type="text" class="form-control input-sm" id="total_debit" onkeyup="calc_total()" readonly="">
               <label for="input1">Total Debit</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-9"></div>
        <div class="col-md-2">
            <div class="form-group">
                <input type="text" class="form-control input-sm" name="closing_balance" id="closing_balance" onkeyup="calc_total()" readonly="">
               <label for="input1">Closing Balance</label>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <button type="button" onclick="insert_data('tbl_account_statement',this.form,'')" class="fcbtn btn btn-success btn-outline btn-1e pull-right"> Save  <i class="fa fa-save"></i> </button>
        </div>
    </div>
</form>
<script>
    function calc_total() {
        var opening_balance = $('#opening_balance').val() != '' ? $('#opening_balance').val() : 0;
        var credit = 0;
        var debit = 0;
        $('.credit').each(function(i,v) {
            if($(this).val() != '') {
                credit += parseFloat($(this).val());
            }
        });
        $('.debit').each(function(i,v) {
            if($(this).val() != '') {
                debit +=  parseFloat($(this).val());
            }
        });
        $("#total_credit").val(credit);
        $("#total_debit").val(debit);
        var final_closing = parseFloat(opening_balance) + parseFloat(credit) - parseFloat(debit); 
        $('#closing_balance').val(final_closing);
    }
    function AddNewRow() {
    var select_option_html = $.trim($('select.cust_dropdown').html());
    var row_html = "<div class='row'>";
        row_html += "<div class='col-md-3'>";
        row_html += "<div class='form-group floating-label'>";
        row_html += "<select class='form-control input-sm' name='customer_id[]'>";
        row_html += select_option_html;
        row_html += "</select>";
        row_html += '</div>';
        row_html += '</div>';
        row_html += '<div class="col-md-4">';
        row_html += '<div class="form-group floating-label">';
        row_html += '<input type="text" class="form-control input-sm" name="description[]" required=""><span class="highlight"></span> <span class="bar"></span>';
        row_html += '<label for="input1">Description</label>';
        row_html += '</div>';
        row_html += '</div>';
        row_html += '<div class="col-md-2">';
        row_html += '<div class="form-group floating-label">';
        row_html += '<input type="text" name="credit[]" class="form-control input-sm credit" onkeyup="calc_total()">';
        row_html += '<label for="input1">Credit</label>';
        row_html += '</div>';
        row_html += '</div>';
        row_html += '<div class="col-md-2">';
        row_html +=  '<div class="form-group floating-label">';
        row_html += '<input type="text" name="debit[]" class="form-control input-sm debit" onkeyup="calc_total()">';
        row_html += '<label for="input1">Debit</label>';
        row_html += '</div>';
        row_html += '</div>';
        row_html += '<div class="col-md-1">';
        row_html += '<div class="form-group">';
        row_html += '<i class="mdi mdi-plus fa-fw" onclick="AddNewRow()"></i>';
        row_html += '</div>';
        row_html += '</div>';
        row_html += '</div>';
        $('#add_new_row').append(row_html);
        $('select').select2('destroy');
        $('select').select2();
    }
</script>