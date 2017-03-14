<?php

	require "../connect.php";
	
	$delposition = $_POST['delPosition'];

	mysql_query("SET AUTOCOMMIT=0");
	mysql_query("START TRANSACTION");
	
	mysql_query("LOCK TABLE personnel_position WRITE;");
	
	try{
		$sqlposition = mysql_query("DELETE FROM personnel_position WHERE position_id ='$delposition'") or die(mysql_error());
		mysql_query("COMMIT");
		//print "<script>alert('Position entry has been deleted.')</script>";
	}catch(Exception $e){
		mysql_query("ROLLBACK");
		print "<script>alert('Something went wrong when deleting the entry. Plase check your connection.')</script>";
	}
	mysql_query("UNLOCK TABLE;");
	
?>