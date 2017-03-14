<?php

	require "../connect.php";

	$EquipmentNo = $_POST['saveEquipmentNo'];
	$EquipmentName = $_POST['saveEquipmentName'];
	$EquipmentUnit = $_POST['saveEquipmentUnit'];
	$EquipmentCategory = $_POST['saveEquipmentCategory'];
	//$saveLimit = $_POST['saveLimit'];
	
	mysql_query("SET AUTOCOMMIT=0");
	mysql_query("START TRANSACTION");
	
	mysql_query("LOCK TABLE items WRITE;");
	
	try{
		mysql_query("UPDATE `items` SET `item_name` = '$EquipmentName',`item_unit_id` = '$EquipmentUnit', `category_id`= '$EquipmentCategory' WHERE item_id = '$EquipmentNo' ");
		mysql_query("COMMIT");
	}catch(Exception $e){
		mysql_query("ROLLBACK");
		print "<script>alert('Something went wrong when saving the entry. Plase check your connection.')</script>";
	}
	mysql_query("UNLOCK TABLE;");
?>