<div class="row">
    <div class="col-md-12">
        <a href="discount" class="btn btn-sm btn-info btn-outline btn-1e pull-right">Back <i class="ti-back-left"></i></a>
    </div>
</div>
<form class="form-material form" id="add_vendor_form" method="post">
    <input type="hidden" name="garage_id" value="<?php echo $_SESSION['setting']->garage_id;?>">
	<input type="hidden" name="discount_id" value="<?php echo base64_decode($_REQUEST['id']);?>">
    <div class="row">
        <div class="col-md-2">
            <div class="form-group floating-label">
                <input type="text" class="form-control input-sm" name="name" autocomplete="off" value="<?php if(isset($discount['name'])) { echo $discount['name']; } ?>">
                <label for="regular2">Discount Name</label>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group floating-label">
                <input type="text" class="form-control input-sm" name="promo_code" autocomplete="off" value="<?php if(isset($discount['promo_code'])) { echo $discount['promo_code']; } ?>">
                <label for="regular2">Promo Code</label>
            </div>
        </div>
		<div class="col-md-2">
            <div class="form-group floating-label">
				<select class="form-control input-sm" name="type">
					<option <?php if($discount['type'] == 'P') { echo "selected"; } ?> value="P">Percentage</option>
					<option <?php if($discount['type'] == 'F') { echo "selected"; } ?> value="F">Fix Amount</option>
				</select>
			</div>
        </div>
        <div class="col-md-2">
            <div class="form-group floating-label">
                <input type="text" class="form-control input-sm" name="value" autocomplete="off" value="<?php if(isset($discount['value'])) { echo $discount['value']; } ?>">
                <label for="regular2">Discount Value</label>
            </div>
        </div>
        <div class="col-md-2">
		<div class="form-group floating-label">
			<input type="text" class="form-control input-sm" id="start_date" name="start_date" autocomplete="off" value="<?php if(isset($discount['start_date'])) { echo date('d-m-Y',strtotime($discount['start_date'])); } ?>">
            <label for="regular2">Start Date</label>
		</div>
		</div>
		<div class="col-md-2">
		<div class="form-group floating-label">
			<input type="text" class="form-control input-sm" id="end_date" name="end_date" autocomplete="off" value="<?php if(isset($discount['end_date'])) { echo date("d-m-Y",strtotime($discount['end_date'])); } ?>">
            <label for="regular2">End Date</label>
		</div>
		</div>
        </div>
    <div class="row">
        <div class="col-md-12">
           <button type="button" onclick="insert_data('tbl_discount',this.form,'discount')" class="btn btn-sm btn-success btn-outline btn-1e pull-right"> <?php if(!isset($_REQUEST['id'])) { ?> Save <?php } else { ?> Update <?php } ?><i class="fa fa-save"></i> </button>
        </div>
    </div>
</form>
<script>
$(document).ready(function() {
     $('select').select2();
	 $('#start_date').datepicker({
		autoclose:true,
        format:'dd-mm-yyyy'
	 });
	 $('#end_date').datepicker({
		autoclose:true,
        format:'dd-mm-yyyy'
	 });
 });
</script>