<div class="row">
        <div class="col-lg-2">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                    <input type="text" name="duration" class="form-control input-sm customer_list" value="<?php echo $_REQUEST['d']; ?>">
                    <label class="floating-label">Duration</label>
            </div>
        </div>
        <div class="col-lg-2">
            <a id="dlink"  style="display:none;"></a>
            <input type="button" style="margin-top:15px;" class="btn btn-sm btn-info" value="Filter" onclick="filter()">
            <input type="button" style="margin-top:15px;" class="btn btn-sm btn-success" value="Export" onclick="exporttoexcel()">
        </div>
    </div>
    <div class="row">
    <div class="col-lg-8">
    <?php if($_REQUEST['type'] == 'lwstsumm' || $_REQUEST['type'] == 'stsumm') { ?>
    <span style="font-size:15px;">Total Stock Value: </span> <span style="font-size:15px;"> <i class="fa fa-rupee"></i> <span id="total_stock_amount">0</span></span>
    <?php } ?>
    </div>
    <div class="col-lg-4">
    <div class="form-group has-search">
    <span class="fa fa-search form-control-feedback"></span>
    <input type="text" class="form-control" placeholder="Search Item..." id="searchbox">
</div>
</div>
</div>
    <div class="row">
    <div class="col-lg-12">
    <?php if($_REQUEST['type'] == 'lwstsumm') { ?>
    <table style="width: 100%;" class="datatable customFilter table table-hover table-bordered" id="stock_report_table">
                <thead>
                    <tr>
                        <th style="background-color: lavender;text-align: left;">Item Name</th>
                        <th style="background-color: lavender;text-align: left;">Item Code.</th>
                        <th style="background-color: lavender;text-align: left;">Stock Qty</th>
                        <th style="background-color: lavender;text-align: left;">Stock Value</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if(count($data) > 0) {
                    $total_stock_value = 0;    
                    foreach($data as $k => $v) { ?>
                    <tr>
                        <td><?php echo $v['item_name']; ?></td>
                        <td><?php echo $v['item_code']; ?></td>
                        <td><?php echo $v['total_stock']; ?></td>
                        <td><i class="fa fa-rupee"></i> <?php echo $v['total_stock'] * $v['sell_price']; ?></td>
                    </tr>
                    <?php $total_stock_value += $v['total_stock'] * $v['sell_price'];
                    } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="4">No any items founds.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else if($_REQUEST['type'] == 'stsumm') { ?>
            <table style="width: 100%;" class="datatable customFilter table table-hover table-bordered" id="stock_report_table">
                <thead>
                    <tr>
                        <th style="background-color: lavender;text-align: left;">Item Name</th>
                        <th style="background-color: lavender;text-align: left;">Item Code.</th>
                        <th style="background-color: lavender;text-align: left;">Purchase Price</th>
                        <th style="background-color: lavender;text-align: left;">Selling Price</th>
                        <th style="background-color: lavender;text-align: left;">Stock Qty</th>
                        <th style="background-color: lavender;text-align: left;">Stock Value</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if(count($data) > 0) {
                    $total_stock_value = 0;    
                    foreach($data as $k => $v) { ?>
                    <tr>
                        <td><?php echo $v['item_name']; ?></td>
                        <td><?php echo $v['item_code']; ?></td>
                        <td><?php echo $v['purchase_price'] != "0" ? '<i class="fa fa-rupee"></i> '.$v['purchase_price'] : ''; ?></td>
                        <td><?php echo $v['sell_price'] != "0" ? '<i class="fa fa-rupee"></i> '.$v['sell_price'] : ''; ?></td>
                        <td><?php echo $v['total_stock']; ?></td>
                        <td><i class="fa fa-rupee"></i> <?php echo $v['total_stock'] * $v['sell_price']; ?></td>
                    </tr>
                    <?php $total_stock_value += $v['total_stock'] * $v['sell_price'];
                    } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="4">No any items founds.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else if($_REQUEST['type'] == 'itmsalsumm') { ?>
                <table style="width: 100%;" class="datatable customFilter table table-hover table-bordered" id="stock_report_table">
                <thead>
                    <tr>
                        <th style="background-color: lavender;text-align: left;">Item Name</th>
                        <th style="background-color: lavender;text-align: left;">Qty</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if(count($data) > 0) {
                    foreach($data as $k => $v) { ?>
                    <tr>
                        <td><?php echo $v['item_name']; ?></td>
                        <td><?php echo $v['total_stock']; ?></td>
                    </tr>
                    <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="4">No any items founds.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else if($_REQUEST['type'] == 'itmpursumm') { ?>
                <table style="width: 100%;" class="datatable customFilter table table-hover table-bordered" id="stock_report_table">
                <thead>
                    <tr>
                        <th style="background-color: lavender;text-align: left;">Item Name</th>
                        <th style="background-color: lavender;text-align: left;">Qty</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    if(count($data) > 0) {
                    foreach($data as $k => $v) { ?>
                    <tr>
                        <td><?php echo $v['item_name']; ?></td>
                        <td><?php echo $v['total_stock']; ?></td>
                    </tr>
                    <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="4">No any items founds.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <?php } else { ?>
                <table style="width: 100%;" class="datatable customFilter table table-hover table-bordered" id="stock_report_table">
                <thead>
                    <tr>
                        <th style="background-color: lavender;text-align: left;">Item Name</th>
                        <th style="background-color: lavender;text-align: left;">Item Code.</th>
                        <th style="background-color: lavender;text-align: left;">Stock Qty</th>
                        <th style="background-color: lavender;text-align: left;">Stock Value</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="4">No any stock found.</td>
                    </tr>
                </tbody>
            </table>
            <?php } ?>
            <input type="hidden" id="total_stock_value" value="<?php echo $total_stock_value; ?>">
            <input type="hidden" id="date_range" value="<?php echo $_REQUEST['d']; ?>">
    </div>
    </div>
    <script>
    function filter() {
        if($('#report_type').val() != '*') {
            window.location.href= 'stock-reports?d='+$('input[name=duration]').val()+'&type='+$('#report_type').val();
        } else {
            toastr.warning("Please select report type !");
        }
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
        $('#total_stock_amount').text($('#total_stock_value').val());
        <?php if(isset($_REQUEST['d']) && $_REQUEST['d'] != "") { ?>
            var dates = $('#date_range').val().split(" - ");
            $("input[name=duration]").data('daterangepicker').setStartDate(dates[0]);
            $("input[name=duration]").data('daterangepicker').setEndDate(dates[1]);
        <?php } ?>
    });
            // $("#searchbox_orders").keyup(function() {
        //     orders_table.fnFilter(this.value);
        // });
    function exporttoexcel()  {
        var generated_html_thead = $('#stock_report_table thead').html();
        var generated_html_tbody = $('#stock_report_table tbody').html();
        var table_html = generated_html_thead +' '+ generated_html_tbody;
        var dt = new Date();
        var day = dt.getDate();
        var month = dt.getMonth() + 1;
        var year = dt.getFullYear();
        var hour = dt.getHours();
        var mins = dt.getMinutes();
        var postfix = day + "." + month + "." + year + "_" + hour + "." + mins;
        var filename = 'Stock Reports_'+ postfix + '.xls';
        tableToExcel(table_html, 'Stock Report' ,filename);
    }
    </script>