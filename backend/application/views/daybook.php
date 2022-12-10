<div class="row">
        <div class="col-lg-2">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                    <input type="text" name="duration" class="form-control input-sm">
                    <label class="floating-label">Duration</label>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group">
                <label>&nbsp;</label>
                <button type="button" style="margin-top:15px;" onclick="filter()" class="btn btn-primary btn-sm">Filter</button>
                <input type="button" style="margin-top:15px;" class="btn btn-sm btn-success" value="Export" onclick="exporttoexcel()">
            </div>
        </div>
    </div>
    <div class="row">
    <div class="col-lg-8"><span style="font-size:18px;">Net Amount : <i class="fa fa-rupee"></i><span style="font-weight:bold;" id="net_amt">0 </span></span></div>
    <div class="col-lg-4">
    <div class="form-group has-search">
    <span class="fa fa-search form-control-feedback"></span>
    <input type="text" class="form-control" placeholder="Search..." id="searchbox">
</div>
</div>
</div>
    <div class="row">
    <div class="col-lg-12">
    <a id="dlink"  style="display:none;"></a>
    <table style="width: 100%;" class="table table-hover table-bordered" id="daybook">
                <thead>
                    <tr>
                        <th style="background-color: lavender;text-align: left;">Date</th>
                        <th style="background-color: lavender;text-align: left;">Name</th>
                        <th style="background-color: lavender;text-align: left;">Transaction</th>
                        <th style="background-color: lavender;text-align: left;">Description</th>
                        <th style="background-color: lavender;text-align: left;">Credit</th>
                        <th style="background-color: lavender;text-align: left;">Debit</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    if(count($daybook) > 0) {
                    $netAmount = 0;
                    foreach($daybook as $k => $v) { ?>
                    <tr>
                        <td><?php echo date('d-m-Y',strtotime($v['date'])); ?></td>
                        <td><?php echo $v['customer_name'] != "" ? $v['customer_name'] : $v['company_name']; ?></td>
                        <td><?php echo str_replace('_', ' ', ucwords($v['transaction_type'], '_')); ?></td>
                        <td><?php echo $v['description']; ?></td>
                        <td><?php
                         if($v['transaction_type'] == 'other_income' || $v['transaction_type'] == 'customer_payment') {
                            echo '<i class="fa fa-rupee"></i>&nbsp;'.$v['amount'];
                            $netAmount += $v['amount'];
                         }
                         ?></td>
                        <td><?php
                         if($v['transaction_type'] == 'bill_payment' || $v['transaction_type'] == 'other_expense') {
                            echo '<i class="fa fa-rupee"></i>&nbsp;'.$v['amount'];
                            $netAmount -= $v['amount'];
                         }
                         ?></td>
                    </tr>
                    <?php  } } else { ?>
                        <tr>
                            <td colspan="6">No any Transaction founds.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <input type="hidden" id="net_amount" value="<?php echo $netAmount;?>">
            <input type="hidden" id="date_range" value="<?php echo $_REQUEST['d']; ?>">
    </div>
    </div>
    <script>
    function filter() {
        window.location.href= 'daybook?d='+$('input[name=duration]').val();
    }
    function exporttoexcel()  {
        var generated_html_thead = $('#daybook thead').html();
        var generated_html_tbody = $('#daybook tbody').html();
        var table_html = generated_html_thead +' '+ generated_html_tbody;
        var dt = new Date();
        var day = dt.getDate();
        var month = dt.getMonth() + 1;
        var year = dt.getFullYear();
        var hour = dt.getHours();
        var mins = dt.getMinutes();
        var postfix = day + "." + month + "." + year + "_" + hour + "." + mins;
        var filename = 'Daybook_'+ postfix + '.xls';
        tableToExcel(table_html, 'Daybook' ,filename);
    }
    $(document).ready(function() {
        $('#net_amt').text($('#net_amount').val());
        var table = $('#daybook').dataTable({
            "bInfo" : false,
            "bLengthChange": false,
            "bFilter": true,
            "bSort": false,
            "bPaginate": false,
            "bSearchable":true,
            "bFilter": true,
            columnDefs : [{ "visible": false, "targets": 0}],
            drawCallback : function ( settings ) {
					var api = this.api();
					var rows = api.rows( {page:'current'} ).nodes();
					var last=null;

                    api.column(0,{page:'current'} ).data().each( function (date, i) {
                        if(last !== date) {
                            $(rows).eq(i).before(
								'<tr class="group" style="background-color:lightgray;"><td style="font-weight:600;text-align: left;" colspan="6">'+date+'</td></tr>'
							);
							last = date;
						}
					});
			} 
        });
        $("#searchbox").keyup(function() {
            table.fnFilter(this.value);
        });
        
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
