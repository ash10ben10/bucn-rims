<?php

	require "../connect.php";
	
	$delSupply = $_POST['delSupply'];

	mysql_query("SET AUTOCOMMIT=0");
	mysql_query("START TRANSACTION");
	
	mysql_query("LOCK TABLE items WRITE;");
	
	try{
		mysql_query("DELETE FROM items WHERE item_id ='$delSupply'") or die(mysql_error());
		mysql_query("COMMIT");
	}catch(Exception $e){
		mysql_query("ROLLBACK");
		print "<script>alert('Something went wrong when deleting the entry. Plase check your connection.')</script>";
	}
	mysql_query("UNLOCK TABLE;");

?>