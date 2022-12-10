<?php
$CI =& get_instance();
$CI->load->model('MenuModel');
$this->load->view('templates/_parts/master_header_view');
if($_SERVER['PATH_INFO'] != '/reportViewer/view') {
?>
<body class="animsition site-navbar-small">
  <nav class="site-navbar navbar navbar-default navbar-fixed-top navbar-mega" role="navigation">

    <div class="navbar-header">
      <button type="button" class="navbar-toggler hamburger hamburger-close navbar-toggler-left hided" data-toggle="menubar">
        <span class="sr-only">Toggle navigation</span>
        <span class="hamburger-bar"></span>
      </button>
      <button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-collapse" data-toggle="collapse">
        <i class="icon md-more" aria-hidden="true"></i>
      </button>
      <div class="navbar-brand navbar-brand-center site-gridmenu-toggle" data-toggle="gridmenu">
        <img class="navbar-brand-logo" src="<?php echo base_url(); ?>assets/photos/logo-2.png" title="Remark">
        <div class="navbar-brand-text hidden-xs-down" style="color:black;font-family:'Open Sans'"> makeMyrepair</div>
      </div>
      <button type="button" class="navbar-toggler collapsed" data-target="#site-navbar-search" data-toggle="collapse">
        <span class="sr-only">Toggle Search</span>
        <i class="icon md-search" aria-hidden="true"></i>
      </button>
    </div>

    <div class="navbar-container container-fluid" style="padding-left:0px;">
      <!-- Navbar Collapse -->
      <div class="collapse navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">
        <!-- Navbar Toolbar -->
        <ul class="nav navbar-toolbar">
          <li class="nav-item hidden-float" id="toggleMenubar">
            <a class="nav-link" data-toggle="menubar" href="#" role="button">
              <i class="icon hamburger hamburger-arrow-left">
                <span class="sr-only">Toggle menubar</span>
                <span class="hamburger-bar"></span>
              </i>
            </a>
          </li>
          <!--<li class="nav-item hidden-float">-->
          <!--  <a class="nav-link icon fa fa-search" data-toggle="collapse" href="#" data-target="#site-navbar-search" role="button">-->
          <!--    <span class="sr-only">Toggle Search</span>-->
          <!--  </a>-->
          <!--</li>-->
        </ul>
        <!-- End Navbar Toolbar -->

        <!-- Navbar Toolbar Right -->
        <ul class="nav navbar-toolbar navbar-right navbar-toolbar-right">
          <!-- <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="javascript:void(0)" title="Notifications" aria-expanded="false" data-animation="scale-up" role="button">
              <i class="icon fa fa-bell" aria-hidden="true"></i>
              <span class="badge badge-pill badge-danger up">5</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-media" role="menu">
              <div class="dropdown-menu-header">
                <h5>NOTIFICATIONS</h5>
                <span class="badge badge-round badge-danger">New 5</span>
              </div>

              <div class="list-group">
                <div data-role="container">
                  <div data-role="content">
                    <a class="list-group-item dropdown-item" href="javascript:void(0)" role="menuitem">
                      <div class="media">
                        <div class="pr-10">
                          <i class="icon md-receipt bg-red-600 white icon-circle" aria-hidden="true"></i>
                        </div>
                        <div class="media-body">
                          <h6 class="media-heading">A new order has been placed</h6>
                          <time class="media-meta" datetime="2017-06-12T20:50:48+08:00">5 hours ago</time>
                        </div>
                      </div>
                    </a>
                    <a class="list-group-item dropdown-item" href="javascript:void(0)" role="menuitem">
                      <div class="media">
                        <div class="pr-10">
                          <i class="icon md-account bg-green-600 white icon-circle" aria-hidden="true"></i>
                        </div>
                        <div class="media-body">
                          <h6 class="media-heading">Completed the task</h6>
                          <time class="media-meta" datetime="2017-06-11T18:29:20+08:00">2 days ago</time>
                        </div>
                      </div>
                    </a>
                    <a class="list-group-item dropdown-item" href="javascript:void(0)" role="menuitem">
                      <div class="media">
                        <div class="pr-10">
                          <i class="icon md-settings bg-red-600 white icon-circle" aria-hidden="true"></i>
                        </div>
                        <div class="media-body">
                          <h6 class="media-heading">Settings updated</h6>
                          <time class="media-meta" datetime="2017-06-11T14:05:00+08:00">2 days ago</time>
                        </div>
                      </div>
                    </a>
                    <a class="list-group-item dropdown-item" href="javascript:void(0)" role="menuitem">
                      <div class="media">
                        <div class="pr-10">
                          <i class="icon md-calendar bg-blue-600 white icon-circle" aria-hidden="true"></i>
                        </div>
                        <div class="media-body">
                          <h6 class="media-heading">Event started</h6>
                          <time class="media-meta" datetime="2017-06-10T13:50:18+08:00">3 days ago</time>
                        </div>
                      </div>
                    </a>
                    <a class="list-group-item dropdown-item" href="javascript:void(0)" role="menuitem">
                      <div class="media">
                        <div class="pr-10">
                          <i class="icon md-comment bg-orange-600 white icon-circle" aria-hidden="true"></i>
                        </div>
                        <div class="media-body">
                          <h6 class="media-heading">Message received</h6>
                          <time class="media-meta" datetime="2017-06-10T12:34:48+08:00">3 days ago</time>
                        </div>
                      </div>
                    </a>
                  </div>
                </div>
              </div>
              <div class="dropdown-menu-footer">
                <a class="dropdown-menu-footer-btn" href="javascript:void(0)" role="button">
                  <i class="icon md-settings" aria-hidden="true"></i>
                </a>
                <a class="dropdown-item" href="javascript:void(0)" role="menuitem">
                  All notifications
                </a>
              </div>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="javascript:void(0)" title="Messages" aria-expanded="false" data-animation="scale-up" role="button">
              <i class="icon fa fa-envelope" aria-hidden="true"></i>
              <span class="badge badge-pill badge-info up">3</span>
            </a>
          </li> -->
          <li class="nav-item dropdown">
            <a class="nav-link navbar-avatar" data-toggle="dropdown" href="#" aria-expanded="false" data-animation="scale-up" role="button">
             <?php 
             if ($_SESSION['data']->profile_photo != "") { $profile_photo_url = UPLOAD_PATH_URL.'profilePhoto/'.$_SESSION['data']->profile_photo; } else { $profile_photo_url = UPLOAD_PATH_URL .'/myprofile-default.png'; } ?>
              <span class="avatar avatar-online" style="vertical-align:middle;">
                <img src="<?php echo $profile_photo_url; ?>" alt="<?php echo $_SESSION['data']->first_name; ?>">
              </span> 
              <span><?php echo $_SESSION['data']->first_name.' '.$_SESSION['data']->last_name; ?></span>
            </a>
            <div class="dropdown-menu" role="menu">
              <a class="dropdown-item" data-toggle="modal" data-target="#myprofile_modal" href="javascript:void(0)" role="menuitem"><i class="icon fa fa-user-o" aria-hidden="true"></i> Profile</a>
              <div class="dropdown-divider" role="presentation"></div>
              <a class="dropdown-item" href="logout" role="menuitem"><i class="icon fa fa-sign-out" aria-hidden="true"></i> Logout</a>
            </div>
          </li>
        </ul>
        <!-- End Navbar Toolbar Right -->
      </div>
      <!-- End Navbar Collapse -->

      <!-- Site Navbar Seach -->
      <div class="collapse navbar-search-overlap" id="site-navbar-search">
        <form role="search">
          <div class="form-group">
            <div class="input-search">
              <i class="input-search-icon md-search" aria-hidden="true"></i>
              <input type="text" class="form-control" name="site-search" placeholder="Search...">
              <button type="button" class="input-search-close icon fa fa-times" data-target="#site-navbar-search" data-toggle="collapse" aria-label="Close"></button>
            </div>
          </div>
        </form>
      </div>
      <!-- End Site Navbar Seach -->
    </div>
  </nav>
  <div class="site-menubar">
  <?php echo $this->MenuModel->get_menu(1,0,0); ?>  
  </div>
  <div class="page">
    <div class="page-content">
      <div class="panel">
        <div class="panel-heading">
          <div id="loader-wrapper">
						<div id="loader" style="display:none;z-index:1;"></div>
					 </div>
          <div class="row">
            <div class="col-lg-12">
              <span style="width:45%;" class="panel-title-text"><?php echo $GLOBALS['title_right'] != "" ? $GLOBALS['title_right'] : ""; ?></span>
              <span class="panel-title-button" style="width:55%;float:right;"><?php echo $GLOBALS['title_left'] != "" ? $GLOBALS['title_left'] : ""; ?></span>
            </div>
          </div>
        </div>
        <div class="panel-body container-fluid">
          <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12">
              <?php echo $the_view_content; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div id="myprofile_modal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content" style="border-radius: 5px !important;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title">My Profile.</h4>
            </div>
            <div class="modal-body" style="padding: 0px 10px 10px 10px;">
            <div class="container emp-profile">
            <form method="post" id="profile_form">
                <div class="row">
                    <div class="col-md-5">
                    <br>
                    <div class="imgUp">
                                    <div <?php
                                            if ($_SESSION['data']->profile_photo != "") { $is_logo = 1; ?> style="background-position:center;background-image: url('<?php echo UPLOAD_PATH_URL . 'profilePhoto/' . $_SESSION['data']->profile_photo ?>')" <?php } else { ?> style="background-position:center;background-image: url('<?php echo UPLOAD_PATH_URL .'/myprofile-default.png' ?>')" <?php } ?> class="profilePhotoPreview">
                                    </div>
                                    
                                    <label class="btn btn-primary btn-sm uploadlogobtn">
                                        <input type="file" class="uploadFile img" value="Upload Photo" style="width: 0px;height: 0px;overflow: hidden;" name="filename">
                                        <span><?php if($is_logo == 1) { ?> Change Photo <?php } else { ?> Upload Photo  <?php } ?></span>
                                    </label>
                                    <input type="hidden" name="photo_path" value="<?php echo $_SESSION['data']->profile_photo; ?>">
                                    <label class="btn btn-primary btn-sm removelogbtn" onclick="removeLogo();" style="display: <?php if($is_logo == 1) {  echo 'inline-block'; } else { echo 'none'; } ?>">
                                        Remove
                                    </label>
                    </div>
                    </div>
                    <div class="col-md-7">
                    <ul class="nav nav-tabs nav-tabs-line" role="tablist">
                                <li role="presentation" tab-id="customer_detail" class="nav-item active">
                                    <a href="#user_details" class="nav-link active" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs"> User detail</span></a>
                                </li>
                                <li role="presentation" class="nav-item" tab-id="customer_auto">
                                    <a href="#change_password" class="nav-link" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-user"></i></span> <span class="hidden-xs">Change Password</span></a>
                                </li>
                            </ul>
                            <div class="tab-content" style="width: 100%;">
                                <div id="user_details" class="tab-pane active" style="padding-top: 10px;">
                                <div class="row">
                                            <div class="col-md-6">
                                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                                <input type="text" class="form-control input-sm" required name="first_name" value="<?php echo $_SESSION['data']->first_name; ?>" autocomplete="off">
                                                <label class="floating-label required">First Name</label>
                                            </div>
                                            </div>
                                            <div class="col-md-6">
                                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                                <input type="text" class="form-control input-sm" required name="last_name" value="<?php echo $_SESSION['data']->last_name; ?>" autocomplete="off">
                                                <label class="floating-label required">Last Name</label>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                                <input type="text" class="form-control input-sm" readonly required name="mobile_no" value="<?php echo $_SESSION['data']->mobile_no; ?>" autocomplete="off">
                                                <label class="floating-label required">Mobile No.</label>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                                <input type="text" class="form-control input-sm" required name="email" value="<?php echo $_SESSION['data']->email; ?>" autocomplete="off">
                                                <label class="floating-label required">Email</label>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                                <input type="text" class="form-control input-sm" readonly name="employee_no" value="<?php echo $_SESSION['data']->employee_no; ?>" autocomplete="off">
                                                <label class="floating-label required">Employee No.</label>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                                <input type="text" class="form-control input-sm" readonly name="joining_date" value="<?php echo $_SESSION['data']->joining_date != '0000-00-00' ? date('d-m-Y',strtotime($_SESSION['data']->joining_date)) : ''; ?>" autocomplete="off">
                                                <label class="floating-label required">Joining Date</label>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button type="button" style="float:right;" onclick="updateProfile()" class="btn btn-sm btn-info btn-outline btn-1e">Update Profile</button>
                                </div>
                                </div>
                                </div>
                                <div id="change_password" class="tab-pane" style="padding-top: 10px;">
                                <div class="row">
                                            <div class="col-md-12">
                                            <div class="form-group form-material floating" data-plugin="formMaterial">
                                                <input type="password" class="form-control input-sm" required name="password" id="new_password" autocomplete="off">
                                                <label class="floating-label required">Enter New Password.</label>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <button type="button" style="float:right;" onclick="update_password()" class="btn btn-sm btn-info btn-outline btn-1e">Change Password</button>
                                </div>
                                </div>
                                </div>
                                
                            </div>
                    </div>
                </div>

                        </div>
                    </div>
                </div>
            </form>           
        </div>
            </div>
        </div>
      </div>
  </div>
  <?php
  } else { ?>
  <div class="page">
    <div class="page-content">
          <div class="row">
            <div class="col-lg-12 col-md-12 col-xs-12">
              <?php echo $the_view_content; ?>
            </div>
          </div>
    </div>
  </div>
 <?php
  }
  $this->load->view('templates/_parts/master_footer_view');
  ?>
  <script>
  $(document).on({
      ajaxStart: function() {
        $('#loader').css('display','block');
      	$('body').css("opacity", "0.8");
      },
      ajaxStop: function() { 
        $('#loader').css('display','none');
        $('body').css("opacity", "1");
      },
      ajaxComplete: function() {
        $('#loader').css('display','none');
        $('body').css("opacity", "1");
      },
  });
  $(function() {
        $(document).on("change", ".uploadFile", function() {
            var uploadFile = $(this);
            var files = !!this.files ? this.files : [];
            if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support
            if (/^image/.test(files[0].type)) { // only image file
                var reader = new FileReader(); // instance of the FileReader
                reader.readAsDataURL(files[0]); // read the local file
                if (files[0].size > 2000000) {
                    toastr.error('Only Image file having size 2MB or less is allowed.');
                    $('input[name=photo_path]').val('');
                    return;
                }
                reader.onloadend = function() { // set image data as background of div
                    uploadFile.closest(".imgUp").find('.profilePhotoPreview').css("background-image", "url(" + this.result + ")").css("background-position","center");
                    $('.removelogbtn').css('display','inline');
                    $('.uploadlogobtn span').text('Change Logo');
                    $('input[name=photo_path]').val(files[0].name);
                }
            } else {
                toastr.error('Only Image file having size 2MB or less is allowed.');
            }
        });
    });
    function updateProfile() {
        var profile_inf_form = $('#profile_form')[0];
        var data = new FormData(profile_inf_form);

        data.append('table_name', 'tbl_profile');

        $.ajax({
            type: "POST",
            enctype: 'multipart/form-data',
            url: 'Transcation/InsertOperation',
            data: data,
            processData: false,
            contentType: false,
            cache: false,
            success: function(data) {
                toastr.success('Profile Updated Successfully.');
                setTimeout(function() {
                  window.location.reload();
                },1500);
            },
            error: function(e) {
                toastr.error('Error occured while saving data.');
            }
        });
    }
    function update_password() {
      if($('#new_password').val() == "") {
        toastr.warning('Please enter new password to change.');
        return false;
      }
      $.ajax({
            method: 'POST',
            url: 'Transcation/InsertOperation',
            data: 'password='+$('#new_password').val()+'&table_name=tbl_reset_password',
            success: function(result) {
              toastr.success('Password changed successfully !.');
              setTimeout(function() {
                  window.location.reload();
                },1500);
            }
        });
    }
    function removeLogo() {
        $(".imgUp").find('.profilePhotoPreview').css("background", "url('<?php echo UPLOAD_PATH_URL .'/myprofile-default.png' ?>')").css('background-size','cover').css("background-position","center");
        $('.removelogbtn').css('display','none');
        $('input[name=photo_path]').val('');
    }
  </script>
