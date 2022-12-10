<?php if(isset($data['models']) && !empty($data['models'])) { ?>
    <span style="font-size: 15px;font-weight: bold"><?php echo $data['models'][0]['make_name']; ?></span>
    <div class="JStableOuter">
    <table class="main">
        <thead>
          <tr style="top: 0px" >
              <th style="left: 0px"></th>
            <?php foreach ($data['models'] as $k1 => $v1) { ?>
              <th style="text-align: center;" colspan="2"><?php echo $v1['name']; ?></th>
            <?php } ?>
          </tr>
          <tr style="top: 0px;">
                <th style="left: 0px;"></th>
            <?php foreach ($data['models'] as $k2 => $v2) { ?>
                <th>Petrol</th>
                <th>Diesel</th>
            <?php  } ?>
        </tr>
        </thead>
        <tbody>
            <?php foreach($data['service_item'] as $k => $v) { ?>
            <tr class="parent parent_<?php echo $k;?>">
                <td class="itemname"><?php echo $v['name']; ?></td>
              <?php foreach ($data['models'] as $k2 => $v2) { ?>
                 <td><input type="text" class="form-control input-sm"></td>
                 <td><input type="text" class="form-control input-sm"></td>
              <?php } ?>
            </tr>
            <tr class="child child_<?php echo $k;?>">
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
            </tr>
            <?php } ?>
        </tbody>
    </table>
    </div>
<?php } else { ?>
    <span class="error">Model not found.Please insert model under this make.</span>
<?php } ?>
<style>
.JStableOuter .main {
  position: relative;
  width: 100%;
  background-color: #fff;
  border-collapse: collapse;
  font-family: arial;
  display: block;
  height: 450px;
  overflow: scroll;
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