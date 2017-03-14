<?php

	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");
	$date = date("Y-m-d");
	$month = date("Y-m");

	require "../connect.php";

	$POid = $_POST['POid'];
	$POfund = $_POST['POfund'];
	//$OSnum = $_POST['OSnum'];
	$FundAmount = $_POST['FundAmount'];
	
	mysql_query("SET AUTOCOMMIT=0");
	mysql_query("START TRANSACTION");
	
	mysql_query("LOCK TABLE funding WRITE;");
	try{
		mysql_query("UPDATE `funding` SET `status`='funded', `type`='$POfund',`amount`='$FundAmount', `fundate`='$date' WHERE `po_id` = '$POid'") or die(mysql_error());
		//mysql_query("INSERT INTO `funding` (`po_id`, `os_num`, `amount`) VALUES ('$POid', '$OSnum', '$FundAmount')") or die(mysql_error());
		mysql_query("COMMIT");
	}catch(Exception $e){
		mysql_query("ROLLBACK");
		print "<script>alert('Something went wrong when placing fund for the order. Plase check your connection.')</script>";
	}
	mysql_query("UNLOCK TABLE;");
	
	$genFundSql = "SELECT fundate,".
		" CONCAT('".$month."-',(COUNT(DATE_FORMAT(fundate, '%Y-%m')) + 1)) AS os_num".
		" FROM funding".
		" GROUP BY DATE_FORMAT(fundate, '%Y-%m')".
		" HAVING DATE_FORMAT(fundate, '%Y-%m') = '".$month."'".
		" ORDER BY fundate DESC LIMIT 1";
	$genFundQry = mysql_query($genFundSql) or die(mysql_error());
	$getfund = mysql_fetch_array(mysql_query("SELECT `type` FROM `funding` WHERE `po_id` = '$POid'"))or die(mysql_error());
	
	if(mysql_num_rows($genFundQry) == 0){
		$fundNum = "MOOE-".$getfund['type']."-".$month."-1";
	}else{
		$genFundArr = mysql_fetch_array($genFundQry);
		$fundNum = "MOOE-".$getfund['type']."-".$genFundArr['os_num'];
	}
	
	mysql_query("UPDATE `funding` SET `os_num` = '$fundNum' WHERE `po_id` = '$POid'")or die(mysql_error());
	
	mysql_query("UPDATE `requisition_status` SET `status`='funded' WHERE `po_id` = '$POid'") or die(mysql_error());
	
?>