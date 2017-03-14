<?php

	require "../connect.php";

	$UnitMeasurementNo = $_POST['saveUnitMeasurementNo'];
	$UnitMeasurementName = $_POST['saveUnitMeasurementName'];
	
	mysql_query("SET AUTOCOMMIT=0");
	mysql_query("START TRANSACTION");
	
	mysql_query("LOCK TABLE item_unit WRITE;");
	
	try{
		mysql_query("UPDATE `item_unit` SET `item_unit_name` = '$UnitMeasurementName' WHERE item_unit_id = '$UnitMeasurementNo'") or die(mysql_error());
		mysql_query("COMMIT");
	}catch(Exception $e){
		mysql_query("ROLLBACK");
		print "<script>alert('Something went wrong when saving the entry. Plase check your connection.')</script>";
	}
	mysql_query("UNLOCK TABLE;");
?>