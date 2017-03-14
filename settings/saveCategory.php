<?php

	require "../connect.php";

	$CategNo = $_POST['saveCategNo'];
	$CategName = $_POST['saveCategName'];
	$CategType = $_POST['saveCategType'];
	
	mysql_query("SET AUTOCOMMIT=0");
	mysql_query("START TRANSACTION");
	
	mysql_query("LOCK TABLE category WRITE;");
	
	try{
		mysql_query("UPDATE category SET category_name = '$CategName', category_type = '$CategType' WHERE category_id = '$CategNo'") or die(mysql_error());
		mysql_query("COMMIT");
	}catch(Exception $e){
		mysql_query("ROLLBACK");
		print "<script>alert('Something went wrong when saving the category. Plase check your connection.')</script>";
	}
	mysql_query("UNLOCK TABLE;");
?>