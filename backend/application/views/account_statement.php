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
                        <td style="text-align: right;">Total Credit (A) :</td>
                        <td style="text-align: right;"><i class="fa fa-rupee"></i> <?php echo $receivable_summary['total_credit']; ?></td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">Total Debit (B) :</td>
                        <td style="text-align: right;"><i class="fa fa-rupee"></i> <?php echo $receivable_summary['total_debit']; ?></td>
                    </tr>
                    <tr>
                        <td style="text-align: right;">Total (A-B) :</td>
                        <td style="text-align: right;"><i class="fa fa-rupee"></i> <?php echo $diff = abs($receivable_summary['total_diff'] - $receivable_summary['total_paid']); echo $receivable_summary['total_diff'] - $receivable_summary['total_paid'] < 0 ? '(Dr)' : '(Cr)'; ?></td>
                    </tr>
                </table>
        </div>
    </div>
    <div class="row">
    <div class="col-lg-12">
            
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
                    if(count($account_statement) > 0) {
                    $balance = 0;
                    foreach($account_statement as $k => $v) { 
                    $balance = $v['credit'] != "" ? $balance + $v['credit'] : $balance;
                    $balance = $v['debit'] != "" ? $balance - $v['debit'] : $balance;
                    ?>
                    <tr>
                        <td><?php echo date('d-m-Y',strtotime($v['date'])); ?></td>
                        <td><?php echo $v['description']; ?></td>
                        <td style="text-align: right;"><?php echo $v['debit'] != "" ? $v['debit'] : ''; ?></td>
                        <td style="text-align: right;"><?php echo $v['credit'] != "" ? $v['credit'] : ''; ?></td>
                        <td style="text-align: right;"><?php echo $balance; ?></td>
                    </tr>
                    <?php } } else { ?>
                        <tr>
                            <td colspan="8">No Transcation found</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
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
            window.location.href = 'account-statement?d='+selectedDate;
    });
    });

    </script>