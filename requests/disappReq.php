<?php

	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$disappdate = date("Y-m-d");

	require "../connect.php";

	$ReqNo = $_POST['ReqNo'];
	$Remarks = $_POST['ReqRemarks'];
	
	mysql_query("SET AUTOCOMMIT=0");
	mysql_query("START TRANSACTION");
	
	mysql_query("LOCK TABLE request_items WRITE;");
	
	try{
		mysql_query("UPDATE `request_items` SET `remarks` = '$Remarks', `pr_status` = 'disapproved', prstat_date = '$disappdate' WHERE req_item_id = '$ReqNo' ") or die(mysql_error());
		mysql_query("COMMIT");
	}catch(Exception $e){
		mysql_query("ROLLBACK");
		print "<script>alert('Something went wrong when disapproving the request. Plase check your connection.')</script>";
	}
	mysql_query("UNLOCK TABLE;");

?>