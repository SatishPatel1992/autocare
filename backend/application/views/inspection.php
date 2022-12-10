<div class="row">
    <ul class="nav customtab nav-tabs" role="tablist">
               <li role="presentation" class="active">
                   <a href="#insp_group" aria-controls="insp_group" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs">Groups </span></a>
               </li>
               <li role="presentation" class="">
                   <a href="#insp_items" aria-controls="insp_items" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-user"></i></span> <span class="hidden-xs">Items</span></a>
               </li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade active in" id="insp_group">
            <div class="row">
    <div class="col-lg-12 col-md-12 pull-right">
        <table style="width: 40%;">
            <tr>
                <td style="width: 80%;"><input type="text" class="form-control input-sm" id="group_name" placeholder="Group Name"></td>
                <td style="width: 20%;"><button class="btn btn-sm btn-warning pull-right" onclick="add_group()"><i class="fa fa-plus"></i> Add Group</button></td>
            </tr>
        </table>
        <br>
        <table class="table table-bordered compact" style="width: 50%;"> 
            <thead>
                <tr>
                    <th>Sr.No</th>
                    <th>Group Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if(isset($data['groups']) && !empty($data['groups'])) {
                $srno = 1;
                foreach ($data['groups'] as $k => $v) { ?>
                <tr>
                    <td><?php echo $srno;?></td>
                    <td><?php echo $v['description'];?></td>
                    <td>
                      <div class="btn-group">
                        <button class="btn btn-xs btn-primary" title="Edit" data-toggle="tooltip" onclick="window.location='add-chartofaccount?id=<?php echo base64_encode($v['ch_acc_id']);?>'"><i class="fa fa-edit"></i></button>
                        <button class="btn btn-xs btn-danger" title="Delete" data-toggle="tooltip"><i class="fa fa-trash-o"></i></button>
                      </div>
                    </td>
                </tr>
                <?php $srno++; } } else { ?>
                <tr>
                    <td colspan="3">No Groups founds.</td>
                </tr>
                <?php }?>
            </tbody>
        </table>
    </div>
</div>
        </div>
        <div role="tabpanel" class="tab-pane" id="insp_items">
            <?php foreach ($data['groups'] as $k1 => $v1) { ?>
            <div class="panel" style="border: 1px solid lavender;">
                <div class="panel-heading" style="background-color: lavender;">
                    <span class="pull-left"><?php echo $v1['description'];?></span>
                    <div  class="pull-right"><a href="group_<?php echo $v1['group_id']; ?>" data-perform="panel-collapse"><i class="ti-minus"></i></a></div>
                </div>
                <div id="group_<?php echo $v1['group_id'];?>" class="panel-wrapper collapse in" aria-expanded="true">
                   <?php if(isset($data['items'][$v1['group_id']]) && !empty($data['items'][$v1['group_id']])) { ?>
                    <div class="panel-body">
                        <div class="table-responsive">
                        <?php $sr = 1;?>
                               <table class="table" id="table_<?php echo $v1['group_id'];?>">
                                <thead>
                                    <tr>
                                        <th>Sr.No</th>
                                        <th>Description</th>
                                        <th>Add Product from Inventory.</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>   
                                <tbody>
                              <?php foreach ($data['items'][$v1['group_id']] as $k2 => $v2) { ?>
                                    <tr class="tr_<?php echo $v1['group_id'];?>">
                                        <td><?php echo $sr;?></td>
                                        <td><input type="text" id="item_desc_<?php echo $v1['group_id'];?>_<?php echo $k2; ?>" class="form-control input-sm" value="<?php echo $v2['description'];?>"></td>
                                        <td>
                                            <input type="text" class="form-control input-sm inventory_search" data-for="inspection" placeholder="Search Inventory" id="product_<?php echo $v1['group_id'];?>_<?php echo $k2; ?>" value="<?php echo $v2['inv_description'];?>">
                                        </td>
                                        <td>
                                          <button class="btn btn-xs btn-danger" title="Delete" data-toggle="tooltip"><i class="fa fa-trash-o"></i></button>
                                        </td>
                                    </tr>
                        <?php $sr++;} ?> 
                                </tbody>
                            </table>
                        </div>
                    <table style="width: 100%">
                       <tr>
                          <td style="width: 50%;text-align: left"><input type="button" class="btn btn-sm btn-default" onclick="save_item(<?php echo $v1['group_id'];?>)" value="Save"></td>
                          <td style="width: 50%;text-align: right;"><input type="button" onclick="add_new_row(<?php echo $v1['group_id'];?>)" class="btn btn-sm btn-default" value="Add New Item"></td>
                       </tr>
                    </table>
                    </div>
                    <?php } else { ?>
                    <div class="panel-body">
                    <p id="appendhere_<?php echo $v1['group_id'];?>">
                            
                    </p>    
                    <table style="width: 100%">
                            <tr>
                                <td style="width: 50%"><span id="no_data_msg_<?php echo $v1['group_id']; ?>">No Any item added in this groups.</span><span class="hide" id="savebtn_<?php echo $v1['group_id']; ?>"> <input type="button" class="btn btn-sm btn-default" onclick="save_item(<?php echo $v1['group_id'];?>)" value="Save"> </span></td>
                                <td style="width: 50%;text-align: right;"><input type="button" class="btn btn-sm btn-default" value="Add New Item" onclick="add_new_row(<?php echo $v1['group_id']; ?>)"></td>
                            </tr>
                        </table>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>
<br>
<style>
    .panel-heading {
        height: 40px;
    }   
    .ls_result_div {
        left:auto !important;
    }
</style>
<script>
function save_item(group_id) {
    if(group_id != "") {
        var dataArray = [];
        $('#table_'+group_id+' tbody tr.tr_'+group_id).each(function(i,v) {
            var tr_id = $(this).attr('class');
            if(tr_id == 'tr_'+group_id) {
                var inv_item_id = $('#product_'+group_id+'_'+i).attr('p_id') != undefined ? $('#product_'+group_id+'_'+i).attr('p_id') : 0;
                var inv_item_type = $('#product_'+group_id+'_'+i).attr('item_type') != undefined ? $('#product_'+group_id+'_'+i).attr('item_type') : 0;
                var obj = {'insp_group_id':group_id,'description':$('#item_desc_'+group_id+'_'+i).val(),'inv_item_id':inv_item_id,'inv_item_type':inv_item_type,'inv_description':$('#product_'+group_id+'_'+i).val()};
                dataArray.push(obj);
            }
        });
        
        if(dataArray.length > 0) {
            $.ajax({
                type: 'POST',
                url: 'inspection/SaveGroupItems',
                data: {'data':dataArray,'group_id':group_id},
                success: function (result) {
                    var json = JSON.parse(result);
                    if(json.status == 200) {
                       toastr.success(json.message);
                    } else {
                       toastr.error(json.message);
                    }
                }
            });
        }
    }
}
function add_new_row(group_id) {
    var new_rows = '';
    var rows_length = $('.tr_'+group_id).length;
    new_rows += "<tr class='tr_"+group_id+"'>";
    new_rows += "<td>"+(parseInt(rows_length) + 1)+"</td>";
    new_rows += "<td><input type='text' id='item_desc_"+group_id+"_"+rows_length+"' class='form-control input-sm'></td>";
    new_rows += "<td>";
    new_rows += "<input type='text' class='form-control input-sm inventory_search' data-for='inspection' placeholder='Search Inventory' id='product_"+group_id+"_"+rows_length+"'>";
    new_rows += '</td>';
    new_rows += '<td>';
    new_rows += '<button class="btn btn-xs btn-danger" title="" data-toggle="tooltip" data-original-title="Delete"><i class="fa fa-trash-o"></i></button>';
    new_rows += '</td>';
    new_rows += '</tr>';
    
    if($('#table_'+group_id).length == 0) {
        var new_rows_table =  "<table class='table' id='table_"+group_id+"'>";
            new_rows_table += "<thead>";
            new_rows_table += '<tr>';
            new_rows_table += '<th>Sr.No</th>';
            new_rows_table += '<th>Description</th>';
            new_rows_table += '<th>Add Product from Inventory.</th>';
            new_rows_table += '<th>Action</th>';
            new_rows_table += '</tr>';
            new_rows_table += '</thead>';
            new_rows_table += '<tbody>';
            new_rows_table += new_rows;
            new_rows_table += '</tbody>';
            new_rows_table += '</table>';
            $('#group_'+group_id+' #appendhere_'+group_id).append(new_rows_table);
            $('#no_data_msg_'+group_id).text('');
            $('#savebtn_'+group_id).removeClass('hide');
    } else {
        $('#table_'+group_id+' tbody tr.tr_'+group_id+':last').after(new_rows);
    }
    
    $("#product_"+group_id+"_"+rows_length).ajaxlivesearch({
      onResultClick: function(e, data) {
         debugger;
         var p_id = $(data.selected).find('td').eq('2').data('id');
         var type = $(data.selected).find('td').eq('0').text();
         var desc = $(data.selected).find('td').eq('2').text();
         var input_id = data.searchField.context.id;
         $('#'+input_id).attr('p_id',p_id);
         $('#'+input_id).attr('item_type',type);
         $('#'+input_id).val(desc);
         $(".inventory_search").trigger('ajaxlivesearch:hide_result');
      },
      onResultEnter: function(e, data) {
         var p_id = $(data.selected).find('td').eq('2').data('id');
         var type = $(data.selected).find('td').eq('0').text();
         var desc = $(data.selected).find('td').eq('2').text();
         var input_id = data.searchField.context.id;
         $('#'+input_id).attr('p_id',p_id);
         $('#'+input_id).attr('item_type',type);
         $('#'+input_id).val(desc);
         $(".inventory_search").trigger('ajaxlivesearch:hide_result');
      }
   });
}
function add_group() {
 var group_name = $('#group_name').val(); 
 if(group_name != "") {
        $.ajax({
           type: 'POST',
           url: 'Transcation/InsertOperation',
           data: 'group_name='+group_name+'&table_name=tbl_inspection_group',
           success: function (result) {
               var json = JSON.parse(result);
               if (json.status == 200) {
                 toastr.success(json.message, '');
                 $('#group_name').val('');
               } else {
                   toastr.warning(json.message, '');
               }
           }
       });
 } else {
     alert('Please enter name of the group.');
     $('#group_name').focus();
 }
}
$(document).ready(function() {
   $(".inventory_search").ajaxlivesearch({
      onResultClick: function(e, data) {
         debugger;
         var p_id = $(data.selected).find('td').eq('2').data('id');
         var type = $(data.selected).find('td').eq('0').text();
         var desc = $(data.selected).find('td').eq('2').text();
         var input_id = data.searchField.context.id;
         $('#'+input_id).attr('p_id',p_id);
         $('#'+input_id).attr('item_type',type);
         $('#'+input_id).val(desc);
         $(".inventory_search").trigger('ajaxlivesearch:hide_result');
      },
      onResultEnter: function(e, data) {
         var p_id = $(data.selected).find('td').eq('2').data('id');
         var type = $(data.selected).find('td').eq('0').text();
         var desc = $(data.selected).find('td').eq('2').text();
         var input_id = data.searchField.context.id;
         $('#'+input_id).attr('p_id',p_id);
         $('#'+input_id).attr('item_type',type);
         $('#'+input_id).val(desc);
         $(".inventory_search").trigger('ajaxlivesearch:hide_result');
      }
   });
   $('body').on('keydown', '.inventory_search', function(e) {
    if (e.which == 9) {
        alert('test');
        e.preventDefault();
        // do your code
    }
   });
});
</script>