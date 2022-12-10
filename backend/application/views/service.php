<div class="row m-b-30">
    <div class="col-lg-12 col-sm-12 col-xs-12 pull-right">
        <a href="add-service" class="btn btn-sm btn-success btn-outline btn-1e pull-right">Add New <i class="fa fa-plus"></i></a>
    </div>
</div>
<div class="row">
    <div class="table-responsive">
        <table id="invoice_table" class="table table-striped table-bordered" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Sr.No</th>
                    <th>Service Code</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>List Price A</th>
                    <th>List Price B</th>
                    <th>List Price C</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $srno = 1;
                    foreach($data as $k=>$v) { ?>
                    <tr>
                        <td><?php echo $srno;?></td>
                        <td><?php echo $v['service_code'];?></td>
                        <td><?php echo $v['description'];?></td>
                        <td><?php echo $v['category_name'];?></td>
                        <td><?php echo $v['list_price_A'];?></td>
                        <td><?php echo $v['list_price_B'];?></td>
                        <td><?php echo $v['list_price_C'];?></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-xs btn-primary" title="Edit" data-toggle="tooltip" onclick="window.location='add-service?id=<?php echo base64_encode($v['service_id']);?>'"><i class="fa fa-edit"></i></button>
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
	$('#invoice_table').DataTable({
             "bLengthChange": false,
             "bSort":false
	});
    });
</script>