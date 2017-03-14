<?php

	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");
	$date = date("Y-m-d");
	$month = date("Y-m");
	
	include "../connect.php";
	
	$getpo_id = $_GET['id'];
	
	mysql_query("UPDATE `requisition_status` SET `status` = 'Delivery Complete', `delivery_complete` = '".$datetime."' WHERE po_id = '$getpo_id'")or die(mysql_error());
	print "<script>window.location='status.php';</script>";
	
?>