<?php
$GLOBALS['title_left'] = '<a href="campaign" class="btn btn-sm btn-info btn-outline btn-1e pull-right">Back <i class="ti-back-left"></i></a>';
?>
<form class="form-material form" id="add_part" name="add_part" method="post">
    <input type="hidden" name="part_id" value="<?php echo $_REQUEST['id']; ?>">
    <div class="row">
        <div class="col-lg-4 col-md-4">
            <div class="form-group form-material floating" data-plugin="formMaterial">
                <input type="text" class="form-control input-sm" name="name">
                <label class="floating-label">Campaign Name</label>
            </div>
        </div>
    </div>
    <div class="panel panel-success">
        <div class="panel-heading" style="background-color: lavender;color: black;padding: 5px;">
            <span>Contacts</span>
        </div>
        <div class="panel-body" style="border: 1px solid lavender;">
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    <div class="radio radio-success">
                        <input type="radio" name="contact_type" id="ManualContact" value="ManualContact">
                        <label for="ManualContact"> Select From Contacts</label>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="radio radio-success">
                        <input type="radio" name="contact_type" id="ExistingContact" value="ExistingCampaign">
                        <label for="ExistingContact">Copy From Existing Campaign's</label>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3">
                    <div class="radio radio-success">
                        <input type="radio" name="contact_type" id="Criteria" value="Criteria">
                        <label for="Criteria">Criteria</label>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-4">
                    <select class="singleSelect1">
                        <option>Select Contact</option>
                        <option value="3">Satish Patel</option>
                        <option value="2">Satish</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="panel panel-success">
        <div class="panel-heading" style="background-color: lavender;color: black;padding: 5px;">
            <span>Email Template</span>
        </div>
        <div class="panel-body" style="border: 1px solid lavender;">
        <div class="row">
        <div class="col-lg-4 col-md-4">
            <select class="singleSelect1">
                <option>Select Template</option>
            </select>
        </div>
        </div><br>
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <textarea type="text" class="form-control input-sm body" rows="4" id="email_body"></textarea>
            </div>
        </div>
        </div>
    </div>
    <div class="panel panel-success">
        <div class="panel-heading" style="background-color: lavender;color: black;padding: 5px;">
            <span>SMS Template</span>
        </div>
        <div class="panel-body" style="border: 1px solid lavender;">
            <div class="row">
            <div class="col-lg-4 col-md-4">
                <select class="singleSelect1">
                    <option>Select Template</option>
                </select>
            </div>
            </div><br>
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <textarea type="text" class="form-control input-sm body" rows="4" id="sms_body"></textarea>
                </div>
            </div>
            </div>
    </div>
    <div class="panel panel-success">
        <div class="panel-heading" style="background-color: lavender;color: black;padding: 5px;">
            <span>Schedule</span>
        </div>
        <div class="panel-body" style="border: 1px solid lavender;">
            
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <a href="campaign" class="btn btn-sm btn-info btn-outline btn-1e pull-right">Save <i class="ti-back-left"></i></a>
        </div>
    </div>
</form>
<div id="add_trans_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 5px !important;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title" id="myModalLabel"></h4>
            </div>
            <div class="modal-body">
                
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() { 
        $('.body').froalaEditor({
            toolbarButtons: ['bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', '|', 'fontFamily', 'fontSize', 'color', 'inlineStyle', 'inlineClass', 'clearFormatting', '|', 'emoticons', 'fontAwesome', 'specialCharacters', '-', 'paragraphFormat', 'lineHeight', 'paragraphStyle', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', '|', 'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', '-', 'insertHR', 'selectAll', 'getPDF', 'print','fullscreen', '|', 'undo', 'redo','custom_dropdown']
            });
    });
</script>