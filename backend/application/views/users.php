<?php
$GLOBALS['title_left'] = '<a href="add-user" class="btn btn-sm btn-success btn-outline btn-1e pull-right"><i class="fa fa-plus"></i> Add New</a>';
?>
<div class="form-group has-search">
    <span class="fa fa-search form-control-feedback"></span>
    <input type="text" class="form-control" placeholder="Search Users..." id="searchbox">
</div>
<div class="row">
    <div class="col-xs-12 col-lg-12 col-sm-12">
    <div class="table-responsive">
        <table id="user_table" class="table table-hover dataTable table-bordered">
            <thead>
                <tr>
                    <th>Sr.No</th>
                    <th>Name</th>
                    <th>Contact No.</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                     $srno = 1;
                     foreach($users as $k=>$v) { ?>
                    <tr>
                        <td><?php echo $srno;?></td>
                        <td><?php echo $v['first_name'].' '.$v['last_name'];?></td>
                        <td><?php echo $v['mobile_no'];?></td>
                        <td><?php echo $v['email'];?></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-xs btn-primary" title="Edit" data-toggle="tooltip" onclick="window.location='add-user?id=<?php echo base64_encode($v['user_id']);?>'"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-xs btn-danger" title="Delete" data-toggle="tooltip"><i class="fa fa-trash-o"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php $srno++; } ?>
            </tbody>
        </table>
    </div>
</div>
</div>
<script>
    $(document).ready(function() {
        var dataTable = $('#user_table').dataTable({
            bPaginate:false,
            bInfo:false,
            aaSorting: [0, 'asc'],
            aoColumnDefs: [
                {"bSortable": false, "aTargets": [0]}
            ],
            fnDrawCallback : function(oSettings) {
                if (oSettings.bSorted || oSettings.bFiltered)
                {
                    for (var i = 0, iLen = oSettings.aiDisplay.length; i < iLen; i++)
                    {
                        $('td:eq(0)', oSettings.aoData[ oSettings.aiDisplay[i] ].nTr).html(i + 1);
                    }
                }
            }
        });
        $("#searchbox").keyup(function() {
            console.log(dataTable);
            dataTable.fnFilter(this.value);
        });
    });
</script>