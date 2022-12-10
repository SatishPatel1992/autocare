<div class="row m-b-30">
    <div class="col-lg-2 col-sm-4 col-xs-12 pull-right">
        <a href="add-part" class="btn btn-sm btn-success btn-outline btn-1e pull-right">Add New <i class="fa fa-plus"></i></a>
    </div>
</div>
<div class="row">
    <div class="table-responsive">
        <table id="customer_table12" class="table table-striped table-bordered table-responsive display">
            <thead>
                <tr>
                    <th>Sr.No</th>
                    <th>Name</th>
                    <th>Contact No.</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                     $srno = 1;
                     foreach($data as $k=>$v) { ?>
                    <tr>
                        <td><?php echo $srno;?></td>
                        <td><?php echo $v['first_name'].' '.$v['last_name'];?></td>
                        <td><?php echo $v['mobile'];?></td>
                        <td><?php echo $v['email'];?></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-xs btn-primary" title="Edit" data-toggle="tooltip" onclick="window.location='add-customer?id=<?php echo base64_encode($v['customer_id']);?>'"><i class="fa fa-edit"></i></button>
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
    $(document).ready(function() {
    $('#customer_table12 thead tr:eq(1) th').each( function (i,v) {
        if(i != 0 && i != 4) {
            var title = $('#customer_table12 thead tr:eq(0) th').eq($(this).index()).text();
            $(this).html('<input type="text" class="form-control input-sm" placeholder="Search '+title+'" />');
        }
    }); 

    var table = $('#customer_table12').DataTable({
        orderCellsTop: true
    });

    table.columns().every(function (index) {
        $('#customer_table12 thead tr:eq(1) th:eq(' + index + ') input').on('keyup change', function () {
            table.column($(this).parent().index() + ':visible').search(this.value).draw();
        });
    }); 
});
</script>