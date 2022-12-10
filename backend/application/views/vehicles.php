<div class="row m-b-30">
    <div class="col-lg-2 col-sm-4 col-xs-12 pull-right">
        <a href="add-vehicle" class="fcbtn btn btn-success btn-outline btn-1e pull-right">Add New <i class="fa fa-plus"></i></a>
    </div>
</div>
<div class="row">
    <div class="table-responsive">
        <table id="customer_table" class="table table-striped">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Make</th>
                    <th>Model</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                foreach ($data as $v) { ?>
                    <td><?= $v['type']; ?></td>
                    <td><?= $v['make']; ?></td>
                    <td><?= $v['model']; ?></td>
                    <td></td>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>