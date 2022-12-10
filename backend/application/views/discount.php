<div class="row m-b-30">
    <div class="col-lg-2 col-sm-4 col-xs-12 pull-right">
        <a href="add-discount" class="btn btn-sm btn-success btn-outline btn-1e pull-right">Add New <i class="fa fa-plus"></i></a>
    </div>
</div>
<div class="row">
    <div class="table-responsive">
        <table id="discount_table" class="table table-striped table-bordered" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Sr.No</th>
                    <th>Discount Name</th>
					<th>Promo Code</th>
                    <th>Type</th>
                    <th>Value</th>
                    <th>Start Date</th>
					<th>End Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $srno = 1;
                    foreach($discount as $k=>$v) { ?>
                    <tr>
                        <td><?php echo $srno;?></td>
                        <td><?php echo $v['name'];?></td>
                        <td><?php echo $v['promo_code'];?></td>
						<td><?php echo $v['type'];?></td>
						<td><?php echo $v['value'];?></td>
						<td><?php echo $v['start_date'];?></td>
						<td><?php echo $v['end_date'];?></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-xs btn-primary" title="Edit" data-toggle="tooltip" onclick="window.location='add-discount?id=<?php echo base64_encode($v['discount_id']);?>'"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-xs btn-danger" title="Delete" data-toggle="tooltip"><i class="fa fa-trash-o"></i></button>
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
	$('#discount_table').DataTable({
         "bLengthChange": false,
	});
    });
</script>