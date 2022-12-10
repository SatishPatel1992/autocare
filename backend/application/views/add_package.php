<?php 
$GLOBALS['title_left'] = '<a href="packages" class="btn btn-sm btn-info btn-outline btn-1e pull-right"><i class="fa fa-arrow-left"></i> Back</a>';
?>
<input type="hidden" id="tax_applicable" value='<?php echo $is_tax_applicable; ?>'>
<input type="hidden" id="discount_applicable" value='<?php echo $is_disc_applicable; ?>'>
<input type="hidden" id="package_id" value='<?php echo $_REQUEST['id']; ?>'>

<form class="form-material form" id="add_vendor_form" method="post">
        <table style="width: 100%;">
          <tr>
              <td style="width: 30%;">
                  <div class="form-group form-material floating" data-plugin="formMaterial">
                      <input type="text" class="form-control input-sm" name="package_name" id="package_name" value="<?php echo $package['package_name']; ?>">
                      <label class="floating-label">Package Name</label>
                  </div>
              </td>
              <td style="width: 70%;">
                  <div class="form-group form-material floating" data-plugin="formMaterial">
                      <input type="text" class="form-control input-sm" id="description" value="<?php echo $package['description']; ?>">
                      <label class="floating-label">Description</label>
                  </div>
              </td>
          </tr>
        </table>
        <div class="row">
            <div class="col-md-8 col-lg-8 col-xs-8" style="margin-top: 10px;">
                    <div class="form-group has-search" style="margin-bottom: 10px;display:flex;">
                        <i class="fa fa-search form-control-feedback"></i>
                    <input type="text" class="form-control" placeholder="Search Item by Name..." id="search_item">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-info" onclick="add_new_item()"><i class="fa fa-plus"></i></button>
                    </span>
                </div>
            </div>
        </div>
        <table class="table table-bordered booking_item_table" style="width: 100%;" id="jobTable">
                            <tr>
                                <td style="width: 3%;text-align: center;background-color: lavender;">
                                <button class="btn btn-xs btn-info jobActionOption dropdown-toggle" type="button" id="sendToButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-arrows" aria-hidden="true"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="sendToButton">
                                                <a class="dropdown-item" onclick="itemAction(1)">Delete Selected</a>
                                        </div>
                                </td>
                                <td style="width: <?php echo 30 + $is_disc_applicable === 'Y' ? 23 : 0 . '%'; ?>;background-color: lavender;">Items</td>
                                <td style="width: 5%;background-color: lavender;text-align:center;">Qty</td>
                                <td style="width: 10%;background-color: lavender;text-align:center;">Unit Price (&#8377)</td>
                                <?php if (isset($is_disc_applicable) && $is_disc_applicable == "Y") { ?>
                                    <td style="width: 12%;background-color: lavender;text-align:center;">Discount</td>
                                <?php } ?>
                                <?php if (isset($is_tax_applicable) && $is_tax_applicable == "Y") { ?>
                                    <td style="width: 12%;background-color: lavender;text-align:center;">Tax (&#8377 / %)</td>
                                <?php } ?>
                                <td style="width: 10%;background-color: lavender;text-align:center;">Line Total</td>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4" style="text-align: left;font-weight: bold;">Grand Total</td>
                                    <?php if (isset($is_disc_applicable) && $is_disc_applicable == "Y") { ?>
                                        <td style="text-align: center;font-weight:bold;font-size:15px;"><i class="fa fa-rupee"></i> <span id="sub_total_discount">0</span></td>
                                    <?php } ?>
                                    <?php if (isset($is_tax_applicable) && $is_tax_applicable == "Y") { ?>
                                        <td style="text-align: center;font-weight:bold;font-size:15px;"><i class="fa fa-rupee"></i> <span id="sub_total_tax">0</span></td>
                                    <?php } ?>
                                    <td style="text-align: center;font-weight:bold;font-size:15px;"><i class="fa fa-rupee"></i> <span id="grand_total">0</span></td>
                                </tr>
                            </tbody>
                        </table>
</form>
                        <div class="row">
                            <div class="col-lg-12">
                                <?php if(!isset($_REQUEST['id'])) { ?>
                                    <button onclick="save_package()" style="float:right;" class="btn btn-primary pull-right btn-sm waves-effect waves-classic">Save Package</button>
                                <?php } else { ?>
                                    <button onclick="save_package()" style="float:right;" class="btn btn-primary pull-right btn-sm waves-effect waves-classic">Update Package</button>
                                <?php }?>
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
                <label class="floating-label">Service Name</label>
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
<script>
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
            }
    }
    function getLabel(result) {
        if(result.item_code != "") {
            return result.item_code +"-"+result.item_name
        } else {
            return result.item_name;
        }
    }
    function add_new_item() {
        $('#add_new_item_modal').modal('show');
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
    function fillValue(vacant_index, data,type='') {
        var is_tax_enable = $('#tax_applicable').val();
        var is_discount_enable = $('#discount_applicable').val();
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
        html += '<tr class="item_tr" id="item_tr_'+vacant_index+'">';
        html += '<td>';
        html += '<div class="checkbox-custom checkbox-info"><input type="checkbox" id="chk_'+vacant_index+'" class="row_chk" checked=""><label style="padding-left:unset;">&nbsp;</label></div>';
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
            html += '<input type="text" readonly style="text-align:right;" id="tax_value_'+vacant_index+'"  class="form-control input-sm do_calculation" name="tax_value[]" value='+tax_value+'>';
            html += '</span>';
            html += '<span style="width: 2%;"></span>';
            html += '<span style="width: 38%;">';
            html += '<input type="hidden" id="tax_type_'+vacant_index+'" value='+tax_type+'>';
            html += '<select style="font-size:10px;text-align-last:center;" class="form-control input-sm no-select do_calculation_drp" name="tax_rate[]" id="tax_rate_'+vacant_index+'">';
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
        html += '<input type="text" style="text-align:center;" id="line_total_'+vacant_index+'" value='+line_total+' name="line_total" class="form-control input-sm do_calculation_drp">';
        html += '</div>';
        html += '</td>';
        html += '</tr>';
        if($('.item_tr').length == 0) {
            $(html).insertAfter('#jobTable > tbody > tr:first');
        } else {
            $(html).insertAfter('#jobTable tbody tr.item_tr:last');
        }
        $('#tax_rate_'+vacant_index).val(tax_rate);
        $('#discount_type_'+vacant_index).val(discount_type);
        do_calculation();
    }
    $(document).on('change','.row_chk', function() {
        do_calculation();
    });
    $(document).ready(function() {
        if($('#package_id').val() != "") {
          loadJobItem($('#package_id').val());
        }
        $(document).on('change', 'select.do_calculation_drp', function() {
            do_calculation();
        });
        $(document).on('keyup', '.do_calculation', function() {
            do_calculation();
        });
        $("#search_item").autocomplete({
            source: function( request, response ) {
                var item_type = $('#item_type').val()
                $.ajax({
                    url: "booking/getItemByName",
                    dataType: "json",  
                    data: {
                        term: request.term  
                    },
                    success: function( data ) {
                        if (data.items.length > 0) {
                            response($.map(data.items, function(result) {
                                return {
                                    label: result.item_name,
                                    value: result.item_id,
                                    data: result
                                }
                            }));
                        } else {
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
        });
        $('input[name=applicable_to]').change(function() {
            if(this.value == 'body_type') {
                $('#option_body_type').css('display','table-row');
                $('#option_specific').css('display','none');
            } else if(this.value == 'specific_model') {
                $('#option_body_type').css('display','none');
                $('#option_specific').css('display','table-row');
            }
        });
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
        function loadJobItem(id) {
        $.ajax({
            method: 'POST',
            url: 'common/commonFunc',
            data: { 'id': id,'do':'get_package_detail'},
            success: function(result) {
                var data = JSON.parse(result);
                if(data.data.length > 0) {
                    data.data.forEach(row => {
                        var Index = ($('.item_tr').length + 1);
                        Index = checkIndexAvailable('item_tr',Index);
                        fillValue(Index,row);
                    });
                }
            }
            });
        }
        function toggleProductService(type) {
            if(type=='product') {
                $('#add_product_form').css('display','block');
                $('#add_service_form').css('display','none');
            } else {
                $('#add_product_form').css('display','none');
                $('#add_service_form').css('display','block');
            }
        }
        function save_package() {
            var dataArray = do_calculation();

            $.ajax({
                method: 'POST',
                url: 'Transcation/InsertOperation',
                data: {
                    'mainItem': dataArray.main,
                    'rowItem': dataArray.rows,
                    'table_name': 'tbl_packages',
                    'package_id': $('#package_id').val()
                },
                success: function(result) {
                    var parseRes = JSON.parse(result);
                    toastr.success("Package save successfully !");
                    setTimeout(function() {
                       window.location = 'packages';
                    },800);
                }
            });
        }
        function do_calculation() {
        var is_tax_enable = $('#tax_applicable').val();
        var is_discount_enable = $('#discount_applicable').val();
        var total_discount = 0;
        var total_tax = 0;
        var total_line = 0;
        var grand_total = 0;
        var total_taxable_amount = 0;
        var row_item = [];
        $('.item_tr').each(function(e) {
            var line_total = 0;
            var taxable_amount = 0;
            var discount_amount = 0;
            var index = $(this).attr('id').split('_')[2];
            if($('#chk_'+index).prop('checked')) {
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

            row_item.push({
                item_type: $('#item_type_'+index).val(),
                item_id: $('#item_id_'+index).val(),
                description: $('#item_description_'+index).val(),
                qty: $('#qty_'+index).val(),
                unit_price: $('#unit_price_'+index).val(),
                discount_type : $('#discount_type_'+index).val(),
                discount_value : discount_amount,
                discount_percentage : $('#discount_type_'+index).val() == 'P' ? $('#discount_value_'+index).val() : 0,
                taxable_amount : taxable_amount,
                tax_rate: $('#tax_rate_'+index).val(),
                tax_amount: $('#tax_value_'+index).val(),
                total: line_total                
            });
            }
        });
            var jobdetails = {
                'package_name': $('#package_name').val(),
                'description': $('#description').val(),
                'taxable_amount': total_taxable_amount,
                'total_discount': total_discount.toFixed(0),
                'total_tax': total_tax.toFixed(0),
                'grand_total': grand_total.toFixed(0),
            }
            $('#sub_total_discount').text(total_discount.toFixed(0));
            $('#sub_total_tax').text(total_tax.toFixed(0));
            $('#grand_total').text(grand_total.toFixed(0));
            return {'main': jobdetails,'rows':row_item};
        }
        

</script>
