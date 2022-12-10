<?php
    $GLOBALS['title_left'] = '<a href="javascript:void(0)" class="btn btn-sm btn-info btn-outline btn-1e pull-right" onclick="add_new_item()"> <i class="fa fa-plus"></i> Add New</a>';
?>
<div class="nav-tabs-horizontal" data-plugin="tabs">
<ul class="nav nav-tabs customtab nav-tabs-line" role="tablist">
    <li role="presentation" class="nav-item active" data-id="parts">
        <a href="#parts_tab" role="tab" data-toggle="tab" aria-expanded="true" class="nav-link active"><span class="hidden-xs">Products</span></a>
    </li>
    <li role="presentation" class="nav-item" data-id="service">
        <a href="#service_tab" role="tab" data-toggle="tab" aria-expanded="true" class="nav-link"><span class="hidden-xs">Services</span></a>
    </li>
</ul>
<div class="tab-content pt-20">
    <div role="parts_tab" class="tab-pane active" id="parts_tab">
        <div class="row">
            <div class="col-lg-12">
            <div class="table-responsive">
                <div class="form-group has-search">
                    <span class="fa fa-search form-control-feedback"></span>
                    <input type="text" class="form-control" placeholder="Search Products..." id="searchbox_parts">
                </div>
                <table id="parts_table" class="table table-bordered" style="border-collapse: collapse;">
                    <thead>
                        <tr>
                            <th style="width: 5%;font-weight: bold;background-color: lavender;">Sr.No</th>
                            <th style="width: 30%;font-weight: bold;background-color: lavender;">Product Name</th>
                            <th style="width: 15%;font-weight: bold;background-color: lavender;">Vendor</th>
                            <th style="width: 12%;font-weight: bold;background-color: lavender;">Quantity on hand</th>
                            <th style="width: 10%;font-weight: bold;background-color: lavender;">Cost Price</th>
                            <th style="width: 10%;font-weight: bold;background-color: lavender;">Sell Price</th>
                            <th style="width: 10%;font-weight: bold;background-color: lavender;text-align: center;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $srno = 1;
                        foreach ($parts as $k => $v) {
                            ?>
                            <tr>
                                <td><?php echo $srno; ?></td>
                                <td><?php echo $v['part_number'] != "" ? $v['part_number'].' - '.$v['part_name'] : $v['part_name']; ?></td>
                                <td><?php echo $v['company_name']; ?></td>
                                <td><?php echo $v['qty_on_hand']; ?></td>
                                <td><?php echo $v['cost_price']; ?></td>
                                <td><?php echo $v['sell_price']; ?></td>
                                <td style="text-align: center;">
                                    <div class="btn-group">
                                        <button class="btn btn-xs btn-primary" title="Edit" data-toggle="tooltip" onclick="window.location='add-part?id=<?php echo base64_encode($v['part_id']); ?>'"><i class="fa fa-edit"></i></button>
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
    </div>
    <div role="service_tab" class="tab-pane" id="service_tab">
        <div class="row">
            <div class="col-lg-12">
        <div class="table-responsive">
        <div class="form-group has-search">
            <span class="fa fa-search form-control-feedback"></span>
            <input type="text" class="form-control" placeholder="Search Service..." id="searchbox_service">
        </div>
        <table id="service_table" class="table table-bordered" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="width: 5%;font-weight: bold;background-color: lavender;">Sr.No</th>
                    <th style="width: 60%;font-weight: bold;background-color: lavender;">Service Name</th>
                    <th style="width: 15%;font-weight: bold;background-color: lavender;">Category</th>
                    <th style="width: 10%;font-weight: bold;background-color: lavender;">Labor Amount</th>
                    <th style="width: 10%;font-weight: bold;background-color: lavender;text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $srno = 1;
                    foreach($services as $k=>$v) { ?>
                    <tr>
                        <td><?php echo $srno;?></td>
                        <td><?php echo $v['service_code'] != "" ? $v['service_code'].' - '.$v['service_name'] : $v['service_name'];?></td>
                        <td><?php echo $v['description'];?></td>
                        <td><?php echo $v['price'];?></td>
                        <td style="text-align: center;">
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
</div>
</div>
<div role="inventory_tab" class="tab-pane" id="inventory_tab">
        
</div>
</div>
</div>    
<script>
    function add_new_item() {
        var cur_act_tab = $('ul.customtab li.active').data('id');
        if(cur_act_tab == 'parts') { 
            window.location = 'add-part';
        } else if(cur_act_tab == 'service') { 
            window.location = 'add-service';
        } else { 
            window.location = 'add-kit';
        }
    }
    function save_item() {
        var cur_act_tab = $('ul.customtab li.active').data('id');
        if(cur_act_tab == 'parts') { 
        
        } else if(cur_act_tab == 'service') { 
        
        } else { 
        
        }
    }
    $(document).ready(function () {
        // $.ajax({
        //    method:'POST',
        //    url:'Transcation/InsertOperation',
        //    data:jQuery('#add_part').serialize()+"&table_name=tbl_parts",
        //    success:function(result) {
        //       var res = JSON.parse(result);
        //       toastr.success(res.message,'');
        //       setTimeout(function() {
        //           window.location = 'inventory';
        //       },1000)
        //    }
        // });  
	var datatable_parts = $('#parts_table').dataTable({
            bPaginate:true,
            bLengthChange: false,
            pageLength:25,
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
        $("#searchbox_parts").keyup(function() {
            datatable_parts.fnFilter(this.value);
        });
        
        var datatable_service = $('#service_table').dataTable({
            bPaginate:true,
            bLengthChange: false,
            pageLength:25,
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
        $("#searchbox_service").keyup(function() {
            datatable_service.fnFilter(this.value);
        });
    });
</script>
