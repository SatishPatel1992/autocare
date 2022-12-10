<div class="row">
        <div class="col-lg-2">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <select id="customer_vendor" class="form-control input-sm outstanding_of_select" onchange="toggleCustomerVendor(this.value)">
                    <option <?php if(!isset($_REQUEST['type']) || $_REQUEST['type'] == 'cstr') { echo "selected"; } ?> value="cstr">Customer</option>
                    <option <?php if($_REQUEST['type'] == 'vndr') { echo "selected"; } ?> value="vndr">Vendor</option>
                </select>
                <label class="floating-label">outstanding of </label>
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
                        <th style="background-color: lavender;text-align: left;">Name</th>
                        <th style="background-color: lavender;text-align: left;">Contact No.</th>
                        <th style="background-color: lavender;text-align: right;"><?php if(!isset($_REQUEST['type']) || $_REQUEST['type'] == 'cstr') { echo 'Total Sales'; } else { echo 'Total Purchase'; } ?></th>
                        <th style="background-color: lavender;text-align: right;"><?php if(!isset($_REQUEST['type']) || $_REQUEST['type'] == 'cstr') { echo 'Total Received'; } else { echo 'Total Paid'; } ?></th>
                        <th style="background-color: lavender;text-align: right;">Balance</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    $total_1 = 0;
                    $total_2 = 0;
                    $total_3 = 0;   
                    if(count($data) > 0) {
                    foreach($data as $k => $v) { ?>
                    <tr>
                        <td><?php if(!isset($_REQUEST['type']) || $_REQUEST['type'] == 'cstr') { echo $v['customer_name']; } else { echo $v['company_name']; } ?></td>
                        <td><?php echo $v['mobile_no']; ?></td>
                        <td style="text-align:right;"><i class="fa fa-rupee"></i> <?php echo $v['total_invoiced']; ?></td>
                        <td style="text-align:right;"><i class="fa fa-rupee"></i> <?php echo $v['total_received'] != "" ? $v['total_received'] :0; ?></td>
                        <td style="text-align:right;"><i class="fa fa-rupee"></i> <?php echo $v['total_invoiced'] - $v['total_received']; ?></td>
                    </tr>
                    <?php 
                        $total_1 += $v['total_invoiced'];
                        $total_2 += $v['total_received'];
                        $total_3 += $v['total_invoiced'] - $v['total_received'];
                        } } else { ?>
                        <tr>
                            <td colspan="5">No date found.</td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td style="text-align:right;font-weight:bold;"></td>
                        <td style="text-align:right;font-weight:bold;">Total</td>
                        <td style="font-weight:bold;text-align:right"><i class="fa fa-rupee"></i> <?php echo $total_1; ?></td>
                        <td style="font-weight:bold;text-align:right"><i class="fa fa-rupee"></i> <?php echo $total_2; ?></td>
                        <td style="font-weight:bold;text-align:right"><i class="fa fa-rupee"></i> <?php echo $total_3; ?></td>
                    </tr>
                </tfoot>
            </table>
            <input type="hidden" id="date_range" value="<?php echo $_REQUEST['d']; ?>">
    </div>
    </div>
    <script>
    function filter(reset='') {
        if(reset =="") {
            window.location.href= 'outstanding?type='+$('#customer_vendor').val()+'&cstr_id='+$('#customer_id').val()+'&vndr_id='+$('#vendor_id').val()+'&d='+$('input[name=duration]').val();
        } else {
            window.location.href= 'outstanding';
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