<?php
$GLOBALS['title_left'] = '<a href="javascript:void(0);" onclick="add_new_item()" class="btn btn-sm btn-info btn-outline btn-1e pull-right"><i class="fa fa-plus"></i> Create New Item</a>';
?>
<div class="row">
                <div class="col-lg-12">
                        <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group has-search">
                                <span class="fa fa-search form-control-feedback"></span>
                                <input type="text" class="form-control" placeholder="Search Items..." id="searchbox_item">
                            </div>
                        </div>
                        <div class="col-lg-8" style="text-align:right;font-size:15px;">
                          <span> Total Stock Item : <span style="font-weight:bold;" id="total_stock_item">0</span> |  Stock Value : <i class="fa fa-rupee"></i> <span style="font-weight:bold;" id="total_stock_value"></span> </span>
                        </div>
                        </div>
                        <table id="item_table" class="table table-bordered" style="border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <!-- <th style="width: 5%;font-weight: bold;background-color: lavender;">Sr.No</th> -->
                                    <th style="width: 30%;font-weight: bold;background-color: lavender;">Item Name</th>
                                    <th style="width: 10%;font-weight: bold;background-color: lavender;">Purchase Price</th>
                                    <th style="width: 12%;font-weight: bold;background-color: lavender;">Stock Qty</th>
                                    <th style="width: 10%;font-weight: bold;background-color: lavender;">Stock Value</th>
                                    <th style="width: 10%;font-weight: bold;background-color: lavender;">Selling Price</th>
                                    <th style="width: 10%;font-weight: bold;background-color: lavender;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $srno = 1;
                                $total_stock_item = 0;
                                $total_stock_value = 0;
                                foreach ($items as $k => $v) {
                                    $current_stock = 0;
                                    ?>
                                    <tr>
                                        <!-- <td><?php echo $srno; ?></td> -->
                                        <td><?php echo $v['item_name']; ?></td>
                                        <td><?php echo $v['item_type'] == 'P' ? '<i class="fa fa-rupee"></i> '.$v['purchase_price'] : ''; ?></td>
                                        <td><?php $current_stock = $v['current_stock'] != "" && $v['current_stock'] != null ? $v['current_stock'] : 0;
                                                  echo $v['item_type'] == 'P' ? $current_stock : '';  
                                        ?></td>
                                        <td><?php $stock_value = $current_stock * $v['purchase_price'];
                                                echo $v['item_type'] == 'P' && $stock_value != "" ? '<i class="fa fa-rupee"></i> '.$stock_value : '';
                                        ?></td>
                                        <td><?php echo $v['sell_price'] != "" && $v['sell_price'] != 0 ? '<i class="fa fa-rupee"></i> '.$v['sell_price'] : ''; ?></td>
                                        <td>
                                            <button type="button" class="btn btn-xs btn-info" onclick="item_details(<?php echo $v['item_id']?>)"><i class="fa fa-eye"></i></button>
                                        </td>
                                    </tr>
                                <?php $srno++;
                                $total_stock_item += $v['item_type'] == 'P' ? $current_stock : 0;
                                $total_stock_value += $v['item_type'] == 'P' ? $stock_value : 0;
                                } ?>
                            </tbody>
                        </table>
                </div>
            </div>
<div id="item_details_modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 5px !important;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title">Item Detail</h4>
            </div>
            <div class="modal-body">
                                    
            </div>
            </div>
        </div>
    </div>
</div>
<div id="payment_details_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="width:30%;">
        <div class="modal-content" style="border-radius: 5px !important;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title">Add Payment</h4>
            </div>
            <div class="modal-body">
                <form method="post" class="form-material form" name="form_payment_details" id="add_payment_form">
                    <table style="width: 100%;" class="table table-bordered">
                        <tr>
                            <td style="width:33%;font-weight:bold;text-align:center;"><span>Payable</span></td>
                            <td style="width:33%;font-weight:bold;text-align:center;"><span>Paid</span></td>
                            <td style="width:33%;font-weight:bold;text-align:center;"><span>Balance</span></td>
                        </tr>
                        <tr>
                            <td style="width:33%;font-weight:bolder;text-align:center;"><i class="fa fa-rupee"></i> <span id="total_payable"></span></td>
                            <td style="width:33%;font-weight:bolder;text-align:center;"><i class="fa fa-rupee"></i> <span id="total_paid"></span></td>
                            <td style="width:33%;font-weight:bolder;text-align:center;"><i class="fa fa-rupee"></i> <span id="total_balance"></span></td>
                        </tr>
                    </table>
                    <input type="hidden" id="payment_item_id" name="po_id">
                    <table style="width: 100%;">
                        <tr>
                            <td style="vertical-align:bottom;">Date</td>
                            <td>
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <input type="text" class="form-control input-sm" name="payment_date" value="<?php echo date('d-m-Y'); ?>">
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="vertical-align:bottom;">Amount (<i class="fa fa-rupee"></i>)</td>
                            <td>
                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                <input type="text" class="form-control input-sm" name="amount">
                            </div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="vertical-align:bottom;">Paid from</td>
                            <td>
                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                <select class="form-control input-sm" name="paid_from" id="paid_from">
                                         <?php foreach ($paid_accounts as $k => $v) { ?>
                                        <option value="<?php echo $v['account_id'] ?>"><?php echo $v['account_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="vertical-align:bottom;">A/P Account</td>
                            <td>
                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                <select class="form-control input-sm" name="ap_account">
                                    <?php foreach ($ap_account as $k => $v) { ?>
                                        <option value="<?php echo $v['account_id'] ?>"><?php echo $v['account_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td style="vertical-align:bottom;">Reference No</td>
                            <td>
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <input type="text" class="form-control input-sm" name="reference_no">
                                </div>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td style="text-align: right;">
                                <br>
                                <button type="button" onclick="save_transcation()" class="btn btn-sm btn-info"><i class="fa fa-save"></i> Save Transcation</button>
                            </td>
                            <td></td>
                        </tr>
                    </table>
                </form>
</div>
</div>
</div>
</div>
<div id="add_new_item_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content" style="border-radius: 5px !important;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title">Item Detail</h4>
            </div>
            <div class="modal-body">

            <ul id="invent_tabs" class="nav nav-tabs customtab nav-tabs-line" role="tablist">
        <li role="presentation" class="nav-item active">
            <a href="#item_detail_tab" role="tab" data-toggle="tab" aria-expanded="true" class="nav-link active"><span class="hidden-xs">Item Detail</span></a>
        </li>
        <li role="presentation" class="nav-item">
            <a href="#stock_detail_tab" role="tab" data-toggle="tab" aria-expanded="true" class="nav-link"><span class="hidden-xs">Stock Detail</span></a>
        </li>
        <li role="presentation" class="nav-item">
            <a href="#adjust_stock_tab" role="tab" data-toggle="tab" aria-expanded="true" class="nav-link"><span class="hidden-xs">Adjust Stock</span></a>
        </li>
    </ul>
    <div class="tab-content pt-20">
        <div class="tab-pane active" id="item_detail_tab">
            <input type="hidden" name="item_id" value="0">
            <input type="hidden" name="item_type" value="P">
            <!-- <div class="row" id="item_type_row">
            <div class="col-lg-4 col-md-4">
                <label>Item Type</label><br>
                <label><input type="radio" checked name="item_type" value="P" onclick="toggleProductService('product')"> Product</label>
                <label class="float-right"><input type="radio" name="item_type" value="S" onclick="toggleProductService('service')"> Service</label>
            </div>
            </div> -->
            <form class="form-material form" id="add_product_form" name="add_part" method="post">
            <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="form-group form-material floating" data-plugin="formMaterial">
                    <input type="text" class="form-control input-sm" id="item_name_product" name="item_name">
                    <label class="floating-label required">Item Name</label>
                </div>
            </div>
            <div class="col-lg-6 col-md-6">
                <div class="form-group form-material floating" data-plugin="formMaterial">
                    <input type="text" class="form-control input-sm" name="item_code">
                    <label class="floating-label">Item Code</label>
                </div>
            </div>
            </div>
        <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" id="description" name="description">
                <label class="floating-label">Description</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="form-group form-material floating" data-plugin="formMaterial" style="display:inline-flex;width:inherit;">
                <input type="text" class="form-control input-sm calcSellingPriceAmt" name="purchase_price" autocomplete="off" id="purchase_price">
                <label class="floating-label">Purchase Price</label>
                <div class="input-group" style="width:auto;">
            <div class="bs-dropdown-to-select-group purchase_price_tax">
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
        <div class="col-lg-6 col-md-6">
            <div class="form-group form-material floating" data-plugin="formMaterial" style="display:inline-flex;width:inherit;">
                <input type="text" class="form-control input-sm" name="sell_price" id="sell_price">
                <label class="floating-label">Sell Price</label>
                <div class="input-group" style="width:auto;">
            <div class="bs-dropdown-to-select-group sell_price_tax">
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
        <div class="col-lg-6 col-md-6 opening_stock_divs">
                <div class="form-group form-material floating" data-plugin="formMaterial">
                    <input type="text" class="form-control input-sm" name="opening_stock">
                    <label class="floating-label">Opening Stock</label>
                </div>
        </div>         
        <div class="col-lg-6 col-md-6 opening_stock_divs">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="date">
                <label class="floating-label">As of Date</label>
            </div>
        </div>
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
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="item_code">
                <label class="floating-label">Service Code</label>
            </div>
        </div>
        </div>
    <div class="row">
    <div class="col-lg-12 col-md-12">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="description">
                <label class="floating-label">Description</label>
            </div>
    </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="form-group form-material floating" data-plugin="formMaterial" style="display:inline-flex;width:inherit;">
                <input type="text" class="form-control input-sm" name="sell_price">
                <label class="floating-label">Sells Price</label>
                <div class="input-group" style="width:auto;">
            <div class="bs-dropdown-to-select-group sell_price_tax_service">
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
        <div class="col-lg-3 col-md-3">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                 <input type="text" class="form-control input-sm" name="hsn_sac_code">
                <label class="floating-label">SAC Code</label>
            </div>
        </div>
        <div class="col-lg-3 col-md-3">
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
        <div class="tab-pane" id="stock_detail_tab">
        <table id="inventory_table" class="table table-bordered" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="font-weight: bold;background-color: lavender;">Date</th>
                    <th style="font-weight: bold;background-color: lavender;">Transaction</th>
                    <th style="font-weight: bold;background-color: lavender;">Qty</th>
                    <th style="font-weight: bold;background-color: lavender;">Closing Stock</th>
                </tr>
            </thead>
            <tbody>
                    <tr>
                    
                    </tr>
            </tbody>
        </table>
        </div>
        <div class="tab-pane" id="adjust_stock_tab">
                <table class="table table-bordered" id="stock_adjustment">
                     <tr>   
                        <th style="text-align:center;width:33%;">Current Qty</th>
                        <th style="text-align:center;width:33%;">Adjust Qty</th>
                        <th style="text-align:center;width:33%;">Closing Qty</th>
                     </tr>
                     <tr>
                        <td style="font-size:20px;text-align:center;" id="count_current_qty">0</td>
                        <td style="font-size:20px;text-align:center;">
                        <div class="form-group form-material" data-plugin="formMaterial">
                                <input type="number" style="font-size:20px;text-align:center;" id="adjust_qty" class="form-control input-sm" name="adjust_stock">
                                <input type="hidden" id="item_id_stock">
                        </div>
                        </td>
                        <td style="font-size:20px;text-align:center;" id="count_final_qty"> 0</td>
                     </tr>
                </table>
                <div class="row pull-right">
                <div class="col-lg-12 text-right">
                    <button type="button" onclick="adjust_qty()" class="btn btn-sm btn-info btn-outline btn-1e">Save</button>
                </div>
                </div>                             
        </div>
    </div>
        </div>
    </div>
</div>
</div>
<div id="add_item_only_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                <label><input type="radio" checked name="item_type_new" value="P" onclick="toggleProductService('add_new_product_form','add_new_service_form','product')"> Product</label>
                <label class="float-right"><input type="radio" name="item_type_new" value="S" onclick="toggleProductService('add_new_product_form','add_new_service_form','service')"> Service</label>
            </div>

            </div>
            <form class="form-material form" id="add_new_product_form" name="add_part" method="post">
            <div class="row">
            <div class="col-lg-6 col-md-6">
                <div class="form-group form-material floating" data-plugin="formMaterial">
                    <input type="text" class="form-control input-sm" id="item_name_product_new_item" name="item_name">
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
            <button type="button" class="btn btn-success btn-sm btn-outline btn-1e pull-right" onclick="save_new_product()"><i class="fa fa-floppy-o"></i> Save Product</button>
        </div>
    </div>
</form>
<form class="form-material form" id="add_new_service_form" name="add_service" method="post" style="display:none;">
    <div class="row">
    <div class="col-lg-6 col-md-6">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="item_name" id="item_name_service_new_item">
                <label class="floating-label">Service Name</label>
            </div>
        </div>
        <div class="col-lg-6 col-md-6">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="item_code">
                <label class="floating-label">Service Code</label>
            </div>
        </div>
        </div>
    <div class="row">
    <div class="col-lg-12 col-md-12">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="description">
                <label class="floating-label">Description</label>
            </div>
    </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6">
            <div class="form-group form-material floating" data-plugin="formMaterial" style="display:inline-flex;width:inherit;">
                <input type="text" class="form-control input-sm" name="sell_price">
                <label class="floating-label">Sells Price</label>
                <div class="input-group" style="width:auto;">
            <div class="bs-dropdown-to-select-group sell_price_tax_service">
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
        <div class="col-lg-3 col-md-3">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                 <input type="text" class="form-control input-sm" name="hsn_sac_code">
                <label class="floating-label">SAC Code</label>
            </div>
        </div>
        <div class="col-lg-3 col-md-3">
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
            <button type="button" class="btn btn-success btn-sm btn-outline btn-1e pull-right" onclick="save_new_product()"><i class="fa fa-floppy-o"></i> Save Service</button>
        </div>
    </div>
     </form>
        </div>
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
    .badge {
        width:50%;
    }
</style>
<script>
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
    function save_po_details() {
        if($('select[name=vendor_id]').val()== '') {
            toastr.warning("Please select vendor name !");
            return false;
        }
        var dataArray = purchaseOrderCalculation();
        var po_id = $('#po_id').val() != "" && $('#po_id').val() != 0 ? $('#po_id').val() : 0;
        $.ajax({
            method: 'POST',
            url: 'Transcation/InsertOperation',
            data: {
                'mainItem': dataArray.main,
                'rowItem': dataArray.rows,
                'table_name': 'tbl_vendor_bills',
                'po_id' : po_id
            },
            success: function(result) {
                var parseRes = JSON.parse(result);
                if(parseRes && parseRes['status'] == 200) {
                    toastr.success(parseRes['message']);
                    setTimeout(function() {
                        window.location.reload();     
                    },1000);
                }
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
    function create_order(id="") {
        var colConfig = JSON.parse($('#colConfig').val());
        var enable_discount = colConfig.is_disc_applicable;
        var enable_tax = colConfig.is_tax_applicable;
        $('#paid_total_td').attr('colspan',colConfig.colspan);
        $('#balance_total_td').attr('colspan',colConfig.colspan);
        enable_discount == 'Y' ? $('.discount').css('display','table-cell') : $('.discount').css('display','none');
        enable_tax == 'Y' ? $('.tax').css('display','table-cell') : $('.tax').css('display','none');

        if(id == "") {
            $('#add_purchase_order').modal('show');
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
                $('#add_purchase_order').modal('show');
                $('#po_id').val(id);
                var JsonResult = JSON.parse(result);
                $('#total_paid_amount').text(JsonResult['data']['paid']);
                var balance = JsonResult['data']['grand_total'] - JsonResult['data']['paid'];
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
    function purchaseOrderRow(vacant_index, data) {
        var is_tax_enable = 'Y';
        var is_discount_enable = 'Y';
        var item_name = '';
        var description = '';
        var qty = 1;
        var purchase_price = 0;
        var tax_value = 0;
        var tax_rate = '';
        var tax_type = '';
        var line_total = 0;
        if(data && data != '') {
            description = data.description != "" ? data.description : '';
            item_name = data.item_name;
            qty = data.qty && data.qty != "" ? data.qty : 1;
            purchase_price = data.unit_price != "" && data.unit_price != undefined ? data.unit_price : data.purchase_price;
            tax_type = data.purchase_price_tax;
            tax_rate = data.tax_rate;
            if(tax_type == 'with_tax' && tax_rate != 0 && purchase_price != 0 && is_tax_enable == 'Y') {
                tax_value = ((parseFloat(purchase_price) * parseFloat(tax_rate)) / (100 + parseFloat(tax_rate))).toFixed(2);
                purchase_price = parseFloat(purchase_price) - parseFloat(tax_value);
            } else if(tax_type == 'without_tax' && tax_rate != 0 && purchase_price != 0 && is_tax_enable == 'Y') {
                tax_value = (parseFloat(purchase_price) * parseFloat(tax_rate) / 100).toFixed(2);
            }
            line_total = parseFloat(purchase_price) + parseFloat(tax_value);
        }

        var html = '';
        html += '<tr class="porow_tr" id="porow_tr_'+vacant_index+'">';
        html += '<td>';
        html += '<i class="fa fa-trash"></i>';
        html += '</td>';
        html += '<td>';
        html += '<span>'+item_name+'</span>';
        html += '<div class="form-group form-material floating" data-plugin="formMaterial">';
        html += '<input type="text" style="height:22px;font-size:12px;" name="description[]" class="form-control input-sm" value="'+description+'" placeholder="Enter Description (optional)" id="po_item_description_'+vacant_index+'">';
        html += '<input type="hidden" name="item_id[]" id="po_item_id_'+vacant_index+'" value="'+data['item_id']+'">';
        html += '</div>';
        html += '</td>';
        html += '<td>';
        html += '<div class="form-group form-material floating" data-plugin="formMaterial">';
        html += '<input type="text"  name="qty[]" id="po_qty_'+vacant_index+'" style="text-align:right;" class="form-control input-sm poRowChange" value='+qty+'>';
        html += '</div>';
        html += '</td>';
        html += '<td>';
        html += '<div class="form-group form-material floating" data-plugin="formMaterial">';
        html += '<input type="text" name="unit_price[]" id="po_purchase_price_'+vacant_index+'" style="text-align:right;" class="form-control input-sm poRowChange" value='+purchase_price+'>';
        html += '</div>';
        html += '</td>';
        if(is_discount_enable == 'Y') {
            html += '<td>';
            html += '<div class="form-group form-material floating" data-plugin="formMaterial">';
            html += '<span style="display: inline-flex;">';
            html += '<span style="width: 60%;">';
            html += '<input type="text" style="text-align:right;" id="po_discount_value_'+vacant_index+'" class="form-control input-sm poRowChange" name="discount_value[]">';
            html += '</span>';
            html += '<span style="width: 2%;"></span>';
            html += '<span style="width: 38%;">';
            html += '<select style="text-align-last:center;" class="form-control no-select input-sm poRowChangeDrp" id="po_discount_type_'+vacant_index+'" name="discount_type[]">';
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
            html += '<input type="text" style="text-align:right;" id="po_tax_value_'+vacant_index+'"  class="form-control input-sm poRowChange" name="tax_value[]" value='+tax_value+'>';
            html += '</span>';
            html += '<span style="width: 2%;"></span>';
            html += '<span style="width: 38%;">';
            html += '<input type="hidden" id="po_tax_type_'+vacant_index+'" value='+tax_type+'>';
            html += '<select style="font-size:10px;text-align-last:end;" class="form-control input-sm no-select poRowChangeDrp" name="tax_rate[]" id="po_tax_rate_'+vacant_index+'">';
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
        html += '<input type="text" style="text-align:center;" id="po_line_total_'+vacant_index+'" value='+line_total+' name="total" class="form-control input-sm">';
        html += '</div>';
        html += '</td>';
        html += '</tr>';
        if($('.porow_tr').length == 0) {
            $(html).insertAfter('#purchaseTable > tbody > tr:first');
        } else {
            $(html).insertAfter('#purchaseTable tbody tr.porow_tr:last');
        }
        $('#po_tax_rate_'+vacant_index).val(tax_rate);
        purchaseOrderCalculation();
    }
    function purchaseOrderCalculation() {
        var is_tax_enable = 'Y';
        var is_discount_enable = 'Y';
        var total_discount = 0;
        var total_tax = 0;
        var total_line = 0;
        var grand_total = 0;
        var total_taxable_amount = 0;
        var row_item = [];
        $('.porow_tr').each(function(e) {
            var discount_value = 0;
            var discount_amount = 0;
            var discount_type = 'F';
            var line_total = 0;
            var taxable_amount = 0;
            var index = $(this).attr('id').split('_')[2];
            //if($('#chk_'+index).prop('checked')) {
            var qty = $('#po_qty_'+index).val() != "" ? parseFloat($('#po_qty_'+index).val()) : 0;
            var purchase_price = $('#po_purchase_price_'+index).val() != "" ? parseFloat($('#po_purchase_price_'+index).val()) : 0;
            line_total = parseFloat(qty) * parseFloat(purchase_price);
            taxable_amount = line_total;
        
            if(is_discount_enable == 'Y') {
                var discount_type = $('#po_discount_type_'+index).val() != "" ? $('#po_discount_type_'+index).val() : 'F';
                var discount_value = $('#po_discount_value_'+index).val() != "" ? $('#po_discount_value_'+index).val() : 0;
                var discount_amount = discount_value;
                if(discount_value != 0 && discount_type == 'P') {
                    discount_amount = parseFloat(line_total) * parseFloat(discount_value) / 100;
                }
                total_discount = parseFloat(total_discount) + parseFloat(discount_amount);
                line_total = parseFloat(line_total) - parseFloat(discount_amount);
                taxable_amount = parseFloat(taxable_amount) - parseFloat(discount_amount);
            }
            if(is_tax_enable == 'Y') {
                total_taxable_amount = parseFloat(total_taxable_amount) + parseFloat(taxable_amount);
                var tax_value = 0;
                var tax_rate = $('#po_tax_rate_'+index).val() != 0 ? $('#po_tax_rate_'+index).val() : 0;
                var tax_type = $('#po_tax_type_'+index).val() != 0 ? $('#po_tax_type_'+index).val() : '';
                tax_value = (parseFloat(line_total) * parseFloat(tax_rate)/ 100).toFixed(2);
                $('#po_tax_value_'+index).val(tax_value);
                total_tax = parseFloat(total_tax) + parseFloat(tax_value);
                line_total = parseFloat(line_total) + parseFloat(tax_value);
            }
            grand_total = parseFloat(grand_total) + parseFloat(line_total);
            $('#po_line_total_'+index).val(line_total.toFixed(2));

            row_item.push({
                item_id: $('#po_item_id_'+index).val(),
                description: $('#po_item_description_'+index).val(),
                qty: $('#po_qty_'+index).val(),
                unit_price: $('#po_purchase_price_'+index).val(),
                discount_type : $('#po_discount_type_'+index).val(),
                discount_value : discount_amount,
                discount_percentage : $('#po_discount_type_'+index).val() == 'P' ? $('#po_discount_value_'+index).val() : 0,
                taxable_amount : taxable_amount,
                tax_rate: $('#po_tax_rate_'+index).val(),
                tax_value: $('#po_tax_value_'+index).val(),
                total: line_total                
            });
            //}
        });
        var podetails = {
            'vendor_id' : $('select[name=vendor_id]').val(),
            'order_date' : $('input[name=order_date]').val(),
            'payment_term' : $('input[name=po_payment_term]').val(),
            'due_date' : $('input[name=due_date]').val(),
            'taxable_amount' : total_taxable_amount,
            'total_discount' : total_discount.toFixed(0),
            'total_tax' : total_tax.toFixed(0),
            'grand_total' : grand_total.toFixed(0),
        }
        $('#po_total_discount').text(total_discount.toFixed(0));
        $('#po_total_tax').text(total_tax.toFixed(0));
        $('#po_grand_total').text(grand_total.toFixed(0));
        return {'main': podetails,'rows':row_item};
    }
    function addDaysToDate(date,days) {
        if(date != "") {
            var CurrentDate = new Date(date.split("-")[2]+'-'+date.split("-")[1]+'-'+date.split("-")[0]);
            CurrentDate.setDate(CurrentDate.getDate() + parseFloat(days));
            return CurrentDate;
        }
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
    function item_details(item_id) {
        $.ajax({
            method: 'POST',
            url: 'Common/CommonFunc',
            data: {
                'item_id': item_id,
                'do': 'get_stock_details'
            },
            success: function(result) {
                var returnJSON = JSON.parse(result);
                $('#add_new_item_modal').modal('show');
                if(returnJSON['data'] && returnJSON['data']['item_details']) {
                    var item_detail = returnJSON['data']['item_details'];
                    $('input[name=item_type]').val(item_detail['item_type']);
                    item_type = item_detail['item_type'] == 'P' ? 'product' : 'service';
                    toggleProductService('add_product_form','add_service_form',item_type);
                    $('input[name=item_id]').val(item_detail['item_id']);
                    $('input[name=item_type]').val(item_detail['item_type']);
                    item_detail['item_type']
                    if(item_detail['item_type'] == 'P') {
                        $('form#add_product_form input[name=item_name]').val(item_detail['item_name']).trigger('change');
                        $('form#add_product_form input[name=item_code]').val(item_detail['item_code']).trigger('change');
                        $('form#add_product_form input[name=description]').val(item_detail['description']).trigger('change');
                        $('form#add_product_form input[name=purchase_price]').val(item_detail['purchase_price']).trigger('change');
                        $('form#add_product_form input[name=sell_price]').val(item_detail['sell_price']).trigger('change');
                        $('form#add_product_form input[name=hsn_sac_code]').val(item_detail['hsn_sac_code']).trigger('change');
                        $('form#add_product_form select[name=tax_rate]').val(item_detail['tax_rate']).trigger('change');
                        $('form#add_product_form input[name=purchase_price_tax]').val(item_detail['purchase_price_tax']);
                        $('form#add_product_form input[name=sell_price_tax]').val(item_detail['sell_price_tax']);
                        $('.purchase_price_tax').find('[data-bind="bs-drp-sel-label"]').text(item_detail['purchase_price_tax']).end();
                        $('.sell_price_tax').find('[data-bind="bs-drp-sel-label"]').text(item_detail['sell_price_tax']).end();
                        $('.opening_stock_divs').css('display','none');
                    } else {
                        $('form#add_service_form input[name=item_name]').val(item_detail['item_name']).trigger('change');
                        $('form#add_service_form input[name=item_code]').val(item_detail['item_code']).trigger('change');
                        $('form#add_service_form input[name=description]').val(item_detail['description']).trigger('change');
                        $('form#add_service_form input[name=sell_price]').val(item_detail['sell_price']).trigger('change');
                        $('form#add_service_form input[name=hsn_sac_code]').val(item_detail['hsn_sac_code']).trigger('change');
                        $('form#add_service_form select[name=tax_rate]').val(item_detail['tax_rate']).trigger('change');
                        $('form#add_product_form input[name=sell_price_tax]').val(item_detail['sell_price_tax']);
                        $('.sell_price_tax_service').find('[data-bind="bs-drp-sel-label"]').text(item_detail['sell_price_tax']).end();
                    }
                }
                if(returnJSON['data'] && returnJSON['data']['inventory'] && returnJSON['data']['inventory'].length > 0) {
                    var inventory_html = '';
                    returnJSON['data']['inventory'].forEach(element => {
                        inventory_html += '<tr>';
                        inventory_html += '<td>'+element.date+'</td>';
                        inventory_html += '<td>'+element.description+'</td>';
                        inventory_html += '<td>'+element.qty+'</td>';
                        inventory_html += '<td>'+element.closing_balance+'</td>';
                        inventory_html += '</tr>';
                    });
                    $('#inventory_table tbody').html(inventory_html);
                } else {
                    $('#inventory_table tbody').html('<tr><td colspan="4">No stock found</td></tr>');
                }

                if(returnJSON['data'] && returnJSON['data']['current_stocks']) {
                    var current_qty = returnJSON['data']['current_stocks']['total_qty'] != "" && returnJSON['data']['current_stocks']['total_qty'] != null ? returnJSON['data']['current_stocks']['total_qty'] : 0;
                    $('#count_current_qty').text(current_qty);
                    $('#count_final_qty').text(current_qty);
                    $('#item_id_stock').val(item_id);
                }
                

                // var data = JSON.parse(result);
                // $('#item_name_span').text(data['data']['item_name']);

            }
        });
    }
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
    function save_new_product() {
        var data = '';
        if($("input[name=item_type_new]:checked").val() =='P') {
            if($('#item_name_product_new_item').val() == "") {
                $('#item_name_product_new_item').focus();
                toastr.warning("Item name is required !");
                return false;
            }
            data = $('#add_new_product_form').serialize();
        } else {
            if($('#item_name_service_new_item').val() == "") {
                $('#item_name_service_new_item').focus();
                toastr.warning("Item name is required !");
                return false;
            }
            data = $('#add_new_service_form').serialize();
        }
        $.ajax({
            method: 'POST',
            url: 'Transcation/InsertOperation',
            data: {
                'data': data,
                'item_id': 0,
                'item_type': $("input[name=item_type_new]:checked").val(),
                'table_name': 'tbl_items'
            },
            success: function(result) {
                toastr.success('Item added successfully !');
                setTimeout(function() {
                    window.location.reload();
                },800);
            }
        });
     }
    function save_product() {
        var data = '';
        if($("input[name=item_type]").val() =='P') {
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
        var item_id = $('input[name=item_id]').val() != "" ? $('input[name=item_id]').val() : 0;
        $.ajax({
            method: 'POST',
            url: 'Transcation/InsertOperation',
            data: {
                'data': data,
                'item_id': item_id,
                'item_type': $("input[name=item_type]:checked").val(),
                'table_name': 'tbl_items'
            },
            success: function(result) {
                if(item_id == 0) {
                    toastr.success('Item added successfully !');
                } else {
                    toastr.success('Item updated successfully !');
                }
                
                setTimeout(function() {
                    window.location.reload();
                },800);
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
    function toggleProductService(product_form,service_form,type) {
        if(type=='product') {
            $('form#'+product_form+'').css('display','block');
            $('form#'+service_form+'').css('display','none');
        } else {
            $('form#'+product_form+'').css('display','none');
            $('form#'+service_form+'').css('display','block');
        }
    }
    function add_new_item() { debugger;
         $('#add_item_only_modal').modal('show');
        // $('input[name=item_id]').val(0);
        // $('.opening_stock_divs').css('display','block');
        // $('#item_type_row').css('display','block');
        // $('#add_new_item_modal').modal('show');
    }
    // $('#add_new_item_modal').on('hidden.bs.modal', function () {
    //     $(this).find("input,textarea,select").val('').end().find("input[type=checkbox], input[type=radio]").prop("checked", "").end();
    //     $('input[name=item_type]').val('P');
    // });
    $('#invent_tabs a').click(function(e) {
        e.preventDefault();
        $(this).tab('show');
    });

    // store the currently selected tab in the hash value
    $("ul.nav-tabs > li > a").on("shown.bs.tab", function(e) {
        var id = $(e.target).attr("href").substr(1);
        window.location.hash = id;
    });
    $(document).ready(function() {
        $('#total_stock_item').text('<?php echo $total_stock_item; ?>');
        $('#total_stock_value').text('<?php echo $total_stock_value != "" ? $total_stock_value : 0; ?>');
        var hash = window.location.hash;
        $('#invent_tabs a[href="' + hash + '"]').tab('show');

        // $('[data-toggle="tooltip"]').tooltip({'container':'body'});
        $('input[name=payment_date]').datepicker({
            format: 'dd-mm-yyyy',
            autoclose:true
        }).datepicker("setDate", new Date());
        $('input[name=date]').datepicker({
            format: 'dd-mm-yyyy',
            autoclose:true
        }).datepicker("setDate", new Date());
        $('input[name=order_date]').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy'
        });
        $('input[name=due_date]').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy'
        });
        var item_table = $('#item_table').dataTable({
            bPaginate: false,
            bInfo: false,
        });
        $("#searchbox_item").keyup(function() {
            item_table.fnFilter(this.value);
        });
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
        $("#search_item_po").autocomplete({
            source: function(request, response) {
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
                    }
                });
            },
            focus: function(event, ui) {
                if (ui.item.label == '<a class="btn btn-success btn-sm btn-block"><i class="fa fa-plus"></i> Add Item</a>') {
                    return false;
                } else {
                    $('#search_item_po').val(ui.item.label);
                }
                // Prevent the default focus behavior.
                event.preventDefault();
                // or return false;
            },
            select: function(event, ui) {
                var data = ui.item.data;
                var Index = ($('.porow_tr').length + 1);
                Index = checkIndexAvailable(Index,'porow_tr');
                purchaseOrderRow(Index,data);
                setTimeout(function() {
                    $('#search_item_po').val('');
                }, 200);
            },
            change: function(event, ui) {
                // provide must match checking if what is in the input is in the list of results. HACK!
                var source = $(this).val();
                var found = false;
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
    function getLabel(result) {
        if (result.item_code != "") {
            return result.item_code + "-" + result.item_name
        } else {
            return result.item_name;
        }
    }

    function checkIndexAvailable(index, tr_class) {
        var IndexArray = [];
        $('.' + tr_class).each(function(e) {
            var eleIdSplit = $(this).attr('id').split('_');
            IndexArray.push(parseInt(eleIdSplit[1]));
        });
        if (IndexArray.indexOf(index) > -1) {
            return checkIndexAvailable(index + 1, tr_class);
        }
        return index;
    }
</script>
