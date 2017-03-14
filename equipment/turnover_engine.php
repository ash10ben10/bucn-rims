<?php

	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");
	$date = date("Y-m-d");
	$month = date("Y-m");
	
	function escapeString($str){
		return mysql_real_escape_string($str);
	}
	
	#Add Purchase Order
	if(isset($_POST['turnow'])){
		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("START TRANSACTION");
		
		$genToSql = "SELECT toDate,".
			" CONCAT('".$month."-',(COUNT(DATE_FORMAT(toDate, '%Y-%m')) + 1)) AS tonum".
			" FROM eqp_turnover".
			" GROUP BY DATE_FORMAT(toDate, '%Y-%m')".
			" HAVING DATE_FORMAT(toDate, '%Y-%m') = '".$month."'".
			" ORDER BY toDate DESC LIMIT 1";
		$genTOQry = mysql_query($genToSql) or die(mysql_error());
		if(mysql_num_rows($genTOQry) == 0){
			$toNum = "TO-".$month."-1";
		}else{
			$genTOArr = mysql_fetch_array($genTOQry);
			$toNum = "TO-".$genTOArr['tonum'];
		}
		
		mysql_query("LOCK TABLE eqp_turnover WRITE;");
		
		try{
			mysql_query("INSERT INTO `eqp_turnover`(
			`tonum`, 
			`toDate`, 
			`toFrom`, 
			`toTo`,
			`date_acquired`
			) VALUES (
			'$toNum',
			'$date',
			'$readpeople_id',
			'".escapeString($_POST['transferto'])."',
			'".escapeString($_POST['dateacq'])."'
			)")or die(mysql_error());
			mysql_query("COMMIT");
		}catch(Exception $e){
			mysql_query("ROLLBACK");
			print "<script>alert('Something went wrong when saving transfer information to the System.')</script>";
		}
		mysql_query("UNLOCK TABLE;");
		
		#get TO id
		$gettoid = mysql_fetch_array(mysql_query("SELECT `to_id` FROM `eqp_turnover` WHERE `to_id` IN (SELECT MAX(to_id) FROM eqp_turnover) "))or die(mysql_error()); //this makes the select last id recorded.
		$toid = $gettoid[0];
		
		#copy the eqp information and insert into new equipment
		$in = "(". implode(", ", $_POST['geteqpid']) .")";
		$getseleqps = "SELECT * FROM `equipments` WHERE `eqp_id` IN ".$in." AND `received_by` = '$readpeople_id' ";
		$geteqps = mysql_query($getseleqps);
		
		while($inserteqps = mysql_fetch_array($geteqps)){
			
			$genEqpSql = "SELECT eqpdate,".
				" CONCAT('".$month."-',(COUNT(DATE_FORMAT(eqpdate, '%Y-%m')) + 1)) AS eqpNum".
				" FROM equipments".
				" GROUP BY DATE_FORMAT(eqpdate, '%Y-%m')".
				" HAVING DATE_FORMAT(eqpdate, '%Y-%m') = '".$month."'".
				" ORDER BY eqpdate DESC LIMIT 1";
			$genEqpQry = mysql_query($genEqpSql) or die(mysql_error());
			if(mysql_num_rows($genEqpQry) == 0){
				$eqpNum = $month."-1";
			}else{
				$genEqpArr = mysql_fetch_array($genEqpQry);
				$eqpNum = $genEqpArr['eqpNum'];
			}
			
			$getdept = mysql_fetch_array(mysql_query("SELECT `dept_id` FROM `personnel_work_info` WHERE `personnel_id` = '".escapeString($_POST['transferto'])."'"));
			
			mysql_query("LOCK TABLE equipments WRITE;");
			
			try{
				mysql_query("INSERT INTO `equipments`(
				`eqpnum`,
				`eqpdate`,
				`prop_num`,
				`item_id`,
				`item_unit_id`,
				`brand`,
				`description`,
				`serialnum`,
				`unit_value`,
				`est_useful_life`,
				`icspar`,
				`ics_par_id`,
				`received_by`,
				`dept_id`,
				`date_acquired`,
				`su_id`, 
				`remarks`,
				`eqp_photo`
				) VALUES (
				'$eqpNum',
				'$date',
				'$inserteqps[prop_num]',
				'$inserteqps[item_id]',
				'$inserteqps[item_unit_id]',
				'$inserteqps[brand]',
				'$inserteqps[description]',
				'$inserteqps[serialnum]',
				'$inserteqps[unit_value]',
				'$inserteqps[est_useful_life]',
				'TO',
				'$toid',
				'".escapeString($_POST['transferto'])."',
				'$getdept[dept_id]',
				'".escapeString($_POST['dateacq'])."',
				'$inserteqps[su_id]',
				'Working',
				'$inserteqps[eqp_photo]'
				)")or die(mysql_error());
				mysql_query("COMMIT");
			}catch(Exception $e){
				mysql_query("ROLLBACK");
				print "<script>alert('Something went wrong when saving Equipment to the System. Please check your connection.')</script>";
			}
			mysql_query("UNLOCK TABLE;");
			
			#get eqp_id
			$geteqpid = mysql_fetch_array(mysql_query("SELECT eqp_id FROM equipments WHERE eqp_id IN (SELECT MAX(eqp_id) FROM equipments) "))or die(mysql_error()); //this makes the select last id recorded.
			$eqpid = $geteqpid[0];
			
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
				'$eqpid',
				'".escapeString($_POST['transferto'])."',
				'$date',
				'TO',
				'$toid',
				'Active'
				)")or die(mysql_error());
				mysql_query("COMMIT");
			}catch(Exception $e){
				mysql_query("ROLLBACK");
				print "<script>alert('Something went wrong when recording Equipment History to the System. Please check your connection.')</script>";
			}
			mysql_query("UNLOCK TABLE;");
			
			$getpersonnel = mysql_fetch_array(mysql_query("SELECT CONCAT (personnel_fname,' ',personnel_lname) AS full_name FROM personnel WHERE personnel_id = '".escapeString($_POST['transferto'])."' "))or die(mysql_error());
			
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
				'$inserteqps[eqp_id]',
				'$inserteqps[received_by]',
				'$date',
				'$inserteqps[icspar]',
				'$inserteqps[ics_par_id]',
				'Transferred to ".$getpersonnel['full_name']."'
				)")or die(mysql_error());
				mysql_query("COMMIT");
			}catch(Exception $e){
				mysql_query("ROLLBACK");
				print "<script>alert('Something went wrong when recording Equipment History to the System. Please check your connection.')</script>";
			}
			mysql_query("UNLOCK TABLE;");
			
			$updateqp = mysql_query("UPDATE `equipments` SET `remarks`='Transferred to ".$getpersonnel['full_name']."' WHERE `eqp_id` = '$inserteqps[eqp_id]'");
			
		}
		
		print "<script>alert('Equipment has been transferred successfully.'); window.location='eq_turnover.php';</script>";
	}

?>