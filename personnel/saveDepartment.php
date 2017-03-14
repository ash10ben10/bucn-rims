<?php

	require "../connect.php";

	$departmentNo = $_POST['saveDepartmentNo'];
	$departmentName = $_POST['saveDepartmentName'];
	
	mysql_query("SET AUTOCOMMIT=0");
	mysql_query("START TRANSACTION");
	
	mysql_query("LOCK TABLE department WRITE;");	
	
	try{
		$department_sql = mysql_query("UPDATE `department` SET `dept_name`= '$departmentName' WHERE dept_id = '$departmentNo'") or die(mysql_error());
		mysql_query("COMMIT");
		//print "<script>alert('Changes has been saved. The departments are updated.')</script>";
	}catch(Exception $e){
		mysql_query("ROLLBACK");
		print "<script>alert('Something went wrong when saving the entry. Plase check your connection.')</script>";
	}
	mysql_query("UNLOCK TABLE;");
?>