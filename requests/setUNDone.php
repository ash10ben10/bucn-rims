<?php

	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$date = date("Y-m-d");

	require "../connect.php";
	
	$riID = $_POST['reqitemid'];
	$rmarks = $_POST['rmarks'];
	$delqty = $_POST['delqty'];
	
	mysql_query("SET AUTOCOMMIT=0");
	mysql_query("START TRANSACTION");
	
	mysql_query("LOCK TABLE request_items WRITE;");	
	
	/* $confirmqty = mysql_fetch_array(mysql_query("SELECT `quantity` FROM `request_items` WHERE `req_item_id` = $riID"));
	if($delqty => $confirmqty['quantity']){
		print "<script>alert('The quantity you have entered is higher than the ordered quantity. Please try again.'); window.location='add_ins.php?id=".$poID['po_id']."';</script>";
	}else{ */
		try{
			$approve = mysql_query("UPDATE request_items SET instat = 'Incomplete', ins_remarks = '$rmarks', `del_quantity` = '$delqty' WHERE req_item_id = $riID");
			mysql_query("COMMIT");
		}catch(Exception $e){
			mysql_query("ROLLBACK");
			print "<script>alert('Something went wrong when setting your inspection. Please check your connection.')</script>";
		}
		mysql_query("UNLOCK TABLE;");
		
		//$poID = mysql_fetch_array(mysql_query("SELECT `po_id` FROM request_items WHERE req_item_id = $riID"))or die(mysql_error());
		
		//mysql_query("UPDATE `requisition_status` SET `status` = 'Inspection under Process' WHERE `po_id` = '$poID[po_id]'") or die(mysql_error());
		
		print "<script>window.location='add_ins.php?id=".$poID['po_id']."';</script>";
	//}
	
?>