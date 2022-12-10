<?php
$GLOBALS['title_left'] = '<a href="vendor" class="btn btn-sm btn-info btn-outline btn-1e pull-right"><i class="fa fa-arrow-left"></i> Back</a>';
?>
<input type="hidden" id="inv_dates" value="<?php echo $invoice_dates['st_date'] . '_' . $invoice_dates['ed_date']; ?>">
<input type="hidden" id="vd_id" value="<?php echo $_REQUEST['id']; ?>">
        <form class="form-material form" id="add_vendor_form" method="post">
            <input type="hidden" name="vendor_id" value="<?php echo base64_decode($_REQUEST['id']); ?>">
            <div class="row">
                <div class="col-lg-3">
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                        <input type="text" class="form-control input-sm" name="company_name" id="company_name" autocomplete="off" value="<?php if (isset($vendor['company_name'])) {
                                                                                                                            echo $vendor['company_name'];
                                                                                                                        } ?>">
                        <label class="floating-label required">Vendor Name</label>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                        <input type="text" class="form-control input-sm" name="mobile_no" autocomplete="off" value="<?php if (isset($vendor['mobile_no'])) {
                                                                                                                            echo $vendor['mobile_no'];
                                                                                                                        } ?>">
                        <label class="floating-label">Mobile No.</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                        <input type="text" class="form-control input-sm" name="email" autocomplete="off" value="<?php if (isset($vendor['email'])) {
                                                                                                                            echo $vendor['email'];
                                                                                                                        } ?>">
                        <label class="floating-label">Email</label>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                        <input type="text" class="form-control input-sm" name="opening_balance" autocomplete="off" value="<?php if (isset($vendor['opening_balance'])) {
                                                                                                                        echo $vendor['opening_balance'];
                                                                                                                    } ?>">
                        <label class="floating-label">Opening Balance</label>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                        <input type="text" class="form-control input-sm" name="billing_address" autocomplete="off" value="<?php if (isset($vendor['billing_address'])) {
                                                                                                                        echo $vendor['billing_address'];
                                                                                                                    } ?>">
                        <label class="floating-label">Billing Address</label>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="form-group form-material floating" data-plugin="formMaterial">
                        <input type="text" class="form-control input-sm" name="gst_no" autocomplete="off" value="<?php if (isset($vendor['gst_no'])) {
                                                                                                                        echo $vendor['gst_no'];
                                                                                                                    } ?>">
                        <label class="floating-label">GSTIN</label>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group form-material floating" data-plugin="formMaterial" style="display:inline-flex;width:inherit;">
                        <input type="text" class="form-control input-sm" name="credit_period" autocomplete="off" maxlength="3" value="<?php if (isset($vendor['credit_period'])) {
                                                                                                                            echo $vendor['credit_period'];
                                                                                                                        } else { echo 30; } ?>">
                        <label class="floating-label">Credit Period</label>
                        <div class="input-group" style="width:auto;">
                        <div class="bs-dropdown-to-select-group">
                            <button type="button" class="btn btn-default">
                                <span data-bind="bs-drp-sel-label">Days</span>
                            </button>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" style="text-align: right;">
                <div class="col-md-12">
                    <button type="button" onclick="save_vendor()" class="btn btn-sm btn-success btn-outline btn-1e"> <?php if (!isset($_REQUEST['id'])) { ?> Save
                        <?php } else { ?> Update <?php } ?><i class="fa fa-save"></i> </button>
                </div>
            </div>
        </form>

<style>
    .ui-autocomplete {
        z-index: 2150000000;
    }

    #part_list_tbl .form-group,
    #part_details_table .form-group,
    #part_list_tbl .form-group {
        margin-bottom: 0px;
    }

    #part_list_tbl tbody tr td input:not([name='part_desc[]']) {
        text-align: center;
    }

    #invoice_part_list_tbl tbody tr td input:not([name='part_desc[]']) {
        text-align: center;
    }
    #vendor_payment_form .form-group, #add_payment_modal .form-group {
        margin-bottom: 2px;
    }
</style>
<script>
function save_vendor() {
        if($('#company_name').val() == "") {
            toastr.warning("Vendor/Company name is required !");
            $('#company_name').focus();
            return false;
        }
        $.ajax({
            type: 'POST',
            url: 'Transcation/InsertOperation',
            data: {
                'data': jQuery('#add_vendor_form').serialize(),
                'vendor_id': $('#vendor_id').val(),
                'table_name': 'tbl_vendor'
            },
            success: function(result) {
                var res = JSON.parse(result);
                if (res != undefined && res.status == '200') {
                    toastr.success(res.message, '');
                } else {
                    toastr.error(res.message, '');
                }
                setTimeout(function() {
                    window.location = 'vendor';
                }, 500);
            }
        });
    }
    var intial_load = 0;
    var oTable;
    $(document).ready(function() {
        var inv_dates = $('#inv_dates').val().split('_');
        $('input[name=duration]').daterangepicker({
            locale: {
                format: 'DD-MM-YYYY'
            },
            startDate: inv_dates[0],
            endDate: inv_dates[1],
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
        });

        $('input[name=duration]').on('change', function(e) {
            var selectedDate = this.value;
            var vendor_id = $("#vd_id").val();
            window.location.href = 'add-vendor?id=' + vendor_id + '&dts=' + selectedDate;
        });
    });    
</script>
