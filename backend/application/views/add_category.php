<div class="row">
    <div class="col-md-12">
        <a href="category" class="btn btn-sm btn-info btn-outline btn-1e pull-right">Back <i class="ti-back-left"></i></a>
    </div>
</div>
<form class="form-material form" id="add_category_form" method="post">
    <input type="hidden" name="company_id" value="<?php echo $_SESSION['data']->company_id;?>">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group floating-label">
                <input type="text" class="form-control input-sm" name="category_code" autocomplete="off" value="<?php if(isset($data['category_code'])) { echo $data['category_code']; } ?>">
                <label for="regular2">Category Code</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group floating-label">
                <input type="text" class="form-control input-sm" name="description" autocomplete="off" value="<?php if(isset($data['description'])) { echo $data['description']; } ?>">
                <label for="regular2">Description</label>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php if(!isset($_REQUEST['id'])) { ?>
            <button type="button" onclick="insert_data('tbl_category',this.form,'category')" class="btn btn-sm btn-success btn-outline btn-1e pull-right"> Save  <i class="fa fa-save"></i> </button>
            <?php } else { ?>
            <button type="button" onclick="update_data('tbl_category','vendor_id','<?php echo $data['vendor_id'];?>','vendor',this.form)" class="btn btn-sm btn-success btn-outline btn-1e pull-right"> Save  <i class="fa fa-save"></i> </button>
            <?php } ?>
        </div>
    </div>
</form>