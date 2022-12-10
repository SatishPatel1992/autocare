<div class="row">
        <div class="col-lg-3">
            <div class="form-group">
                <label>Filter by Vendor</label><br>
                <select id="vendor_id" class="form-control input-sm">
                <option value="">Select All</option>
                <?php foreach($vendor_master as $k => $v) { ?>
                    <option <?php if($_REQUEST['sear_id'] && $_REQUEST['sear_id'] == $v['vendor_id']) { echo "selected"; } ?> value="<?php echo $v['vendor_id']; ?>"><?php echo $v['company_name']; ?></option>
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
    <table style="width: 100%;" class="datatable customFilter table table-hover table-bordered" id="vendor_invoice_table">
                <thead>
                    <tr>
                        <th style="background-color: lavender;text-align: left;">Vendor Name</th>
                        <th style="background-color: lavender;text-align: left;">Invoice No.</th>
                        <th style="background-color: lavender;text-align: left;">Date</th>
                        <th style="background-color: lavender;text-align: right;">Invoice Amount</th>
                        <th style="background-color: lavender;text-align: right;">Taxable Amount</th>
                        <th style="background-color: lavender;text-align: right;">Tax %</th>
                        <th style="background-color: lavender;text-align: right;">SGST</th>
                        <th style="background-color: lavender;text-align: right;">CGST</th>
                        <th style="background-color: lavender;text-align: right;">IGST</th>
                        <th style="background-color: lavender;text-align: right;">Total Tax</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    if(count($vendors) > 0) {
                    foreach($vendors as $k => $data) { ?>
                    <tr>
                        <td style="vertical-align: middle;" rowspan="<?php echo count($data['taxDetails']) + 1; ?>"><?php echo $data['company_name']; ?></td>
                        <td style="vertical-align: middle;" rowspan="<?php echo count($data['taxDetails']) + 1; ?>"><?php echo $data['invoice_no']; ?></td>
                        <td style="vertical-align: middle;" rowspan="<?php echo count($data['taxDetails']) + 1; ?>"><?php echo date('d-m-Y', strtotime($data['invoice_date'])); ?></td>
                        <td style="vertical-align: middle;text-align:right;" rowspan="<?php echo count($data['taxDetails']) + 1; ?>"><?php
                          echo '<i class="fa fa-rupee"></i> '.round(array_sum(array_column($vendors[$data['po_no']]['taxDetails'],'taxable_value')) + array_sum(array_column($vendors[$data['po_no']]['taxDetails'],'total_tax')),2);
                         ?></td>
                    </tr>
                    <?php foreach($data['taxDetails'] as $t) { ?>
                        <tr>
                            <td style="text-align:right;"><?php echo '<i class="fa fa-rupee"></i> '.round($t['taxable_value'],2); ?></td>
                            <td style="text-align:right;"><?php echo $t['tax_percentage']; ?></td>
                            <td style="text-align:right;"><?php echo $t['sgst'] ? '<i class="fa fa-rupee"></i> '.round($t['sgst'],2) : ''; ?></td>
                            <td style="text-align:right;"><?php echo $t['cgst'] ? '<i class="fa fa-rupee"></i> '.round($t['cgst'],2) : ''; ?></td>
                            <td style="text-align:right;"><?php echo $t['igst'] ? '<i class="fa fa-rupee"></i> '.round($t['igst'],2) : ''; ?></td>
                            <td style="text-align:right;"><?php echo '<i class="fa fa-rupee"></i> '.round($t['sgst'] + $t['cgst'] + $t['igst'] , 2); ?></td>
                        </tr>
                    <?php } } } else { ?>
                        <tr>
                            <td colspan="10">No any Invoices founds.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
    </div>
    </div>
    <script>
    function filter() {
        window.location.href= 'gstr-2?sear_id='+$('#vendor_id').val();
    }
    </script>