<div class="row m-b-30">
    <form nam="search_form" method="get">
    <div class="col-lg-2 col-md-2">
        <div class="form-group">
            <label for="input1">From</label>
            <input type="text" class="form-control input-sm date" name="from" value="<?php if(!isset($_REQUEST['from'])) { echo date('01-m-Y'); } else { echo $_REQUEST['from']; } ?>" required=""><span class="highlight"></span> <span class="bar"></span>
        </div>
    </div>
    <div class="col-lg-2 col-md-2">
        <div class="form-group">
            <label for="input1">To</label>
            <input type="text" class="form-control input-sm date" name="to" required="" value="<?php echo date('d-m-Y');?>"><span class="highlight"></span> <span class="bar"></span>
        </div>
    </div>
    <div class="col-lg-2 col-md-2">
        <br>
        <input type="submit" class="btn btn-sm btn-success btn-outline" value="Apply">
    </div>
</form>
    <div class="col-lg-2 col-sm-4 col-xs-12 pull-right">
        <a href="add-invoice" class="btn btn-sm btn-success btn-outline btn-1e pull-right">Add New <i class="fa fa-plus"></i></a>
    </div>
</div>
<div class="row">
    <div class="table-responsive">
        <table id="invoice_table" class="table table-striped table-bordered" style="border-collapse: collapse;">
            <thead>
                <tr>
                    <th>Sr.No</th>
                    <th>Invoice Date</th>
                    <th>Customer name</th>
                    <th>Contact No.</th>
                    <th>Invoice #</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                     $srno = 1;
                    foreach($data as $k=>$v) { ?>
                    <tr>
                        <td><?php echo $srno;?></td>
                        <td><?php echo $v['invoice_date'];?></td>
                        <td><?php echo $v['name'];?></td>
                        <td><?php echo $v['mobile'];?></td>
                        <td><?php echo $v['item_no'];?></td>
                        <td><?php echo $v['amount_due'];?></td>
                        <td>Paid</td>
                        <td></td>
                    </tr>
                <?php $srno++; } ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready(function () {
	$('#invoice_table').DataTable({
             "bLengthChange": false,
             "bSort":false,
             "columnDefs": [
                { "visible": false, "targets": 1 }
             ],
             "drawCallback": function ( settings ) {
                    var api = this.api();
                    var rows = api.rows( {page:'current'} ).nodes();
                    var last=null;
                    api.column(1, {page:'current'} ).data().each( function (Date, i ) {
                        if (last !== Date && Date != '') {
                            $(rows).eq( i ).before(
                                '<tr class="group"><td colspan="7">'+Date+'</td></tr>'
                            );
                            last = Date;
                        }
                    });
            }
	});
    });
</script>
