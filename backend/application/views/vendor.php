<?php
$GLOBALS['title_left'] = '<a href="add-vendor" class="btn btn-sm btn-info btn-outline btn-1e pull-right"><i class="fa fa-plus"></i> Add New Vendor</a>';
?>
<div class="form-group has-search">
    <span class="fa fa-search form-control-feedback"></span>
    <input type="text" class="form-control" placeholder="Search Vendor..." id="searchbox">
</div>
<div class="row">
    <div class="col-xs-12 col-lg-12 col-sm-12">
      <div class="table-responsive">
        <table id="vendor_table" class="table table-striped table-bordered" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="width: 5%;font-weight: bold;background-color: lavender;">Sr.No</th>
                    <th style="width: 20%;font-weight: bold;background-color: lavender;">Company Name</th>
                    <th style="width: 30%;font-weight: bold;background-color: lavender;">Address</th>
                    <th style="width: 15%;font-weight: bold;background-color: lavender;">Email</th>
                    <th style="width: 10%;font-weight: bold;background-color: lavender;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $srno = 1;
                    foreach($vendors as $k=>$v) { ?>
                    <tr>
                        <td><?php echo $srno;?></td>
                        <td><?php echo $v['company_name'];?></td>
                        <td><?php echo $v['billing_address'];?></td>
                        <td><?php echo $v['email'];?></td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-xs btn-primary" title="Edit" data-toggle="tooltip" onclick="window.location='add-vendor?id=<?php echo base64_encode($v['vendor_id']);?>'"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-xs btn-danger" title="Delete" data-toggle="tooltip" onclick="deleteVendor('<?php echo $v['vendor_id']; ?>')" ><i class="fa fa-trash-o"></i></button>
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
    function deleteVendor(id) {
        if(confirm("Are you sure want to delete ?")) {
        $.ajax({
                method: 'POST',
                url: 'common/commonFunc',
                data: {'id': id,'do':'delete_vendor'},
                success: function(result) {
                    toastr.success("Vendor deleted successfully !");
                    setTimeout(function() { 
                        window.location.reload();
                    },700);
                }
            });
        }
    }
    $(document).ready(function() {
        var dataTable = $('#vendor_table').dataTable({
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
