<div class="row">
    <div class="col-md-2 pull-right">
        <a href="invoice" class="btn-sm btn btn-info btn-outline btn-1e pull-right">Back <i class="ti-back-left"></i></a>
    </div>
    <div class="col-md-1">
        <a href="invoice" class="btn-sm btn btn-primary btn-outline btn-1e"><i class="fa fa-file-pdf-o"></i> PDF</a>
    </div>
    <div class="col-md-1">
        <a href="invoice" class="btn-sm btn btn-primary btn-outline btn-1e"><i class="fa fa-print"></i>  PRINT</a>
    </div>
    <div class="col-md-2">
        <button type="button" onclick="insert_data('tbl_jobcard',this.form,'jobcard')" class="btn btn-sm btn-primary btn-outline btn-1e pull-right"> Save and Email  <i class="fa fa-save"></i> </button>
    </div>
    <div class="col-md-1">
        <button type="button" onclick="save_jobcard()" class="btn btn-sm btn-primary btn-outline btn-1e pull-right"> Save  <i class="fa fa-save"></i> </button>
    </div>
    <?php if (empty($data['setting'])) { ?>
        <div class="col-md-10">
            <div class="alert alert-success"> Your Account Setting is not created yet.  Please create Account Setting first.</div>    
        </div>
   <?php return false; } ?>
</div><br>
<div style="border: solid thin lightgray;padding: 20px;border-radius: 5px;">
<div class="row">
    <?php if($data['setting']['show_logo'] == 'true' && $data['setting']['logo'] != "") { ?> 
        <div class="col-md-3">
       <img src="uploads/logos/thumbs/<?php echo $data['setting']['logo']; ?>" alt="image" class="img-responsive img-rounded" width="auto">
        </div>
        <div class="col-md-6 text-center">
            <h1><?php echo $data['setting']['company_name']; ?></h1>
            <h5>Mobile: <?php echo $data['setting']['mobile']; ?></h5>
        </div>
    <?php } else { ?>
    <div class="col-md-6 pull-left">
        <h1><?php echo $data['setting']['company_name']; ?></h1>
        <h5>Mobile: <?php echo $data['setting']['mobile']; ?></h5>
    </div>
    <div class="col-md-3"></div>
    <?php } ?>
    <div class="col-md-3">
        <h1 class="pull-right">JOBCARD</h1>
    </div>
</div>
    <p style="border-bottom: 5px solid darkseagreen;"></p><br>
<form class="form-material form" id="add_jobcard_form" method="post">
    <input type="hidden" name="company_id" value="<?php echo $_SESSION['data']->company_id; ?>">
    <div class="row">
        <div class="col-md-5">
            <table class="table table-bordered">
                <tr>
                    <td style="width: 30%;">JobCard No.</td>
                    <td style="width: 70%;" id="jobcard_no"><?php echo $data['next_jobcard_no'];?></td>
                </tr>
                <tr>
                    <td>Customer</td>
                    <td>
                        <select class="form-control input-sm" id="customer_select" name="customer_id" onchange="getcustomerdetail(this)">
                           <option value="">Select</option>
                           <?php foreach ($data['customer'] as $value) { ?>
                           <option value="<?php echo $value['customer_id']; ?>" <?php if(isset($data['save_booking']['customer_id']) && $data['save_booking']['customer_id'] ==  $value['customer_id']) { ?> selected=""<?php } ?>><?php echo $value['first_name'] . ' ' . $value['last_name']; ?> 
                           </option>
                           <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Address</td>
                    <td id="add"></td>
                </tr>
                <tr>
                    <td>Mobile</td>
                    <td id="mobi"></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td id="ema"></td>
                </tr>
            </table>
        </div>
        <div class="col-md-2">

        </div>
        <div class="col-md-5">
            <table class="table table-bordered">
                <tr>
                    <td style="width: 30%">Date</td>
                    <td style="width: 70%"><?php echo date('d-m-Y'); ?></td>
                </tr>
                <tr>
                    <td>Model</td>
                    <td>
                        <select class="form-control input-sm" id="cust_vehi" name="vehicle_id" onchange="getcustomervehicle(this)">
                            <option value="">Select</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Reg No.</td>
                    <td id="reg_no"></td>
                </tr>
                <tr>
                    <td>Color</td>
                    <td id="color"></td>
                </tr>
                <tr>
                    <td>Kilometer</td>
                    <td>
                        <div class="form-group floating-label">
                        <input type="text" class="form-control input-sm" id="kilometer" name="kilometer" value="<?php if(isset($data['save_booking']['mileage'])) { echo $data['save_booking']['mileage']; } ?>">
                        </div>
                        </td>
                </tr>
            </table>
        </div>
    </div>
        <div class="row">
            <div class="col-md-12">
                <p style="background-color: darkseagreen;padding: 8px;"><span>Labor</span></p>
            </div>
        </div>
        <div class="row"> 
            <div class="col-md-12">
                <table class="table table-bordered" id="labor_table">
                    <thead>
                        <tr>
                            <th style="width: 5%;">Sr.No</th>
                            <th style="width: 85%;">Description <button type="button" class="btn btn-primary btn-xs pull-right" title="Add New Row" data-toggle="tooltip" onclick="add_extra_row(1)">+</button></th>
                            <th style="width: 10%;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>
                                <div class="form-group floating-label">
                                    <input type="text" class="form-control input-sm labor_item" name="description[]" id="description_labor_1">
                                </div>
                            </td>
                            <td>
                                <div class="form-group floating-label">
                                    <input type="text" id="labor_amount_1" class="form-control input-sm labor_amount" name="labor_amount[]" onkeyup="calculate_sub_total(1,1)">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>
                                <div class="form-group floating-label">
                                    <input type="text" class="form-control input-sm labor_item" name="description[]" id="description_labor_2">
                                </div>
                            </td>
                            <td>
                                <div class="form-group floating-label">
                                    <input type="text" id="labor_amount_2" class="form-control input-sm labor_amount" name="labor_amount[]" onkeyup="calculate_sub_total(1,2)">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>
                                <div class="form-group floating-label">
                                    <input type="text" class="form-control input-sm labor_item" name="description[]" id="description_labor_3">
                                </div>
                            </td>
                            <td>
                                <div class="form-group floating-label">
                                    <input type="text" id="labor_amount_3" class="form-control input-sm labor_amount" name="labor_amount[]" onkeyup="calculate_sub_total(1,3)">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>
                                <div class="form-group floating-label">
                                    <input type="text" class="form-control input-sm labor_item" name="description[]" id="description_labor_4">
                                </div>
                            </td>
                            <td>
                                <div class="form-group floating-label">
                                    <input type="text" id="labor_amount_4" class="form-control input-sm labor_amount" name="labor_amount[]" onkeyup="calculate_sub_total(1,4)">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: right;">SUB TOTAL</td>
                            <td id="sub_total_labor"></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: right;">TOTAL TAX</td>
                            <td id="total_tax_labor"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <p style="background-color: darkseagreen;padding: 8px;"><span>Parts</span></p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered" id="parts_table">
                <thead>
                    <th style="width: 5%">Sr.No</th>
                    <th style="width: 60%">Description <button type="button" class="btn btn-primary btn-xs pull-right" title="Add New Row" data-toggle="tooltip" onclick="add_extra_row(2)">+</button></th>
                    <th style="width: 10%">Quantity</th>
                    <th style="width: 10%">Unit Price</th>
                    <th style="width: 9%">Amount</th>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>
                            <div class="form-group floating-label">
                                <input type="text" class="form-control input-sm part_item" name="description[]" id="description_parts_1">
                            </div>
                        </td>
                        <td>
                            <div class="form-group floating-label">
                                <input type="text" id="quantity_1" class="form-control input-sm part_quantity" name="quantity[]" onkeyup="calculate_sub_total(2,1)">
                            </div>
                        </td>
                        <td>
                            <div class="form-group floating-label">
                                <input type="text" id="unit_price_1" class="form-control input-sm unit_price" name="unit_price[]" onkeyup="calculate_sub_total(2,1)">
                            </div>
                        </td>
                        <td>
                            <div class="form-group floating-label">
                                <input type="text" id="parts_total_1" class="form-control input-sm part_total" name="parts_total[]" onkeyup="calculate_sub_total('',1)">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>
                            <div class="form-group floating-label">
                                <input type="text" class="form-control input-sm part_item" name="description[]" id="description_parts_2">
                            </div>
                        </td>
                        <td>
                            <div class="form-group floating-label">
                                <input type="text" id="quantity_2" class="form-control input-sm part_quantity" name="quantity[]" onkeyup="calculate_sub_total(2,2)">
                            </div>
                        </td>
                        <td>
                            <div class="form-group floating-label">
                                <input type="text" id="unit_price_2" class="form-control input-sm unit_price" name="unit_price[]" onkeyup="calculate_sub_total(2,2)">
                            </div>
                        </td>
                        <td>
                            <div class="form-group floating-label">
                                <input type="text" id="parts_total_2" class="form-control input-sm part_total" name="parts_total[]" onkeyup="calculate_sub_total('',2)">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>
                            <div class="form-group floating-label">
                                <input type="text" class="form-control input-sm part_item" name="description[]" id="description_parts_3">
                            </div>
                        </td>
                        <td>
                            <div class="form-group floating-label">
                                <input type="text" id="quantity_3" class="form-control input-sm part_quantity" name="quantity[]" onkeyup="calculate_sub_total(2,3)">
                            </div>
                        </td>
                        <td>
                            <div class="form-group floating-label">
                                <input type="text" id="unit_price_3" class="form-control input-sm unit_price" name="unit_price[]" onkeyup="calculate_sub_total(2,3)">
                            </div>
                        </td>
                        <td>
                            <div class="form-group floating-label">
                                <input type="text" id="parts_total_3" class="form-control input-sm part_total" name="parts_total[]" onkeyup="calculate_sub_total('',3)">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>
                            <div class="form-group floating-label">
                                <input type="text" class="form-control input-sm part_item" name="description[]" id="description_parts_4">
                            </div>
                        </td>
                        <td>
                            <div class="form-group floating-label">
                                <input type="text" id="quantity_4" class="form-control input-sm part_quantity" name="quantity[]" onkeyup="calculate_sub_total(2,4)">
                            </div>
                        </td>
                        <td>
                            <div class="form-group floating-label">
                                <input type="text" id="unit_price_4" class="form-control input-sm unit_price" name="unit_price[]" onkeyup="calculate_sub_total(2,4)">
                            </div>
                        </td>
                        <td>
                            <div class="form-group floating-label">
                                <input type="text" id="parts_total_4" class="form-control input-sm part_total" name="parts_total[]" onkeyup="calculate_sub_total('',4)">
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align: right;">SUB TOTAL</td>
                        <td id="sub_total_parts">0</td>
                    </tr>
                    <tr>
                        <td colspan="4" style="text-align: right;">TOTAL TAX</td>
                        <td id="total_tax_parts">0</td>
                    </tr>
                </table>
                <table style="width: 70%;float: left;text-align: left;">
                        <tr>
                            <td> 
                            <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group floating-label">
                                            <textarea class="form-control input-sm" rows="5" id="comments" style="resize: none;" placeholder="Comments" resize="false" name="notes"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                </table>
                <table class="table table-bordered" style="width: 30%;float: right;text-align: right;">
                    <tbody>
                        <tr>
                            <td style="width: 21%;">TOTAL LABOR</td>
                            <td id="total_labor" style="width: 9%;text-align: left;">0</td>
                        </tr>
                        <tr>
                            <td>TOTAL PARTS</td>
                            <td id="total_parts" style="text-align: left;">0</td>
                        </tr>
                        <tr>
                            <td>TOTAL TAX</td>
                            <td id="total_tax" style="text-align: left;">0</td>
                        </tr>
                        <tr>
                            <td>AMOUNT PAID</td>
                            <td>
                                <div class="form-group floating-label">
                                <input type="text" id="amount_paid" onkeyup="calculate_amount_due()" class="form-control input-sm" name="amount_paid">
                            </div>
                            </td>
                        </tr>
                        <tr>
                            <td>TOTAL DUE</td>
                            <td id="total_due" style="text-align: left;">0</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <ul>
            <li> Subject to Ahmedabad Juridiction.</li>
            <li> All cheque payable to company name.</li>
            <li> Subject to Ahmedabad Juridiction.</li>
        </ul>
    <div class="row">
        <div class="col-md-12">
            <p style="background-color: darkseagreen;height: 50px;color: white;text-align: center;">
                C-11,VishvasCity - 11,Gota Ahmedabad <br>
                Mobile : 8389349232 | E-mail: info@gmail.com
            </p>
        </div>
    </div>
</div>
<style>
    .form .form-group, .form-inline .form-group {
        padding-top: 0px;
        margin-bottom: 0px;
    }
</style>
<script>
    function save_jobcard() {
        var labor_item = [];
        var labor_amount = [];
        var part_item = [];
        var part_quantity = [];
        var unit_price = [];
        var part_total = [];
        
        
        var labor_tax =   $("#total_tax_labor").text() != "" ? $("#total_tax_labor").text() : 0;
        var parts_tax =   $("#total_tax_parts").text() != "" ? $("#total_tax_parts").text() : 0;
        var total_labor = $("#total_labor").text() != "" ? $("#total_labor").text() : 0;
        var total_parts = $("#total_parts").text() != "" ? $("#total_parts").text() : 0;;
        var total_tax = $("#total_tax").text() != "" ? $("#total_tax").text() : 0;
        var amount_paid = $('#amount_paid').val();
        var total_due = $('#total_due').text() != "" ? $('#total_due').text() : 0;
        var comments = $('#comments').val();
        
        $('.labor_item').each(function(i,v) {
            labor_item.push($(this).val());
        });
        $('.labor_amount').each(function(i,v) {
            labor_amount.push($(this).val());
        });
        $('.part_item').each(function(i,v) {
            part_item.push($(this).val());
        });
        $('.part_quantity').each(function(i,v) {
            part_quantity.push($(this).val());
        });
        $('.unit_price').each(function(i,v) {
            unit_price.push($(this).val());
        });
        $('.part_total').each(function(i,v) {
            part_total.push($(this).val());
        });
        
        var obj = {'jobcard_no':$('#jobcard_no').text(),'customer_select':$('#customer_select').val(),'model':$('#cust_vehi').val(),'kilometer':$('#kilometer').val(),'notes':comments,'total_labor':total_labor,'total_parts':total_parts,'labor_tax' : labor_tax,'parts_tax' : parts_tax,'total_tax':total_tax,'amount':total_due,'amount_paid':amount_paid};
        
        var final_obj = {'general':obj,'labor_item':labor_item,'labor_amount':labor_amount,'part_item':part_item,'part_quantity':part_quantity,
        'unit_price':unit_price,'part_total':part_total};
    
        $.ajax({
            type: 'POST',
            url: 'Transcation/ProcessData',
            data: {'data':final_obj,'table_name':'tbl_jobcard','transcation':'insert'},
            success: function(result) {

            }
        });
        
    }
    function add_extra_row(section_type) {
        var html_row = '';
        if(section_type == 1) {
        var tr_length = $('#labor_table tbody tr').length;
        var srno = ((parseInt(tr_length) - 2) + 1);
        html_row += '<tr>';
        html_row += '<td>'+srno+'</td>';
        html_row += '<td>';
        html_row += '<div class="form-group floating-label">';
        html_row += '<input type="text" class="form-control input-sm labor_item" name="description[]" id="description_labor_'+srno+'">';
        html_row += '</div>';
        html_row += '</td>';
        html_row += '<td>';
        html_row += '<div class="form-group floating-label">';
        html_row += '<input type="text" id="labor_amount_'+srno+'" class="form-control input-sm labor_amount" name="labor_amount[]" onkeyup="calculate_sub_total(1,'+srno+')">';
        html_row += '</div>';
        html_row += '</td>';
        html_row += '</tr>';
        $('#labor_table tr:nth-last-child(3)').after(html_row);
        } else {
        var tr_length = $('#parts_table tbody tr').length;
        var srno = ((parseInt(tr_length) - 2) + 1);
        html_row += '<tr>';
        html_row += '<td>'+srno+'</td>';
        html_row += '<td>';
        html_row += '<div class="form-group floating-label">';
        html_row += '<input type="text" class="form-control input-sm labor_item" name="description[]" id="description_parts_'+srno+'">';
        html_row += '</div>';
        html_row += '</td>';
        html_row += '<td>';
        html_row += '<div class="form-group floating-label">';
        html_row += '<input type="text" id="quantity_'+srno+'" class="form-control input-sm part_quantity" name="quantity[]" onkeyup="calculate_sub_total(2,'+srno+')">';
        html_row += '</div>';
        html_row += '</td>';
        html_row += '<td>';
        html_row += '<div class="form-group floating-label">';
        html_row += '<input type="text" id="unit_price_'+srno+'" class="form-control input-sm unit_price" name="unit_price[]" onkeyup="calculate_sub_total(2,'+srno+')">';
        html_row += '</div>';
        html_row += '</td>';
        html_row += '<td>';
        html_row += '<div class="form-group floating-label">';
        html_row += '<input type="text" id="parts_total_'+srno+'" class="form-control input-sm part_total" name="parts_total[]" onkeyup="calculate_sub_total("",'+srno+')">';
        html_row += '</div>';
        html_row += '</td>';
        html_row += '</tr>';
        $('#parts_table tr:nth-last-child(3)').after(html_row);
        }
    }
    $(document).ready(function() {
        if($('#customer_select').val() != "") {
            $('#customer_select').trigger('change');
        }
    });
    function calculate_final() {
        var parts_total = $('#indiv_parts_total').text() != '' ? $('#indiv_parts_total').text() : 0;
        var labor_total = $('#indiv_labor_total').text() != '' ? $('#indiv_labor_total').text() : 0;
        var labor_taxes = $('#labor_taxes').val() != '' ? $('#labor_taxes').val() : 0;
        var parts_taxes = $('#part_taxes').val() != '' ? $('#part_taxes').val() : 0;
        var amount_paid = $('#amount_paid').val() != '' ? $('#amount_paid').val() : 0;
        var grd_total = parseFloat(parts_total) + parseFloat(labor_total) + parseFloat(labor_taxes) + parseFloat(parts_taxes);
        $('#grand_total').text(grd_total);
        $('#amount_due').text(parseFloat(grd_total) - parseFloat(amount_paid));
    }
    function calculate_amount_due() {
        var amount_paid = $('#amount_paid').val() != '' ? $('#amount_paid').val() : 0;
        var labor_total = $('#total_labor').text();
        var parts_total = $('#total_parts').text();
        //$('#total_tax').text();
        $('#total_due').text(parseFloat(parts_total) + parseFloat(labor_total) - parseFloat(amount_paid));
    }
    function calculate_sub_total(section_type,sr_no) {
        if(section_type == '2') {
            var quantity = $('#quantity_'+sr_no).val() != '' ? parseFloat($('#quantity_'+sr_no).val()) : 0;
            var unit_price = $('#unit_price_'+sr_no).val() != '' ? parseFloat($('#unit_price_'+sr_no).val()) : 0;
            var part_amount = parseFloat(quantity) * parseFloat(unit_price);
            $('#parts_total_'+sr_no).val(part_amount);
        }
        var labor_total = 0;
        var parts_total = 0;
        var tr_length_labor = $('#labor_table tbody tr').length;
        var total_rows_labor = (parseInt(tr_length_labor) - 2);
            
        var tr_length_parts = $('#parts_table tbody tr').length;
        var total_rows_parts = (parseInt(tr_length_parts) - 2);
        
        for(var i=1;i<=total_rows_labor;i++) {
            labor_total += $('#labor_amount_'+i).val() != '' ? parseFloat($('#labor_amount_'+i).val()) : 0;
        }
        $('#sub_total_labor').text(labor_total);
        for(var i=1;i<=total_rows_parts;i++) {
                parts_total += $('#parts_total_'+i).val() != '' ? parseFloat($('#parts_total_'+i).val()) : 0;
        }
        $('#sub_total_parts').text(parts_total);
        var amount_paid = $('#amount_paid').val() != '' ? $('#amount_paid').val() : 0;
        $('#total_labor').text(labor_total);
        $('#total_parts').text(parts_total);
        $('#total_tax').text();
        $('#total_due').text(parseFloat(parts_total) + parseFloat(labor_total) - parseFloat(amount_paid));
        
    }
    function getcustomerdetail(elem) {
        if(elem.value != '') {
            $.ajax({
                method:'post',
                url:'jobcard/GetCustomerDetail',
                data:{'cust_id':elem.value},
                success:function(result) {
                    var JsonData = JSON.parse(result);
                    if(JsonData.status == 0) {
                        $('#add').text(JsonData.data.cust.address);
                        $('#mobi').text(JsonData.data.cust.mobile);
                        $('#ema').text(JsonData.data.cust.email);
                        if(JsonData.data.cust_vehicle != undefined) {
                            $.each(JsonData.data.cust_vehicle,function(key,value) {
                                $('#cust_vehi').html($('<option></option>').attr('value',"").text('Select'));
                                $('#cust_vehi').append($('<option></option>').attr('value',value.vehicle_id).attr('reg',value.reg_no).attr('clr',value.color).text(value.model));
                            });
                            if($('#forwarded_veh_id').val() != 0) {
                                $('#cust_vehi').val($('#forwarded_veh_id').val()).trigger('change');
                            }
                        }
                    } else {
                        $('#cust_vehi').html($('<option></option>').attr('value',"").text('Select'))
                        $('#add').text('');
                        $('#mobi').text('');
                        $('#ema').text('');
                    }
                }
            })
        } else {
            $('#cust_vehi').html($('<option></option>').attr('value',"").text('Select'));
            $('#add').text('');
            $('#mobi').text('');
            $('#ema').text('');
        }
    }
    function getcustomervehicle(elem) {
        if(elem.value != '') {
            var reg_no =  $('option:selected',elem).attr('reg');
            var color =  $('option:selected',elem).attr('clr');
            $('#reg_no').text(reg_no);
            $('#color').text(color);
        } else {
            $('#reg_no').text('');
            $('#color').text('');
        }
    }
    function add_new_row() {
        var tr_length = $('#demand_table tbody tr').length;
        var srno = (parseInt(tr_length) - 4);
        var add_row_html = "<tr>";
        add_row_html += "<td>"+srno+"</td>";
        add_row_html += "<td>";
        add_row_html += "<div class='form-group floating-label'>";
        add_row_html += "<input type='text' class='form-control input-sm' name='demand_repairs[]'>";
        add_row_html += "</div>";
        add_row_html += "</td>";
        add_row_html += "<td>";
        add_row_html += "<div class='form-group floating-label'>";
        add_row_html += "<input type='text' id='quantity_"+srno+"' class='form-control input-sm' name='quantity[]' onkeyup='calculate_total("+srno+")'>";
        add_row_html += "</div>";
        add_row_html += "</td>";
        add_row_html += "<td>";
        add_row_html += "<div class='form-group floating-label'>";
        add_row_html += "<input type='text' id='unit_price_"+srno+"' class='form-control input-sm' name='unit_price[]' onkeyup='calculate_total("+srno+")'>";
        add_row_html += "</div>";
        add_row_html += "</td>";
        add_row_html += "<td>";
        add_row_html += "<div class='form-group floating-label'>";
        add_row_html += "<input type='text' id='parts_total_"+srno+"' class='form-control input-sm' name='parts_total[]'>";
        add_row_html += "</div>";
        add_row_html += "</td>";
        add_row_html += "<td>";
        add_row_html += "<div class='form-group floating-label'>";
        add_row_html += "<input type='text' id='labor_total_"+srno+"' class='form-control input-sm' name='labor[]' onkeyup='calculate_total("+srno+")'>";
        add_row_html += "</div>";
        add_row_html += "</td>";
        add_row_html += "</tr>";
        
        $('#demand_table tr:nth-last-child(6)').after(add_row_html);
    }
</script>