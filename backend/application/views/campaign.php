<?php
$GLOBALS['title_left'] = '<a href="add-campaign" class="btn btn-sm btn-success btn-outline btn-1e pull-right"><i class="fa fa-plus"></i> Add Campaign</a>';
?>      
<div class="form-group has-search">
    <span class="fa fa-search form-control-feedback"></span>
    <input type="text" class="form-control" placeholder="Search Campaign..." id="searchbox_campaign">
</div>
<div class="table-responsive">
    <table class="table table-bordered" id="campaign">
        <thead>
            <tr>
                <th style="background-color: lavender;">Sr.No</th>
                <th style="background-color: lavender;">Name</th>
                <th style="background-color: lavender;">Address</th>
                <th style="background-color: lavender;">Last Reminder On</th>
                <th style="background-color: lavender;">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($due_reminder as $k => $v) { ?>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<script>
    $(document).ready(function() {
        var datatable_manual = $('#campaign').dataTable({
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
        $("#searchbox_campaign").keyup(function() {
            datatable_manual.fnFilter(this.value);
        });
    });
</script>