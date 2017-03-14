<?php

	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");
	$date = date("Y-m-d");
	$month = date("Y-m");
	
	include "../connect.php";
	
	$getpo_id = $_GET['id'];

	$getdelterm = mysql_fetch_array(mysql_query("SELECT `delivery_term` FROM `purchase_order` WHERE `po_id` = '$getpo_id'"));
	$datevalue = date('Y-m-d H:i:s', strtotime("+".$getdelterm['delivery_term']." day")); //days from delivery term plus current date equals delivery date
	
	$updatepo = mysql_query("UPDATE `purchase_order` SET `delivery_date` = '".$datevalue."', `orig_deliverydate` = '".$datevalue."' WHERE po_id = '$getpo_id'");
	$updatereqstat = mysql_query("UPDATE `requisition_status` SET `status` = 'ordered' WHERE po_id = '$getpo_id'");
	
	print "<script>window.location='status.php';</script>";

?>