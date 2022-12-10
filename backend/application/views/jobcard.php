<div class="row m-b-30">
    <div class="col-lg-2 col-sm-4 col-xs-12 pull-right">
        <a href="add-jobcard" class="fcbtn btn btn-success btn-outline btn-1e pull-right">Add New <i class="fa fa-plus"></i></a>
    </div>
</div>
<div class="row">
    <div class="table-responsive">
        <table id="customer_table" class="table table-striped">
            <thead>
                <tr>
                    <th>Sr.No</th>
                    <th>Name</th>
                    <th>Contact No.</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                     $srno = 1;
                     foreach($data as $k=>$v) { ?>
                    <tr>
                        <td><?php echo $srno;?></td>
                        <td><?php echo $v['name'];?></td>
                        <td><?php echo $v['mobile'];?></td>
                        <td><?php echo $v['email'];?></td>
                        <td></td>
                    </tr>
                <?php $srno++; } ?>
            </tbody>
        </table>
    </div>
</div>
