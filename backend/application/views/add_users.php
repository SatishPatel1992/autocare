<?php
$GLOBALS['title_left'] = '<a href="users" class="btn btn-sm btn-info btn-outline btn-1e pull-right"><i class="fa fa-arrow-left"></i> Back</a>';
?>
<div class="nav-tabs-horizontal" data-plugin="tabs">
<ul class="nav nav-tabs nav-tabs-line" role="tablist">
    <li role="presentation" tab-id="user_detail" class="nav-item active">
        <a href="#user_detail" id="user_detail_tab" class="nav-link active" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs"> User Information</span></a>
    </li>
    <li role="presentation" class="nav-item" tab-id="menu_rights">
        <a href="#menu_rights" id="menu_rights_tab" class="nav-link" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-user"></i></span> <span class="hidden-xs">Menu Access Rights</span></a>
    </li>    
</ul>
<input type="hidden" name="user_id" value="<?php if(isset($_REQUEST['id'])) { echo $_REQUEST['id']; } ?>">
<div class="tab-content pt-20">
    <div class="tab-pane active" id="user_detail">
    <form class="form-material form" id="add_users_form" method="post">
    <div class="row">
        <div class="col-md-4">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" required name="first_name" value="<?php if(isset($user['first_name'])) { echo $user['first_name'];} ?>" autocomplete="off">
                <label class="floating-label required">First Name</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="last_name" value="<?php if(isset($user['last_name'])) { echo $user['last_name'];} ?>" autocomplete="off">
                <label class="floating-label">Last Name</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="employee_no" value="<?php if(isset($user['employee_no'])) { echo $user['employee_no'];} else { echo $emp_no['default_emp_no']; } ?>" autocomplete="off" id="employee_no" <?php if(isset($_REQUEST['id'])) { ?> readonly="" <?php } ?>>
                <label class="floating-label">Employee No.</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" required name="current_address" value="<?php if(isset($user['current_address'])) { echo $user['current_address']; } ?>" autocomplete="off">
                <label class="floating-label required">Current Address</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="permenant_address" value="<?php if(isset($user['permenant_address'])) { echo $user['permenant_address'];} ?>" autocomplete="off">
                <label class="floating-label">Permanent Address</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <select class="form-control input-sm" name="sex">
                    <option value="1">Male</option>
                    <option value="2">Female</option>
               </select>
               <label class="floating-label">Sex</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="email" value="<?php if(isset($user['email'])) { echo $user['email'];} ?>" autocomplete="off">
                <label class="floating-label">Email</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="number" class="form-control input-sm" required name="mobile_no" minlength="10" maxlength="10" value="<?php if(isset($user['mobile_no'])) { echo $user['mobile_no'];} ?>" autocomplete="off">
                <label class="floating-label required">Mobile</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="password" class="form-control input-sm" <?php if(!isset($_REQUEST['id'])) { ?> required <?php } ?> name="password"  autocomplete="off">
                <label class="floating-label <?php if(!isset($_REQUEST['id'])) { ?> required <?php } ?>">Default Password</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="emergency_contact_name" value="<?php if(isset($user['emergency_contact_name'])) { echo $user['emergency_contact_name'];} ?>" autocomplete="off">
                <label class="floating-label">Emergency Contact Name</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="emergency_contact_no" value="<?php if(isset($user['emergency_contact_no'])) { echo $user['emergency_contact_no'];} ?>" autocomplete="off">
                <label class="floating-label">Emergency Contact No.</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="dob" value="<?php if(isset($user['dob']) && $user['dob'] != '0000-00-00') { echo $user['dob'];} ?>" autocomplete="off" id="birth_date">
                <label class="floating-label">Birth Date</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="joining_date" value="<?php if(isset($user['joining_date']) && $user['joining_date']  != '0000-00-00') { echo $user['joining_date'];} ?>" autocomplete="off" id="joining_date">
                <label class="floating-label">Joining Date</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="education" value="<?php if(isset($user['education'])) { echo $user['education'];} ?>" autocomplete="off">
                <label class="floating-label">Education</label>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="remarks" value="<?php if(isset($user['remarks'])) { echo $user['remarks'];} ?>" autocomplete="off">
                <label class="floating-label">Remarks</label>
            </div>
        </div>
        <div class="col-lg-4 text-center">
            <label class="floating-label text-center">Status</label><br>
                    <span>De-Active</span>                    
                    <input type="checkbox" id="chkStatus" name="status" data-plugin="switchery" checked="" value="A"/>
                    <span>Active</span>
        </div>
        <div class="col-lg-4" id="leavingDateDiv" style="display: none;">
               <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="leaving_date" value="<?php if(isset($user['leaving_date'])) { echo $user['leaving_date'];} ?>" autocomplete="off" id="leaving_date">
                <label class="floating-label">Leaving Date</label>
            </div>
        </div>
    </div>
  </form>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <button type="button" style="float:right;" onclick="save_user()" class="btn btn-sm btn-info btn-outline btn-1e">Save & Assign Menu Right <i class="fa fa-arrow-right"></i></button>
    </div>
  </div>
</div>
<div class="tab-pane" id="menu_rights">
    <form id="menu_rights_form" name="menu_rights_form">
    <table style="width: 50%;">
        <tr>
            <td style="vertical-align: middle;">Select All Menu Access</td>
            <td style="text-align: center;">
                <div class="form-group form-material floating" data-plugin="formMaterial">
                <div class="checkbox checkbox-custom text-center">
                    <input type="checkbox" name="fullPermission" class="rightschk" id="fullPermission" value="Y">
                    <label>&nbsp;</label>
                </div>
                </div>
            </td>
            <td></td>
            <td></td>
        </tr>
    </table> 
    <table style="width: 50%;" class="table">
        <tr>
          <td style="font-weight: bold;">Menu Name</td>
          <td style="font-weight: bold;" class="text-center">Access</td>
        </tr>
        <?php for($i=0;$i<sizeof($menus);$i++) { ?>
        <tr>
        <td style="vertical-align: middle;<?php if($menus[$i]['level'] == 2) { ?>padding-left:25px;<?php } ?>"><?php if($menus[$i]['level'] == 1) { ?> <i class="fa fa-chevron-right" aria-hidden="true"></i> <?php } else { ?><i class="fa fa-arrow-right" aria-hidden="true"></i> <?php } ?> <?php echo $menus[$i]['name'];?></td>
                <td>
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                    <div class="checkbox checkbox-custom text-center">
                        <input type="hidden" name="<?php echo $menus[$i]['alias'];?>[menu_id]" value="<?php echo $menus[$i]['menu_id']; ?>">
                        <input type="checkbox"  name="<?php echo $menus[$i]['alias'];?>[have_rights]" class="rightschk chk_<?php echo $menus[$i]['menu_id']; ?>_view" id="<?php echo $menus[$i]['alias'];?>_v" value="Y" <?php if(isset($menu_rights[$menus[$i]['menu_id']]['have_rights']) && $menu_rights[$menus[$i]['menu_id']]['have_rights'] == 'Y') { echo 'checked'; } ?>>
                        <label>&nbsp;</label>
                    </div>
                    </div>
                </td>
            </tr>
        <?php } ?>
    </table>
  </form>
  <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <button type="button" style="float:right;" onclick="save_menurights()" class="btn btn-sm btn-info btn-outline btn-1e">Assign Menu Right</button>
    </div>
  </div>
</div>
</div>
</div>
<style>
.checkbox-custom input[type=checkbox], .checkbox-custom input[type=radio] {
     opacity:1 !important;
}
</style>
<script>
   $('#role_id').on('change',function() {
     if(this.prop('checked')) {
            $('.rightschk').each(function() {
                $(this).prop('checked',true);
            });
     } else {
            $('.rightschk').each(function() {
                $(this).prop('checked',false);
            });
     }
   });
//    $('#role_id').on('change',function() {
//        var selectedText = $('#role_id option:selected').text() != "Select Role" ? $('#role_id option:selected').text() : '';
       
//        $('#selected_role_text').text(selectedText);
//        $.ajax({
//             type: "GET",
//             url: 'users/getDefaultMenuRights?role_id='+this.value,
//             success: function (data) {
//                 if(data.default_menu_rights != undefined) {
//                     $.each(data.default_menu_rights,function(i,v) {
//                         if(v.have_rights == 'Y') {
//                            $('.chk_'+v.menu_id+'_view').prop('checked',true);
//                         }
//                     });
//                 } else {
//                     $('.rightschk').each(function() {
//                         $(this).prop('checked',false);
//                     });
//                 }
//             }
//        });
//    });
   $('#chkStatus').on('change',function() {
       if(!$(this).prop('checked')) {
           $("#leavingDateDiv").css('display','block');
           $('#leaving_date').datepicker({'format': 'dd-mm-yyyy'}).setDate('2010-10-10');
       } else {
           $("#leavingDateDiv").css('display','none');
       }
   });
   function save_user() {
        if($('#add_users_form').valid()) {
            var user_id = $("input[name=user_id]").val();
            var data = {'add_user' : $('#add_users_form').serialize()+'&user_id='+user_id,'table_name':'tbl_users'};
            $.ajax({
                    type: "POST",
                    url: 'Transcation/InsertOperation',
                    data: data,
                    success: function (data) {
                        var jsonParse = JSON.parse(data);
                        if(jsonParse && jsonParse['data']) {
                            toastr.success(jsonParse['message']);
                            $("input[name=user_id]").val(jsonParse['data']['user_id']);
                            setTimeout(function() {
                                $('#menu_rights_tab').trigger('click');
                            },500);
                        } else {
                            toastr.error('Error occured !.');
                        }
                    },
                    error: function (e) {
                        toastr.error('Error occured while saving data.');
                    }
                });
        }
   }
   function save_menurights() {
       var menu_rights_form = $('#menu_rights_form');
       var user_id = $("input[name=user_id]").val();
       var data = {'menu_right' : menu_rights_form.serialize(),'user_id': user_id,'table_name':'tbl_menu_rights'};
       $.ajax({
            type: "POST",
            url: 'Transcation/InsertOperation',
            data: data,
            success: function (data) {
                var jsonParse = JSON.parse(data);
                if(jsonParse && jsonParse['message']) {
                    toastr.success(jsonParse['message']);
                } else {
                    toastr.error('Error occured !.');
                }
            },
            error: function (e) {
                toastr.error('Error occured while saving data.');
            }
        });
   }
 $(document).ready(function() {
     $('#birth_date').datepicker({
        'format': 'dd-mm-yyyy',
        'autoclose': true
     });
     $('#joining_date').datepicker({
        'format': 'dd-mm-yyyy',
        'autoclose': true
     });
     $('#leaving_date').datepicker({
        'format': 'dd-mm-yyyy',
        'autoclose': true
     });
 });
</script>
