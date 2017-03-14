<?php

	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");
	$date = date("Y-m-d");
	$month = date("Y-m");
	
	function escapeString($str){
		return mysql_real_escape_string($str);
	}
	
	#Save RIS
	if(isset($_POST['risave'])){
		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("START TRANSACTION");
	
		$genRISSql = "SELECT risdate,".
			" CONCAT('".$month."-',(COUNT(DATE_FORMAT(risdate, '%Y-%m')) + 1)) AS risNum".
			" FROM request_issue_slip".
			" GROUP BY DATE_FORMAT(risdate, '%Y-%m')".
			" HAVING DATE_FORMAT(risdate, '%Y-%m') = '".$month."'".
			" ORDER BY risdate DESC LIMIT 1";
		$genRISQry = mysql_query($genRISSql) or die(mysql_error());
		if(mysql_num_rows($genRISQry) == 0){
			$risNum = "BUCN-".$getfunding['type']."-".$month."-1";
		}else{
			$genRISArr = mysql_fetch_array($genRISQry);
			$risNum = "BUCN-".$getfunding['type']."-".$genRISArr['risNum'];
		}
		
		mysql_query("LOCK TABLE request_issue_slip WRITE;");
		
		try{
			mysql_query("INSERT INTO `request_issue_slip`
			(`risnum`,
			`risdate`,
			`pr_id`,
			`sai_no`,
			`sai_date`,
			`requestedBy`,
			`approvedBy`,
			`issuedBy`,
			`receivedBy`
			) VALUES (
			'$risNum',
			'$date',
			'$readpr_id',
			'".escapeString($_POST['sainum'])."',
			'".escapeString($_POST['saidate'])."',
			'$getprinfo[personnel_id]',
			'$getsupplyofficer[personnel_id]',
			'".$_SESSION['logged_personnel_id']."',
			'$getprinfo[personnel_id]'
			)");
			mysql_query("COMMIT");
		}catch(Exception $e){
			mysql_query("ROLLBACK");
			print "<script>alert('Something went wrong when saving RIS Info to the System. Please check your connection.')</script>";
		}
		mysql_query("UNLOCK TABLE;");
		
		#get ris info
		$getrisid = mysql_fetch_array(mysql_query("SELECT * FROM request_issue_slip WHERE ris_id IN (SELECT MAX(ris_id) FROM request_issue_slip) ")); //this makes the select last id recorded.
		
		#this part will update stock items to descrease quantity during issuance if request is for personnel use.
		#stock items will not change its quantity if request is for store room.
		
		#identify PR type
		$purtype = mysql_fetch_array(mysql_query("SELECT pur_type FROM purchase_request WHERE pr_id = '$readpr_id'"));
		
		if($purtype['pur_type'] == "fper"){
			
			#get pr items
			$getpritems = mysql_query("SELECT ri.*, i.item_type FROM request_items AS ri LEFT JOIN items AS i ON i.item_id = ri.item_id WHERE ri.pr_id = '$readpr_id' AND issuance = 'Ready'");
			
			while($getme = mysql_fetch_array($getpritems)){
				
				if($getme['item_type'] == "Supply"){
					$getdesc = mysql_query("SELECT * FROM `stock_units` WHERE `su_id` = '$getme[su_id]' AND `item_unit_id` = '$getme[item_unit_id]' LIMIT 1");
					$getdescrows = mysql_num_rows($getdesc);
					
					if($getdescrows == 1){
						while($getdescinfo = mysql_fetch_array($getdesc)){
							
							mysql_query("UPDATE `stock_units` SET `quantity` = quantity - '$getme[del_quantity]' WHERE `su_id` = '$getdescinfo[su_id]'");
							
							$updqty = mysql_fetch_array(mysql_query("SELECT * FROM stock_units WHERE su_id = '$getdescinfo[su_id]'"));
							
							$genStockCardSql = "SELECT recdate,".
								" CONCAT('".$month."-',(COUNT(DATE_FORMAT(recdate, '%Y-%m')) + 1)) AS reference".
								" FROM stock_card".
								" GROUP BY DATE_FORMAT(recdate, '%Y-%m')".
								" HAVING DATE_FORMAT(recdate, '%Y-%m') = '".$month."'".
								" ORDER BY recdate DESC LIMIT 1";
							$genStockCardQry = mysql_query($genStockCardSql) or die(mysql_error());
							if(mysql_num_rows($genStockCardQry) == 0){
								$scNum = $month."-1";
							}else{
								$genStockCardArr = mysql_fetch_array($genStockCardQry);
								$scNum = $genStockCardArr['reference'];
							}
							
							mysql_query("LOCK TABLE stock_card WRITE;");
							
							try{
								mysql_query("
								INSERT INTO `stock_card`(
								`su_id`,
								`recdate`,
								`reference`,
								`qty_receipt`,
								`issue_qty`,
								`personnel_id`,
								`issue_stock_bal`
								) VALUES (
								'$getdescinfo[su_id]',
								'$date',
								'$scNum',
								'$getme[del_quantity]',
								'$getme[del_quantity]',
								'$getprinfo[personnel_id]',
								'$updqty[quantity]'
								)");
								mysql_query("COMMIT");
							}catch(Exception $e){
								mysql_query("ROLLBACK");
								print "<script>alert('Something went wrong when saving issuance to stock card in the System. Please check your connection.')</script>";
							}
							
							mysql_query("UNLOCK TABLE;");
						}
					}
				}else if($getme['item_type'] == "Equipment"){
					$getdesc = mysql_query("SELECT * FROM `stock_units` WHERE `su_id` = '$getme[su_id]' AND `item_unit_id` = '$getme[item_unit_id]' LIMIT 1");
					$getdescrows = mysql_num_rows($getdesc);
					
					if($getdescrows == 1){
						while($getdescinfo = mysql_fetch_array($getdesc)){
							mysql_query("UPDATE `stock_units` SET `quantity` = quantity - '$getme[del_quantity]' WHERE `su_id` = '$getdescinfo[su_id]'");
						}
					}
				}
			}
		}else if($purtype['pur_type'] == "finv"){
			
			#get pr items
			$getpritems = mysql_query("SELECT ri.*, i.item_type FROM request_items AS ri LEFT JOIN items AS i ON i.item_id = ri.item_id WHERE pr_id = '$readpr_id' AND issuance = 'Ready'");
			
			while($getme = mysql_fetch_array($getpritems)){
				
				//$itemtype = mysql_fetch_array(mysql_query("SELECT `item_type` FROM `items` WHERE `item_id` = '$getme[item_id]'"))or die(mysql_error());
				
				if($getme['item_type'] == "Supply"){
					$getdesc = mysql_query("SELECT * FROM stock_units WHERE su_id = '$getme[su_id]' LIMIT 1") or die(mysql_error());
					$getdescrows = mysql_num_rows($getdesc);
					
					if($getdescrows == 1){
						while($getdescinfo = mysql_fetch_array($getdesc)){
							
							$genStockCardSql = "SELECT recdate,".
								" CONCAT('".$month."-',(COUNT(DATE_FORMAT(recdate, '%Y-%m')) + 1)) AS reference".
								" FROM stock_card".
								" GROUP BY DATE_FORMAT(recdate, '%Y-%m')".
								" HAVING DATE_FORMAT(recdate, '%Y-%m') = '".$month."'".
								" ORDER BY recdate DESC LIMIT 1";
							$genStockCardQry = mysql_query($genStockCardSql) or die(mysql_error());
							if(mysql_num_rows($genStockCardQry) == 0){
								$scNum = $month."-1";
							}else{
								$genStockCardArr = mysql_fetch_array($genStockCardQry);
								$scNum = $genStockCardArr['reference'];
							}
							
							mysql_query("LOCK TABLE stock_card WRITE;");
							
							try{
								mysql_query("
								INSERT INTO `stock_card`(
								`su_id`,
								`recdate`,
								`reference`,
								`qty_receipt`,
								`issue_stock_bal`
								) VALUES (
								'$getdescinfo[su_id]',
								'$date',
								'$scNum',
								'$getme[del_quantity]',
								'$getdescinfo[quantity]'
								)")or die(mysql_error());
								mysql_query("COMMIT");
							}catch(Exception $e){
								mysql_query("ROLLBACK");
								print "<script>alert('Something went wrong when saving issuance to stock card in the System. Please check your connection.')</script>";
							}
							mysql_query("UNLOCK TABLE;");
						}
					}
				}else if($getme['item_type'] == "Equipment"){
					#skip this process since equipments does not have stock cards
				}
			}
			
		}
		
		mysql_query("UPDATE `purchase_request` SET `iar_stat` = 'Done', `ris_stat`='Done' WHERE `pr_id` = '$readpr_id'");
		mysql_query("UPDATE `request_items` SET `ris_id` = '$getrisid[ris_id]' WHERE pr_id = '$readpr_id' AND issuance = 'Ready'");
		
		print "<script>alert('Requisition and Issue Slip has been saved successfully.'); window.location='issuance.php';</script>";
	}

?>