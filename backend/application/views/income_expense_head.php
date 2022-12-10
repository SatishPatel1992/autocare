<?php
$GLOBALS['title_left'] = '<a href="add-income-expense-head" class="btn btn-sm btn-info btn-outline btn-1e pull-right"><i class="fa fa-plus"></i> Add Income Expense Head</a>';
?>
<div class="row">
                <div class="col-lg-12">
                            <div class="form-group has-search">
                                <span class="fa fa-search form-control-feedback"></span>
                                <input type="text" class="form-control" placeholder="Search Items..." id="searchbox_item">
                            </div>
                        <table id="item_table" class="table table-bordered" style="border-collapse: collapse;">
                            <thead>
                                <tr>
                                    <th style="width: 20%;font-weight: bold;background-color: lavender;">Head Type</th>
                                    <th style="width: 70%;font-weight: bold;background-color: lavender;">Name</th>
                                    <th style="width: 10%;font-weight: bold;background-color: lavender;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($category as $k => $v) { ?>
                                    <tr>
                                        <td><?php echo $v['head_type']; ?></td>
                                        <td><?php echo $v['name']; ?></td>
                                        <td>
                                            <button class="btn btn-xs btn-primary" title="Edit" data-toggle="tooltip" onclick="window.location='add-income-expense-head?id=<?php echo base64_encode($v['category_id']);?>'"><i class="fa fa-edit"></i></button>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                </div>
            </div>
<style>
    .ui-autocomplete {
        z-index: 2150000000;
    }
    #stock_adjustment .form-group,
    #add_payment_form .form-group
    {
        margin-bottom: 2px;
    }
    #add_payment_form .form-material.floating {
        margin-top: 0px !important;
        margin-bottom: 0px !important;
    }
    .badge {
        width:50%;
    }
</style>
<script>
    function add_insurance() {

    }
</script>