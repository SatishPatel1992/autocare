<div class="row">
        <div class="col-lg-3">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <select id="customer_id" class="form-control input-sm customer_list">
                <option value="*">Select</option>
                <?php foreach($customers as $k => $v) { ?>
                    <option <?php if(isset($_REQUEST['cust_id']) && $v['customer_id'] == $_REQUEST['cust_id']) { echo "selected"; } ?> value="<?php echo $v['customer_id']; ?>"><?php echo $v['name']; echo $v['mobile_no'] != "" ? ' - '.$v['mobile_no'] : ''; ?></option>
                <?php } ?>
                </select>
                <label class="floating-label">Filter by customer</label>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                    <input type="text" name="duration" class="form-control input-sm customer_list" value="<?php echo $_REQUEST['d']; ?>">
                    <label class="floating-label">Duration</label>
            </div>
        </div>
        <div class="col-lg-1">
            <input type="button" style="margin-top:15px;" class="btn btn-sm btn-info" value="Filter" onclick="filter()">
        </div>
    </div>
    <div class="row">
    <div class="col-lg-8"><span style="font-size:18px;">Total Sales: </span> <i class="fa fa-rupee"></i> <span style="font-size:18px;" id="total_sale_span"><?php echo 0; ?></span></div>
    <div class="col-lg-4">
    <div class="form-group has-search">
    <span class="fa fa-search form-control-feedback"></span>
    <input type="text" class="form-control" placeholder="Search Invoice..." id="searchbox">
</div>
</div>
</div>
    <div class="row">
    <div class="col-lg-12">
    <table style="width: 100%;" class="datatable customFilter table table-hover table-bordered">
                <thead>
                    <tr>
                        <th style="width:20%;background-color: lavender;text-align: left;">Date</th>
                        <th style="width:20%;background-color: lavender;text-align: left;">Invoice No.</th>
                        <th style="width:40%;background-color: lavender;text-align: left;">Customer Name</th>
                        <th style="width:20%;background-color: lavender;text-align: left;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    if(count($sales) > 0) {
                    $total_sale = 0;
                    foreach($sales as $k => $v) { ?>
                    <tr>
                        <td><?php echo date("d-m-Y",strtotime($v['date'])); ?></td>
                        <td><?php echo $v['invoice_no']; ?></td>
                        <td><?php echo $v['customer_name']; ?></td>
                        <td><i class="fa fa-rupee"></i> <?php echo $v['amount']; ?></td>
                    </tr>
                    <?php $total_sale += $v['amount']; } ?>
                    <?php } else { ?>
                        <tr class="odd">
                            <td colspan="4">No any sales found.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
    </div>
    </div>
    <input type="hidden" id="total_sale" value="<?php echo $total_sale; ?>">
    <input type="hidden" id="date_range" value="<?php echo $_REQUEST['d']; ?>">
    <script>
    function filter() {
        var duration = $('input[name=duration]').val();
        var customer_id = $('#customer_id').val();
        window.location.href = 'sales-summary?d='+duration+'&cust_id='+customer_id
    }
    $(document).ready(function() {
        $('#total_sale_span').text($('#total_sale').val());
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