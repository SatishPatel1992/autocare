<div class="vtabs">
    <ul class="nav tabs-vertical">
        <li class="tab active">
            <a data-toggle="tab" href="#tab1" aria-expanded="true"> <span class="visible-xs"><i class="ti-home"></i></span> <span class="hidden-xs">Car Care</span> </a>
        </li>
        <li class="tab">
            <a data-toggle="tab" href="#tab2" aria-expanded="false"> <span class="visible-xs"><i class="ti-user"></i></span> <span class="hidden-xs">Wheel Care</span> </a>
        </li>
        <li class="tab">
            <a data-toggle="tab" href="#tab3" aria-expanded="false"> <span class="visible-xs"><i class="ti-user"></i></span> <span class="hidden-xs">Body Shop</span> </a>
        </li>
        <li class="tab">
            <a data-toggle="tab" href="#tab4" aria-expanded="false"> <span class="visible-xs"><i class="ti-user"></i></span> <span class="hidden-xs">Accidental Repair</span> </a>
        </li>
        <li class="tab">
            <a data-toggle="tab" href="#tab5" aria-expanded="false"> <span class="visible-xs"><i class="ti-user"></i></span> <span class="hidden-xs">Emergency Repair</span> </a>
        </li>
        <li class="tab">
            <a data-toggle="tab" href="#tab6" aria-expanded="false"> <span class="visible-xs"><i class="ti-user"></i></span> <span class="hidden-xs">Scheduled Service</span> </a>
        </li>
    </ul>
    <div class="tab-content">
        <div id="tab1" class="tab-pane active">
        <ul class="nav customtab nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#item" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs">Item Master</span></a></li>
            <li role="presentation"><a href="#package" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs">Package Master</span></a></li>
        </ul>
            <div class="tab-content" style="width: 750px;">
            <div role="tabpanel" class="tab-pane fade active in" id="item">
            <br>
            <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group floating-labels">
                            <input type="text" class="form-control input-sm" required autocomplete="off">
                            <label>Item Name</label>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <select class="form-control input-sm">
                                <option value="">Action</option>
                                <option value="">Replace</option>
                                <option value="">Repair</option>
                                <option value="">TopUp</option>
                                <option value="">Labor</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <select class="form-control input-sm">
                                <option value="">Type</option>
                                <option value="">Part</option>
                                <option value="">Labor</option>
                            </select>
                        </div>
                    </div>
                </div>    
            </div>
            <div role="tabpanel" class="tab-pane" id="package">
            <br>
            <form>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group floating-labels">
                            <input type="text" class="form-control input-sm" required autocomplete="off">
                            <label>Package Name</label>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="form-group floating-labels">
                            <input type="text" class="form-control input-sm" required autocomplete="off">
                            <label>Description</label>
                        </div>
                    </div>
                </div>
                </form>
                <div class="row">
                    <div class="col-lg-3">
                        <div class="form-group">
                            What All It is Covered :
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="form-group">
                            <input type="text" class="form-control input-sm" required autocomplete="off">
                        </div>
                    </div>
                    <div class="col-lg-1">
                        <button type="button" class="btn btn-primary btn-xs" title="Add Row" data-toggle="tooltip" onclick="add_rows()">+</button>
                    </div>
                </div>
                <div class="row">
                <div class="col-lg-12">
                    <div class="JStableOuter">
                    <table class="main">
                        <thead>
                          <tr style="top: 0px" >
                              <th style="left: 0px"></th>
                              <?php foreach ($data['model'] as $k1 => $v1) { ?>
                              <?php foreach ($v1 as $k2 => $v2) { ?>
                              <th style="text-align: center;" colspan="2"><?php echo $v2['name']; ?></th>
                            <?php } } ?>
                          </tr>
                          <tr style="top: 0px;">
                                <th style="left: 0px;"></th>
                            <?php foreach ($data['model'] as $k1 => $v1) { ?>
                              <?php foreach ($v1 as $k2 => $v2) { ?>
                                <th>Petrol</th>
                                <th>Diesel</th>
                            <?php  } } ?>
                        </tr>
                        </thead>
                        <tbody>
                            <tr class="parent parent_<?php echo $k;?>">
                                <td class="itemname">Quantity</td>
                              <?php foreach ($data['model'] as $k1 => $v1) { ?>
                              <?php foreach ($v1 as $k2 => $v2) { ?>
                                 <td><input type="text" class="form-control input-sm" readonly=""></td>
                                 <td><input type="text" class="form-control input-sm" readonly=""></td>
                              <?php } } ?>
                            </tr>
                            <tr class="parent parent_<?php echo $k;?>">
                                <td class="itemname">Unit Price</td>
                              <?php foreach ($data['model'] as $k1 => $v1) { ?>
                              <?php foreach ($v1 as $k2 => $v2) { ?>
                                 <td><input type="text" class="form-control input-sm" readonly=""></td>
                                 <td><input type="text" class="form-control input-sm" readonly=""></td>
                              <?php } } ?>
                            </tr>
                            <tr class="parent parent_<?php echo $k;?>">
                                <td class="itemname">Tax</td>
                              <?php foreach ($data['model'] as $k1 => $v1) { ?>
                              <?php foreach ($v1 as $k2 => $v2) { ?>
                                 <td><input type="text" class="form-control input-sm" readonly=""></td>
                                 <td><input type="text" class="form-control input-sm" readonly=""></td>
                              <?php } } ?>
                            </tr>
                            
<!--                            <tr class="child child_<?php echo $k;?>">
                                <td colspan="7"><h4>Additional information</h4>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <input type="text" class="form-control input-sm">
                                    </div>
                                </div>
                                <br>
                            </td>
                            </tr>
                            <tr class="child child_<?php echo $k;?>">
                                <td></td>
                                <?php foreach ($data['models'] as $k2 => $v2) { ?>
                                <td>123</td>
                                <td>12</td>
                              <?php } ?>
                            </tr>-->
                        </tbody>
                </table>
                    </div>
                </div>
            </div>
         </div>
      </div>
    </div>
  </div>
</div>
<style>
.tab-content {
   margin-top: 0px;     
}
.JStableOuter .main {
  position: relative;
  width: 100%;
  background-color: #fff;
  border-collapse: collapse;
  font-family: arial;
  display: block;
  overflow: scroll;
}
.vtabs .tab-content {
    display: block !important;
}
/*thead*/
.JStableOuter .main thead {
  position: relative;
  /*display: block;*/ /*seperates the header from the body allowing it to be positioned*/
  overflow: visible;
}

.JStableOuter .main thead th {
  background-color: #fff;
 /* min-width: 120px;*/
  height: 32px;
  padding: 3px 15px 0;
  font-size: 13px;
  vertical-align: top;
  position: relative;
  box-shadow: 0 1px 0px 1px #999;
  border-top: solid thin;
  z-index: 90;
}
.JStableOuter .main thead th:nth-child(1) {/*first cell in the header*/
  position: relative;
 /* display: block;*/ /*seperates the first cell in the header from the header*/
  background-color: #fff;
  z-index: 99;
  box-shadow: 0 1px 1px 1px #999;
  min-width: 200px;
  border-left: solid thin;
}

.JStableOuter .main thead tr {/*first cell in the header*/
  position: relative;

}
.JStableOuter .main tbody { /*border-top: 1px solid #999;*/}
.JStableOuter .main tbody td {
  background-color: #fff;
  /*min-width: 120px;*/
  border: 1px solid #999;
  padding: 0 15px;
  min-width: 100px;
  font-size: 13px;
  box-shadow: 0 1px 0px 1px #999;
}

.JStableOuter .main tbody tr td:nth-child(1) {  /*the first cell in each tr*/
  position: relative;
  /*display: block;*/ /*seperates the first column from the tbody*/
  height: 40px;
  background-color: #fff;
}
.tableOuter {
    max-width: 800px;
    overflow: auto;
 }
table.main.table-hover > tbody > tr:nth-child(even):hover td {
    background-color: white;
}
</style>
<script>
$(document).ready(function() {
    $('.JStableOuter .main ').scroll(function(e) {
        $('.JStableOuter .main thead').css("left", -$(".JStableOuter .main tbody").scrollLeft());
        $('.JStableOuter .main thead th:nth-child(1)').css("left", $(".JStableOuter .main").scrollLeft() -0); 
        $('.JStableOuter .main tbody td:nth-child(1)').css("left", $(".JStableOuter .main").scrollLeft()); 
        $('.JStableOuter .main thead').css("top", -$(".JStableOuter .main tbody").scrollTop());
        $('.JStableOuter .main thead tr th').css("top", $(".JStableOuter table").scrollTop()); 
    });
});
(function ($) {
    $(function () {
        $('.main').each(function () {
            var table = $(this);
            table.children('tbody').children('tr.child').hide();
            table.children('tbody').children('tr').children('.itemname').click(function () {
                var element_index = $(this).parent('tr').attr('class').split('_');
                var index = element_index[1];
                $('tr.child_'+index).toggle();
            });
        });
    });
})(jQuery); 
</script>