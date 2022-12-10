<div class="row" style="font-size:18px;">
    <?php if($_REQUEST['type'] == 'cstr') { ?>
  <div class="col-lg-6">
    <span>Customer Ledger</span>
  </div>
  <div class="col-lg-6" style="text-align:right;">
    <span>Duration: <?php echo $_REQUEST['d'];?></span><br>
    <span>Total Receivable: <span id="total_receivable_span"></span> </span>  
  </div>
  <?php } else { ?>
  <div class="col-lg-6">
    <span>Vendor Ledger</span>
  </div>
  <div class="col-lg-6" style="text-align:right;">
    <span>Duration: <?php echo $_REQUEST['d'];?></span><br>
    <span>Total Payable: <span id="total_receivable_span"></span> </span>
  </div>
  <?php } ?>
</div>
    <div class="row">
    <div class="col-lg-12">
    <div class="table-responsive">
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
                            <tr style="background-color:linen;" class="closing_balance_tr">
                                <td></td>
                                <td style="text-align:right;font-weight:bold;">Closing Balance</td>
                                <td></td>
                                <td style="font-weight:bold;" class="closing_balance">
                                <?php if($closing_balance < 0) { ?>
                                    <i class="fa fa-rupee"></i> <?php echo abs($closing_balance); ?>
                                <?php } ?>
                                </td>
                                <td style="font-weight:bold;" class="closing_balance">
                                <?php if($closing_balance > 0) { ?>
                                    <i class="fa fa-rupee"></i> <?php echo abs($closing_balance); ?>
                                <?php } ?>
                                </td>
                            </tr>
                </tbody>
            </table>
    </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#total_receivable_span').html('<i class="fa fa-rupee"></i>'+ $('tr.closing_balance_tr td.closing_balance').text())
});
</script>