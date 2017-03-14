<?php

	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");
	$date = date("Y-m-d");
	$month = date("Y-m");

	require "../connect.php";
	
	$mdNo = $_POST['mdNo'];
	$mdDesc = $_POST['mdDesc'];
	$mdPrice = $_POST['mdPrice'];
	
	mysql_query("UPDATE `more_desc` SET `description`='$mdDesc',`price`='$mdPrice' WHERE `md_id` = '$mdNo'");

?>