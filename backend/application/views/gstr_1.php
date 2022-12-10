<div class="row">
<div class="col-lg-2">
    <div class="form-group form-material floating" data-plugin="formMaterial">
        <input type="text" name="duration" class="form-control input-sm customer_list" value="<?php echo $_REQUEST['d']; ?>">
        <label class="floating-label">Duration</label>
    </div>
</div>
<div class="col-lg-4">
    <input type="button" style="margin-top:15px;" class="btn btn-sm btn-info" value="Filter" onclick="filter()">
    <input type="button" style="margin-top:15px;" class="btn btn-sm btn-success" value="Export" onclick="html_table_to_excel('xlsx')">
 </div>
</div>
    <div class="row">
    <div class="col-lg-12">
    <table style="width: 100%;" class="datatable customFilter table table-hover table-bordered" id="gstr_1_table">
                <thead>
                    <tr>
                        <th style="background-color: lavender;text-align: left;">GSTIN</th>
                        <th style="background-color: lavender;text-align: left;">Customer Name</th>
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
                    if(count($customer) > 0) {
                    foreach($customer as $k => $data) { ?>
                    <tr>
                        <td style="white-space:nowrap;vertical-align: middle;" rowspan="<?php echo count($data['taxDetails']); ?>"><?php echo $data['gst_no']; ?></td>
                        <td style="white-space:nowrap;vertical-align: middle;" rowspan="<?php echo count($data['taxDetails']); ?>"><?php echo $data['customer_name']; ?></td>
                        <td style="white-space:nowrap;vertical-align: middle;" rowspan="<?php echo count($data['taxDetails']); ?>"><?php echo $data['invoice_no']; ?></td>
                        <td style="white-space:nowrap;vertical-align: middle;" rowspan="<?php echo count($data['taxDetails']); ?>"><?php echo $data['invoice_date']; ?></td>
                        <td style="white-space:nowrap;vertical-align: middle;text-align:right;" rowspan="<?php echo count($data['taxDetails']); ?>"><?php
                          echo '<i class="fa fa-rupee"></i> '.round(array_sum(array_column($customer[$data['invoice_no']]['taxDetails'],'taxable_value')) + array_sum(array_column($customer[$data['invoice_no']]['taxDetails'],'total_tax')),2);
                         ?></td>
                    <?php foreach($data['taxDetails'] as $t) { ?>
                            <td style="white-space:nowrap;text-align:right;"><?php echo '<i class="fa fa-rupee"></i> '.round($t['taxable_value'],2); ?></td>
                            <td style="white-space:nowrap;text-align:right;"><?php echo $t['tax_percentage']; ?></td>
                            <td style="white-space:nowrap;text-align:right;"><?php echo $t['sgst'] ? '<i class="fa fa-rupee"></i> '.round($t['sgst'],2) : ''; ?></td>
                            <td style="white-space:nowrap;text-align:right;"><?php echo $t['cgst'] ? '<i class="fa fa-rupee"></i> '.round($t['cgst'],2) : ''; ?></td>
                            <td style="white-space:nowrap;text-align:right;"><?php echo $t['igst'] ? '<i class="fa fa-rupee"></i> '.round($t['igst'],2) : ''; ?></td>
                            <td style="white-space:nowrap;text-align:right;"><?php echo '<i class="fa fa-rupee"></i> '.round($t['sgst'] + $t['cgst'] + $t['igst'] , 2); ?></td>
                        </tr>
                    <?php } ?>
                    <?php if($insurance[$data['invoice_no']]) { ?>
                    <tr>
                        <td style="white-space:nowrap;vertical-align: middle;" rowspan="<?php echo count($data['taxDetails']); ?>"><?php echo $insurance[$data['invoice_no']]['gst_no']; ?></td>
                        <td style="white-space:nowrap;vertical-align: middle;" rowspan="<?php echo count($data['taxDetails']); ?>"><?php echo $insurance[$data['invoice_no']]['insurance_name']; ?></td>
                        <td style="white-space:nowrap;vertical-align: middle;" rowspan="<?php echo count($data['taxDetails']); ?>"><?php echo $data['invoice_no']; ?></td>
                        <td style="white-space:nowrap;vertical-align: middle;" rowspan="<?php echo count($data['taxDetails']); ?>"><?php echo $data['invoice_date']; ?></td>
                        <td style="white-space:nowrap;vertical-align: middle;text-align:right;" rowspan="<?php echo count($data['taxDetails']); ?>">
                        <?php echo '<i class="fa fa-rupee"></i> '.round(array_sum(array_column($insurance[$data['invoice_no']]['taxDetails'],'taxable_value')) + array_sum(array_column($insurance[$data['invoice_no']]['taxDetails'],'total_tax')),2);?></td>
                    <?php foreach($insurance[$data['invoice_no']]['taxDetails'] as $t) { ?>
                            <td style="white-space:nowrap;text-align:right;"><?php echo '<i class="fa fa-rupee"></i> '.round($t['taxable_value'],2); ?></td>
                            <td style="white-space:nowrap;text-align:right;"><?php echo $t['tax_percentage']; ?></td>
                            <td style="white-space:nowrap;text-align:right;"><?php echo $t['sgst'] ? '<i class="fa fa-rupee"></i> '.round($t['sgst'],2) : ''; ?></td>
                            <td style="white-space:nowrap;text-align:right;"><?php echo $t['cgst'] ? '<i class="fa fa-rupee"></i> '.round($t['cgst'],2) : ''; ?></td>
                            <td style="white-space:nowrap;text-align:right;"><?php echo $t['igst'] ? '<i class="fa fa-rupee"></i> '.round($t['igst'],2) : ''; ?></td>
                            <td style="white-space:nowrap;text-align:right;"><?php echo '<i class="fa fa-rupee"></i> '.round($t['sgst'] + $t['cgst'] + $t['igst'] , 2); ?></td>
                        </tr>
                    <?php } } } } else {?>
                        <tr>
                            <td colspan="10">No any Invoices founds.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
    </div>
    </div>
    <div id="xls_heading"></div>
    <input type="hidden" id="date_range" value="<?php echo $_REQUEST['d']; ?>">
    <input type="hidden" id="exportDetails" value='<?php echo json_encode($garageDetails); ?>'>
    <script>
    function html_table_to_excel(type) { debugger;
        var tableBody = document.getElementById('gstr_1_table');
        var garageDetails = JSON.parse($('#exportDetails').val());
        var header_html = '<tr><td colspan="3">'+garageDetails.name+'</td></tr>';
        header_html += '<tr><td colspan="3">GST NO:'+garageDetails.gst_no+'</td></tr>';
        header_html += '<tr><td colspan="3">PH:'+garageDetails.phone_no+'</td></tr>';
        file_name = garageDetails.name+'_gstr1.'+type;
        var file = XLSX.utils.table_to_book(tableBody, {sheet: "gstr1"});
        XLSX.write(file, { bookType: type, bookSST: true, type: 'base64' });
        XLSX.writeFile(file, file_name);
    }
    function filter() {
        window.location.href= 'gstr-1?d='+$('input[name=duration]').val();
    }
    $(document).ready(function() {
        var currentMonth = new Date().getMonth();
        var financialYearStartMonth = 3;
        var financialYearStartDate = moment().month(financialYearStartMonth).startOf('month');
        if (currentMonth < financialYearStartMonth) {
            financialYearStartDate = financialYearStartDate.subtract(1, 'year');
        }
        $('input[name=duration]').daterangepicker({
            locale: {
                format: 'DD-MM-YYYY'
            },
            ranges: {
                "All Time": [],
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'This Financial Year': [moment(financialYearStartDate), moment()]
            },
            startDate: moment(financialYearStartDate),
            endDate: moment()
        });
        <?php if(isset($_REQUEST['d']) && $_REQUEST['d'] != "") { ?>
            var dates = $('#date_range').val().split(" - ");
            $("input[name=duration]").data('daterangepicker').setStartDate(dates[0]);
            $("input[name=duration]").data('daterangepicker').setEndDate(dates[1]);
        <?php } ?>
    });
    </script>
