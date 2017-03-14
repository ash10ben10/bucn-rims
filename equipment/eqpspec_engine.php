<?php

	require_once "../connect.php";
	
	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");
	$date = date("Y-m-d");
	$month = date("Y-m");
	
	function escapeString($str){
		return mysql_real_escape_string($str);
	}
	
	if(isset($_POST['submitdesc'])){
		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("START TRANSACTION");
		
		mysql_query("LOCK TABLE more_desc WRITE;");
		
		try{
			mysql_query("INSERT INTO `more_desc`(`munit_id`, `description`, `price`) 
			VALUES (
			'".escapeString($_POST['iunit'])."',
			'".escapeString($_POST['idesc'])."',
			'".escapeString($_POST['iprice'])."'
			)");
			mysql_query("COMMIT");
		}catch(Exception $e){
			mysql_query("ROLLBACK");
			print "<script>alert('Something went wrong when saving your item description to the System. Please check your connection.')</script>";
		}
		
		mysql_query("UNLOCK TABLE;");
		
		print "<script>alert('Item description has been saved.'); window.location='equipment_specs.php';</script>";
		
	}else if(isset($_POST['ctchunit'])){
		if($_POST['ctchunit'] == "getIUnit"){
			$getitmunit = mysql_query("SELECT mu.munit_id, mu.item_unit_id, iu.item_unit_name FROM `more_units` AS mu LEFT JOIN `item_unit` AS iu ON iu.item_unit_id = mu.item_unit_id WHERE mu.item_id = '".mysql_escape_string($_POST['itmid'])."'");
			$ctr = 0;
			$unt = array();
			while($read = mysql_fetch_array($getitmunit)){
				$unt[$ctr]["untid"] = $read["munit_id"];
				$unt[$ctr++]["untname"] = $read["item_unit_name"];
			}
			echo json_encode(array("data" => $unt));
		}
	}

?>