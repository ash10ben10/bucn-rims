<?php
	
	$conn = mysql_connect("localhost","root","");
	if (!$conn)
	{
	  	die('Could not connect: ' . mysql_error());
	}

	mysql_select_db("bucn_rims",$conn);
	
	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");

	//$mysqli=mysqli_connect("localhost","root","","inventory _monitoring");
?>