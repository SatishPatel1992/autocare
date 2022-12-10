<div class="row">
        <div class="col-lg-3">
            <div class="form-group">
                <label>Duration</label><br>
                <input type="text" name="duration" class="form-control input-sm" value="<?php if(isset($_REQUEST['d'])) { echo $_REQUEST['d']; } ?>">
            </div>
        </div>
        <div class="col-lg-5"></div>
        <div class="col-lg-4">
        <table style="font-size: 14px;width: 100%;background-color: whitesmoke;">
                    <tr>
                        <td style="text-align: right;">Total Receivable (A) :</td>
                        <td style="text-align: right;"><i class="fa fa-rupee"></i> <?php echo $receivable_summary['total_amt']; ?></td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">Received (B) :</td>
                        <td style="text-align: right;"><i class="fa fa-rupee"></i> <?php echo $receivable_summary['total_paid']; ?></td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">Total Receivable as on date (A-B) :</td>
                        <td style="text-align: right;"><i class="fa fa-rupee"></i> <?php echo $receivable_summary['total_amt'] - $receivable_summary['total_paid']; ?></td>
                    </tr>
                </table>
        </div>
    </div>
        <div class="nav-tabs-horizontal">
    <ul class="nav nav-tabs nav-tabs-line" role="tablist">
        <li role="presentation" class="nav-item active"><a href="#tab4" role="tab" data-toggle="tab" aria-expanded="true" class="nav-link active">Statement</a></li>
        <li role="presentation" class="nav-item"><a href="#tab5" role="tab" data-toggle="tab" aria-expanded="true" class="nav-link">Invoices</a></li>
        <li role="presentation" class="nav-item"><a href="#tab6" role="tab" data-toggle="tab" aria-expanded="true" class="nav-link">Payments</a></li>
    </ul>
    <div class="tab-content">
        <div id="tab4" class="tab-pane active">
        
        <table style="width: 100%;" class="table table-hover table-bordered">
                <thead>
                    <tr>
                        <th style="background-color: lavender;width: 15%;text-align: left;">Txn Date</th>
                        <th style="background-color: lavender;width: 40%;text-align: left;">Description</th>
                        <th style="background-color: lavender;width: 10%;text-align: right;">Debit (<i class="fa fa-rupee"></i>)</th>
                        <th style="background-color: lavender;width: 10%;text-align: right;">Credit (<i class="fa fa-rupee"></i>)</th>
                        <th style="background-color: lavender;width: 10%;text-align: right;">Balance (<i class="fa fa-rupee"></i>)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if(count($receivable_accounts) > 0) {
                    $balance = 0;
                    foreach($receivable_accounts as $k => $v) { 
                    $balance = $v['credit'] != "" ? $balance + $v['credit'] : $balance;
                    $balance = $v['debit'] != "" ? $balance - $v['debit'] : $balance; 
                    ?>
                    <tr>
                        <td><?php echo date('d-m-Y',strtotime($v['date'])); ?></td>
                        <td><?php echo $v['description']; ?></td>
                        <td style="text-align: right;"><?php echo $v['debit'] != "" ? '<i class="fa fa-rupee"></i> '.$v['debit'] : ''; ?></td>
                        <td style="text-align: right;"><?php echo $v['credit'] != "" ? '<i class="fa fa-rupee"></i> '.$v['credit'] : ''; ?></td>
                        <td style="text-align: right;"><?php echo '<i class="fa fa-rupee"></i> '.$balance; ?></td>
                    </tr>
                    <?php } } else { ?>
                        <tr>
                            <td colspan="8">No Transcation found</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div id="tab5" class="tab-pane">
        <table class="table table-bordered" id="invoice_table">
            <thead>
                <tr>
                    <th style="background-color: lavender;">Invoice No.</th>
                    <th style="background-color: lavender;">Date</th>
                    <th style="background-color: lavender;">Due Date</th>
                    <th style="background-color: lavender;text-align:right;">Invoice Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($receivable_invoices) && !empty($receivable_invoices)) {
                    foreach ($receivable_invoices as $inv) {
                ?>
                        <tr id="tr_<?php echo $inv['item_id']; ?>">
                            <td><?php echo $inv['invoice_no']; ?></td>
                            <td><?php echo date('d-m-Y', strtotime($inv['date'])); ?></td>
                            <td><?php echo date('d-m-Y', strtotime($inv['due_date'])); ?></td>
                            <td style="text-align:right;"><?php echo '<i class="fa fa-rupee"></i> '.$inv['amount']; ?></td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="5">No any invoices created yet.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        </div>
        <div id="tab6" class="tab-pane">
        <table class="table table-bordered" id="payment_table">
            <thead>
                <tr>
                    <th style="background-color: lavender;">Invoice No</th>
                    <th style="background-color: lavender;">Paid Date</th>
                    <th style="background-color: lavender;">Paid By</th>
                    <th style="background-color: lavender;">Paid Amount</th>
                    <th style="background-color: lavender;">Reference No.</th>
                    <th style="background-color: lavender;">Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($receivable_payments) && !empty($receivable_payments)) {
                    foreach ($receivable_payments as $pay) { ?>
                        <tr>
                            <td><?php echo $pay['invoice_no']; ?></td>
                            <td><?php echo date('d-m-Y', strtotime($pay['date'])); ?></td>
                            <td><?php echo $pay['name']; ?></td>
                            <td><?php echo '<i class="fa fa-rupee"></i> '.$pay['amount']; ?></td>
                            <td><?php echo $pay['reference_no']; ?></td>
                            <td><?php echo $pay['notes']; ?></td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr>
                        <td colspan="6">No any payment created yet.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

        </div>
    </div>
 </div>
<script>
    $(document).ready(function() {
        $('input[name=duration]').daterangepicker({
              locale: {
                   format: 'DD-MM-YYYY'
              },
              <?php if(!isset($_REQUEST['d']) && $_REQUEST['d'] == "") { ?>
              startDate: moment().startOf('month'),
              endDate: moment().endOf('month'),
              <?php } ?>
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
            e.preventDefault();
            var selectedDate = this.value;
            window.location.href = 'account-receivable?d='+selectedDate;
        });
    });
</script>