<?php
//$GLOBALS['title_left'] = '<a href="add-template" class="btn btn-sm btn-success btn-outline btn-1e pull-right"><i class="fa fa-plus"></i> Add New Template</a>';
?>
<div class="form-group has-search">
    <span class="fa fa-search form-control-feedback"></span>
    <input type="text" class="form-control" placeholder="Search Template..." id="searchbox">
</div>
<div class="row">
    <div class="col-lg-12">
    <div class="table-responsive">
        <table id="template_table" class="table table-bordered" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="width: 5%;text-align: center;font-weight: bold;">Sr.No</th>
                    <th style="width: 55%;text-align: center;font-weight: bold;">Template Name</th>
                    <th style="width: 20%;text-align: center;font-weight: bold;">Active</th>
                    <th style="width: 15%;text-align: center;font-weight: bold;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $srno = 1;
                    foreach($templates as $k=>$v) { ?>
                    <tr>
                        <td class="rowSpanCenter"><?php echo $srno;?></td>
                        <td class="rowSpanCenter"><?php echo $v['name']; ?></td>
                        <td class="rowSpanCenter">
                        <span>Yes</span>
                                <input type="checkbox" id="inp_gstinno_switch" name="inputiCheckBasicCheckboxes" data-plugin="switchery" checked="" value="Y"/>
                            <span>No</span>
                        </td>
                        <td class="rowSpanCenter">
                            <div class="btn-group">
                            <button class="btn btn-xs btn-primary" title="Edit" data-toggle="tooltip" onclick="window.location='add-template?id=<?php echo base64_encode($v['template_id']); ?>'"><i class="fa fa-edit"></i></button>
<!--                            <button class="btn btn-xs btn-danger" title="Delete" data-toggle="tooltip"><i class="fa fa-trash-o"></i></button>-->
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
    $(document).ready(function () {
        var template_table = $('#template_table').dataTable({
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
            template_table.fnFilter(this.value);
        });
    });
</script>