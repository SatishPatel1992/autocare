<form class="floating-labels" id="add_booking_form" method="post">
<div class="row">
    <div class="col-md-3">
        <span><h4>Add Booking</h4></span>
    </div>
    <div class="col-md-9">
        <a href="booking" class="btn btn-sm btn-info btn-outline btn-1e pull-right">Back <i class="ti-back-left"></i></a>
    </div>
</div>
    <input type="hidden" name="company_id" value="<?php echo $_SESSION['data']->company_id;?>">
    <div class="row">
        <div class="col-md-3">
            <div class="form-group">
                <select class="form-control" id="customer_id" name="customer_id">
                     <option value="">Select Customer</option>
                    <?php foreach ($customers as $cs) { ?>
                     <option value="<?php echo $cs['customer_id'];?>"><?php echo $cs['first_name'].' '.$cs['last_name'];?></option>
                    <?php } ?>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <select class="form-control" id="cust_vehi" name="vehicle_id">
                     <option value="">Select Vehicle</option>
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <input type="text" class="form-control input-sm" id="reg_no" required>
                <label>Reg. No</label>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <input type="text" class="form-control input-sm" autocomplete="off" required="" name="mileage">
                <label>Mileage</label>
            </div>
        </div>
        </div>
        <div class="row">
        <div class="col-md-9">
        </div>
        <div class="col-md-3"> 
            <div class="form-group">
                <input type="text" class="form-control input-sm" name="date" id="booking_date" autocomplete="off" required="" value="<?php echo date('d-m-Y');?>">
            <label>Date</label>
    </div>
    </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <button type="button" onclick="save_booking('save_booking')" class="btn btn-sm btn-success btn-outline btn-1e pull-right"> Save Booking <i class="fa fa-save"></i> </button>
            <button type="button" onclick="save_booking('add_payment')" class="btn btn-sm btn-success btn-outline btn-1e pull-right"> Add Payment  <i class="fa fa-save"></i> </button>
            <button type="button" onclick="save_booking('invoice')" class="btn btn-sm btn-success btn-outline btn-1e pull-right"> Invoice  <i class="fa fa-save"></i> </button>
            <button type="button" onclick="save_booking('add-jobcard')" class="btn btn-sm btn-success btn-outline btn-1e pull-right"> Jobcard  <i class="fa fa-save"></i> </button>
        </div>
    </div>
      </form>
</div>
<div class="white-box">
    <div class="row">
       <div class="col-md-12">
            <span><h4>Service History</h4></span>
       </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered" style="width: 50%;">
            <thead>
                <tr>
                    <td>Date</td>
                    <td>Mileage</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>12-12-2012</td>
                    <td>15,100</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
 function save_booking(redirect) {
      $.ajax({
        method:'post',
        url:'booking/saveBooking',
        data:jQuery('#add_booking_form').serialize(),
        success:function(result) {
            if($.trim(result) != '') {
                toastr.success('Saved Successfully.', '');
                setTimeout(function() {
                    if(redirect == 'save_booking') {
                        window.location = redirect;
                    } else if(redirect == 'add-jobcard') {
                        window.location = redirect+'?id='+$.trim(result);
                    } else if(redirect == 'invoice') {
                        window.location = redirect+'?id='+$.trim(result);
                    }
                },1000);
            }
        }
     });
 }
 $(document).ready(function() {
   $('select').select2();
   $('#service_date').datepicker({
       'format':'dd-mm-yyyy',
       'autoclose':true
  });
   $('#booking_date').datepicker({
       'format':'dd-mm-yyyy',
       'autoclose':true
  });
  $('#customer_id').on('change',function() {
        if(this.value != '') {
            $.ajax({
                method:'post',
                url:'jobcard/GetCustomerDetail',
                data:{'cust_id':this.value},
                success:function(result) {
                    var JsonData = JSON.parse(result);
                    if(JsonData.status == 0) {
                        $('#add').text(JsonData.data.cust.address);
                        $('#mobi').text(JsonData.data.cust.mobile);
                        $('#ema').text(JsonData.data.cust.email);
                        
                        if(JsonData.data.cust_vehicle != undefined) {
                            $.each(JsonData.data.cust_vehicle,function(key,value) {
                                console.log(value);
                                $('#cust_vehi').html($('<option></option>').attr('value',"").text('Select Vehicle'));
                                $('#cust_vehi').append($('<option></option>').attr('reg_no',value.reg_no).attr('clr',value.color).attr('value',value.vehicle_id).text(value.model));
                            });
                        }
                    } else {
                        $('#cust_vehi').html($('<option></option>').attr('value',"").text('Select Vehicle'));
                        $('#add').text('');
                        $('#mobi').text('');
                        $('#ema').text('');
                    }
                }
            })
        } else {
            $('#cust_vehi').html($('<option></option>').attr('value',"").text('Select'));
            $('#add').text('');
            $('#mobi').text('');
            $('#ema').text('');
        }
    });
    $('#cust_vehi').on('change',function() {
        if(this.value != '') {
            var reg_no =  $('option:selected',this).attr('reg_no');
            $('#reg_no').val(reg_no);
        } else {
            $('#reg_no').val('');
        }
    });
   });
</script>