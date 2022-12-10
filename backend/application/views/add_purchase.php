<?php
$GLOBALS['title_left'] = '<a href="javascript:void(0)" onclick="window.history.back()" class="btn btn-sm btn-info btn-outline btn-1e pull-right"><i class="fa fa-arrow-left"></i> Back</a>';
?>
<input type="hidden" id="settingArray" value='<?php echo json_encode($settings); ?>'>
<input type="hidden" id="colConfig" value='<?php echo json_encode($colConfig);?>'>
<form class="form-material form" id="add_purchase_order_form" method="post">
            <input type="hidden" id="po_id" name="po_id" value="<?php echo $_REQUEST['id']; ?>">
            <div class="row">
                        <div class="col-md-2">
                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                <select class="form-control input-sm" name="vendor_id" id="vendor_id">
                                    <?php foreach ($vendors as $v) { ?>
                                        <option data-gst_no="<?php echo $v['gst_no']; ?>" <?php if(isset($purchase['vendor_id']) && ($purchase['vendor_id'] == $v['vendor_id'])) { echo "selected"; } ?> data-payTerms="<?php echo $v['credit_period']; ?>" value="<?php echo $v['vendor_id']; ?>"><?php echo $v['company_name']; ?></option>
                                    <?php } ?>
                                </select>
                                <label class="floating-label required">Vendor</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                <input type="text" class="form-control input-sm" name="invoice_no" value="<?php if (isset($purchase['bill_no']) && !empty($purchase['bill_no'])) { echo $purchase['bill_no']; }?>">
                                <label class="floating-label">Invoice/Bill No.</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                <input type="text" class="form-control input-sm" name="invoice_date" value="<?php if(isset($purchase['invoice_date']) && $purchase['invoice_date'] != "") { echo date('d-m-Y',strtotime($purchase['invoice_date'])); } else { echo date('d-m-Y'); } ?>">
                                <label class="floating-label">Date</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                <input type="text" class="form-control input-sm" name="due_date" id="due_date" value="<?php if(isset($purchase['due_date']) && $purchase['due_date'] != "") { echo date('d-m-Y',strtotime($purchase['due_date'])); } else { echo date('d-m-Y'); } ?>">
                                <label class="floating-label">Due Date</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                            <div class="col-md-8 col-lg-8 col-xs-8" style="margin-top: 10px;">
                                <div class="form-group has-search" style="margin-bottom: 10px;display:flex;">
                                    <i class="fa fa-search form-control-feedback"></i>
                                    <input type="text" class="form-control" placeholder="Search Part/Labor Item..." id="search_item">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-info" onclick="add_new_item()"><i class="fa fa-plus"></i></button>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-lg-12 col-xs-12" style="margin-top: 10px;">
                            <table class="table table-bordered booking_item_table" data-plugin="floatThead" style="width: 100%;" id="jobTable">
                            <tr>
                                <td style="width: 3%;text-align: center;background-color: lavender;">
                                <button class="btn btn-xs btn-info jobActionOption dropdown-toggle" type="button" id="sendToButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-arrows" aria-hidden="true"></i>
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="sendToButton">
                                            <a class="dropdown-item" onclick="itemAction(1)">Delete Selected</a>
                                        </div>
                                </td>
                                <td style="width: 30%;background-color: lavender;">Items</td>
                                <td style="width: 5%;background-color: lavender;text-align:center;">Qty</td>
                                <td style="width: 10%;background-color: lavender;text-align:center;">Unit Price (&#8377)</td>
                                <td style="width: 12%;background-color: lavender;text-align:center;">Discount</td>
                                <td style="width: 12%;background-color: lavender;text-align:center;">Tax (&#8377 / %)</td>
                                <td style="width: 10%;background-color: lavender;text-align:center;">Line Total</td>
                            </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4" style="text-align: right;font-weight: bold;">Sub Total</td>
                                    <td style="text-align: right;font-size:15px;"><i class="fa fa-rupee"></i> <span id="sub_total_discount">0</span></td>
                                    <td style="text-align: right;font-size:15px;"><i class="fa fa-rupee"></i> <span id="sub_total_tax">0</span></td>
                                    <td style="text-align: right;font-size:15px;"><i class="fa fa-rupee"></i> <span id="grand_total">0</span></td>
                                </tr>
                            </tbody>
                        </table>
                        <input type="hidden" id="tax_type">
                        <table class="table table-bordered" style="width: 100%;" id="taxable_amt_summary">
                                <tr>
                                    <th style="width:70%"></th>
                                    <th style="width:30%">
                                        <span style="text-align: left;">Taxable Amount</span>
                                        <span style="float: right;font-size:15px;"><i class="fa fa-rupee"></i> <span id="total_taxable_amount">0</span>
                                    </th>
                                </tr>
                                <tr class="scgst_tr">
                                    <th style="width:70%"></th>
                                    <th style="width:30%">
                                        <span style="text-align: left;">SGST</span>
                                        <span style="float: right;font-size:15px;"><i class="fa fa-rupee"></i> <span id="total_sgst">0</span>
                                    </th>
                                </tr>
                                <tr class="scgst_tr">
                                    <th style="width:70%"></th>
                                    <th style="width:30%">
                                        <span style="text-align: left;">CGST</span>
                                        <span style="float: right;font-size:15px;"><i class="fa fa-rupee"></i> <span id="total_cgst">0</span>
                                    </th>
                                </tr>
                                <tr class="igst_tr" style="display: none;">
                                    <th style="width:70%"></th>
                                    <th style="width:30%">
                                        <span style="text-align: left;">IGST</span>
                                        <span style="float: right;font-size:15px;"><i class="fa fa-rupee"></i> <span id="total_igst">0</span>
                                    </th>
                                </tr>
                                <tr>
                                    <th style="width:70%"></th>
                                    <th style="width:30%">
                                        <span style="color:#3f51b5;text-align: left;font-weight:bold;">Total Payable</span>
                                        <span style="color:#3f51b5;float: right;font-weight:bold;font-size:15px;"><i class="fa fa-rupee"></i> <span id="total_amount">0</span>
                                    </th>
                                </tr>
                                <!-- <tr>
                                    <th style="width:70%"></th>
                                    <th style="width:30%">
                                        <span style="color:#4caf50;text-align: left;">Paid Amount</span>
                                        <span style="color:#4caf50;float: right;font-size:15px;"><i class="fa fa-rupee"></i> <span id="received_amount">0</span>
                                    </th>
                                </tr>
                                <tr>
                                    <th style="width:70%"></th>
                                    <th style="width:30%">
                                        <span style="color:red;text-align: left;">Balance Amount</span>
                                        <span style="color:red;float: right;font-size:15px;"><i class="fa fa-rupee"></i> <span id="balance_amount">0</span>
                                    </th>
                                </tr> -->
                        </table>
                        </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-10">
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <input type="text" class="form-control input-sm" name="notes" value="<?php if(isset($purchase['notes']) && $purchase['notes'] != "") { echo $purchase['notes']; } ?>">
                                    <label class="floating-label">Notes</label>
                                </div>
                            </div>
                            <div class="col-lg-2" style="text-align:right;">
                                <button type="button" style="margin-top:10px;" onclick="save_po_details()" class="btn btn-sm btn-success btn-outline btn-1e"><?php if(!isset($_REQUEST['id'])) { echo 'Save'; } else { echo 'Update'; }  ?></button>
                            </div>
                        </div><br>
                        </form>
<div id="add_new_item_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 5px !important;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title">Add Item</h4>
            </div>
            <div class="modal-body">
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
        </div>
    </div>
</div>
<style>
    .ui-autocomplete {
        z-index: 2150000000;
    }
    #stock_adjustment .form-group,
    #add_payment_form .form-group
    {
        margin-bottom: 2px;
    }
    #add_payment_form .form-material.floating {
        margin-top: 0px !important;
        margin-bottom: 0px !important;
    }
</style>
<script>
    function addDaysToDate(date,days) {
        // if(date != "") {
        //     var CurrentDate = new Date(date.split("-")[2]+'-'+date.split("-")[1]+'-'+date.split("-")[0]);
        //     CurrentDate.setDate(CurrentDate.getDate() + parseFloat(days));
        //     return CurrentDate;
        // }
        return date;
    }
    $(document).on('change', 'select[name=vendor_id]',function() {
        var days = $(this).find(':selected').data('payterms');
        $('#po_payment_term').val(days);
        var order_date = $('input[name=order_date]').val();
        if(order_date) {
            $('input[name=due_date]').datepicker("setDate", addDaysToDate(order_date,days));
        }
    });
    $(document).on('keyup', '#po_payment_term',function() {
        var days = this.value != "" ? this.value : 0;
        var order_date = $('input[name=order_date]').val();
        if(order_date) {
            $('input[name=due_date]').datepicker("setDate", addDaysToDate(order_date,days));
        }
    });
    function add_payment(id) {
        $.ajax({
            method: 'POST',
            url: 'Common/CommonFunc',
            data: {
                'po_id': id,
                'do': 'get_payment_details'
            },
            success: function(result) {
                var returnData = JSON.parse(result);
                $('#payment_details_modal').modal('show');
                $('#payment_item_id').val(id);
                $('#total_payable').text(returnData['data']['payable']);
                $('#total_paid').text(returnData['data']['paid']);
                $('#total_balance').text(returnData['data']['balance']);
                $('input[name=amount]').val(returnData['data']['balance']);
            }
        });
    }
    function save_transcation() {
        if($('input[name=payment_date]').val() == "") {
            toastr.warning('Payment date is required !');
            $('input[name=date]').focus();
            return false;
        } else if($('input[name=amount]').val() == "") {
            toastr.warning('Amount is required !');
            $('input[name=amount]').focus();
            return false;
        }

        $.ajax({
            method: 'POST',
            url: 'Transcation/InsertOperation',
            data: {'table_name': 'tbl_transaction_po','data': $('#add_payment_form').serialize()},
            success: function(result) {
                var parseRes = JSON.parse(result);
                if(parseRes && parseRes['status'] == 200) {
                    toastr.success("Payment recorded successfully !");
                    setTimeout(function() {
                       window.location.reload();     
                    },1000);
                }
            }
        });
    }
    function loadJobItem(id) {
        $.ajax({
            method: 'POST',
            url: 'common/commonFunc',
            data: { 'id': id,'do':'get_bill_item'},
            success: function(result) {
                var data = JSON.parse(result);

                if(data.data.bill_items && data.data.bill_items.length > 0) {
                    data.data.bill_items.forEach(row => {
                        var Index = ($('.item_tr').length + 1);
                        Index = checkIndexAvailable('item_tr',Index);
                        itemArray.push(row);
                        fillValue(Index,row);
                    });
                }

            }
            });
    }
    $(document).on('change','.row_chk', function() {
        do_calculation();
    });
    function save_po_details() {
        if($('select[name=vendor_id]').val()== '') {
            toastr.warning("Please select vendor name !");
            return false;
        }
        var dataArray = do_calculation();
        var po_id = $('#po_id').val() != "" && $('#po_id').val() != 0 ? $('#po_id').val() : 0;
        $.ajax({
            method: 'POST',
            url: 'Transcation/InsertOperation',
            data: {
                'mainItem': dataArray.main,
                'rowItem': dataArray.rows,
                'table_name': 'tbl_vendor_bills',
                'po_id' : po_id,
                'ap_account': $('#acc_ap_account').val()
            },
            success: function(result) {
                var parseRes = JSON.parse(result);
                if(parseRes && parseRes['status'] == 200) {
                    toastr.success(parseRes['message']);
                    setTimeout(function() {
                        window.location = 'purchase';
                    },1000);
                }
            }
        });
    }
    function create_order(id="") {
        var colConfig = JSON.parse($('#colConfig').val());
        var enable_discount = colConfig.is_disc_applicable;
        var enable_tax = colConfig.is_tax_applicable;
        enable_discount == 'Y' ? $('.discount').css('display','table-cell') : $('.discount').css('display','none');
        enable_tax == 'Y' ? $('.tax').css('display','table-cell') : $('.tax').css('display','none');

        if(id == "") {
            $('#purchaseTable tbody tr.porow_tr').remove();
            purchaseOrderCalculation();
            $('#po_id').val(0);
        } else {
            $.ajax({
            method: 'POST',
            url: 'Common/CommonFunc',
            data: {
                'po_id': id,
                'do': 'get_po_details'
            },
            success: function(result) {
                $('#po_id').val(id);
                var JsonResult = JSON.parse(result);
                var paid = JsonResult['data']['paid'] != null && JsonResult['data']['paid'] != "" ? JsonResult['data']['paid'] : 0;
                $('#total_paid_amount').text(paid);
                var balance = JsonResult['data']['grand_total'] - paid;
                $('#total_due_amount').text(balance);
                if(JsonResult && JsonResult['data']['data']) {
                    $('#purchaseTable tbody tr.porow_tr').remove();
                    JsonResult['data']['data'].forEach(function(element) {
                        var Index = $('.porow_tr').length + 1;
                        Index = checkIndexAvailable(Index,'porow_tr');
                        purchaseOrderRow(Index,element);
                    });
                }
            }
            });         
        }
    }
    function updateTaxDetails(total_taxable_amount,companyGSTIN) {
        var customerGSTIN = $('#vendor_id').find(':selected').data('gst_no');
        var totalTaxAmount = 0;
        var totalGST = 0;
        taxableItems.forEach(element => {
            totalTaxAmount += parseFloat(element['taxable_amount']);
            totalGST += parseFloat((element['taxable_amount'] * element['tax_rate']) / 100);
        });
        $('#total_taxable_amount').text(totalTaxAmount.toFixed(2));

        if(companyGSTIN && companyGSTIN != '') {
            custStateCode =  customerGSTIN.substr(0, 2);
            compStateCode =  companyGSTIN.substr(0, 2);

            if(customerGSTIN && customerGSTIN != '' && custStateCode == compStateCode) { // CGST-SGST Applicable.
                var scgst = (totalGST / 2).toFixed(2);
                $('.igst_tr').css('display','none');
                $('.scgst_tr').css('display','');
                $('#total_sgst').text(scgst);
                $('#total_cgst').text(scgst);
                $('#tax_type').val('scgst');
            } else if(customerGSTIN && customerGSTIN != '' && custStateCode != compStateCode) { // IGST Applicable
                $('.igst_tr').css('display','');
                $('.scgst_tr').css('display','none');
                $('#total_igst').text((totalGST).toFixed(0));
                $('#tax_type').val('igst');
            } else {
                var scgst = (totalGST / 2).toFixed(2);
                $('.igst_tr').css('display','none');
                $('.scgst_tr').css('display','');
                $('#total_sgst').text(scgst);
                $('#total_cgst').text(scgst);
                $('#tax_type').val('scgst');
            }
        }
    }
    function addDaysToDate(date,days) {
        // if(date != "") {
        //     var CurrentDate = new Date(date.split("-")[2]+'-'+date.split("-")[1]+'-'+date.split("-")[0]);
        //     CurrentDate.setDate(CurrentDate.getDate() + parseFloat(days));
        //     return CurrentDate;
        // }
        return date;
    }
    $(document).on('change', 'select[name=vendor_id]',function() {
        var days = $(this).find(':selected').data('payterms');
        $('#po_payment_term').val(days);
        var order_date = $('input[name=order_date]').val();
        if(order_date) {
            $('input[name=due_date]').datepicker("setDate", addDaysToDate(order_date,days));
        }
    });
    $(document).on('keyup', '#po_payment_term',function() {
        var days = this.value != "" ? this.value : 0;
        var order_date = $('input[name=order_date]').val();
        if(order_date) {
            $('input[name=due_date]').datepicker("setDate", addDaysToDate(order_date,days));
        }
    });
    $(document).on('change', 'select.poRowChangeDrp', function() {
            purchaseOrderCalculation();
    });
    $(document).on('keyup', '.poRowChange', function() {
            purchaseOrderCalculation();
    });
    function adjust_qty() {
        if($('#adjust_qty').val() == "" && $('#adjust_qty').val() == 0) {
            toastr.warning("Please enter qty to save!");
            $('#adjust_qty').focus();
            return false;
        }
        if($('input[name=item_id]').val() == 0 || $('input[name=item_id]').val() == "") {
            toastr.warning("Item ID Parameter is missing can not save !");
            return false;
        }
        $.ajax({
            method: 'POST',
            url: 'Transcation/InsertOperation',
            data: {
                'qty': $('#adjust_qty').val(),
                'item_id': $('input[name=item_id]').val(),
                'table_name': 'tbl_inventory'
            },
            success: function(result) {
                toastr.success('Stock adjust successfully !');
                setTimeout(function() {
                    window.location.reload();
                },800);
            }
        });
    }
    $('#adjust_qty').on('keyup',function() {
        var current_qty = $('#count_current_qty').text() != "" && $('#count_current_qty').text() != null ? $('#count_current_qty').text() : 0;
        var adjust_qty = this.value != "" ? this.value : 0;
        var final_stock = parseFloat(current_qty) + parseFloat(adjust_qty);
        $('#count_final_qty').text(final_stock);
    });
    function save_product() {
        var data = '';
        if($('#item_name_product').val() == "") {
            $('#item_name_product').focus();
            toastr.warning("Item name is required !");
            return false;
        }
        data = $('#add_product_form').serialize();
        var item_id = $('input[name=item_id]').val() != "" ? $('input[name=item_id]').val() : 0;
        $.ajax({
            method: 'POST',
            url: 'Transcation/InsertOperation',
            data: {
                'data': data,
                'item_id': item_id,
                'item_type': 'P',
                'table_name': 'tbl_items'
            },
            success: function(result) {
                toastr.success('Item added successfully !');
                var retunData = JSON.parse(result);
                var Index = ($('.item_tr').length + 1);
                Index = checkIndexAvailable('item_tr',Index);
                itemArray.push(retunData['data']);
                fillValue(Index,retunData['data'],'default');
                $('#add_new_item_modal').modal('hide');
                $('#add_product_form').trigger("reset");
                $("#add_product_form").each(function(){
                    $(this).find(':input').addClass('empty');
                });
            }
        });
     }
     $('#enableLowStockCheck').on('change',function() {
        if($(this).prop('checked')) {
            $('input[name=low_stock_warning]').val('Y');
            $('#lowStockInputDiv').css('display','block');
            $('input[name=low_stock_unit]').focus();
        } else {
            $('input[name=low_stock_warning]').val('N');
            $('#lowStockInputDiv').css('display','none');
        }
    });
    function add_new_item() {
        $('input[name=item_id]').val(0);
        $('.opening_stock_divs').css('display','block');
        $('#item_type_row').css('display','block');
        $('#add_new_item_modal').modal('show');
    }
    itemArray = [];
    $(function() {
        $(document).on('change', 'select.do_calculation_drp', function() {
            do_calculation();
        });
        $(document).on('keyup', '.do_calculation', function() {
            do_calculation();
        });
    });
    $(document).ready(function() {
        if($('#po_id').val() != "") {
          loadJobItem($('#po_id').val());
        }
        $('input[name=payment_date]').datepicker({
            format: 'dd-mm-yyyy',
            autoclose:true
        }).datepicker("setDate", new Date());
        $('input[name=invoice_date]').datepicker({
            format: 'dd-mm-yyyy',
            autoclose:true
        });
        $('input[name=order_date]').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy'
        });
        $('input[name=due_date]').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy'
        });
        var orders_table = $('#orders_table').dataTable({
            bPaginate: false,
            bInfo: false,
        });
        var days = $('#po_id').val() != "" ? $('#po_payment_term').val() : $('select[name=vendor_id]').find(':selected').data('payterms');
 
        // if($('input[name=order_date]').val() != "") {
        //     $('input[name=due_date]').datepicker("setDate", addDaysToDate($('input[name=order_date]').val(),days));
        // }
        $( document ).on('click','.bs-dropdown-to-select-group .dropdown-menu li', function( event ) {
            var $target = $(event.currentTarget);
    		$target.closest('.bs-dropdown-to-select-group')
			.find('[data-bind="bs-drp-sel-value"]').val($target.attr('data-value'))
			.end()
			.children('.dropdown-toggle').dropdown('toggle');
	    	    $target.closest('.bs-dropdown-to-select-group')
    		.find('[data-bind="bs-drp-sel-label"]').text($(this).data('value')).end().find('input[type=hidden]').val($(this).data('value'));
		return false;
	    });
        $("#search_item").autocomplete({
            source: function(request, response) {
                if ($('#vehicle_id').val() == "" || $('#customer_id').val() == "") {
                    toastr.warning('Please select customer first.');
                    $('#auto_vehicle_no').focus();
                    return false;
                }
                $.ajax({
                    url: "booking/getItemByName",
                    dataType: "json",
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        if (data.items.length > 0) {
                            response($.map(data.items, function(result) {
                                return {
                                    label: result.item_name,
                                    value: result.item_id,
                                    data: result
                                }
                            }));
                        }
                         else {
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
                    itemArray.push(data);
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
    function fillValue(vacant_index, data,type='',trID='') {
        var is_tax_enable = 'Y';
        var is_discount_enable = 'Y';
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
        console.log(tax_rate);
        var html = '';
        html += '<tr class="item_tr '+trID+'" id="item_tr_'+vacant_index+'">';
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
            html += '<select style="text-align-last:center;" class="form-control input-sm no-select do_calculation_drp" name="tax_rate[]" id="tax_rate_'+vacant_index+'">';
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
        html += '<input type="text" style="text-align:right;" readonly id="line_total_'+vacant_index+'" value='+line_total+' name="line_total" class="form-control input-sm do_calculation_drp">';
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
    var taxableItems = [];
    function do_calculation() {
        var is_tax_enable = 'Y';
        var is_discount_enable = 'Y';
        var total_discount = 0;
        var total_tax = 0;
        var total_line = 0;
        var grand_total = 0;
        var total_taxable_amount = 0;
        var row_item = [];
        var total_customer_payable = 0;
        taxableItems = [];
        var total
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
            taxableItems.push({'tax_rate' : $('#tax_rate_'+index).val(),'taxable_amount' : taxable_amount});
            }
        });
        var settings = JSON.parse($('#settingArray').val());
        if(settings['gstin_no']) {
            updateTaxDetails(total_taxable_amount,settings['gstin_no']);
        }
        var jobdetails = {
            'vendor_id' : $('#vendor_id').val(),
            'bill_no' : $('input[name=invoice_no]').val(),
            'invoice_date' : $('input[name=invoice_date]').val(),
            'due_date': $('input[name=due_date]').val(),
            'tax_type' : $('#tax_type').val(),
            'taxable_amount': total_taxable_amount,
            'total_discount': total_discount.toFixed(0),
            'total_tax': total_tax.toFixed(0),
            'grand_total': grand_total.toFixed(0),
            'notes': $('input[name=notes]').val()
        }
        $('#sub_total_discount').text(total_discount.toFixed(2));
        $('#sub_total_tax').text(total_tax.toFixed(2));
        $('#grand_total').text(grand_total.toFixed(2));
        $('#total_amount').text(grand_total.toFixed(0));
        var total_balance_amount = parseFloat(grand_total) - parseFloat($('#received_amount').text());
        $('#balance_amount').text(total_balance_amount.toFixed(0));
        return {'main': jobdetails,'rows':row_item};
    }
    function getLabel(result) {
        if (result.item_code != "") {
            return result.item_code + "-" + result.item_name
        } else {
            return result.item_name;
        }
    }
    function openItemModel() {
        $('#item_name_product').val($('#search_item').val());
        $('#item_name_product').removeClass('empty');
        setTimeout(function() {
            $('#search_item').val('');
        }, 200);
        $('#add_new_item_modal').modal('show');
    }
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
</script> 