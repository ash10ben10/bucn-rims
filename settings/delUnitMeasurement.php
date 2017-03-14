<?php

	require "../connect.php";
	
	$delUnitMeasurement = $_POST['delUnitMeasurement'];

	mysql_query("SET AUTOCOMMIT=0");
	mysql_query("START TRANSACTION");
	
	mysql_query("LOCK TABLE item_unit WRITE;");
	
	try{
		mysql_query("DELETE FROM item_unit WHERE item_unit_id ='$delUnitMeasurement'") or die(mysql_error());
		mysql_query("COMMIT");
	}catch(Exception $e){
		mysql_query("ROLLBACK");
		print "<script>alert('Something went wrong when deleting the entry. Plase check your connection.')</script>";
	}
	mysql_query("UNLOCK TABLE;");
	
?>