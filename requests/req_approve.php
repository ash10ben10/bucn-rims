<?php
	
	include "../connect.php";
	
	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$appdate = date("Y-m-d");
	
	$getid = $_GET['id'];
	
	mysql_query("SET AUTOCOMMIT=0");
	mysql_query("START TRANSACTION");
	
	mysql_query("LOCK TABLE request_items WRITE;");	
	
	try{
		mysql_query("UPDATE request_items SET pr_status = 'approved', prstat_date = '$appdate' WHERE req_item_id = '$getid'");
		$getpritems = mysql_fetch_array(mysql_query("SELECT * FROM `request_items` WHERE req_item_id = '$getid'"));
		mysql_query("UPDATE request_items SET qty_approved = '$getpritems[quantity]' WHERE req_item_id = '$getid'");
		mysql_query("COMMIT");
	}catch(Exception $e){
		mysql_query("ROLLBACK");
		print "<script>alert('Something went wrong when approving your request. Please check your connection.')</script>";
	}
	mysql_query("UNLOCK TABLE;");
	
	$getpr = mysql_fetch_array(mysql_query("SELECT pr_id FROM request_items WHERE req_item_id = '$getid'"));
	
	print "<script>alert('Requested item has been approved.')</script>";
	print "<script>window.location='view_pr.php?id=".$getpr['pr_id']."';</script>";
?>