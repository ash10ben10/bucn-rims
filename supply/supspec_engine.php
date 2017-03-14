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
			'".escapeString($_POST['itemunit'])."',
			'".escapeString($_POST['itemdesc'])."',
			'".escapeString($_POST['itemprice'])."'
			)");
			mysql_query("COMMIT");
		}catch(Exception $e){
			mysql_query("ROLLBACK");
			print "<script>alert('Something went wrong when saving your item description to the System. Please check your connection.')</script>";
		}
		
		mysql_query("UNLOCK TABLE;");
		
		print "<script>alert('Item description has been saved.'); window.location='supply_specs.php';</script>";
		
	}else if(isset($_POST['getunit'])){
		if($_POST['getunit'] == "getItemUnit"){
			$getitemunit = mysql_query("SELECT mu.munit_id, mu.item_unit_id, iu.item_unit_name FROM `more_units` AS mu LEFT JOIN `item_unit` AS iu ON iu.item_unit_id = mu.item_unit_id WHERE mu.item_id = '".mysql_escape_string($_POST['item_id'])."'");
			$counter = 0;
			$unit = array();
			while($row = mysql_fetch_array($getitemunit)){
				$unit[$counter]["unitid"] = $row["munit_id"];
				$unit[$counter++]["unitname"] = $row["item_unit_name"];
			}
			echo json_encode(array("data" => $unit));
		}
	}

?>