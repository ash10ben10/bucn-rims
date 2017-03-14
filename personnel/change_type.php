<?php
	//session_start();
	$getid = $_GET['id'];
	$gettype = $_GET['type'];
	
	include "../connect.php";
	
	mysql_query("SET AUTOCOMMIT=0");
	mysql_query("START TRANSACTION");
	
	mysql_query("LOCK TABLE account WRITE;");	
	
	try{
		mysql_query("UPDATE account SET account_type = '$gettype' WHERE account_id = '$getid'")or die(mysql_error());
		mysql_query("COMMIT");
		print "<script>alert('Account type has been changed.'); </script>";
	}catch(Exception $e){
		mysql_query("ROLLBACK");
		print "<script>alert('Something went wrong then changing the account type. Plase check your connection.')</script>";
	}
	mysql_query("UNLOCK TABLE;");
	
	print "<script>window.location='act.php';</script>";
?>