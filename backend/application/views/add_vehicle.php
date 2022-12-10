<div class="row">
    <div class="col-md-12">
        <a href="vehicles" class="fcbtn btn btn-info btn-outline btn-1e pull-right">Back <i class="ti-back-left"></i></a>
    </div>
</div>	
<form class="form-material form" id="vehicle_add" method="post">
    <input type="hidden" name="created_by" value="<?php echo $_SESSION['data']->user_id;?>">
    <input type="hidden" name="updated_by" value="<?php echo $_SESSION['data']->user_id;?>">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <select class="form-control input-sm" name="type">
                    <option value="2 Wheeler">2 Wheeler</option>
                    <option value="3 Wheeler">3 Wheeler</option>
                    <option value="4 Wheeler">4 Wheeler</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <select class="form-control input-sm" name="make">
                    <option value="Bajaj">Bajaj</option>
                    <option value="Honda">Honda</option>
                    <option value="Suzuki">Suzuki</option>
                    <option value="Toyoto">Toyoto</option>
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group floating-label">
                <input type="text" class="form-control input-sm" name="model" placeholder="Model Name">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <button type="button" onclick="insert_data('tbl_vehicle_master',this.form,'vehicles')" class="fcbtn btn btn-success btn-outline btn-1e pull-right"> Save  <i class="fa fa-save"></i> </button>
        </div>
    </div>
</form>
