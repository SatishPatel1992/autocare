/*jslint browser: true*/
/*global $, jQuery, alert*/
$(function() {
    $("#submit_data").click(function( e ) {
        var fields = $( ":input" ).serializeArray();
        $( "#results" ).empty().append( JSON.stringify( fields , null, "\t") );
    });
    $('[data-toggle="tooltip"]').tooltip()
});
var singleSelect1;
var singleSelect2;
$(document).ready(function () {
    $('select:not(.no-select)').each(function () {
        $(this).select2({
            dropdownParent: $(this).parent()
        });
    });
    $('.select2-selection__rendered').hover(function () {
        $(this).removeAttr('title');
    });
        // $("select:not(.no-select)").select2({});
        var table = $('.datatable').dataTable({
            "bInfo" : false,
            "bLengthChange": false,
            "bFilter": true,
            "bPaginate": false,
            "bSearchable":true,
            "bFilter": true,
            "sDom": '<"top"fip>',
        });
        if($('.datatable').hasClass('customFilter')) {
            $("#searchbox").keyup(function() {
                table.fnFilter(this.value);
            });
        }
        singleSelect1 = tail.select(".singleSelect1", {
                        search: true,
                        searchMarked: true,
                        searchFocus: true,
                        multiSelectAll:true
        });
        singleSelect2 = tail.select(".singleSelect2", {
                        search: true,
                        searchMarked: true,
                        searchFocus: true,
                        multiSelectAll:true
        });
        
        singleSelect3 = tail.select(".singleSelect3", {
                        search: true,
                        searchMarked: true,
                        searchFocus: true,
                        multiSelectAll:true
        });
        
        singleSelect4 = tail.select(".singleSelect4", {
                        search: true,
                        searchMarked: true,
                        searchFocus: true,
                        multiSelectAll:true
        });
        
        singleSelect5 = tail.select(".singleSelect5", {
                        search: true,
                        searchMarked: true,
                        searchFocus: true,
                        multiSelectAll:true
        });
        
        singleSelect6 = tail.select(".singleSelect6", {
                        search: true,
                        searchMarked: true,
                        searchFocus: true,
                        multiSelectAll:true
        });
        
        multiSelect = tail.select(".multiSelect", {
                        search: true,
                        searchMarked: true,
                        searchFocus: true,
                        multiSelectAll:true,
                        multiSelectGroup:true
        });
        $('#date').datepicker({
           format:'dd-mm-yyyy',
           autoclose:true
       });
       $('.date').datepicker({
           format:'dd-mm-yyyy',
           autoclose:true
       });
       $('.js-switch').each(function() {
           new Switchery($(this)[0], $(this).data());
       });
});
function getsingleSelect1Ref() {
            return singleSelect1;
       }
function check_duplication(table_name,field_id,elem,item_field,item_id,message) {
    if(elem.value != "") {
        $.ajax({
            type: 'POST',
            url: 'Transcation/CheckDuplication',
            data: {'table_name':table_name,'field_id':field_id,'field_value':elem.value,'item_field':item_field,'item_value':item_id},
            success: function(result) {
                if($.trim(result) > 0) {
                    toastr.error(message,'');
                    $(elem).val('');
                    $(elem).removeClass('dirty');
                    $(elem).addClass('empty');
                    return false;
                }
            }
        });
    }
}
function getUrlQueryString()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}
function insert_data(table_name,form,redirect) {
        //if(jQuery('#' + form.id).valid()) {
        jQuery.ajax({
            type: 'POST',
            url: 'Transcation/InsertOperation',
            data: jQuery('#' + form.id).serialize()+'&table_name='+table_name,
            success: function(result) {
                var res = JSON.parse(result);
                if(res != undefined && res.status == '200') {
                    toastr.success(res.message, '');
                } else {
                    toastr.error(res.message, '');
                }
                if(redirect != "") {
                    setTimeout(function() {
                        window.location = redirect;
                    },1000);
                }
            }
        });
    //}
}
function update_data(table_name, cond_field, cond_value, redirect, form) {
    //alert(table_name);
    //if ($('#' + form.id).valid()) {
        $.ajax({
            type: 'POST',
            url: 'Transcation/UpdateOperation',
            data: jQuery('#' + form.id).serialize() +"&table_name=" + table_name + "&cond_field=" + cond_field + "&cond_value=" + cond_value,
            success: function(result) {
                result = JSON.parse(result);
                result.status == '200' ? toastr.success(result.message, '') : toastr.error(result.message, '');

                if(redirect != "") {
                    setTimeout(function() {
                        window.location = redirect;
                    },1000);
                }
            }
        });
    //}
}
