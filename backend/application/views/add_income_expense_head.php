        <input type="hidden" id="category_id" value="<?php if(isset($_REQUEST['id'])) { echo base64_decode($_REQUEST['id']); } ?>">
        <form class="form-material form" id="add_customer_form" method="post">
                                        <div class="row">
                                        <div class="col-md-3">
                                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                                <select class="form-control input-sm" name="head_type" id="head_type">
                                                    <option value="Income" <?php echo $head['head_type'] == 'Income' ? 'selected' : ''?>>Income</option>
                                                    <option value="Expense" <?php echo $head['head_type'] == 'Expense' ? 'selected' : ''?>>Expense</option>
                                                </select>
                                                <label class="floating-label required">Head Type</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                                    <input type="text" class="form-control input-sm" name="name" required id="name" value="<?php if(isset($head['name'])) { echo $head['name'];} ?>">
                                                    <label class="floating-label required">Name</label>
                                                </div>
                                            </div>
                                            <div class="col-md-3" style="margin-top:10px;">
                                            <button type="button" onclick="save_head()" class="btn btn-sm btn-success btn-outline btn-1e"> <?php if (!isset($_REQUEST['id'])) { ?> Save
                                            <?php } else { ?> Update <?php } ?><i class="fa fa-save"></i> </button>
                                            <a href="income-expense-head" class="btn btn-sm btn-info btn-outline btn-1e pull-right"><i class="fa fa-arrow-left"></i> Back</a>
                                            </div>
                               </div>
                               </div>
                                        </form>                                     
<script>
    function save_head() {
        if($('#name').val() == "") {
            toastr.warning("Enter Head Name !");
            $('#name').focus();
            return false;
        }

        $.ajax({
            type: 'POST',
            url: 'Transcation/InsertOperation',
            data: {
                'name': $('#name').val(),
                'head_type': $('#head_type').val(),
                'table_name': 'tbl_category_master',
                'category_id' : $('#category_id').val()
            },
            success: function(result) {
                var res = JSON.parse(result);
                if (res != undefined && res.status == '200') {
                    toastr.success(res.message, '');
                } else {
                    toastr.error(res.message, '');
                }
                setTimeout(function() {
                    window.location = 'income-expense-head';
                },1000);
            }
        });
    }
    function remove_vehicle(row_id) {
        $('table#vehicle_list_table tbody tr#row_'+row_id).remove();
    }
    function add_vehicle() {
        var make_select = $('select#make_1').html();
        var cnt = $(".vehicle_div").length + 1;
        var html = "<div class='row vehicle_div'>";
        html += "<div class='col-lg-12 col-sm-12'>";
        html += "<table style='width:100%;'>";
        html += "<tr>";
        html += "<td style='width: 20%;'>";
        html += "<div class='form-group form-material floating' data-plugin='formMaterial'>";
        html += "<select class='form-control input-sm make_drp' name='vehicle["+cnt+"][make_id]' id='make_" + cnt + "'>";
        html += make_select;
        html += "</select>";
        html += "</div>";
        html += "</td>";
        html += "<td style='width: 20%;'>";
        html += "<div class='form-group form-material floating' data-plugin='formMaterial'>";
        html += "<select class='form-control input-sm model_drp' name='vehicle["+cnt+"][model_id]' id='model_" + cnt + "'>";
        html += "<option value=''>Select Model</option>";
        html += "</select>";
        html += "</div>";
        html += "</td>";
        html += "<td style='width: 20%;'>";
        html += "<div class='form-group form-material floating' data-plugin='formMaterial'>";
        html += "<select class='form-control input-sm' name='vehicle["+cnt+"][variant_id]' id='variant_" + cnt + "'>";
        html += "<option value=''>Select Variant</option>";
        html += "</select>";
        html += "</div>";
        html += "</td>";
        html += "<td style='width: 20%;'>";
        html += "<div class='form-group form-material floating' data-plugin='formMaterial'>";
        html += "<input type='text' class='form-control input-sm' name='vehicle["+cnt+"][reg_no]' id='reg_" + cnt + "' onblur=check_duplication('tbl_customer_vehicle','reg_no',this,'vehicle_id',0,'Duplicate Licence Plate found. please enter another.')>";
        html += "<label class='floating-label'>Vehicle No.</label>";
        html += "</div>";
        html += "</td>";
        html += "<td style='width: 15%;'>";
        html += "<div class='form-group form-material floating' data-plugin='formMaterial'>";
        html += "<input type='text' class='form-control input-sm' name='vehicle["+cnt+"][year]' id='year_" + cnt + "'>";
        html += "<label class='floating-label'>Year</label>";
        html += "</div>";
        html += "</td>";
        html += "<td style='width: 5%;'>";
        html += "</td>";
        html += "</tr>";
        html += "</table>";
        html += "</div>";
        $('.vehicle_div:last').after(html);
        $('select').select2();
    }
</script>
