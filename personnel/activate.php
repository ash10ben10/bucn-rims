<?php
	//session_start();
	$getid = $_GET['id'];
	
	include "../connect.php";
	
	mysql_query("SET AUTOCOMMIT=0");
	mysql_query("START TRANSACTION");
	
	mysql_query("LOCK TABLE account WRITE;");	
	
	
		$result = "success";
		$msg = "The account is activated.";
	
	try{
		$activate = mysql_query("UPDATE account SET account_status = 'activated' WHERE account_id = $getid")or die(mysql_error());
		mysql_query("COMMIT");
	}catch(Exception $e){
		mysql_query("ROLLBACK");
		$result = "failed";
		$msg = "Something went wrong when activating the account. Please check your connection.";
	}
	mysql_query("UNLOCK TABLE;");
	
	echo json_encode(array("result" => $result, "msg" => $msg));
?>