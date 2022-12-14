<div class="row">
<div class="col-lg-3">
            <div class="form-group">
                <label>Filter by Customer</label><br>
                <select id="customer_id" class="singleSelect1 form-control input-sm">
                    <option value="">Select All</option>
                    <?php foreach($customer as $k => $v) { ?>
                        <option <?php if($_REQUEST['cust_id'] && $_REQUEST['cust_id'] == $v['customer_id']) { echo "selected"; } ?> value="<?php echo $v['customer_id'].'_'.$v['vehicle_id']; ?>"><?php echo $v['customer_name'].' ('.$v['reg_no'].')'; ?></option>
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
    </div>
    </div>
    <div class="row">
    <div class="col-lg-8"></div>
    <div class="col-lg-4">
    <div class="form-group has-search">
    <span class="fa fa-search form-control-feedback"></span>
    <input type="text" class="form-control" placeholder="Search Jobcard..." id="searchbox">
</div>
</div>
</div>
    <div class="row">
    <div class="col-lg-12">
    <table style="width: 100%;" class="datatable customFilter table table-hover table-bordered">
                <thead>
                    <tr>
                        <th style="background-color: lavender;text-align: left;">Sr No.</th>
                        <th style="background-color: lavender;text-align: left;">Jobcard No.</th>
                        <th style="background-color: lavender;text-align: left;">Date</th>
                        <th style="background-color: lavender;text-align: left;">Customer Name</th>
                        <th style="background-color: lavender;text-align: left;">Vehicle No.</th>
                        <th style="background-color: lavender;text-align: left;">Odometer</th>
                        <th style="background-color: lavender;text-align: right;">Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(count($jobcards) > 0) {
                    $srno = 1;
                    foreach($jobcards as $k => $v) { ?>
                    <tr>
                        <td><?php echo $srno; ?></td>
                        <td><?php echo $v['jobcard_no']; ?></td>
                        <td><?php echo date("d-m-Y",strtotime($v['date'])); ?></td>
                        <td><?php echo $v['customer_name']; ?></td>
                        <td><?php echo $v['reg_no']; ?></td>
                        <td><?php echo $v['odometer']; ?></td>
                        <td style="text-align:right;"><i class="fa fa-rupee"></i> <?php echo $v['grand_total']; ?></td>
                    </tr>
                    <?php $srno++; } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="7">No any jobcard founds.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
    </div>
    </div>
    <script>
    function filter() {
        window.location.href= 'invoice-reports?cust_id='+$('#customer_id').val();
    }
    </script>