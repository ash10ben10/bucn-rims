<?php

	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");
	$date = date("Y-m-d");
	$month = date("Y-m");
	
	function escapeString($str){
		return mysql_real_escape_string($str);
	}
	
	#add Disposal Form
	if(isset($_POST['dispnow'])){
		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("START TRANSACTION");
		
		$genDispSql = "SELECT dispdate,".
			" CONCAT('".$month."-',(COUNT(DATE_FORMAT(dispdate, '%Y-%m')) + 1)) AS dispNum".
			" FROM eqp_disposal".
			" GROUP BY DATE_FORMAT(dispdate, '%Y-%m')".
			" HAVING DATE_FORMAT(dispdate, '%Y-%m') = '".$month."'".
			" ORDER BY dispdate DESC LIMIT 1";
		$genDispQry = mysql_query($genDispSql) or die(mysql_error());
		if(mysql_num_rows($genDispQry) == 0){
			$dispNum = "DISP-".$month."-1";
		}else{
			$genDispArr = mysql_fetch_array($genDispQry);
			$dispNum = "DISP-".$genDispArr['dispNum'];
		}
		
		mysql_query("LOCK TABLE eqp_disposal WRITE;");
		
		try{
			mysql_query("INSERT INTO `eqp_disposal`(
			`dispdate`, 
			`dispnum`, 
			`dispstatus`, 
			`disp_chairman`, 
			`disp_memberA`, 
			`disp_memberB`, 
			`disp_memberC`, 
			`disp_coa`
			) VALUES (
			'$date',
			'$dispNum',
			'Pending',
			'".escapeString($_POST['chairman'])."',
			'".escapeString($_POST['membera'])."',
			'".escapeString($_POST['memberb'])."',
			'".escapeString($_POST['memberc'])."',
			'".escapeString($_POST['coarep'])."'
			)")or die(mysql_error());
			mysql_query("COMMIT");
		}catch(Exception $e){
			mysql_query("ROLLBACK");
			print "<script>alert('Something went wrong when saving disposal information to the System.')</script>";
		}
		mysql_query("UNLOCK TABLE;");
		
		#get PM id
		$getDisp = mysql_fetch_array(mysql_query("SELECT * FROM `eqp_disposal` WHERE `eqpd_id` IN (SELECT MAX(eqpd_id) FROM eqp_disposal) "))or die(mysql_error()); //this makes the select last id recorded.
		
		#copy the eqp information and insert into new equipment
		$in = "(". implode(", ", $_POST['geteqpid']) .")";
		$getseleqps = "SELECT * FROM `equipments` WHERE `eqp_id` IN ".$in."";
		$geteqps = mysql_query($getseleqps);
		
		while($updateqps = mysql_fetch_array($geteqps)){
			
			mysql_query("LOCK TABLE eqp_disposal_items WRITE;");
			try{
				mysql_query("INSERT INTO `eqp_disposal_items`(
				`eqpd_id`, 
				`eqp_id`
				) VALUES (
				'$getDisp[eqpd_id]',
				'$updateqps[eqp_id]'
				)")or die(mysql_error());
			}catch(Exception $e){
				mysql_query("ROLLBACK");
				print "<script>alert('Something went wrong when updating items for disposal to the System.')</script>";
			}
			mysql_query("UNLOCK TABLE;");
			
			mysql_query("UPDATE `equipments` SET `remarks` = 'Pending for Disposal' WHERE `eqp_id` = '$updateqps[eqp_id]'");
			
			mysql_query("LOCK TABLE eqp_history WRITE;");
			try{
				mysql_query("INSERT INTO `eqp_history`(
				`eqp_id`, 
				`receivedBy`, 
				`historydate`, 
				`icspar`, 
				`icspar_id`, 
				`remarks`
				) VALUES (
				'$updateqps[eqp_id]',
				'$updateqps[received_by]',
				'$date',
				'DSP',
				'$getDisp[eqpd_id]',
				'Pending for Disposal'
				)")or die(mysql_error());
			}catch(Exception $e){
				mysql_query("ROLLBACK");
				print "<script>alert('Something went wrong when updating items to the System.')</script>";
			}
			mysql_query("UNLOCK TABLE;");
		}
		print "<script>alert('Equipment/s has been set for disposal.'); window.location='eq_disposal.php';</script>";
	}

?>