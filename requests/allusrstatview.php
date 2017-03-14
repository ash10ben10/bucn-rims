<?php

	if($getdata['status'] == 'pending'){
		?><div style="color:#DC7633;"><?php
		print "Pending for funds available.";
		?></div><?php
	}else if($getdata['status'] == 'funded'){
		?><div style="color:#117864;"><?php
		print "Funding Accomplished.";
		?></div><?php
	}else if($getdata['status'] == 'ordered'){
		include "delivery.php";
	}/* else if($getdata['status'] == 'Delivery Cancelled'){
		print "This purchase has been cancelled.";
	} */else if($getdata['status'] == 'Delivery Complete'){
		
		$getreqpo = "SELECT * FROM `request_items` WHERE po_id = '$getdata[po_id]'";
		$getreqpoinstat = "SELECT * FROM `request_items` WHERE po_id = '$getdata[po_id]' AND instat = 'Cancelled'";
		
		$datarrayone = mysql_num_rows(mysql_query($getreqpo));
		$datarraytwo = mysql_num_rows(mysql_query($getreqpoinstat));
		
		$subtractreqitemsfromcancellation = ($datarrayone - $datarraytwo);
		
		if($subtractreqitemsfromcancellation == 0){
			print "<div style='color:red;'>";
		print "Delivery is not successful.";
		/* print "<br/>";
		print "Request has been cancelled."; */
		print "</div>";
		}else if ($subtractreqitemsfromcancellation > 0){
			?><div style="color:#21618C;"><?php
			print "Ready for Acceptance.";
			print "<br/>";
			?></div><?php
		}
		/* $delivered = mysql_fetch_array(mysql_query("SELECT `delivery_complete` FROM requisition_status WHERE po_id = '$getdata[po_id]'"))or die(mysql_error());
		print date("M d, Y",strtotime($delivered['delivery_complete']))." at ".date("h:i a",strtotime($delivered['delivery_complete']));
		 */
	}/* else if($getdata['status'] == 'Inspection under Process'){
		?><div style="color:#943126;"><?php
		print "Inspection is under Process";
		?></div><?php
	}else if($getdata['status'] == 'Inspection Complete'){
		?><div style="color:#8E44AD;"><?php
		print "Inspection Complete";
		?></div><?php
	} */else if($getdata['status'] == 'Acceptance Complete'){
		?><div style="color:#3498DB;"><?php
		print "Acceptance Complete";
		?></div><?php
	}

?>