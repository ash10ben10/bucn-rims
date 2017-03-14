<?php

	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$date = date("Y-m-d");

	require "../connect.php";
	
	$poID = $_GET['id'];
	
	mysql_query("UPDATE `request_items` SET `instat`='Cancelled',`ins_remarks`='Delivery Terminated.' WHERE `po_id` = '$poID' AND `instat` IN ('Incomplete','')");
	
	$getcompleteditems = mysql_fetch_array(mysql_query("SELECT count(*) FROM `request_items` WHERE `po_id` = '$poID' AND instat = 'Complete'"));
	if($getcompleteditems[0] == 0){
		#skip me
	}else if($getcompleteditems[0] > 0){
		mysql_query("LOCK TABLE inspection WRITE;");
			
		try{
			mysql_query("INSERT INTO inspection (`inspection_date`, `status`, `po_id`, `personnel_id`) VALUES
			(
			'$date',
			'Inspected',
			'$poID',
			'".$_SESSION['logged_personnel_id']."'
			)");
			mysql_query("COMMIT");
		}catch(Exception $e){
			mysql_query("ROLLBACK");
			print "<script>alert('Something went wrong when submitting your inspection report to the System. Please check your connection.')</script>";
		}
		
		mysql_query("UNLOCK TABLE;");
	}
	
	mysql_query("UPDATE `requisition_status` SET `status`='Delivery Complete' WHERE `po_id` = '$poID'");
	print "<script>window.location='status.php';</script>";

?>