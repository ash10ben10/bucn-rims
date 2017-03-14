<?php

	require "../connect.php";
	
	$deldepartment = $_POST['delDepartment'];

	mysql_query("SET AUTOCOMMIT=0");
	mysql_query("START TRANSACTION");
	
	mysql_query("LOCK TABLE department WRITE;");
	
	try{
		$sqldepartment = mysql_query("DELETE FROM department WHERE dept_id ='$deldepartment'") or die(mysql_error());
		mysql_query("COMMIT");
		//print "<script>alert('Department entry has been deleted.')</script>";
	}catch(Exception $e){
		mysql_query("ROLLBACK");
		print "<script>alert('Something went wrong when deleting the entry. Plase check your connection.')</script>";
	}
	mysql_query("UNLOCK TABLE;");
	
?>