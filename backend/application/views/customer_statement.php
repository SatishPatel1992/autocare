<div class="row">
        <div class="col-lg-3">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <select id="customer_id" class="form-control input-sm customer_list">
                <option value="*">Select</option>
                <?php foreach($customers as $k => $v) { ?>
                    <option value="<?php echo $v['customer_id']; ?>"><?php echo $v['name']; echo $v['mobile_no'] != "" ? ' - '.$v['mobile_no'] : ''; ?></option>
                <?php } ?>
                </select>
                <label class="floating-label">Select Customer</label>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                    <input type="text" name="duration" class="form-control input-sm customer_list">
                    <label class="floating-label">Duration</label>
            </div>
        </div>
    </div>
    <div class="row">
    <div class="col-lg-12">
    <table style="width: 100%;" class="datatable customFilter table table-hover table-bordered" id="customer_statement_table">
                <thead>
                    <tr>
                        <th style="background-color: lavender;text-align: left;">Date</th>
                        <th style="background-color: lavender;text-align: left;">Transaction Type</th>
                        <th style="background-color: lavender;text-align: left;">Transaction No.</th>
                        <th style="background-color: lavender;text-align: left;">Credit</th>
                        <th style="background-color: lavender;text-align: left;">Debit</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="5">No data founds</td>
                    </tr>
                </tbody>
            </table>
    </div>
    </div>
    <script>
    $(document).ready(function() {
        var currentMonth = new Date().getMonth();
        var financialYearStartMonth = 3;
        var financialYearStartDate = moment().month(financialYearStartMonth).startOf('month');
        if (currentMonth < financialYearStartMonth) {
            financialYearStartDate = financialYearStartDate.subtract(1, 'year');
        }
        $('input[name=duration]').daterangepicker({
            locale: {
                format: 'DD-MM-YYYY'
            },
            ranges: {
                "All Time": [],
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'This Financial Year': [moment(financialYearStartDate), moment()]
            },
            startDate: moment(financialYearStartDate),
            endDate: moment()
        });
    });
    $(document).on('change','.customer_list',function() {
        var customer_id = $('#customer_id').val();
        var duration = $('input[name=duration]').val();
        if(customer_id != "*") {
                $.ajax({
                    method: 'POST',
                    url: 'common/commonFunc',
                    data: {
                        'do': 'get_customer_statement',
                        'customer_id': customer_id,
                        'duration': duration
                    },
                    success: function(result) {
                        var statement = JSON.parse(result);

                        if(statement && statement['data'] && statement['data']['transactions']) {
                            var opening_balance = parseFloat(statement['data']['opening_debit']) - parseFloat(statement['data']['opening_credit']) + parseFloat(statement['data']['fix_opening_balance']);
                            var rows = '<tr style="background-color:linen;">';
                                rows += '<td></td>';
                                rows += '<td style="text-align:right;font-weight:bold;">Opening Balance</td>';
                                rows += '<td></td>';
                                rows += '<td style="font-weight:bold;">';
                                if(opening_balance < 0) {
                                    rows += '<i class="fa fa-rupee"></i> '+Math.abs(opening_balance);
                                }
                                rows += '</td>';
                                rows += '<td style="font-weight:bold;">';
                                if(opening_balance > 0) {
                                    rows += '<i class="fa fa-rupee"></i> '+Math.abs(opening_balance);
                                }
                                rows += '</td>';
                                rows += '</tr>';
                            var total_closing_credit = 0;
                            var total_closing_debit = 0;
                            statement['data']['transactions'].forEach((element) => {
                                var transcation_type = element['transaction_type'].replace(/_/g, ' ').replace(/(?: |\b)(\w)/g, function(key) { return key.toUpperCase()});
                                rows += '<tr>';
                                rows += '<td>'+element['trans_date']+'</td>';
                                rows += '<td>'+transcation_type+'</td>';
                                rows += '<td>'+element['transaction_id']+'</td>';
                                rows += '<td>';
                                if(element['transaction_type'] == 'customer_invoice') {
                                    rows += '<i class="fa fa-rupee"></i> '+element['amount'];
                                    total_closing_credit += parseFloat(element['amount']);
                                }
                                rows += '</td>';
                                rows += '<td>';
                                if(element['transaction_type'] == 'customer_payment') {
                                    rows += '<i class="fa fa-rupee"></i> '+element['amount'];
                                    total_closing_debit += parseFloat(element['amount']);
                                }
                                rows += '</td>';
                                rows += '</tr>';
                            });
                                var closing_balance = parseFloat(total_closing_debit) - parseFloat(total_closing_credit) + parseFloat(opening_balance);
                                rows += '<tr style="background-color:linen;">';
                                rows += '<td></td>';
                                rows += '<td style="text-align:right;font-weight:bold;">Closing Balance</td>';
                                rows += '<td></td>';
                                rows += '<td style="font-weight:bold;">';
                                if(closing_balance < 0) {
                                    rows += '<i class="fa fa-rupee"></i> '+Math.abs(closing_balance);
                                }
                                rows += '</td>';
                                rows += '<td style="font-weight:bold;">';
                                if(closing_balance > 0) {
                                    rows += '<i class="fa fa-rupee"></i> '+Math.abs(closing_balance);
                                }
                                rows += '</td>';
                                rows += '</tr>';
                            $('#customer_statement_table tbody').html(rows);
                        }
                    }
                });
        } else {

        }
    });
    </script>