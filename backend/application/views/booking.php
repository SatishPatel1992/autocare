<?php
$GLOBALS['title_left'] = '<a href="javascript:void(0);" onclick="create_new_jobcard()" class="btn btn-sm btn-info btn-outline btn-1e pull-right"><i class="fa fa-plus"></i> Create Jobcard</a>';
?>
<div class="row">
    <div class="col-xs-4 col-lg-4 col-sm-4">
        <div class="form-group has-search">
            <span class="fa fa-search form-control-feedback"></span>
            <input type="text" class="form-control" placeholder="Search Jobcard..." id="searchbox">
        </div>
    </div>
    <div class="col-lg-8" style="text-align:right;font-size:15px;">
        <span> Total Sale : <i class="fa fa-rupee"></i> <span style="font-weight:bold;" id="total_sale">0</span> |  Received : <i class="fa fa-rupee"></i> <span style="font-weight:bold;" id="total_received"></span> | Balance : <i class="fa fa-rupee"></i> <span style="font-weight:bold;" id="total_balance"></span></span>
    </div>
</div>
<div class="row">
    <div class="col-xs-12 col-lg-12 col-sm-12">
    <div class="table-responsive">
        <table id="jobcard_table" class="table table-hover dataTable table-bordered">
            <thead>
                <tr>
                    <th style="width: 10%;font-weight: bold;background-color: lavender;">Job/Invoice No</th>
                    <th style="text-align:center;width: 12%;font-weight: bold;background-color: lavender;">Status</th>
                    <th style="width: 10%;font-weight: bold;background-color: lavender;">Date</th>
                    <th style="width: 12%;font-weight: bold;background-color: lavender;">Customer Name</th>
                    <th style="width: 20%;font-weight: bold;background-color: lavender;">Vehicle</th>
                    <th style="width: 10%;font-weight: bold;background-color: lavender;">Contact No.</th>
                    <th style="width: 10%;font-weight: bold;background-color: lavender;">Reg No.</th>
                    <th style="width: 8%;text-align:center;font-weight: bold;background-color: lavender;">Amount</th>
                    <th style="width: 8%;text-align:center;font-weight: bold;background-color: lavender;">UnPaid</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $total_sale = 0;
                    $total_received = 0;
                    $total_due = 0;
                    
                    foreach($jobcards as $k=>$v) { 
                        $total_sale += $v['status'] != 'estimate_created' ? $v['grand_total'] : 0;
                        $total_received += $v['total_paid'] != "" ? $v['total_paid'] : 0;
                    ?>
                    <tr style="cursor:pointer;" onclick="edit_jobcard('<?php echo base64_encode($v['jobcard_id']); ?>')">
                        <td><?php echo $v['jobcard_no'].' / '.($v['invoice_no'] != "" ? $v['invoice_no'] : '-'); ?></td>
                        <td style="text-align:center;"><?php 
                            if($v['status'] == 'close') {
                                echo '<span class="badge badge-success">Close</span>';
                            } else if($v['status'] == 'payment_due') {
                                echo '<span class="badge badge-danger">Payment due</span>';
                            } else if($v['status'] == 'estimate_created') {
                                echo '<span class="badge badge-info">Estimate</span>';
                            } else if($v['status'] == 'partial_paid') {
                                echo '<span class="badge badge-primary">Partial Paid</span>';
                            } else if($v['status'] == 'invoice') {
                                echo '<span class="badge badge-warning">Invoice</span>';
                            }
                        ?></td>
                        <td><?php echo date('d-m-Y',strtotime($v['date'])); ?></td>
                        <td><?php echo strlen($v['name']) > 25 ? substr($v['name'],0,25).'...' : $v['name'];?></td>
                        <td><?php echo $v['make_name'].' '.$v['model_name']; ?></td>
                        <td><?php echo $v['mobile_no']; ?></td>
                        <td><?php echo $v['reg_no']; ?></td>
                        <td style="text-align:center;"><?php echo '<i class="fa fa-rupee"></i> '.$v['grand_total']; ?></td>
                        <td style="text-align:center;"><?php echo $v['status'] != 'estimate_created' && ($v['grand_total'] - $v['total_paid']) != 0 ? '<i class="fa fa-rupee"></i> '. ($v['grand_total'] - $v['total_paid']) : '-'; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    </div>
</div>
<div id='calendar'></div>
<div class="calendar-container">
   <div id="calendar"></div>
</div>
<style>
    .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
        padding: 6px;
    }
    .badge {
        width:80%;
    }
</style>
<script>
    $(document).ready(function () {
        var dataTable = $('#jobcard_table').dataTable({
            bPaginate:false,
            bInfo:false,
            bSort:false
        });
        $("#searchbox").keyup(function() {
            console.log(dataTable);
            dataTable.fnFilter(this.value);
        });
        $('#total_sale').text('<?php echo $total_sale; ?>');
        $('#total_received').text('<?php echo $total_received != "" ? $total_received : 0; ?>');
        $('#total_balance').text('<?php echo $total_sale - $total_received; ?>');
        // $('#calendar').fullCalendar({
        //     header: {
        //         left: 'prev,next today',
        //         center: 'title',
        //         right: 'month,agendaWeek,agendaDay'
        //     },
        //     defaultView: 'month',
        //     selectable: true,
        //     editable: true,
        //     eventLimit: false,
        //     eventClick: function(calEvent, jsEvent, view) {
        //         var jobcard_id = calEvent.jobcard_id;
        //         window.location = "job-view?job_id="+jobcard_id;
        //     },
        //     showNonCurrentDates: false,
        //     events: 'booking/GetBookings',
        //     allDaySlot: false,
        //     eventAfterAllRender: function(view) {
        //         if(view.name == 'month') {
        //             $('.fc-day-top').each(function(r,v) {
        //                 var date = $(this).data('date');
        //                 if(date != ""  && date != undefined) {
        //                     $(this).append('<a class="add_event_label" data-date="'+date+'" style="position:absolute;margin-left:5px;display: block;cursor:pointer;"><i class="fa fa-plus"></i></a>');
        //                 }
        //             });
        //             // $('[data-toggle="tooltip"]').tooltip();
        //         }
        //     }
        // });
        // $('#invoice_table').DataTable({
        //     "bLengthChange": false
        // });
    });
    function create_new_jobcard() {
        var date = new Date().toJSON().slice(0,10);
        var encrypted = CryptoJS.AES.encrypt(date, "Secret");
        window.location.href = "job-view?vdtx="+encrypted;
    }
    function edit_jobcard(job_id) {
        window.location.href = "job-view?job_id="+job_id;
    }
    $(document).on('click','.add_event_label', function(e) {
        e.preventDefault();
        var date = $(this).data('date');
        console.log(date);
        var encrypted = CryptoJS.AES.encrypt(date, "Secret");
        // Encrypt
        // var ciphertext = CryptoJS.AES.encrypt(date, 'autosecret');
       // window.location = "job-view?vdtx="+encrypted;
    });
</script>
