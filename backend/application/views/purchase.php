<?php
$GLOBALS['title_left'] = '<a href="javascript:void(0);" onclick=window.location="add-purchase" class="btn btn-sm btn-info btn-outline btn-1e pull-right"><i class="fa fa-plus"></i> Create Bill</a>';
?>
<div class="row">
                <div class="col-lg-12">
                        <div class="row">
                        <div class="col-lg-4">
                            <div class="form-group has-search">
                                <span class="fa fa-search form-control-feedback"></span>
                                <input type="text" class="form-control" placeholder="Search Order..." id="searchbox_orders">
                            </div>
                        </div>
                        <div class="col-lg-8" style="text-align:right;font-size:15px;">
                          <span> Total Purchase : <i class="fa fa-rupee"></i> <span style="font-weight:bold;" id="total_purchase">0</span> |  Paid : <i class="fa fa-rupee"></i> <span style="font-weight:bold;" id="total_paid">0</span> | Balance : <i class="fa fa-rupee"></i> <span style="font-weight:bold;" id="total_balance">0</span></span>
                        </div>
                        </div>
                        <table id="orders_table" class="table table-bordered" style="border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th style="font-weight: bold;background-color: lavender;">PO NO.</th>
                                    <th style="text-align:center;font-weight: bold;background-color: lavender;">Status</th>
                                    <th style="font-weight: bold;background-color: lavender;">Date</th>
                                    <th style="font-weight: bold;background-color: lavender;">Due Date</th>
                                    <th style="font-weight: bold;background-color: lavender;">Vendor</th>
                                    <th style="font-weight: bold;background-color: lavender;">Due IN</th>
                                    <th style="font-weight: bold;background-color: lavender;">Purchase Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $total_purchase = 0;
                                $total_paid = 0;
                                if(!empty($orders)) {
                                foreach ($orders as $k => $v) { ?>
                                    <tr style="cursor:pointer;" onclick="edit_purchase('<?php echo base64_encode($v['po_id']); ?>')">
                                        <td><?php echo $v['po_no']; ?></td>
                                        <td style="text-align:center;"><?php 
                                        if($v['status'] == 'paid') {
                                            echo '<span class="badge badge-success">Paid</span>';
                                        } else if($v['status'] == 'unpaid') {
                                            echo '<span class="badge badge-danger">Unpaid</span>';
                                        } else {
                                            echo '<span class="badge badge-info">Partial Paid</span>';
                                        }
                                        ?></td>
                                        <td><?php echo date('d-m-Y',strtotime($v['invoice_date'])); ?></td>
                                        <td><?php echo date('d-m-Y',strtotime($v['due_date'])); ?></td>
                                        <td><?php echo $v['company_name']; ?></td>
                                        <td><?php 
                                        if($v['status'] != 'paid') {
                                            $datediff = strtotime($v['due_date']) - strtotime(date('Y-m-d'));
                                            echo round($datediff / (60 * 60 * 24));
                                        } else {
                                            echo " - ";
                                        }
                                        ?></td>
                                        <td>
                                        <?php 
                                            echo '<i class="fa fa-rupee"></i> '.$v['grand_total'];
                                            $total_purchase += $v['grand_total'];
                                        ?>
                                        </td>
                                    </tr>
                                <?php } } else { ?>
                                    <tr>
                                        <td colspan="8">No any vendor bill added yet. </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
            </div>
        </div>
        <style>
        .badge {
            width:80%;
        }
        </style>
<script>
$(document).ready(function() {
        var dataTable = $('#orders_table').dataTable({
            bPaginate:false,
            bInfo:false,
            aaSorting: [0, 'desc'],
        });
        $("#searchbox_orders").keyup(function() {
            console.log(dataTable);
            dataTable.fnFilter(this.value);
        });
        $('#total_purchase').text('<?php echo $total_purchase; ?>');
        $('#total_paid').text('<?php echo $total_paid; ?>');
        $('#total_balance').text('<?php echo $total_purchase - $total_paid; ?>');
});
function edit_purchase(po_id) {
    window.location = 'add-purchase?id='+po_id;
}
</script>