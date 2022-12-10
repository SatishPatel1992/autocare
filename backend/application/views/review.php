<div class="form-group has-search">
    <span class="fa fa-search form-control-feedback"></span>
    <input type="text" class="form-control" placeholder="Search feedback..." id="searchbox">
</div>
<div class="row">
    <div class="col-xs-12 col-lg-12 col-sm-12">
    <div class="table-responsive">
        <table id="customer_table" class="table table-hover dataTable table-bordered">
            <thead>
                <tr>
                    <th style="width: 5%;font-weight: bold;background-color: lavender;">Sr.No</th>
                    <th style="width: 20%;font-weight: bold;background-color: lavender;">Customer Name</th>
                    <th style="width: 20%;font-weight: bold;background-color: lavender;">Vehicle No.</th>
                    <th style="width: 20%;font-weight: bold;background-color: lavender;">Jobcard No.</th>
                    <th style="width: 20%;font-weight: bold;background-color: lavender;">Review Date</th>
                    <th style="width: 30%;font-weight: bold;background-color: lavender;">Rating</th>
                    <th style="width: 10%;font-weight: bold;background-color: lavender;text-align: center;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                     $srno = 1;
                     foreach($feedbacks as $k=>$v) { ?>
                    <tr>
                        <td><?php echo $srno;?></td>
                        <td><?php echo $v['first_name'].' '.$v['last_name'];?></td>
                        <td><?php echo $v['reg_no'];?></td>
                        <td><?php echo $v['jobcard_no'];?></td>
                        <td><?php echo date('d-m-Y H:i:s',strtotime($v['created_date'])); ?></td>
                        <td><?php echo $v['rating'];?></td>
                        <td style="text-align: center;">
                            <div class="btn-group">
                                <button class="btn btn-xs btn-primary" title="Edit" data-toggle="tooltip" onclick="getReviewDetails(<?php echo $v['receive_id']?>)"><i class="fa fa-edit"></i></button>
                                <a class="btn btn-xs btn-danger" target="__blank" href="job-view?job_id=<?php echo base64_encode($v['jobcard_id']); ?>" title="view jobcard" data-toggle="tooltip"><i class="fa fa-trash-o"></i></a>
                            </div>
                        </td>
                    </tr>
                <?php $srno++; } ?>
            </tbody>
        </table>
    </div>
    </div>
</div>
<div id="review_detail_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content" style="border-radius: 5px !important;">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
                <h4 class="modal-title" id="myModalLabel">Feedback received</h4>
            </div>
            <div class="modal-body">
            <div class="survey-body-wrapper">
        <div>
            <table width="100%">
                <tr>
                    <td colspan="2">
                            <fieldset id="QuestionSection_2072" class="Q1 survey-question-wrapper  has-separator">
                           <div class="answer-container matrix-multipoint-question has-mobile-on">
                            <div class="table-wrapper table-responsive">
                            <table class="parent-table" id="feed_table">
                            <thead>
                            <tr>
                            <td width="30%"></td>
                            <td width="11%"><div class="answer-options"><div class="controls">
                            <span class="control-label">Poor</span></div></div></td>
                            <td width="11%">
                            <div class="answer-options"><div class="controls">
                            <span class="control-label">Fair</span></div></div>
                            </td>
                            <td width="11%">
                            <div class="answer-options"><div class="controls">
                            <span class="control-label">Good</span></div></div>
                            </td>
                            <td width="11%">
                            <div class="answer-options"><div class="controls">
                            <span class="control-label">Very Good</span></div></div>
                            </td>
                            <td width="11%">
                            <div class="answer-options"><div class="controls">
                            <span class="control-label">Excellent</span></div></div>
                            </td>
                            <td width="11%"><div class="answer-options"><div class="controls">
                            <span class="control-label">N/A</span></div></div>
                            </td>
                            </tr>
                            </thead>
                            <tbody>
                            
                            </tbody>
                            </table>
                            </div>
                            </div>
                            </fieldset>
                    </td>
                </tr>
            </table>
            <div class="row">
            <div class="col-lg-6">
            <div class="stars starrr" data-rating="0"></div>
            <input type="hidden" name="ratings" id="ratings-hidden">
            <textarea cols="5" rows="5" name="overall_feedback" placeholder="Overall Feedback" class="form-control input-sm"></textarea><br>
            </button>
            </div>
            </div>
        </div>
    </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        var dataTable = $('#customer_table').dataTable({
            bPaginate:false,
            bInfo:false,
            aaSorting: [0, 'asc'],
            aoColumnDefs: [
                {"bSortable": false, "aTargets": [0]}
            ],
            fnDrawCallback : function(oSettings) {
                if (oSettings.bSorted || oSettings.bFiltered)
                {
                    for (var i = 0, iLen = oSettings.aiDisplay.length; i < iLen; i++)
                    {
                        $('td:eq(0)', oSettings.aoData[ oSettings.aiDisplay[i] ].nTr).html(i + 1);
                    }
                }
            }
        });
        $("#searchbox").keyup(function() {
            console.log(dataTable);
            dataTable.fnFilter(this.value);
        });
    });
    function getReviewDetails(id) {
        $.ajax({
                method:'POST',
                url:'review/getReviewById',
                data: {'id': id},
                success:function(result) {
                    $('#review_detail_modal').modal('show');
                    var html = '';
                    if(result && result['feedback']) {
                    $.each(result['feedback'], function(i,v) {
                        html += '<tr class=" this-height">';
                        html += '<td class="this-accordion">';
                        html += '<div class="answer-options rotate">';
                        html += '<div class="controls">';
                        html += '<div class="control-label">';
                        html += '<span id="question-text-span">'+v.question+'</span>';
                        html += '</div>';
                        html += '</div>';
                        html += '</div>';
                        html += '</td>';

                        html += '<td>';
                        html += '<label class="controls control-selection">';
                        html += '<input type="radio" name="answer_'+v.ans_id+'" value="1" class="radio-check">';
                        html += '<span class="qp-icomoon-icons control-indicator"></span>';
                        html += '<span class="blinker"></span>';
                        html += '<span class="control-label">Poor</span>';
                        html += '</label>';
                        html += '</td>';

                        html += '<td>';
                        html += '<label class="controls control-selection">';
                        html += '<input disabled type="radio" name="answer_'+v.ans_id+'" checked class="radio-check">';
                        html += '<span class="qp-icomoon-icons control-indicator"></span>';
                        html += '<span class="blinker"></span><span class="control-label">Fair</span>';
                        html += '</label>';
                        html += '</td>';

                        html += '<td>';
                        html += '<label class="controls control-selection">';
                        html += '<input disabled type="radio" name="answer_'+v.ans_id+'" value="3" class="radio-check">';
                        html += '<span class="qp-icomoon-icons control-indicator"></span>';
                        html += '<span class="blinker"></span><span class="control-label">Good</span>';
                        html += '</label>';
                        html += '</td>';
                            
                        html += '<td>';
                        html += '<label class="controls control-selection">';
                        html += '<input disabled type="radio" name="answer_'+v.ans_id+'" value="4" class="radio-check">';
                        html += '<span class="qp-icomoon-icons control-indicator"></span>';
                        html += '<span class="blinker"></span><span class="control-label">Very Good</span>';
                        html += '</label>';
                        html += '</td>';
                        
                        html += '<td>';
                        html += '<label class="controls control-selection">';
                        html += '<input disabled type="radio" name="answer_'+v.ans_id+'" value="5" class="radio-check">';
                        html += '<span class="qp-icomoon-icons control-indicator"></span>';
                        html += '<span class="blinker"></span>';
                        html += '<span class="control-label">Excellent</span>';
                        html += '</label>';
                        html += '</td>';
                            
                        html += '<td>';
                        html += '<label class="controls control-selection">';
                        html += '<input disabled type="radio" name="answer_'+v.ans_id+'" value="0" class="radio-check">';
                        html += '<span class="qp-icomoon-icons control-indicator"></span>';
                        html += '<span class="blinker"></span><span class="control-label">N/A</span>';
                        html += '<div class="control-label matrix-anchor hidden"></div>';
                        html += '</label>';
                        html += '</td>';
                        html += '</tr>';
                        });
                        $('#feed_table tbody').html(html);
                        $.each(result['feedback'], function(i,v) {
                            $('input[name=answer_'+v.ans_id+'][value='+v.answer+']').prop('checked', true);
                            $('textarea[name=overall_feedback]').text(v.feedback);
                        });
                   }
                
                }
            });
    }
</script>