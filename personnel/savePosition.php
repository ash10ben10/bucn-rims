<?php

	require "../connect.php";

	$positionNo = $_POST['savePositionNo'];
	$positionName = $_POST['savePositionName'];
	
	mysql_query("SET AUTOCOMMIT=0");
	mysql_query("START TRANSACTION");
	
	mysql_query("LOCK TABLE personnel_position WRITE;");	
	
	try{
		$position_sql = mysql_query("UPDATE `personnel_position` SET `position_name`= '$positionName' WHERE position_id = '$positionNo'") or die(mysql_error());
		mysql_query("COMMIT");
		print "<script>alert('Changes has been saved. The positions are updated.')</script>";
	}catch(Exception $e){
		mysql_query("ROLLBACK");
		print "<script>alert('Something went wrong when saving the entry. Plase check your connection.')</script>";
	}
	mysql_query("UNLOCK TABLE;");
?>