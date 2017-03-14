<?php
	//session_start();
	$getid = $_GET['id'];
	$getname = $_GET['suppname'];
	$getunit = $_GET['suppunit'];
	
	include "../connect.php";
	
	function escapeString($str){
		return mysql_real_escape_string($str);
	}
	
	mysql_query("SET AUTOCOMMIT=0");
	mysql_query("START TRANSACTION");
	
	mysql_query("LOCK TABLE supply WRITE;");	
	
	try{
		mysql_query("UPDATE supply SET supply_name = '$getname' AND item_unit_id = '$getunit' WHERE supply_id = '$getid'")or die(mysql_error());
		mysql_query("COMMIT");
		print "<script>alert('Supply properties has been changed.'); </script>";
	}catch(Exception $e){
		mysql_query("ROLLBACK");
		print "<script>alert('Something went wrong then changing your request. Plase check your connection.')</script>";
	}
	mysql_query("UNLOCK TABLE;");
	
	print "<script>window.location='inv_settings.php?cTab=sn';</script>";
?>
