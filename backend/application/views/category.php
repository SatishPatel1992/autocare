<div class="row m-b-30">
    <div class="col-lg-2 col-sm-4 col-xs-12 pull-right">
        <a href="add-category" class="btn btn-sm btn-success btn-outline btn-1e pull-right">Add New <i class="fa fa-plus"></i></a>
    </div>
</div>
<div class="row">
    <div class="table-responsive">
        <table id="invoice_table" class="table table-striped table-bordered" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Sr.No</th>
                    <th>Category Code</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $srno = 1;
                    foreach($data as $k=>$v) { ?>
                    <tr>
                        <td><?php echo $srno;?></td>
                        <td><?php echo $v['category_code'];?></td>
                        <td><?php echo $v['description'];?></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-xs btn-primary" title="Edit" data-toggle="tooltip" onclick="window.location='add-category?id=<?php echo base64_encode($v['category_id']);?>'"><i class="fa fa-edit"></i></button>
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
	});
    });
</script>