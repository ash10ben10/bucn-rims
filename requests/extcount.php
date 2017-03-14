<?php

	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");
	$date = date("Y-m-d");
	$month = date("Y-m");
	
	include "../connect.php";
	
	$POid = $_POST['POid'];
	$extdel = $_POST['extdel'];
	$reason = $_POST['reason'];

	$getPOinfo = mysql_fetch_array(mysql_query("SELECT * FROM `purchase_order` WHERE `po_id` = '$POid'"))or die(mysql_error());
	$datevalue = date('Y-m-d H:i:s', strtotime("+".$extdel." day")); //days to extend from delivery term plus current date equals another delivery date from extension
	
	/* $fivep = ($getPOinfo['allitem_nums'])*10/11.2*0.05;
	$onep = ($getPOinfo['allitem_nums'])*10/11.2*0.01;
	$sump = ($fivep + $onep);
	$contract = ($getPOinfo['allitem_nums'] - $sump); */
	
	$penalty = ($getPOinfo['allitem_nums'])*($extdel)*1/10*0.01; //counting of penalty
	
	$updatepo = mysql_query("UPDATE `purchase_order` SET `delivery_term` = delivery_term + '$extdel', `delivery_date` = '".$datevalue."', ext_reason = '$reason', `ext_penalty` = '$penalty', `allitem_nums` = allitem_nums + '$penalty', `ext_delterm` = ext_delterm + '$extdel' WHERE po_id = '$POid'")or die(mysql_error());
	$updatereqstat = mysql_query("UPDATE `requisition_status` SET `status` = 'ordered' WHERE po_id = '$POid'")or die(mysql_error());
	
	print "<script>window.location='status.php';</script>";

?>