<?php

	$getid = $_GET['id'];
	$getqty = $_GET['type'];
	
	include "../connect.php";
	
	if($getqty == 0 || $getqty == "0"){
		print "<script>alert('You cannot enter an empty or zero quantity.');</script>";
		$prID = mysql_fetch_array(mysql_query("SELECT `pr_id` FROM request_items WHERE req_item_id = $getid"))or die(mysql_error());
		print "<script>window.location='view_pr2.php?id=".$prID['pr_id']."';</script>";
	}else{
		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("START TRANSACTION");
		
		mysql_query("LOCK TABLE request_items WRITE;");
		
		try{
			/* $verify = mysql_fetch_array(mysql_query("SELECT * FROM request_items WHERE req_item_id = '$getid'"))or die(mysql_error());
			if($verify['qty_orig'] == 0){
				$getOrig = mysql_fetch_array(mysql_query("SELECT quantity FROM request_items WHERE req_item_id = '$getid'"))or die(mysql_error());
				mysql_query("UPDATE request_items SET qty_orig = '".$getOrig['quantity']."' WHERE req_item_id = '$getid'")or die(mysql_error());
				mysql_query("UPDATE request_items SET quantity = '$getqty' WHERE req_item_id = '$getid'")or die(mysql_error());
				mysql_query("COMMIT");
			}else{ */
				mysql_query("UPDATE request_items SET quantity = '$getqty' WHERE req_item_id = '$getid'")or die(mysql_error());
				mysql_query("UPDATE request_items SET qty_approved = '$getqty' WHERE req_item_id = '$getid'")or die(mysql_error());
				mysql_query("UPDATE request_items SET est_total_cost = quantity * est_unit_cost WHERE req_item_id = '$getid'")or die(mysql_error());
				mysql_query("COMMIT");
		}catch(Exception $e){
			mysql_query("ROLLBACK");
			print "<script>alert('Something went wrong then changing the quantity. Plase check your connection.')</script>";
		}
		
		mysql_query("UNLOCK TABLE;");
		
		$prID = mysql_fetch_array(mysql_query("SELECT `pr_id` FROM request_items WHERE req_item_id = $getid"))or die(mysql_error());
		
		print "<script>window.location='view_pr2.php?id=".$prID['pr_id']."';</script>";
	}
	
	
?>