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
        <button type="button" onclick="SaveTranscation(this.form)" class="btn btn-sm btn-primary btn-outline btn-1e pull-right"> Save  <i class="fa fa-save"></i> </button>
    </div>
    <?php if (empty($data['setting'])) { ?>
        <div class="col-md-10">
            <div class="alert alert-success"> Your Account Setting is not created yet.  Please create Account Setting first.</div>    
        </div>
   <?php return false; } ?>
</div>
<br>
<input type="hidden" id="forwarded_veh_id" value="<?php echo isset($data['save_booking']['vehicle_id']) ? $data['save_booking']['vehicle_id'] : 0?>">
<div style="border: solid thin lightgray;padding: 20px;border-radius: 5px;">
<div class="row">
    <?php if($data['setting']['show_logo'] == 'true' && $data['setting']['logo'] != "") { ?> 
        <div class="col-md-3">
       <img src="uploads/company_logos/thumbs/<?php echo $data['setting']['logo']; ?>" alt="image" class="img-responsive img-rounded" width="auto">
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
        <h2 class="pull-right">INVOICE</h2>
    </div>
</div>
    <p style="border-bottom: 5px solid darkseagreen;"></p><br>
<form class="form-material form" id="add_jobcard_form" method="post">
    <input type="hidden" name="company_id" value="<?php echo $_SESSION['data']->company_id; ?>">
    <div class="row">
        <div class="col-md-5">
            <table class="table table-bordered">
                <tr>
                    <td style="width: 30%;">Invoice No.</td>
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
                            <th style="width: 85%;" colspan="3">Description <button type="button" class="btn btn-primary btn-xs pull-right" title="Add New Row" data-toggle="tooltip" onclick="add_extra_row(1)">+</button></th>
                            <th style="width: 10%;">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td colspan="3">
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
                            <td colspan="3">
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
                            <td colspan="3">
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
                            <td colspan="3">
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
                            <td colspan="4" style="text-align: right;">SUB TOTAL</td>
                            <td id="sub_total_labor"></td>
                        </tr>
                    <tr>
                       <td colspan="4" style="text-align: right;">TOTAL TAX</td>
                       <td id="total_tax_labor"></td>
                    </tr>
                    <tr>
                        <th style="width: 5%">Sr.No</th>
                        <th style="width: 60%">Description <button type="button" class="btn btn-primary btn-xs pull-right" title="Add New Row" data-toggle="tooltip" onclick="add_extra_row(2)">+</button></th>
                        <th style="width: 10%">Quantity</th>
                        <th style="width: 10%">Unit Price</th>
                        <th style="width: 9%">Amount</th>
                    </tr>
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
                    <tr>
                        <td rowspan="6" colspan="3">
                        <textarea class="form-control input-sm" rows="5" id="comments" style="resize: none;" placeholder="Comments" resize="false" name="notes"></textarea>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">TOTAL LABOR</td>
                        <td id="total_labor" style="width: 9%;text-align: left;">0</td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">TOTAL PARTS</td>
                        <td id="total_parts" style="text-align: left;">0</td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">TOTAL TAX</td>
                        <td id="total_tax" style="text-align: left;">0</td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">Amount</td>
                        <td id="total_due" style="text-align: left;">0</td>
                    </tr>
                </table>
                <table style="width: 70%;float: left;text-align: left;">
                        <tr>
                            <td> 
                            <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group floating-label">
                                            
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                </table>
                <table class="table table-bordered" style="width: 30%;float: right;text-align: right;">
                    <tbody>
                        
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
    .dlk-radio input[type="radio"],
    .dlk-radio input[type="checkbox"] 
    {
            margin-left:-99999px;
            display:none;
    }
    .dlk-radio input[type="radio"] + .fa ,
    .dlk-radio input[type="checkbox"] + .fa {
         opacity:0.15
    }
    .dlk-radio input[type="radio"]:checked + .fa,
    .dlk-radio input[type="checkbox"]:checked + .fa{
        opacity:1
    }

</style>
<script>
    $(document).ready(function() {
        if($('#customer_id').val() != "") {
            $('#customer_id').trigger('change');
        }
    });
    function SaveTranscation() {
        if($('#customer_id').val() == '') {
            alert('Please select customer first.');
            return false;
        } else if($('#cust_vehi').val() == '') {
            alert('Please select vehicle.');
            return false;
        }
        
        var Invoice = {'company_id': $('#company_id').val(),'customer_id':$('#customer_id').val(),'jobcard_id':0,'item_no':$.trim($('#item_no').text()),'model':$('#cust_vehi').val(),'mileage':$('#kilometer').val(),'fuel':$('input[name=fuel]:checked').val(),'parts_total':$('#indiv_parts_total').text(),'labor_total':$('#indiv_labor_total').text(),'parts_tax':$('#part_taxes').val(),'labor_tax':$('#labor_taxes').val(),'grand_total':$('#grand_total').text(),'amount_paid':$('#amount_paid').val(),'amount_due':$('#amount_due').text(),'notes':$('#notes').val()};
        
        var tr_length = $('#demand_table tbody tr').length;
        var total_rows = (parseInt(tr_length) - 5);
        var invoice_item = [];
        for(var i=1;i<=total_rows;i++) {
            var demand_repairs = $("#demand_repairs_"+i).val();
            var quantity = $("#quantity_"+i).val();
            var unit_price = $("#unit_price_"+i).val();
            var parts_total = $("#parts_total_"+i).val();
            var labor_total = $("#labor_total_"+i).val();
            var obj = {'description':demand_repairs,'quantity':quantity,'unit_price':unit_price,'parts_total':parts_total,'labor_total':labor_total}
            invoice_item.push(obj);
        }
        $.ajax({
            method:'post',
            url:'Invoice/SaveInvoice',
            data:{'invioce':Invoice,'invoice_item':invoice_item},
            success:function(result) {
                   console.log(result); 
            }
        });
        
    }
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
    function calculate_total(sr_no) {
        var quantity = $('#quantity_'+sr_no).val() != '' ? $('#quantity_'+sr_no).val() : 0;
        var unit_price = $('#unit_price_'+sr_no).val() != '' ? $('#unit_price_'+sr_no).val() : 0;
        var part_taxes = $('#part_taxes').val() != '' ? $('#part_taxes').val() : 0;
        var labor_taxes = $('#labor_taxes').val() != '' ? $('#labor_taxes').val() : 0;
        var parts_total = parseFloat(quantity) * parseFloat(unit_price);
        $('#parts_total_'+sr_no).val(parts_total);
        
        var tr_length = $('#demand_table tbody tr').length;
        var total_rows = (parseInt(tr_length) - 5);
        var part_total = 0;
        var labor_total = 0;
        for(var i=1;i<=total_rows;i++) {
            part_total += $('#parts_total_'+i).val() != '' ? parseFloat($('#parts_total_'+i).val()) : 0;
            labor_total += $('#labor_total_'+i).val() != '' ? parseFloat($('#labor_total_'+i).val()) : 0;
        }
        $('#indiv_parts_total').text(part_total);
        $('#indiv_labor_total').text(labor_total);
        var grand_total = part_total + labor_total;
        $("#grand_total").text(grand_total);
        $('#amount_due').text(parseFloat(grand_total) + parseFloat(part_taxes) + parseFloat(labor_taxes));
        calculate_final();
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
                                $('#cust_vehi').append($('<option></option>').attr('reg_no',value.reg_no).attr('clr',value.color).attr('value',value.vehicle_id).text(value.model));
                                if($('#forwarded_veh_id').val() != 0) {
                                    $('#cust_vehi').val($('#forwarded_veh_id').val()).trigger('change');
                                }
                            });
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
        if(this.value != '') {
            var reg_no =  $('option:selected',elem).attr('reg_no');
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
        add_row_html += "<input type='text' class='form-control input-sm' name='demand_repairs[]' id='demand_repairs_"+srno+"'>";
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
        add_row_html += "<input type='text' id='parts_total_"+srno+"' class='form-control input-sm' name='parts_total[]' readonly=''>";
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