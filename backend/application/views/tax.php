<?php
$GLOBALS['title_left'] = '<button type="button" class="btn btn-primary btn-sm" onclick="saveTax()"><i class="fa fa-save"></i> Save </button>';
?>
<form autocomplete="off" class="form" id="tax_rate_form" method="post">
    <div class="row">
        <div class="col-lg-12">
            <table id="tax_rate_table" style="width: 100%;" class="table table-bordered">
                <thead>
                    <tr>
                        <th style="background-color: lavender;width: 20%;font-weight:bold;">Name</th>
                        <th style="background-color: lavender;width: 50%;font-weight:bold;">Description</th>
                        <th style="background-color: lavender;width: 15%;font-weight:bold;">HSN/SAC Code</th>
                        <th style="background-color: lavender;width: 10%;font-weight:bold;">Rate</th>
                        <th style="background-color: lavender;width: 5%;font-weight:bold;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                <input type="text" name="name[]" class="form-control input-sm" value="<?php echo isset($taxes[0]['name']) ? $taxes[0]['name'] : ''; ?>">
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                <input type="text" name="description[]" class="form-control input-sm" value="<?php echo isset($taxes[0]['description']) ? $taxes[0]['description'] : ''; ?>">
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                <input type="text" name="HSN[]" class="form-control input-sm" value="<?php echo isset($taxes[0]['HSN']) ? $taxes[0]['HSN'] : ''; ?>">
                            </div>
                        </td>
                        <td>
                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                <input type="number" name="rate[]" class="form-control input-sm" value="<?php echo isset($taxes[0]['rate']) ? $taxes[0]['rate'] : ''; ?>">
                            </div>
                        </td>
                        <td style="text-align: center;" onclick="addNewTax()">
                            <i data-toggle="tooltip" data-placement="top" title="Tooltip on top" class="fa fa-plus"></i>
                        </td>
                    </tr>
                    <?php foreach ($taxes as $krow => $taxRow) {
                        if ($krow == 0) {
                            continue;
                        } ?>
                        <tr>
                            <td>
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <input type="text" name="name[]" class="form-control input-sm" value="<?php echo $taxRow['name']; ?>">
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <input type="text" name="description[]" class="form-control input-sm" value="<?php echo $taxRow['description']; ?>">
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <input type="text" name="HSN[]" class="form-control input-sm" value="<?php echo $taxRow['HSN']; ?>">
                                </div>
                            </td>
                            <td>
                                <div class="form-group form-material floating" data-plugin="formMaterial">
                                    <input type="number" name="rate[]" class="form-control input-sm" value="<?php echo $taxRow['rate']; ?>">
                                </div>
                            </td>
                            <td style="text-align: center;" class="taxRowTd">
                                <i class="fa fa-trash"></i>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</form>
<style type="text/css">
    #tax_rate_table .form-group {
        margin-bottom: 0px;
        margin-top: 0px;
    }
</style>
<script>
    $(document).ready(function() {
        $('#tax_table').DataTable({
            "bLengthChange": false,
        });
    });

    function addNewTax() {
        var html = '<tr>';
        html += '<td>';
        html += '<div class="form-group form-material floating" data-plugin="formMaterial">';
        html += '<input type="text" name="name[]" class="form-control input-sm">';
        html += '</div>';
        html += '</td>';
        html += '<td>';
        html += '<div class="form-group form-material floating" data-plugin="formMaterial">';
        html += '<input type="text" name="description[]" class="form-control input-sm">';
        html += '</div>';
        html += '</td>';
        html += '<td>';
        html += '<div class="form-group form-material floating" data-plugin="formMaterial">';
        html += '<input type="text" name="HSN[]" class="form-control input-sm">';
        html += '</div>';
        html += '</td>';
        html += '<td>';
        html += '<div class="form-group form-material floating" data-plugin="formMaterial">';
        html += '<input type="number" name="rate[]" class="form-control input-sm">';
        html += '</div>';
        html += '</td>';
        html += '<td style="text-align: center;" class="taxRowId">';
        html += '<i class="fa fa-trash"></i>';
        html += '</td>';
        html += '</tr>';
        $(html).insertAfter('#tax_rate_table tbody tr:last');
    }

    function saveTax() {
        $.ajax({
            type: "POST",
            url: 'Transcation/InsertOperation',
            data: {
                'data': $('#tax_rate_form').serialize(),
                'table_name': 'tbl_tax_rate'
            },
            success: function(data) {
                toastr.success('Tax saved successfully.');
            },
            error: function(e) {
                toastr.error('Error occured while saving tax.');
            }
        });
    }
</script>