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
        </div>
    </div>
</div>