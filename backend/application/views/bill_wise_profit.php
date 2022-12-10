    <div class="row">
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
        <div class="col-lg-2">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                    <input type="text" name="duration" class="form-control input-sm customer_list" value="<?php echo $_REQUEST['d']; ?>">
                    <label class="floating-label">Duration</label>
            </div>
        </div>
        <div class="col-lg-2">
            <input type="button" style="margin-top:15px;" class="btn btn-sm btn-info" value="Filter" onclick="filter()">
            <input type="button" style="margin-top:15px;" class="btn btn-sm btn-warning" value="Reset" onclick="filter('reset')">
        </div>
    </div>
    <div class="row">
    <div class="col-lg-8"></div>
    <div class="col-lg-4">
        <div class="form-group has-search">
        <span class="fa fa-search form-control-feedback"></span>
        <input type="text" class="form-control" placeholder="Search Invoice..." id="searchbox">
    </div>
</div>
</div>
    <div class="row">
    <div class="col-lg-12">
    <table style="width: 100%;" class="datatable customFilter table table-hover table-bordered" id="vendor_invoice_table">
                <thead>
                    <tr>
                        <th style="background-color: lavender;text-align: left;">Customer Name</th>
                        <th style="background-color: lavender;text-align: left;">Invoice No.</th>
                        <th style="background-color: lavender;text-align: left;">Date</th>
                        <th style="background-color: lavender;text-align: left;">Sales Amount</th>
                        <th style="background-color: lavender;text-align: left;">Purchase Amount</th>
                        <th style="background-color: lavender;text-align: left;">Profit</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    if(count($data) > 0) {
                    foreach($data as $k => $v) { ?>
                    <tr>
                        <td><?php echo $v['customer_name']; ?></td>
                        <td><?php echo $v['invoice_no']; ?></td>
                        <td><?php echo date('d-m-Y',strtotime($v['date'])); ?></td>
                        <td><i class="fa fa-rupee"></i> <?php echo $v['amount']; ?></td>
                        <td><?php echo $v['total_purchase_price'] != "" ? '<i class="fa fa-rupee"></i> '.$v['total_purchase_price'] : ' - '; ?></td>
                        <td><i class="fa fa-rupee"></i> <?php echo $v['amount'] - $v['total_purchase_price']; ?></td>
                    </tr>
                    <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="6">No data founds.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <input type="hidden" id="date_range" value="<?php echo $_REQUEST['d']; ?>">
    </div>
    </div>
    <script>
    function filter(reset='') {
        if(reset =="") {
            window.location.href= 'bill_wise_profit?cstr_id='+$('#customer_id').val()+'&d='+$('input[name=duration]').val();
        } else {
            window.location.href= 'bill_wise_profit';
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
    </script>