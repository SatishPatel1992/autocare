<?php
$GLOBALS['title_left'] = '<a href="add-customer" class="btn btn-sm btn-info btn-outline btn-1e pull-right"><i class="fa fa-plus"></i> Add New Customer</a>';
?>
<div class="form-group has-search">
    <span class="fa fa-search form-control-feedback"></span>
    <input type="text" class="form-control" placeholder="Search Customer..." id="searchbox">
</div>
<div class="row">
    <div class="col-xs-12 col-lg-12 col-sm-12">
    <div class="table-responsive">
        <table id="customer_table" class="table table-hover dataTable table-bordered">
            <thead>
                <tr>
                    <th style="width: 5%;font-weight: bold;background-color: lavender;">Sr.No</th>
                    <th style="width: 20%;font-weight: bold;background-color: lavender;">Name</th>
                    <th style="width: 30%;font-weight: bold;background-color: lavender;">Mobile No</th>
                    <th style="width: 20%;font-weight: bold;background-color: lavender;">Balance</th>
                    <th style="width: 10%;font-weight: bold;background-color: lavender;text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                     $srno = 1;
                     foreach($data as $k=>$v) { ?>
                    <tr>
                        <td><?php echo $srno;?></td>
                        <td><?php echo $v['name'];?></td>
                        <td><?php echo $v['mobile_no'];?></td>
                        <td><?php echo '<i class="fa fa-rupee"></i> '.($v['invoiced'] - $v['payment']);?></td>
                        <td style="text-align: center;">
                            <div class="btn-group">
                                <button class="btn btn-xs btn-primary" title="Edit" data-toggle="tooltip" onclick="window.location='add-customer?id=<?php echo base64_encode($v['customer_id']);?>'"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-xs btn-danger" title="Delete" data-toggle="tooltip" onclick="deleteCustomer('<?php echo $v['customer_id']; ?>')"><i class="fa fa-trash-o"></i></button>
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
    function deleteCustomer(id) {
        if(confirm("Are you sure want to delete ?")) {
        $.ajax({
                method: 'POST',
                url: 'common/commonFunc',
                data: {'id': id,'do':'delete_customer'},
                success: function(result) {
                    toastr.success("customer deleted successfully !");
                    setTimeout(function() { 
                        window.location.reload();
                    },700);
                }
            });
        }
    }
    $(document).ready(function() {
        var dataTable = $('#customer_table').dataTable({
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
