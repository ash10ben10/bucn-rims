<?php

	require "../connect.php";
	
	$delCateg = $_POST['delCateg'];

	mysql_query("SET AUTOCOMMIT=0");
	mysql_query("START TRANSACTION");
	
	mysql_query("LOCK TABLE category WRITE;");
	
	try{
		mysql_query("DELETE FROM category WHERE category_id ='$delCateg'") or die(mysql_error());
		mysql_query("COMMIT");
	}catch(Exception $e){
		mysql_query("ROLLBACK");
		print "<script>alert('Something went wrong when deleting the category. Plase check your connection.')</script>";
	}
	mysql_query("UNLOCK TABLE;");
	
?>