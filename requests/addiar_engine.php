<?php

	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");
	$date = date("Y-m-d");
	$month = date("Y-m");
	
	function escapeString($str){
		return mysql_real_escape_string($str);
	}
	
	#Add IAReport
	if(isset($_POST['iarsave'])){
		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("START TRANSACTION");

		$genIARSql = "SELECT iardate,".
			" CONCAT('".$month."-',(COUNT(DATE_FORMAT(iardate, '%Y-%m')) + 1)) AS iarNum".
			" FROM inspect_accept_report".
			" GROUP BY DATE_FORMAT(iardate, '%Y-%m')".
			" HAVING DATE_FORMAT(iardate, '%Y-%m') = '".$month."'".
			" ORDER BY iardate DESC LIMIT 1";
		$genIARQry = mysql_query($genIARSql) or die(mysql_error());
		if(mysql_num_rows($genIARQry) == 0){
			$iarNum = $month."-1";
		}else{
			$genIARArr = mysql_fetch_array($genIARQry);
			$iarNum = $genIARArr['iarNum'];
		}
			
			mysql_query("LOCK TABLE inspect_accept_report WRITE;");
			
			try{
				mysql_query("INSERT INTO inspect_accept_report (po_id, iarnumber, iardate, invoice_num, invoice_date, inspection_id, personnel_id) VALUES 
				(
				'$readpo_id',
				'$iarNum',
				'$date',
				'".escapeString($_POST['invoicenum'])."',
				'".escapeString($_POST['invoicedate'])."',
				'$getins[inspection_id]',
				'".$_SESSION['logged_personnel_id']."'
				)")or die(mysql_error());
				mysql_query("COMMIT");
			}catch(Exception $e){
				mysql_query("ROLLBACK");
				print "<script>alert('Something went wrong when saving your report to the System. Please check your connection.')</script>";
			}
			mysql_query("UNLOCK TABLE;");
			
			#get IAR id
			$getiarid = mysql_fetch_array(mysql_query("SELECT * FROM inspect_accept_report WHERE iar_id IN (SELECT MAX(iar_id) FROM inspect_accept_report) ")); //this makes the select last id recorded.
			$iarid = $getiarid[0];
			
			$getiarinfo = mysql_fetch_array(mysql_query("SELECT * FROM `inspect_accept_report` WHERE iar_id = '$iarid'"));
			
			//insert the function to add items here
			/* $select = "ri.req_item_id, i.item_id, i.item_name, i.item_type, iu.item_unit_id, iu.item_unit_name, ri.description, ri.quantity, ri.est_unit_cost";
			$from = "request_items AS ri LEFT JOIN items AS i ON i.item_id = ri.item_id LEFT JOIN item_unit AS iu ON iu.item_unit_id = ri.item_unit_id";
			$getpoitems = mysql_query("SELECT ".$select." FROM ".$from." WHERE po_id = '$readpo_id' AND instat = 'Complete'"); */
			
			$getpoitems = mysql_query("SELECT ri.*, i.item_type FROM `request_items` AS ri LEFT JOIN items AS i ON i.item_id = ri.item_id WHERE ri.po_id = '$readpo_id' AND ri.del_quantity != '0'");
			
			while($getme = mysql_fetch_array($getpoitems)){
				
				
				if($getme['item_type'] == "Supply"){
					//$getdesc = mysql_query("SELECT stock_id, description FROM stock_items WHERE description = '$getme[description]' LIMIT 1") or die(mysql_error());
					$getdesc = mysql_query("SELECT su.su_id, si.stock_id, si.item_id, si.description, su.item_unit_id FROM `stock_items` AS si LEFT JOIN `stock_units` AS su ON su.stock_id = si.stock_id WHERE si.item_id = '$getme[item_id]' AND si.description = '$getme[description]' AND su.item_unit_id = '$getme[item_unit_id]' LIMIT 1");
					$getdescrows = mysql_num_rows($getdesc);
					
					if($getdescrows == 0){
						
							mysql_query("INSERT INTO `stock_items`(`item_id`, `stock_type`, `description`) VALUES
							(
							'$getme[item_id]',
							'$getme[item_type]',
							'$getme[description]'
							)");
								
							#get new stock id
							$getstockid = mysql_fetch_array(mysql_query("SELECT stock_id FROM stock_items WHERE stock_id IN (SELECT MAX(stock_id) FROM stock_items) ")); //this makes the select last id recorded.
							$stockid = $getstockid[0];
							
							mysql_query("INSERT INTO `stock_units`(`stock_id`, `item_unit_id`, `price`, `quantity`) VALUES 
							(
							'$stockid',
							'$getme[item_unit_id]',
							'$getme[est_unit_cost]',
							'$getme[del_quantity]'
							)");
							
							#get new stockunit id
							$getsuid = mysql_fetch_array(mysql_query("SELECT su_id FROM stock_units WHERE su_id IN (SELECT MAX(su_id) FROM stock_units) ")); //this makes the select last id recorded.
							$suid = $getsuid[0];
							
							mysql_query("UPDATE `stock_units` SET `stock_no`='".$getme['item_id']."-".$stockid."-".$suid."' WHERE su_id = '$suid'");
							
							mysql_query("UPDATE request_items SET su_id = '$suid', `issuance` = 'Ready' WHERE req_item_id = '$getme[req_item_id]'");
							
							/* try{
								mysql_query("INSERT INTO `stock_supplier`(
								`stock_id`,
								`supplier_id`,
								`dateacquired`,
								`receipt_qty`
								) VALUES (
								'$getme[item_id]',
								'$getsupplier[supplier_id]',
								'$getiarinfo[iardate]',
								'$getme[quantity]'
								)")or die(mysql_error());
								mysql_query("COMMIT");
							}catch(Exception $e){
								mysql_query("ROLLBACK");
								print "<script>alert('Something went wrong when recording the issuance log to the System. Please check your connection.')</script>";
							}
							mysql_query("UNLOCK TABLE;"); */
							
						
							/* print $getme['description'];
							print " - WALA SA RECORD";
							print "<br />"; */
					}else{
						while($getdescinfo = mysql_fetch_array($getdesc)){
							
							mysql_query("UPDATE `stock_units` SET 
									`price` = '$getme[est_unit_cost]',
									`quantity` = quantity + '$getme[del_quantity]'
									WHERE `su_id` = '$getdescinfo[su_id]'
							");
							
							mysql_query("UPDATE request_items SET su_id = '$getdescinfo[su_id]', `issuance` = 'Ready'  WHERE req_item_id = '$getme[req_item_id]'");
							
							/* print $getdescinfo['description']." - ";
							print $getme['description'];
							print " - MERON NA SA RECORD!";
							print "<br />"; */
						}
					}
				}if($getme['item_type'] == "Equipment"){
					//$getdesc = mysql_query("SELECT stock_id, description FROM stock_items WHERE description = '$getme[description]' LIMIT 1") or die(mysql_error());
					$getdesc = mysql_query("SELECT su.su_id, si.stock_id, si.item_id, si.description, su.item_unit_id FROM `stock_items` AS si LEFT JOIN `stock_units` AS su ON su.stock_id = si.stock_id WHERE si.item_id = '$getme[item_id]' AND si.description = '$getme[description]' AND su.item_unit_id = '$getme[item_unit_id]' LIMIT 1");
					$getdescrows = mysql_num_rows($getdesc);
					
					if($getdescrows == 0){
						
							mysql_query("INSERT INTO `stock_items`(`item_id`, `stock_type`, `description`) VALUES
							(
							'$getme[item_id]',
							'$getme[item_type]',
							'$getme[description]'
							)");
								
							#get new stock id
							$getstockid = mysql_fetch_array(mysql_query("SELECT stock_id FROM stock_items WHERE stock_id IN (SELECT MAX(stock_id) FROM stock_items) ")); //this makes the select last id recorded.
							$stockid = $getstockid[0];
							
							mysql_query("INSERT INTO `stock_units`(`stock_id`, `item_unit_id`, `price`, `quantity`) VALUES 
							(
							'$stockid',
							'$getme[item_unit_id]',
							'$getme[est_unit_cost]',
							'$getme[del_quantity]'
							)");
							
							#get new stockunit id
							$getsuid = mysql_fetch_array(mysql_query("SELECT su_id FROM stock_units WHERE su_id IN (SELECT MAX(su_id) FROM stock_units) ")); //this makes the select last id recorded.
							$suid = $getsuid[0];
							
							mysql_query("UPDATE `stock_units` SET `stock_no`='".$getme['item_id']."-".$stockid."-".$suid."' WHERE su_id = '$suid'");
							
							mysql_query("UPDATE request_items SET su_id = '$suid', `issuance` = 'Not Ready' WHERE req_item_id = '$getme[req_item_id]'");
							
							/* try{
								mysql_query("INSERT INTO `stock_supplier`(
								`stock_id`,
								`supplier_id`,
								`dateacquired`,
								`receipt_qty`
								) VALUES (
								'$getme[item_id]',
								'$getsupplier[supplier_id]',
								'$getiarinfo[iardate]',
								'$getme[quantity]'
								)")or die(mysql_error());
								mysql_query("COMMIT");
							}catch(Exception $e){
								mysql_query("ROLLBACK");
								print "<script>alert('Something went wrong when recording the issuance log to the System. Please check your connection.')</script>";
							}
							mysql_query("UNLOCK TABLE;"); */
							
						
							/* print $getme['description'];
							print " - WALA SA RECORD";
							print "<br />"; */
					}else{
						while($getdescinfo = mysql_fetch_array($getdesc)){
							
							mysql_query("UPDATE `stock_units` SET 
								`price` = '$getme[est_unit_cost]',
								`quantity` = quantity + '$getme[del_quantity]'
								WHERE `su_id` = '$getdescinfo[su_id]'
							");
							
							mysql_query("UPDATE request_items SET su_id = '$getdescinfo[su_id]', `issuance` = 'Not Ready'  WHERE req_item_id = '$getme[req_item_id]'");
							
							/* print $getdescinfo['description']." - ";
							print $getme['description'];
							print " - MERON NA SA RECORD!";
							print "<br />"; */
						}
					}
				}
				
					
			}
			
			#update request items, purchase order and requisition status
			$updateRI = "UPDATE request_items SET iar_id = '$iarid', accstat = 'Accepted' WHERE po_id = '$readpo_id' AND del_quantity != '0'";
			mysql_query($updateRI);
			
			mysql_query("UPDATE `purchase_order` SET `IARstat`='Complete' WHERE `po_id` = '$readpo_id'");
			
			/* $datarrayone = mysql_num_rows(mysql_query("SELECT `accstat` FROM request_items WHERE pr_id = '$getpo[pr_id]'"));
			$datarraytwo = mysql_num_rows(mysql_query("SELECT `accstat` FROM request_items WHERE pr_id = '$getpo[pr_id]' AND accstat = 'Accepted'"));
			
			$subtract = ($datarrayone - $datarraytwo);
			
			if ($subtract == 0){
				mysql_query("UPDATE `purchase_request` SET `iar_stat` = 'Completed' WHERE pr_id = '$getpo[pr_id]'");
			}else{
				mysql_query("UPDATE `purchase_request` SET `iar_stat` = 'Completing' WHERE pr_id = '$getpo[pr_id]'");
			} */
			
			mysql_query("UPDATE `requisition_status` SET iar_id = '$iarid', `status`='Acceptance Complete' WHERE `po_id` = '$readpo_id'");
			
			mysql_query("UPDATE `purchase_request` SET `iar_stat` = 'Completed' WHERE pr_id = '$getpo[pr_id]'");
		
		print "<script>alert('Inspection and Acceptance Report has been saved successfully.'); window.location='status.php';</script>";
	}

?>

<script>

/* 	$(document).ready(function(){
		
		$("#addItem").on("show.bs.modal", function(event){
			var button = $(event.relatedTarget);
			data = button.data('id');
			request_data = data.split('|');
			
			$("#getreitemID").val(request_data[0]);
			$("#getItem").text(request_data[1]);
			$("#getItemUnit").text(request_data[2]);
			$("#getDescription").text(request_data[3]);
			$("#getQuantity").val(request_data[4]);
			$("#getCost").text(request_data[5]);
		});
		
		$("#savePOFundBtn").click(function(event){
			var POid = $("#getpoId").val();
			///var POfund = $("#fund").val();
			var OSnum = $("#POOSNum").val();
			var FundAmount = $("#amount").val();

			var hr = new XMLHttpRequest();
				var url = "savePOFund.php";
				var vars = "POid="+POid+"&OSnum="+OSnum+"&FundAmount="+FundAmount;

				hr.open("POST", url, true);
				hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

				hr.onreadystatechange = function() {
				if(hr.readyState == 4 && hr.status == 200) {
				  var return_data = hr.responseText;
					document.getElementById("saveFundingResult").innerHTML = return_data;
					window.location = "po_budget.php";
					
				  }
				}
				hr.send(vars);
				document.getElementById("saveFundingResult").innerHTML = "Funding the Order...";
			
		});
	
	}); */

</script>