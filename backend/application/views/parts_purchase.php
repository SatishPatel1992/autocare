<div class="row">
        <div class="col-lg-3">
            <div class="form-group">
                <label>Filter by Parts</label><br>
                <select id="part_id" class="singleSelect1 form-control input-sm">
                <option value="">Select All</option>
                <?php foreach($parts as $k => $v) { ?>
                    <option <?php if($_REQUEST['p_id'] && $_REQUEST['p_id'] == $k) { echo "selected"; } ?> value="<?php echo $k; ?>"><?php echo $v; ?></option>
                <?php } ?>
                </select>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <label>Filter by Vendor</label><br>
                <select id="vendor_id" class="singleSelect2 form-control input-sm">
                <option value="">Select All</option>
                <?php foreach($vendors as $k => $v) { ?>
                    <option <?php if($_REQUEST['v_id'] && $_REQUEST['v_id'] == $v['vendor_id']) { echo "selected"; } ?> value="<?php echo $v['vendor_id']; ?>"><?php echo $v['company_name']; ?></option>
                <?php } ?>
                </select>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <label>&nbsp;</label><br>
                <button type="button" onclick="filter()" class="btn btn-primary btn-sm">Filter</button>
            </div>
        </div>
    </div>
    <div class="row">
    <div class="col-lg-12">
    <table style="width: 100%;" class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th style="background-color: lavender;text-align: left;">Sr No.</th>
                        <th style="background-color: lavender;text-align: left;">PO No.</th>
                        <th style="background-color: lavender;text-align: left;">Date</th>
                        <th style="background-color: lavender;text-align: left;">Vendor</th>
                        <th style="background-color: lavender;text-align: right;">Status</th>
                        <th style="background-color: lavender;text-align: left;">Part Name</th>
                        <th style="background-color: lavender;text-align: right;">Order Qty</th>
                        <th style="background-color: lavender;text-align: right;">Unit Price</th>
                        <th style="background-color: lavender;text-align: right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(count($purchase_orders) > 0) {
                    $srno = 1;
                    $total_qty=0;
                    $total_order_amount=0;
                    foreach($purchase_orders as $k => $v) {
                    if($temp != $v['po_no'] || $k == 0) {
                    if($k !=0) { ?>
                    <tr>
                        <td style="font-weight:bold;text-align:right;" colspan="6">Total</td>
                        <td style="font-weight:bold;text-align:right;"><?php echo $total_qty; ?></td>
                        <td></td>
                        <td style="font-weight:bold;text-align:right;"><i class="fa fa-rupee"></i> <?php echo $total_order_amount; ?></td>
                    </tr>
                    <?php $total_qty = 0;$total_order_amount=0;} ?>
                    <tr>
                        <td><?php echo $srno; ?></td>
                        <td><?php echo $v['po_no']; ?></td>
                        <td><?php echo date("d-m-Y",strtotime($v['order_date'])); ?></td>
                        <td><?php echo $v['company_name']; ?></td>
                        <td style="text-align:right;"><?php echo $v['status']; ?></td>
                        <td><?php echo $v['description']; ?></td>
                        <td style="text-align:right;"><?php echo $v['ordered_qty']; ?></td>
                        <td style="text-align:right;"><i class="fa fa-rupee"></i> <?php echo $v['per_unit_price']; ?></td>
                        <td style="text-align:right;"><i class="fa fa-rupee"></i> <?php echo $v['part_total_amount']; ?></td>
                    </tr>
                    <?php $srno++;} else { ?>
                    <tr>
                        <td colspan="5"></td>
                        <td><?php echo $v['part_name']; ?></td>
                        <td style="text-align:right;"><?php echo $v['ordered_qty']; ?></td>
                        <td style="text-align:right;"><i class="fa fa-rupee"></i> <?php echo $v['per_unit_price']; ?></td>
                        <td style="text-align:right;"><i class="fa fa-rupee"></i> <?php echo $v['part_total_amount']; ?></td>
                    </tr>
                    <?php } ?>
                    <?php $temp = $v['po_no'];
                          $total_qty += $v['ordered_qty'];
                          $total_order_amount += $v['part_total_amount'];
                    } ?>
                    <tr>
                        <td style="font-weight:bold;text-align:right;" colspan="6">Total</td>
                        <td style="font-weight:bold;text-align:right;"><?php echo $total_qty; ?></td>
                        <td></td>
                        <td style="font-weight:bold;text-align:right;"><i class="fa fa-rupee"></i> <?php echo $total_order_amount; ?></td>
                    </tr>
                    <?php } else { ?>
                        <tr>
                            <td colspan="9">No any purchase order founds.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
    </div>
    </div>
    <script>
    function filter() {
        window.location.href= 'parts-purchase?p_id='+$('#part_id').val()+'&v_id='+$('#vendor_id').val();
    }
    </script>