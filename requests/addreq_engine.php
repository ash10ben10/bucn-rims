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
	
	#Add Purchase Request
	if(isset($_POST['prsave'])){
		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("START TRANSACTION");
			
		/* $reqexist = mysql_fetch_array(mysql_query("SELECT count(*) FROM purchase_request WHERE prnum = '$_POST[reqnum]'"));
		if($reqexist[0] > 0){
			print "<script>alert('Purchase Request Number ".$_POST['reqnum']." is already in your requests.')</script>";
		}else{ */
			
			$one = mysql_fetch_array(mysql_query("SELECT pwi_id FROM personnel_work_info WHERE personnel_id = ".$personnel['personnel_id']."")) or die(mysql_error());
			$getpwi = $one[0];
			
			$genPrSql = "SELECT prdate,".
				" CONCAT('".$month."-',(COUNT(DATE_FORMAT(prdate, '%Y-%m')) + 1)) AS prNum".
				" FROM purchase_request".
				" GROUP BY DATE_FORMAT(prdate, '%Y-%m')".
				" HAVING DATE_FORMAT(prdate, '%Y-%m') = '".$month."'".
				" ORDER BY prdate DESC LIMIT 1";
			$genPRQry = mysql_query($genPrSql) or die(mysql_error());
			if(mysql_num_rows($genPRQry) == 0){
				$prNum = $month."-1";
			}else{
				$genPRArr = mysql_fetch_array($genPRQry);
				$prNum = $genPRArr['prNum'];
			}
			
			mysql_query("LOCK TABLE purchase_request WRITE;");
			
			try{	
				mysql_query("INSERT INTO `purchase_request` (`office_dept`, `dept_id`, `prnum`, `sai_no`, `purpose`, `pur_type`, `prdate`, `personnel_id`, `pwi_id`)
				VALUES
				(
					'BUCN',
					'$_POST[reqsec]',
					'$prNum',
					'".escapeString($_POST['reqsai'])."',
					'".escapeString($_POST['reqpur'])."',
					'".escapeString($_POST['purtype'])."',
					'".$date."',
					'".$personnel['personnel_id']."',
					'$getpwi'
				)")or die(mysql_error());
				mysql_query("COMMIT");
				//mysql_query("UNLOCK TABLE;");
			}catch(Exception $e){
				mysql_query("ROLLBACK");
				print "<script>alert('Something went wrong when saving your request to the System. Please check your connection.')</script>";
			}
			
			mysql_query("UNLOCK TABLE;");
			
			#get request id
			$getreqid = mysql_fetch_array(mysql_query("SELECT pr_id FROM purchase_request WHERE pr_id IN (SELECT MAX(pr_id) FROM purchase_request) "))or die(mysql_error()); //this makes the select last id recorded.
			//$getreqid = mysql_fetch_array(mysql_query("SELECT pr_id FROM purchase_request WHERE prnum LIKE '%".escapeString($_POST['reqnum'])."%' "))or die(mysql_error()); //other ways of finding the request. Use this for alternative options.
			$reqid = $getreqid[0];
			
			#record request items from purchase request
			if($_POST['reqCtr'] > 0){
				for($a=1; $a<=$_POST['reqCtr']; $a++){
					if(isset($_POST["item".$a])){
						$item = escapeString($_POST["item".$a]);
						$unit = escapeString($_POST["unit".$a]);
						$desc = escapeString($_POST["desc".$a]);
						$qty = escapeString($_POST["qty".$a]);
						$estone = escapeString($_POST["estone".$a]);
						$esttotal = escapeString($_POST["esttotal".$a]);
						
						mysql_query("LOCK TABLE request_items WRITE;");
						try{
							mysql_query("INSERT INTO `request_items` (`item_id`, `item_unit_id`, `description`, `quantity`, `qty_orig`, `est_unit_cost`, `est_total_cost`, `pr_id`, `datecreated`, `pr_status`)
							VALUES
							(
								'$item',
								'$unit',
								'$desc',
								'$qty',
								'$qty',
								'$estone',
								'$esttotal',
								'$reqid',
								'".$datetime."',
								'pending'
							)
							") or die(mysql_error());
							mysql_query("COMMIT");
						}catch(Exception $e){
							mysql_query("ROLLBACK");
							print "<script>alert('Something went wrong when saving the requested items to the system.')</script>";
						}
						mysql_query("UNLOCK TABLE");
					}
				}
			}
			
			/* #set status of the request
			mysql_query("LOCK TABLE purchase_request_status WRITE;");
			
			try{	
				mysql_query("INSERT INTO `purchase_request_status` (`pr_status`, `pr_id`)
				VALUES
				(
					'Pending',
					'$reqid'
				)")or die(mysql_error());
				mysql_query("COMMIT");
				//mysql_query("UNLOCK TABLE;");
			}catch(Exception $e){
				mysql_query("ROLLBACK");
				print "<script>alert('Something went wrong when setting your request status to the System.')</script>";
			}
			mysql_query("UNLOCK TABLE;"); */
			print "<script>alert('Your purchase request has been added to the system.'); window.location='req.php';</script>";
		
	}else if(isset($_POST['func'])){
		if($_POST['func'] == "getItemDescription"){
			//$getdesc = mysql_query("SELECT description FROM stock_items WHERE item_id = '".mysql_escape_string($_POST['item_id'])."' ORDER BY description");
			$getdesc = mysql_query("SELECT * FROM `more_desc` WHERE `munit_id` = '".mysql_escape_string($_POST['munit_id'])."'");
			$counter = 0;
			$data = array();
			while($row = mysql_fetch_array($getdesc)){
				$data[$counter]["desc"] = $row["description"];
				$data[$counter++]["price"] = $row["price"];
			}
			echo json_encode(array("data" => $data));
		}
	}else if(isset($_POST['getunit'])){
		if($_POST['getunit'] == "getItemUnit"){
			$getitemunit = mysql_query("SELECT mu.munit_id, mu.item_unit_id, iu.item_unit_name FROM `more_units` AS mu LEFT JOIN `item_unit` AS iu ON iu.item_unit_id = mu.item_unit_id WHERE mu.item_id = '".mysql_escape_string($_POST['item_id'])."'");
			$counter = 0;
			$unit = array();
			while($row = mysql_fetch_array($getitemunit)){
				$unit[$counter]["unitid"] = $row["item_unit_id"];
				$unit[$counter]["munit"] = $row["munit_id"];
				$unit[$counter++]["unitname"] = $row["item_unit_name"];
			}
			echo json_encode(array("data" => $unit));
		}
	}
	
			
?>