<?php

	require "../connect.php";

	$SupplyNo = $_POST['saveSupplyNo'];
	$SupplyName = $_POST['saveSupplyName'];
	$SupplyUnit = $_POST['saveSupplyUnit'];
	$SupplyCategory = $_POST['saveSupplyCategory'];
	$saveLimit = $_POST['saveLimit'];
	
	mysql_query("SET AUTOCOMMIT=0");
	mysql_query("START TRANSACTION");
	
	mysql_query("LOCK TABLE items WRITE;");
	
	try{
		mysql_query("UPDATE `items` SET `item_name` = '$SupplyName',`item_unit_id` = '$SupplyUnit', `category_id`= '$SupplyCategory', `criticalimit` = '$saveLimit' WHERE item_id = '$SupplyNo' ") or die(mysql_error());
		mysql_query("COMMIT");
	}catch(Exception $e){
		mysql_query("ROLLBACK");
		print "<script>alert('Something went wrong when saving the entry. Plase check your connection.')</script>";
	}
	mysql_query("UNLOCK TABLE;");
?>