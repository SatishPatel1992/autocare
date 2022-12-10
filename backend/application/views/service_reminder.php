            <div class="row">
                <div class="col-lg-3">
                    <div class="form-group">
                        <label>Search by Due Date</label>
                        <input type="text" name="duration" class="form-control input-sm">
                    </div>
                </div>
                <div class="col-lg-1"><br>
                    <input type="button" style="margin-top:7px;" class="btn btn-sm btn-info" value="Filter" onclick="filter()">
                </div>
                <div class="col-lg-2"></div>
                <div class="col-lg-3">
                <div class="card border border-primary">
              <div class="card-block">
                <span><i class="fa fa-bell"></i> Total Reminder Sent : <span style="font-size:15px;font-weight:bold;"> <?php echo $total_reminder_sent ?> </span></span>
              </div>
            </div>
                </div>
                <div class="col-lg-3">
                <div class="card border border-primary">
              <div class="card-block">
                <span><i class="fa fa-car"></i> Vehicle Received : <span style="font-size:15px;font-weight:bold;"> <?php echo $total_vehicle_received; ?> </span> </span>
              </div>
            </div>
                </div>
            </div>
            <div class="row">
            <div class="col-lg-6">
<!-- Split dropright button -->
<div class="btn-group dropright">
  <button type="button" class="btn btn-info btn-sm" onclick="sendCommunication()">
   <i class="fa fa-send"> </i> Send
  </button>
  <button type="button" style="background-color:#00BCDD" class="btn btn-info btn-sm dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    <span class="sr-only">Toggle Dropright</span>
  </button>
  <div class="dropdown-menu" style="padding:5px;">
    <input checked type="checkbox" id="send_email"> Email &nbsp;&nbsp;&nbsp;&nbsp;
    <input checked type="checkbox" id="send_sms"> SMS
  </div>
</div>
            </div>
            <div class="col-lg-6">
            <div class="form-group has-search">
                <span class="fa fa-search form-control-feedback"></span>
                <input type="text" class="form-control" placeholder="Search Customer.." id="searchbox_manual">
            </div>
            </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-bordered" id="table_manual">
                    <thead>
                        <tr>
                            <th style="background-color: lavender;"><input type="checkbox"></th>
                            <th style="background-color: lavender;">Customer Name</th>
                            <th style="background-color: lavender;">Vehicle No.</th>
                            <th style="background-color: lavender;">Vehicle Name.</th>
                            <th style="background-color: lavender;">Mobile No.</th>
                            <th style="background-color: lavender;">Odometer</th>
                            <th style="background-color: lavender;">Due In</th>
                            <th style="background-color: lavender;">Last Service On</th>
                            <th style="background-color: lavender;">Service Due On</th>
                            <th style="background-color: lavender;">Reminder Send</th>
                            <th style="background-color: lavender;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($due_remiders as $k => $v) { ?>
                            <tr>
                                <td><input type="checkbox" class="commonClass" id="chk_<?php echo $v['serv_remider_id']?>"></td>
                                <td><?php echo $v['name']; ?></td>
                                <td><?php echo $v['reg_no']; ?></td>
                                <td><?php echo $v['make_name'].'<br>'.$v['model_name']; ?></td>
                                <td><?php echo $v['mobile_no']; ?></td>
                                <td><?php echo $v['odometer']; ?></td>
                                <td><?php echo $v['days']; ?></td>
                                <td><?php echo date('d-m-Y',strtotime($v['service_date'])); ?></td>
                                <td><?php echo date('d-m-Y',strtotime($v['reminder_date'])); ?></td>
                                <td><?php echo isset($reminder_count[$v['vehicle_id']]) ? $reminder_count[$v['vehicle_id']] : 0; ?></td>
                                <td><a target="__blank" href="job-view?job_id=<?php echo base64_encode($v['job_id']); ?>" class="btn btn-xs btn-info"><i class="fa fa-eye"></i></a></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot></tfoot>
                </table>
            </div>

<div id="view_email_sms" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
              <div class="modal-content" style="border-radius: 5px !important;">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                    <h4 class="modal-title" id="myModalLabel">EMAIL / SMS Sent to Customer </h4>
              </div>
              <div class="modal-body">
                        
              <div class="nav-tabs-horizontal">
    <ul class="nav nav-tabs customtab nav-tabs-line" role="tablist">
        <li role="presentation" class="nav-item active">
            <a href="#view_email_tab" role="tab" data-toggle="tab" class="nav-link active" aria-expanded="true"><span class="hidden-xs">Email</span></a>
        </li>
        <li role="presentation" class="nav-item" >
            <a href="#view_sms_tab" role="tab" data-toggle="tab" class="nav-link" aria-expanded="false"><span class="hidden-xs">SMS</span></a>
        </li>
    </ul><br>
    <div class="tab-content">
        <div class="tab-pane active" id="view_email_tab">
            <div id="email_body_html"></div>
        </div>
        <div class="tab-pane" id="view_sms_tab">
            <div id="sms_body_html"></div>
        </div>
    </div>
              </div>
          </div>
      </div>
</div>
<input type="hidden" id="date_range" value="<?php echo $_REQUEST['d']; ?>">
<script>
    function filter() {
        window.location.href= 'service-reminder?d='+$('input[name=duration]').val();
    }
    function sendCommunication() {
        var isAllowed = 'N';
        var servReminderIds = [];
        $('.commonClass').each(function(v) {
            if($(this).prop('checked')) {
                servReminderIds.push($(this).attr('id').split('_')[1]);
                isAllowed = 'Y';
            }
        });
        if(isAllowed == 'N') {
            toastr.error("Please select at-lease one customer to send Email / SMS.");
            return false; 
        } else if(!$('#send_email').prop('checked') && !$('#send_sms').prop('checked')) {
            toastr.error("Please select Email/SMS or Both to be send to selected customer.");
            return false; 
        }
        if(confirm('Are you sure want to send ?')) {
        $.ajax({
             method: 'POST',
             url: 'Transcation/InsertOperation',
             data: {'data': servReminderIds, 'is_email': $('#send_email').prop('checked'),'is_sms': $('#send_sms').prop('checked'),'table_name': 'tbl_service_reminder'},
             success: function(result) {

             }
        });
      }
    }
    $(document).ready(function() {
        var currentMonth = new Date().getMonth();
        var financialYearStartMonth = 3;
        var financialYearStartDate = moment().month(financialYearStartMonth).startOf('month');
        if (currentMonth < financialYearStartMonth) {
            financialYearStartDate = financialYearStartDate.subtract(1, 'year');
        }
        var datatable_manual = $('#table_manual').DataTable({
            bPaginate: false,
            bInfo: false
        });
        $("#searchbox_manual").keyup(function() {
            datatable_manual.fnFilter(this.value);
        });
        $('input[name=duration]').daterangepicker({
              locale: {
                   format: 'DD-MM-YYYY'
              },
              startDate: moment().subtract(10, 'days'),
              endDate: moment().add(15, 'days'),
              ranges: {
                   'Today': [moment(), moment()],
                   'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                   'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                   'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                   'This Month': [moment().startOf('month'), moment().endOf('month')],
                   'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
              },
        });
        <?php if(isset($_REQUEST['d']) && $_REQUEST['d'] != "") { ?>
            var dates = $('#date_range').val().split(" - ");
            $("input[name=duration]").data('daterangepicker').setStartDate(dates[0]);
            $("input[name=duration]").data('daterangepicker').setEndDate(dates[1]);
        <?php } ?>
    })
</script>
