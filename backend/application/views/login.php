<?php include "templates/_parts/master_header_view.php"; ?>
<link href="<?php echo base_url();?>assets/css/page-login2.min.css" rel="stylesheet" type="text/css">
  <div class="page vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out">>
    <div class="page-content vertical-align-middle" style="width: 30%;margin-top: 50px;">
      <div class="panel">
      <div class="panel-body">
          <div class="brand">
            <!-- <img class="brand-img" src="assets/photos/logo1.gif"> -->
            <h2 class="brand-text font-size-18">AUTOCARE</h2>
          </div>
            <form method="post" id="loginForm" autocomplete="off">
            <div class="form-group form-material floating" data-plugin="formMaterial">
              <input type="text" class="form-control" name="email" id="email"/>
              <label class="floating-label">Username</label>
            </div>
              <div class="form-group form-material floating" data-plugin="formMaterial" style="margin-top: 25px;">
              <input type="password" class="form-control" name="password" id="password" />
              <label class="floating-label">Password</label>
            </div>
              <button type="submit" class="btn btn-primary btn-block btn-lg mt-40">Log In</button>
          </form>
        </div>
      </div>
      <footer class="page-copyright page-copyright-inverse">
        <p>© 2020. All RIGHT RESERVED.</p>
        <div class="social">
          <a class="btn btn-icon btn-pure" href="javascript:void(0)">
            <i class="icon bd-twitter" aria-hidden="true"></i>
          </a>
          <a class="btn btn-icon btn-pure" href="javascript:void(0)">
            <i class="icon bd-facebook" aria-hidden="true"></i>
          </a>
          <a class="btn btn-icon btn-pure" href="javascript:void(0)">
            <i class="icon bd-google-plus" aria-hidden="true"></i>
          </a>
        </div>
      </footer>
    </div>
  </div>
<?php if($this->session->flashdata('SUCCESSMSG')) { ?>
    <div role="alert" class="alert alert-danger">
        <button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
		<?=$this->session->flashdata('SUCCESSMSG')?>
    </div>
<?php } ?>
<?php include "templates/_parts/master_footer_view.php"; ?>
<script>
//$('#loginform').on('submit',function() {
//  
//});
</script>