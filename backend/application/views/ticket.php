<link href="<?php echo base_url();?>bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="<?php echo base_url();?>plugins/bower_components/jquery/dist/jquery.min.js"></script>
<table style="width: 50%;" >
    <tr>
        <td style="width: 30%;">Garage Name : </td>
        <td style="width: 70%;text-align: left;"><?php echo $garage_row['name']; ?> <br></td>
    </tr>
    <tr>
        <td>Booking No: </td>
        <td><?php echo $booking_row['booking_no'];?> <br></td>
    </tr>
    <tr>
        <td>Service Date: </td>
        <td><?php echo date('d-m-Y H:i',  strtotime($booking_row['booking_date_time'])); ?> <br></td>
    </tr>
    <tr>
        <td>Delivery Date: </td>
        <td><?php echo date('d-m-Y H:i',  strtotime($booking_row['actual_delivery_time'])); ?> <br></td>
    </tr>    
</table>
<form class="form" name="ticket_form" id="ticket_form">
<input type="hidden" name="booking_id" value="<?php echo $_REQUEST['bk_id'];?>">
<input type="hidden" name="garage_id" value="<?php echo $garage_row['garage_id'];?>">
<table style="width: 50%;" >
    <tr>
            <td style="width: 30%;">Description </td>
            <td style="width: 70%;">
                <textarea type="text" class="form-control" name="description"></textarea><br>
            </td>
    </tr>
    <tr>
            <td style="width: 30%;"></td>
            <td style="width: 70%;">
                <input type="button" class="btn-sm btn btn-info" value="Submit" onclick="postTicket()">
            </td>
    </tr> 
</table>
</form>
<script>
    function postTicket() {
        $.ajax({
                method:'POST',
                url:'Transcation/InsertOperation',
                data: $('#ticket_form').serialize()+'&table_name=tbl_ticket',
                success:function(result) {
                    alert("Your Ticket has been created.");
                    window.close();
                }
            });
    }
</script>