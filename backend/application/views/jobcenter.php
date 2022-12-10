<div class="row">
    <div class="table-responsive">
        <table id="invoice_table" class="table table-striped table-bordered" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Sr.No</th>
                    <th>Date</th>
                    <th>Booking No.</th>
                    <th>Customer Name</th>
                    <th>Model</th>
                    <th>Reg. No.</th>
                    <th>Contact No.</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $srno = 1;
                    foreach($data as $k=>$v) { ?>
                    <tr>
                        <td><?php echo $srno;?></td>
                        <td><?php echo date('d-m-Y',  strtotime($v['date_in']));?></td>
                        <td><?php echo $v['booking_no']; ?></td>
                        <td><?php echo $v['name'];?></td>
                        <td><?php echo $v['model_name'];?></td>
                        <td><?php echo $v['reg_no'];?></td>
                        <td>
                        <?php 
                            if ($v['mobile'] != "") {
                                echo $v['mobile'];
                            }
                        ?>
                        </td>
                         <td><?php echo $v['total'];?></td>
                        <td><?php echo $v['status'];?></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-xs btn-danger" title="Delete" data-toggle="tooltip"><i class="fa fa-eye"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php $srno++; } ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready(function () {
	$('#invoice_table').DataTable({
             "bLengthChange": false
	});
    });
</script>
