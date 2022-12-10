<?php 
$GLOBALS['title_left'] = '<a href="add-package" class="btn btn-sm btn-success btn-outline btn-1e pull-right"><i class="fa fa-plus"></i> Add New</a>';
?>
<div class="form-group has-search">
    <span class="fa fa-search form-control-feedback"></span>
    <input type="text" class="form-control" placeholder="Search Packages..." id="searchbox">
</div>
<div class="row">
    <div class="col-xs-12 col-lg-12 col-sm-12">
<div class="table-responsive">
    <table id="package_table" class="table table-hover dataTable table-bordered" style="border-collapse: collapse;">
    <thead>
        <tr>
            <th>Sr No.</th>
            <th>Package Name</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($packages as $k => $v) { ?>
        <tr>
            <td><?php echo $k + 1; ?></td>
            <td><?php echo $v['package_name']; ?></td>
            <td>
            <div class="btn-group">
                <button class="btn btn-xs btn-primary" title="Edit" data-toggle="tooltip" onclick="window.location='add-package?id=<?php echo base64_encode($v['package_id']);?>'"><i class="fa fa-edit"></i></button>
                <button class="btn btn-xs btn-danger" title="Delete" data-toggle="tooltip"><i class="fa fa-trash-o"></i></button>
            </div>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
</div>
</div>
</div>
<script>
    $(function(){ 
    var dataTable = $('#package_table').DataTable({
        bPaginate:false,
        bInfo:false,
        aaSorting: [0, 'asc'],
        aoColumnDefs: [
                {"bSortable": false, "aTargets": [0]}
        ],
        fnDrawCallback : function(oSettings) {
            if (oSettings.bSorted || oSettings.bFiltered) {
                for (var i = 0, iLen = oSettings.aiDisplay.length; i < iLen; i++)
                {
                    $('td:eq(0)', oSettings.aoData[ oSettings.aiDisplay[i] ].nTr).html(i + 1);
                }
            }
       }
    });
    $("#searchbox").keyup(function() {
        dataTable.fnFilter(this.value);
    });
    });
</script>
