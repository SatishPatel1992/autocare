<div class="panel panel-primary panel-line" style="border:1px solid lavender;">
    <div class="panel-heading">
        <h3 class="panel-title" style="padding: 5px 10px;border-bottom: 1px solid lavender;">Account Summary</h3>
        <span class="panel-actions panel-actions-keep">
        <div class="input-group">
                    <span class="input-group-addon" style="height:20px;">
                      <i class="icon md-calendar" aria-hidden="true"></i>
                    </span>
        <input type="text" name="duration" style="height:20px;" class="form-control input-sm">
        </div>
        </span>
    </div>
    <div class="panel-body">
    <div class="row">
    <div class="col-md-4">
      <div class="card-counter primary">
        <i class="fa fa-rupee"></i>
        <span class="count-numbers total_recieve"><?php echo $summary['total_recieve']; ?></span>
        <span class="count-name">Total Sale</span>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card-counter info">
        <i class="fa fa-rupee"></i>
        <span class="count-numbers total_paid"><?php echo $summary['total_paid']; ?></span>
        <span class="count-name">Total Purchase</span>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card-counter success">
        <i class="fa fa-rupee"></i>
        <span class="count-numbers total_profit"><?php echo $summary['total_profit']; ?></span>
        <span class="count-name">Total Profit</span>
      </div>
    </div>
  </div>
    </div>
</div>
<div class="row">
<div class="col-lg-6 col-md-6">
<div class="panel panel-primary panel-line" style="border:1px solid lavender;">
    <div class="panel-heading">
        <h3 class="panel-title" style="padding: 5px 10px;border-bottom: 1px solid lavender;">Account Receivable <span id="total_receivable" style="float:right;"></span></h3>
        
    </div>
    <div class="panel-body" style="min-height:250px;max-height: 250px;overflow: auto;">
                <table class="table">
                        <thead>
                            <tr>
                                <td style="font-weight:bold;">Customer</td>
                                <td style="font-weight:bold;">Invoice No.</td>
                                <td style="font-weight:bold;">Amount</td>
                                <td style="font-weight:bold;">Balance</td>
                                <td style="font-weight:bold;">View</td>
                            </tr>
                        </thead>
                        <tbody> 
                        <?php
                            $total_receivable = 0;
                            if(!empty($sales)) {
                            foreach($sales as $k => $v) {
                            $total_receivable += $v['due_amount'];
                            ?>
                            <tr>
                                <td><?php echo strlen($v['customer_name']) > 25 ? substr($v['customer_name'],0,25).'...' : $v['customer_name']; ?></td>
                                <td><?php echo $v['invoice_no']; ?></td>
                                <td><?php echo '<i class="fa fa-rupee"></i> '.$v['invoice_amount']; ?></td>
                                <td><?php echo '<i class="fa fa-rupee"></i> '.$v['due_amount']; ?></td>
                                <td><a class="btn btn-xs btn-info" href="job-view?job_id=<?php echo base64_encode($v['item_id']); ?>"><i class="fa fa-external-link"></i></a></td>
                            </tr>
                        <?php } } else {  ?>
                            <tr>
                                <td class="text-center" colspan="5">Nothing to receive.</td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
            </div>
            </div>
        </div>
<div class="col-lg-6 col-md-6">
<div class="panel panel-primary panel-line" style="border:1px solid lavender;">
    <div class="panel-heading">
        <h3 class="panel-title" style="padding: 5px 10px;border-bottom: 1px solid lavender;">Account Payable <span id="total_payable" style="float:right;"></span></h3>
    </div>
    <div class="panel-body" style="min-height:250px;max-height: 250px;overflow: auto;">
                <table class="table">
                        <thead>
                            <tr>
                                <td style="font-weight:bold;">Vendor</td>
                                <td style="font-weight:bold;">Invoice No.</td>
                                <td style="font-weight:bold;">Amount</td>
                                <td style="font-weight:bold;">Balance</td>
                                <td style="font-weight:bold;">View</td>
                            </tr>
                        </thead>
                        <tbody> 
                        <?php
                            $total_payable = 0;
                            if(!empty($purchases)) {
                            foreach($purchases as $k => $v) {
                            $total_payable += $v['due_amount'];
                            ?>
                            <tr>
                                <td><?php echo $v['company_name']; ?></td>
                                <td><?php echo $v['po_no']; ?></td>
                                <td><?php echo '<i class="fa fa-rupee"></i> '.$v['po_amount']; ?></td>
                                <td><?php echo '<i class="fa fa-rupee"></i> '.$v['due_amount']; ?></td>
                                <td><a class="btn btn-xs btn-info" href="add-purchase?id=<?php echo base64_encode($v['po_id']); ?>"><i class="fa fa-external-link"></i></a></td>
                            </tr>
                        <?php } } else {  ?>
                            <tr>
                                <td class="text-center" colspan="5">Nothing to pay.</td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
    </div>
</div>
</div>
</div>
<div class="row">
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
<div class="panel panel-primary panel-line" style="border:1px solid lavender;">
    <div class="panel-heading">
        <h3 class="panel-title" style="padding: 5px 10px;border-bottom: 1px solid lavender;">Recent Activity</h3>
    </div>
    <div class="panel-body" style="min-height:312px;max-height: 312px;overflow: auto;">
    <ul class="list-group list-group-dividered list-group-full">
        <?php if(!empty($notifications)) { 
            foreach($notifications as $n) { ?>
             <li class="list-group-item"><span><?php echo $n['description']; ?></span> <span style="color:#bdbdbd;float:right;"><?php echo date('d-m-Y H:i',strtotime($n['date'])); ?></span></li>
        <?php } } else { ?>
            <li class="list-group-item">No any activity found.</li>
        <?php } ?>
    </ul>
    </div>
</div>
</div>
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
<div class="panel panel-primary panel-line" style="border:1px solid lavender;">
    <div class="panel-heading">
        <h3 class="panel-title" style="padding: 5px 10px;border-bottom: 1px solid lavender;">Notification</h3>
    </div>
    <div class="panel-body" style="min-height:312px;max-height: 312px;overflow: auto;">
    <ul class="list-group list-group-dividered list-group-full">
        <li class="list-group-item">No records found.</li>
    </ul>
    </div>
</div>
</div>
</div>
<script>
$(document).ready(function() {
    $('input[name=duration]').daterangepicker({
              locale: {
                   format: 'DD-MM-YYYY'
              },
              startDate: moment().startOf('month'),
              endDate: moment().endOf('month'),
              ranges: {
                   'Today': [moment(), moment()],
                   'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                   'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                   'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                   'This Month': [moment().startOf('month'), moment().endOf('month')],
                   'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
              },
        });
        $('input[name=duration]').on('change', function(e) {
            var selectedDate = this.value;
            console.log(selectedDate);
            if(selectedDate != "") {
                var dates = selectedDate.split(" - ");
                $.ajax({
                    url:'Common/CommonFunc',
                    method: "POST",
                    data: {'st_date':dates[0],'ed_date':dates[1],'do': 'get_account_summary'},
                    success: function(data) {
                        var acc_summary = JSON.parse(data);
                        $('.total_recieve').text(acc_summary.data['total_recieve']);
                        $('.total_paid').text(acc_summary.data['total_paid']);
                        $('.total_profit').text(acc_summary.data['total_profit']);
                        $('.total_payable').text(acc_summary.data['total_payable']);
                        $('.total_recievable').text(acc_summary.data['total_recievable']);
                        $('.total_upcoming').text(acc_summary.data['total_upcoming']);
                    }
                });
            }
            // window.location.href = 'accounts?d='+selectedDate;
        });
        $('#total_receivable').html('<?php echo '<i class="fa fa-rupee"></i> '.$total_receivable; ?>');
        $('#total_payable').html('<?php echo '<i class="fa fa-rupee"></i> '.$total_payable; ?>');
});
</script>
