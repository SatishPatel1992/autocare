<?php
$GLOBALS['title_right'] = 'Vehicle Master';
?>
<div class="nav-tabs-horizontal" data-plugin="tabs">
<ul class="nav nav-tabs nav-tabs-line" role="tablist">
    <li role="presentation" tab-id="user_detail" class="nav-item active">
        <a href="#make_tab" class="nav-link active" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs"> Make</span></a>
    </li>
    <li role="presentation" class="nav-item" tab-id="model_tab">
        <a href="#model_tab" class="nav-link" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-user"></i></span> <span class="hidden-xs">Model</span></a>
    </li>    
    <li role="presentation" class="nav-item" tab-id="varient_tab">
        <a href="#varient_tab" class="nav-link" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-user"></i></span> <span class="hidden-xs">Varient</span></a>
    </li>
</ul>
<div class="tab-content pt-20">
    <div class="tab-pane active" id="make_tab">
    <div class="row">
                <div class="col-lg-12">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group has-search" style="margin-top:10px;">
                                    <span class="fa fa-search form-control-feedback"></span>
                                    <input type="text" class="form-control" placeholder="Search Items..." id="searchbox_item">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <input type="text" class="form-control input-sm" required name="make_name" id="make_name" autocomplete="off">
                                    <label class="floating-label required">Enter Make Name</label>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <button type="button" style="float:right;margin-top:10px;" onclick="add_make()" class="btn btn-sm btn-info btn-outline btn-1e">Add Make</button>
                            </div>
                        </div>
                        <table id="make_table" class="table table-bordered" style="border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th style="width: 90%;font-weight: bold;background-color: lavender;">Make Name</th>
                                    <th style="width: 10%;font-weight: bold;background-color: lavender;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($makeNames as $k => $v) { ?>
                                    <tr>
                                        <td><?php echo $v['name']; ?></td>
                                        <td>
                                            <button class="btn btn-xs btn-primary" title="Edit" data-toggle="tooltip" onclick="window.location='add-insurance?id=<?php echo base64_encode($v['insurance_id']);?>'"><i class="fa fa-edit"></i></button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                </div>
            </div>
    </div>
    <div class="tab-pane" id="model_tab">
    <div class="row">
                <div class="col-lg-12">
                        <div class="row">
                        <div class="col-md-3">
                            <div class="form-group has-search" style="margin-top:10px;">
                                <span class="fa fa-search form-control-feedback"></span>
                                <input type="text" class="form-control" placeholder="Search Items..." id="searchbox_item_1">
                            </div>
                        </div>
                            <div class="col-md-3">
                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                <select class="form-control input-sm" name="make_id_1" id="make_id_1">
                                    <option value="*">Select</option>
                                    <?php foreach ($makeNames as $k => $v) { ?>
                                        <option value="<?php echo $v['make_id']; ?>"><?php echo $v['name']; ?></option>
                                    <?php } ?>
                                </select>
                                <label class="floating-label">Select Make</label>
                            </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <input type="text" class="form-control input-sm" required name="model_name_1" id="model_name_1" autocomplete="off">
                                    <label class="floating-label required">Enter Model Name</label>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <button type="button" style="float:right;margin-top:10px;" onclick="add_model()" class="btn btn-sm btn-info btn-outline btn-1e">Add Model</button>
                            </div>
                        </div>
                        <table id="model_table" class="table table-bordered" style="border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th style="width: 45%;font-weight: bold;background-color: lavender;">Make Name</th>
                                    <th style="width: 45%;font-weight: bold;background-color: lavender;">Model Name</th>
                                    <th style="width: 10%;font-weight: bold;background-color: lavender;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($modelNames as $k => $v) { ?>
                                    <tr>
                                        <td><?php echo $v['make_name']; ?></td>
                                        <td><?php echo $v['name']; ?></td>
                                        <td>
                                            <button class="btn btn-xs btn-primary" title="Edit" data-toggle="tooltip" onclick="window.location='add-insurance?id=<?php echo base64_encode($v['insurance_id']);?>'"><i class="fa fa-edit"></i></button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                </div>
            </div>
    </div>
    <div class="tab-pane" id="varient_tab">
    <div class="row">
                <div class="col-lg-12">
                <div class="row">
                        <div class="col-md-3">
                            <div class="form-group has-search" style="margin-top: 10px;">
                                <span class="fa fa-search form-control-feedback"></span>
                                <input type="text" class="form-control" placeholder="Search Items..." id="searchbox_item_2">
                            </div>
                        </div>
                            <div class="col-md-2">
                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                <select class="form-control input-sm" name="make_id_2" id="make_id_2" onchange="getModelByMakeID(this.value,'model_id_2')">
                                    <option value="*">Select</option>
                                    <?php foreach ($makeNames as $k => $v) { ?>
                                        <option value="<?php echo $v['make_id']; ?>"><?php echo $v['name']; ?></option>
                                    <?php } ?>
                                </select>
                                <label class="floating-label">Select Make</label>
                            </div>
                            </div>
                            <div class="col-md-2">
                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                <select class="form-control input-sm" name="model_id_2" id="model_id_2">
                                    <option value="*">Select</option>
                                </select>
                                <label class="floating-label">Select Model</label>
                            </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <input type="text" class="form-control input-sm" required name="variant_name" id="variant_name" autocomplete="off">
                                    <label class="floating-label required">Enter Variant Name</label>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <button type="button" style="float:right;margin-top:10px;" onclick="add_variant()" class="btn btn-sm btn-info btn-outline btn-1e">Add Variant</button>
                            </div>
                        </div>
                        
                        <table id="variant_table" class="table table-bordered" style="border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th style="width: 30%;font-weight: bold;background-color: lavender;">Make Name</th>
                                    <th style="width: 30%;font-weight: bold;background-color: lavender;">Model Name</th>
                                    <th style="width: 30%;font-weight: bold;background-color: lavender;">Varient Name</th>
                                    <th style="width: 10%;font-weight: bold;background-color: lavender;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($varientNames as $k => $v) { ?>
                                    <tr>
                                        <td><?php echo $v['make_name']; ?></td>
                                        <td><?php echo $v['model_name']; ?></td>
                                        <td><?php echo $v['name']; ?></td>
                                        <td>
                                            <button class="btn btn-xs btn-primary" title="Edit" data-toggle="tooltip" onclick="window.location='add-insurance?id=<?php echo base64_encode($v['insurance_id']);?>'"><i class="fa fa-edit"></i></button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                </div>
            </div>
    </div>
</div>
</div>
<script>
$(document).ready(function() {
    var dataTable = $('#make_table').dataTable({
            bPaginate:false,
            bInfo:false,
            bSort:false
    });
    $("#searchbox_item").keyup(function() {
         dataTable.fnFilter(this.value);
    });

    var dataTable1 = $('#model_table').dataTable({
            bPaginate:false,
            bInfo:false,
            bSort:false
    });
    $("#searchbox_item_1").keyup(function() {
         dataTable1.fnFilter(this.value);
    });

    var dataTable2 = $('#variant_table').dataTable({
            bPaginate:false,
            bInfo:false,
            bSort:false
    });
    $("#searchbox_item_2").keyup(function() {
         dataTable2.fnFilter(this.value);
    });
});
function getModelByMakeID(make_id,elementID) {
    $.ajax({
            type: "POST",
            url: 'common/CommonFunc',
            data: {'do': 'getModelNameByMakeID','make_id' : make_id},
            success: function (result) {
                var dataObj = JSON.parse(result);
                html = '<option value="*">Select</option>';
                if(dataObj['data']) {
                    dataObj['data'].forEach(element => {
                        html += '<option value='+element.model_id+'>'+element.name+'</option>';
                    });
                    debugger;
                    console.log(html);
                    $('#model_id_2').html(html);
                }
            }
        });
}
function add_make() {
    if($('#make_name').val() != '') {
        var data = {};
        data['name'] = $('#make_name').val();
        data['table_name'] = 'tbl_make';
       
        $.ajax({
            type: "POST",
            url: 'Transcation/InsertOperation',
            data: data,
            success: function (data) {
                toastr.success("Make Added Successfully");
                setTimeout(function() {
                    window.location.reload();
                },1000);
            }
        });
    } else {
        toastr.warning("Please enter Make Name !");
        $('#make_name').focus();
    }
}
function add_model() {
    if($('#make_id_1').val() != '' && $('#model_name_1').val() != '') {
        var data = {};
        data['make_id'] = $('#make_id_1').val();
        data['name'] = $('#model_name_1').val();
        data['table_name'] = 'tbl_model';
        
        $.ajax({
            type: "POST",
            url: 'Transcation/InsertOperation',
            data: data,
            success: function (data) {
                toastr.success("Model Added Successfully");
                setTimeout(function() {
                    window.location.reload();
                },1000);
            }
        });
    } else {
        toastr.warning("Please select make name and enter Model Name !");
    }
}
function add_variant() {
    if($('#make_id_2').val() != '' && $('#model_id_2').val() != '' && $('#variant_name').val() != '') {
        var data = {};
        data['model_id'] = $('#model_id_2').val();
        data['name'] = $('#variant_name').val();
        data['table_name'] = 'tbl_variant';
        $.ajax({
            type: "POST",
            url: 'Transcation/InsertOperation',
            data: data,
            success: function (data) {
                toastr.success("Variant Added Successfully");
                setTimeout(function() {
                    window.location.reload();
                },1000);
            }
        });
    } else {
        toastr.warning("Please enter make/model and variant !");
    }
}
</script>