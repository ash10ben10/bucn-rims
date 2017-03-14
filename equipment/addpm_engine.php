<?php

	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");
	$date = date("Y-m-d");
	$month = date("Y-m");
	
	function escapeString($str){
		return mysql_real_escape_string($str);
	}
	
	#add Corrective Maintenance
	if(isset($_POST['pmnow'])){
		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("START TRANSACTION");
		
		$genPMSql = "SELECT pmDate,".
			" CONCAT('".$month."-',(COUNT(DATE_FORMAT(pmDate, '%Y-%m')) + 1)) AS pmNum".
			" FROM eqp_preventive_maintenance".
			" GROUP BY DATE_FORMAT(pmDate, '%Y-%m')".
			" HAVING DATE_FORMAT(pmDate, '%Y-%m') = '".$month."'".
			" ORDER BY pmDate DESC LIMIT 1";
		$genPMQry = mysql_query($genPMSql) or die(mysql_error());
		if(mysql_num_rows($genPMQry) == 0){
			$pmNum = "PM-".$month."-1";
		}else{
			$genPMArr = mysql_fetch_array($genPMQry);
			$pmNum = "PM-".$genPMArr['pmNum'];
		}
		
		mysql_query("LOCK TABLE eqp_preventive_maintenance WRITE;");
		
		try{
			mysql_query("INSERT INTO `eqp_preventive_maintenance`(
			`pmDate`, 
			`pmNum`, 
			`pmSched`,  
			`pmStatus`, 
			`pmRepairer`, 
			`pmCompany`, 
			`pmAddress`, 
			`pmContact`,
			`pmRequestedBy`
			) VALUES (
			'$date',
			'$pmNum',
			'".escapeString($_POST['datesched'])."',
			'Ongoing',
			'".escapeString($_POST['rprer'])."',
			'".escapeString($_POST['company'])."',
			'".escapeString($_POST['cadd'])."',
			'".escapeString($_POST['cpnum'])."',
			'".$_SESSION['logged_personnel_id']."'
			)")or die(mysql_error());
			mysql_query("COMMIT");
		}catch(Exception $e){
			mysql_query("ROLLBACK");
			print "<script>alert('Something went wrong when saving Corrective Maintenance information to the System.')</script>";
		}
		mysql_query("UNLOCK TABLE;");
		
		#get PM data
		$getPM = mysql_fetch_array(mysql_query("SELECT * FROM `eqp_preventive_maintenance` WHERE `eqp_pm_id` IN (SELECT MAX(eqp_pm_id) FROM eqp_preventive_maintenance) "))or die(mysql_error()); //this makes the select last id recorded.
		
		#copy the eqp information and insert into new equipment
		$in = "(". implode(", ", $_POST['geteqpid']) .")";
		$getseleqps = "SELECT * FROM `equipments` WHERE `eqp_id` IN ".$in."";
		$geteqps = mysql_query($getseleqps);
		
		while($updateqps = mysql_fetch_array($geteqps)){
			
			mysql_query("LOCK TABLE eqp_pm_items WRITE;");
			try{
				mysql_query("INSERT INTO `eqp_pm_items`(
				`eqp_pm_id`, 
				`eqp_id`, 
				`status`
				) VALUES (
				'$getPM[eqp_pm_id]',
				'$updateqps[eqp_id]',
				'Under Maintenance'
				)")or die(mysql_error());
				mysql_query("COMMIT");
			}catch(Exception $e){
				mysql_query("ROLLBACK");
				print "<script>alert('Something went wrong when setting up items for repair and maintenance to the System.')</script>";
			}
			mysql_query("UNLOCK TABLE;");
			
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
				'PM',
				'$getPM[eqp_pm_id]',
				'Under Maintenance'
				)")or die(mysql_error());
				mysql_query("COMMIT");
			}catch(Exception $e){
				mysql_query("ROLLBACK");
				print "<script>alert('Something went wrong when recording Equipment History to the System. Please check your connection.')</script>";
			}
			mysql_query("UNLOCK TABLE;");
			
			$updateeqps = mysql_query("UPDATE `equipments` SET `remarks` = 'Under Maintenance' WHERE `eqp_id` = '$updateqps[eqp_id]'");
			
		}
		print "<script>alert('Equipment/s has been set for maintenance.'); window.location='eq_pmaintenance.php';</script>";
		
	}

?>