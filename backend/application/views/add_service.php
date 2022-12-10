<?php
$GLOBALS['title_left'] = '<a href="parts" class="btn btn-sm btn-info btn-outline btn-1e pull-right"><i class="fa fa-arrow-left"></i> Back</a>';
?>
<form class="form-material form" id="add_service" name="add_service" method="post">
    <input type="hidden" name="service_id" value="<?php echo $_REQUEST['id'];?>">
    <div class="row">
        <div class="col-lg-3 col-md-3">
            <div class="form-group floating-label">
                <input type="text" class="form-control input-sm" name="service_code" value="<?php if(!empty($service)) { echo $service['service_code']; } ?>">
                <label for="regular2">Service Code</label>
            </div>
        </div>
        <div class="col-lg-4 col-md-4">
            <div class="form-group floating-label">
                <input type="text" class="form-control input-sm" name="service_name" value="<?php if(!empty($service)) { echo $service['service_name']; } ?>">
                <label for="regular2">Service Name</label>
            </div>
        </div>
        <div class="col-lg-3 col-md-3">
            <div class="form-group floating-label">
                <input type="text" class="form-control input-sm" name="default_qty" autocomplete="off" value="<?php if(!empty($service)) { echo $service['default_qty']; } else { echo 1;} ?>">
                <label for="regular2">Default Sales Quantity</label>
            </div>
        </div>
        </div>
        <div class="row">
         <div class="col-lg-3 col-md-3">
            <div class="form-group floating-label">
                <input type="text" class="form-control input-sm" name="price" value="<?php if(!empty($service)) { echo $service['price']; } ?>" autocomplete="off">
            <label for="regular2">Service Charge</label>
            </div>
        </div>
        <div class="col-lg-3 col-md-3">
            <div class="form-group floating-label">
                <select class="form-control input-sm" name="tax_id">
                        <option value="">Select Tax</option>
                        <?php foreach ($taxes as $v) { ?>
                        <option <?php if(!empty($service) && $service['tax_id'] == $v['tax_id']) { echo 'selected'; } ?> value="<?php echo $v['tax_id']; ?>"><?php echo $v['name'].' - '.$v['description'];?></option>
                        <?php } ?>
                </select>
            </div>
        </div>
        <div class="col-lg-3 col-md-3">
            <div class="form-group floating-label">
                <select class="form-control input-sm" name="tax_type">
                        <option value="">Tax Type</option>
                        <option <?php if(!empty($service) && $service['tax_type'] == 'Inc') { echo 'selected'; } ?> value="Inc">Inclusive</option>
                        <option <?php if(!empty($service) && $service['tax_type'] == 'Exc') { echo 'selected'; } ?> value="Exc">Exclusive</option>
                </select>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <input type="button" class="btn btn-sm btn-success btn-outline btn-1e pull-right" value="Save" onclick="insert_data('tbl_services',this.form,'inventory')">
        </div>
    </div>
     </form>
<script>
 $('document').ready(function() {
     $('select').select2();
     $('#purchase_date').datepicker({
        'format':'yyyy-mm-dd',
        autoclose:true
     });
 });
</script>