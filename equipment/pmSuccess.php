<?php

	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");
	$date = date("Y-m-d");
	$month = date("Y-m");

	require "../connect.php";
	
	$pmItemId = $_POST['pmItemId'];
	$pmFindings = $_POST['pmFindings'];
	$pmId = $_POST['pmId'];
	
	mysql_query("SET AUTOCOMMIT=0");
	mysql_query("START TRANSACTION");
	
	mysql_query("LOCK TABLE eqp_pm_items WRITE;");
	try{
		mysql_query("UPDATE `eqp_pm_items` SET `findings` = '$pmFindings', `status` = 'Repaired' WHERE pmitems_id = '$pmItemId'");
		mysql_query("COMMIT");
	}catch(Exception $e){
		mysql_query("ROLLBACK");
		print "<script>alert('Something went wrong when updating the status of the maintenance. Plase check your connection.')</script>";
	}
	mysql_query("UNLOCK TABLE;");
	
	$geteqp = mysql_fetch_array(mysql_query("SELECT `eqp_id` FROM `eqp_pm_items` WHERE `pmitems_id` = '$pmItemId'"));
	$updateeqp = mysql_query("UPDATE `equipments` SET `remarks`='Working' WHERE `eqp_id`='$geteqp[eqp_id]'");
	$geteqps = mysql_fetch_array(mysql_query("SELECT * FROM equipments WHERE eqp_id = '$geteqp[eqp_id]'"));
	
	mysql_query("LOCK TABLE eqp_history WRITE;");
	try{
		mysql_query("INSERT INTO `eqp_history`(
		`eqp_id`, 
		`receivedBy`, 
		`historydate`, 
		`icspar`, 
		`icspar_id`, 
		`remarks`
		) VALUES (
		'$geteqps[eqp_id]',
		'$geteqps[received_by]',
		'$date',
		'PM',
		'$pmId',
		'Repair Successful'
		)");
		mysql_query("COMMIT");
	}catch(Exception $e){
		mysql_query("ROLLBACK");
		print "<script>alert('Something went wrong when saving the event of the item. Plase check your connection.')</script>";
	}
	mysql_query("UNLOCK TABLE;");
?>