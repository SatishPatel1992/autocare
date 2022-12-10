<div class="row">
<div class="col-lg-2">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <select id="customer_vendor" class="form-control input-sm outstanding_of_select" onchange="toggleCustomerVendor(this.value)">
                    <option <?php if(!isset($_REQUEST['type']) || $_REQUEST['type'] == 'cstr') { echo "selected"; } ?> value="cstr">Customer</option>
                    <option <?php if($_REQUEST['type'] == 'vndr') { echo "selected"; } ?> value="vndr">Vendor</option>
                </select>
                <label class="floating-label">ledger of</label>
            </div>
        </div>
        <div class="col-lg-3" id="customer_select">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <select id="customer_id" class="form-control input-sm">
                <option value="*">Select All</option>
                <?php foreach($customers as $k => $v) { ?>
                    <option <?php if($v['customer_id'] == $_REQUEST['cstr_id']) { echo "selected"; } ?> value="<?php echo $v['customer_id']; ?>"><?php echo $v['name']; echo $v['mobile_no'] != "" ? ' - '.$v['mobile_no'] : ''; ?></option>
                <?php } ?>
                </select>
                <label class="floating-label">Filter by customer</label><br>
            </div>
        </div>
        <div class="col-lg-3" id="vendor_select" style="display:none">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <select id="vendor_id" class="form-control input-sm">
                <option value="*">Select All</option>
                <?php foreach($vendors as $k => $v) { ?>
                    <option <?php if($_REQUEST['vndr_id'] && $_REQUEST['vndr_id'] == $v['vendor_id']) { echo "selected"; } ?> value="<?php echo $v['vendor_id']; ?>"><?php echo $v['company_name']; ?></option>
                <?php } ?>
                </select>
                <label class="floating-label">Filter by vendor</label><br>
            </div>
        </div>
        <div class="col-lg-2">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                    <input type="text" name="duration" class="form-control input-sm customer_list" value="<?php echo $_REQUEST['d']; ?>">
                    <label class="floating-label">Duration</label>
            </div>
        </div>
        <div class="col-lg-4">
            <input type="button" style="margin-top:15px;" class="btn btn-sm btn-info" value="Filter" onclick="filter()">
            <input type="button" style="margin-top:15px;" class="btn btn-sm btn-warning" value="Reset" onclick="filter('reset')">
            <input type="button" style="margin-top:15px;" class="btn btn-sm btn-success" value="Export" onclick="exporttoexcel()">
            <input type="button" style="margin-top:15px;" class="btn btn-sm btn-success" value="Whatsapp" onclick="sendToWhatsApp()">
        </div>
</div>
    <div class="row">
    <div class="col-lg-12">
    <a id="dlink"  style="display:none;"></a>
    <table style="width: 100%;" class="table table-hover table-bordered" id="vendor_statement_table">
                <thead>
                    <tr>
                        <th style="background-color: lavender;text-align: left;">Date</th>
                        <th style="background-color: lavender;text-align: left;">Transaction Type</th>
                        <th style="background-color: lavender;text-align: left;">Transaction No.</th>
                        <th style="background-color: lavender;text-align: left;">Credit</th>
                        <th style="background-color: lavender;text-align: left;">Debit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                         $total_closing_credit = 0;
                         $total_closing_debit = 0;
                         if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'cstr') {
                            $opening_balance = $opening_debit - $opening_credit + $fix_opening_balance;
                         } else if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'vndr') {
                            $opening_balance = $opening_debit - $opening_credit - $fix_opening_balance;
                         }
                         ?>
                       <tr style="background-color:linen;">
                            <td></td>
                            <td style="text-align:right;font-weight:bold;">Opening Balance</td>
                            <td></td>
                            <td style="font-weight:bold;">
                               <?php  if($opening_balance < 0) { ?>
                                    <i class="fa fa-rupee"></i> <?php echo abs($opening_balance); ?>
                                <?php } ?>
                            </td>
                            <td style="font-weight:bold;">
                                <?php  if($opening_balance >
                                 0) { ?>
                                    <i class="fa fa-rupee"></i> <?php echo abs($opening_balance); ?>
                                <?php } ?>
                            </td>
                      </tr>
                    <?php  foreach($transactions as $k => $v) { 
                            $transcation_type = $v['transaction_type']; ?>
                            <tr>
                            <td><?php echo date('d-m-Y',strtotime($v['trans_date'])); ?></td>
                            <td><?php echo str_replace('_', ' ', $transcation_type); ?></td>
                            <td><?php echo $v['transaction_id']; ?></td>
                            <?php if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'cstr') {  ?>
                                <td>
                                <?php
                                if($v['transaction_type'] == 'customer_payment') {
                                    echo '<i class="fa fa-rupee"></i> '.$v['amount'];
                                    $total_closing_credit += $v['amount'];
                                }
                                ?>
                                </td>
                                <td>
                                <?php
                                if($v['transaction_type'] == 'customer_invoice') {
                                    echo '<i class="fa fa-rupee"></i> '.$v['amount'];
                                    $total_closing_debit += $v['amount'];
                                }
                                ?>
                                </td>
                            <?php } else if(isset($_REQUEST['type']) && $_REQUEST['type'] == 'vndr') { ?>
                                <td>
                                <?php
                                if($v['transaction_type'] == 'bill') {
                                    echo '<i class="fa fa-rupee"></i> '.$v['amount'];
                                    $total_closing_credit += $v['amount'];
                                }
                                ?>
                                </td>
                                <td>
                                <?php
                                if($v['transaction_type'] == 'bill_payment') {
                                    echo '<i class="fa fa-rupee"></i> '.$v['amount'];
                                    $total_closing_debit += $v['amount'];
                                }
                                ?>
                                </td>
                            <?php }  ?>
                            </tr>
                            <?php } 
                             $closing_balance = $total_closing_debit - $total_closing_credit + $opening_balance;
                            ?>
                            <tr style="background-color:linen;">
                                <td></td>
                                <td style="text-align:right;font-weight:bold;">Closing Balance</td>
                                <td></td>
                                <td style="font-weight:bold;">
                                <?php if($closing_balance < 0) { ?>
                                    <i class="fa fa-rupee"></i> <?php echo abs($closing_balance); ?>
                                <?php } ?>
                                </td>
                                <td style="font-weight:bold;">
                                <?php if($closing_balance > 0) { ?>
                                    <i class="fa fa-rupee"></i> <?php echo abs($closing_balance); ?>
                                <?php } ?>
                                </td>
                            </tr>
                </tbody>
            </table>
            <input type="hidden" id="date_range" value="<?php echo $_REQUEST['d']; ?>">
    </div>
    </div>
    <script>
    function sendToWhatsApp() {
        $.ajax({
                    method: 'POST',
                    url: 'common/commonFunc',
                    data: {
                        'do': 'send_ledger_to_whatsapp',
                        'param': window.location.search
                    },
                    success: function(result) {
                        var returnResponse = JSON.parse(result);
                        window.open(
                            'https://web.whatsapp.com/send?text='+returnResponse['data'],
                            '_blank'
                        );
                    }
        });
    }
    function filter(reset='') {
        if(reset =="") {
            window.location.href= 'ledger?type='+$('#customer_vendor').val()+'&cstr_id='+$('#customer_id').val()+'&vndr_id='+$('#vendor_id').val()+'&d='+$('input[name=duration]').val();
        } else {
            window.location.href= 'ledger';
        }
    }
    function toggleCustomerVendor(value) {
        if(value == 'cstr') {
            $('#customer_select').css('display','block');
            $('#vendor_select').css('display','none');
        } else {
            $('#customer_select').css('display','none');
            $('#vendor_select').css('display','block');
        }
    }
    function exporttoexcel()  {
        var generated_html_thead = $('#vendor_statement_table thead').html();
        var generated_html_tbody = $('#vendor_statement_table tbody').html();
        var table_html = generated_html_thead +' '+ generated_html_tbody;
        var dt = new Date();
        var day = dt.getDate();
        var month = dt.getMonth() + 1;
        var year = dt.getFullYear();
        var hour = dt.getHours();
        var mins = dt.getMinutes();
        var postfix = day + "." + month + "." + year + "_" + hour + "." + mins;
        var filename = 'Ledger_'+ postfix + '.xlsx';
        tableToExcel(table_html, 'Customer Leadger' ,filename);
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
        <?php if(isset($_REQUEST['type']) && $_REQUEST['type'] == "vndr") { ?>
            toggleCustomerVendor('vndr');
        <?php } ?>
        <?php if(isset($_REQUEST['type']) && $_REQUEST['type'] == "cstr") { ?>
            toggleCustomerVendor('cstr');
        <?php } ?>
    });
    $(document).on('change','.vendor_list',function() {
        var vendor_id = $('#vendor_id').val();
        var duration = $('input[name=duration]').val();
        if(vendor_id != "*") {
                $.ajax({
                    method: 'POST',
                    url: 'common/commonFunc',
                    data: {
                        'do': 'get_vendor_statement',
                        'vendor_id': vendor_id,
                        'duration': duration
                    },
                    success: function(result) {
                        var statement = JSON.parse(result);

                        if(statement && statement['data'] && statement['data']['transactions']) {
                            var opening_balance = parseFloat(statement['data']['opening_debit']) - parseFloat(statement['data']['opening_credit']) - parseFloat(statement['data']['fix_opening_balance']);
                            var rows = '<tr style="background-color:linen;">';
                                rows += '<td></td>';
                                rows += '<td style="text-align:right;font-weight:bold;">Opening Balance</td>';
                                rows += '<td></td>';
                                rows += '<td style="font-weight:bold;">';
                                if(opening_balance < 0) {
                                    rows += '<i class="fa fa-rupee"></i> '+Math.abs(opening_balance);
                                }
                                rows += '</td>';
                                rows += '<td style="font-weight:bold;">';
                                if(opening_balance > 0) {
                                    rows += '<i class="fa fa-rupee"></i> '+Math.abs(opening_balance);
                                }
                                rows += '</td>';
                                rows += '</tr>';
                            var total_closing_credit = 0;
                            var total_closing_debit = 0;
                            statement['data']['transactions'].forEach((element) => {
                                var transcation_type = element['transaction_type'].replace(/_/g, ' ').replace(/(?: |\b)(\w)/g, function(key) { return key.toUpperCase()});
                                rows += '<tr>';
                                rows += '<td>'+element['trans_date']+'</td>';
                                rows += '<td>'+transcation_type+'</td>';
                                rows += '<td>'+element['transaction_id']+'</td>';
                                rows += '<td>';
                                if(element['transaction_type'] == 'bill') {
                                    rows += '<i class="fa fa-rupee"></i> '+element['amount'];
                                    total_closing_credit += parseFloat(element['amount']);
                                }
                                rows += '</td>';
                                rows += '<td>';
                                if(element['transaction_type'] == 'bill_payment') {
                                    rows += '<i class="fa fa-rupee"></i> '+element['amount'];
                                    total_closing_debit += parseFloat(element['amount']);
                                }
                                rows += '</td>';
                                rows += '</tr>';
                            });
                                var closing_balance = parseFloat(total_closing_debit) - parseFloat(total_closing_credit) + parseFloat(opening_balance);
                                rows += '<tr style="background-color:linen;">';
                                rows += '<td></td>';
                                rows += '<td style="text-align:right;font-weight:bold;">Closing Balance</td>';
                                rows += '<td></td>';
                                rows += '<td style="font-weight:bold;">';
                                if(closing_balance < 0) {
                                    rows += '<i class="fa fa-rupee"></i> '+Math.abs(closing_balance);
                                }
                                rows += '</td>';
                                rows += '<td style="font-weight:bold;">';
                                if(closing_balance > 0) {
                                    rows += '<i class="fa fa-rupee"></i> '+Math.abs(closing_balance);
                                }
                                rows += '</td>';
                                rows += '</tr>';
                            $('#vendor_statement_table tbody').html(rows);
                        }
                    }
                });
        } else {

        }
    });
    </script>
