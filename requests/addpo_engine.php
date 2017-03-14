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
	if(isset($_POST['posave'])){
		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("START TRANSACTION");
		
		/* $poexist = mysql_fetch_array(mysql_query("SELECT count(*) FROM purchase_order WHERE ponumber = '$_POST[ponum]'"));
		if($poexist[0] > 0){
			print "<script>alert('Purchase Order Number ".$_POST['ponum']." is already in your orders.')</script>";
		}else{ */
		
			$genPoSql = "SELECT podate,".
				" CONCAT('".$month."-',(COUNT(DATE_FORMAT(podate, '%Y-%m')) + 1)) AS poNum".
				" FROM purchase_order".
				" GROUP BY DATE_FORMAT(podate, '%Y-%m')".
				" HAVING DATE_FORMAT(podate, '%Y-%m') = '".$month."'".
				" ORDER BY podate DESC LIMIT 1";
			$genPOQry = mysql_query($genPoSql) or die(mysql_error());
			if(mysql_num_rows($genPOQry) == 0){
				$poNum = $month."-1";
			}else{
				$genPOArr = mysql_fetch_array($genPOQry);
				$poNum = $genPOArr['poNum'];
			}
			
			mysql_query("LOCK TABLE purchase_order WRITE;");
			
			try{
				mysql_query("INSERT INTO `purchase_order`
				(`ponumber`,
				`supplier_id`,
				`podate`,
				`modepayment`,
				`delivery_place`,
				`delivery_term`,
				`orig_deliveryterm`,
				`payment_term`,
				`allitem_nums`,
				`orig_allitemnums`,
				`pr_id`,
				`personnel_id`)
				VALUES
				(
				'$poNum',
				'$_POST[supplier]',
				'$date',
				'Check',
				'BUCN',
				'".escapeString($_POST['delivery_term'])."',
				'".escapeString($_POST['delivery_term'])."',
				'".escapeString($_POST['payment_term'])."',
				'".escapeString($_POST['total_items_nums'])."',
				'".escapeString($_POST['total_items_nums'])."',
				'$readpr_id',
				'".$_SESSION['logged_personnel_id']."'
				)
				")or die(mysql_error());
				mysql_query("COMMIT");
			}catch(Exception $e){
				mysql_query("ROLLBACK");
				print "<script>alert('Something went wrong when saving your order to the System.')</script>";
			}
			
			mysql_query("UNLOCK TABLE;");
			
			#get PO id
			$getpoid = mysql_fetch_array(mysql_query("SELECT po_id FROM purchase_order WHERE po_id IN (SELECT MAX(po_id) FROM purchase_order) "))or die(mysql_error()); //this makes the select last id recorded.
			$poid = $getpoid[0];
			
			#set item orders
			$in = "(". implode(", ", $_POST['getitemid']) .")";
			$updatesql = "UPDATE request_items SET po_id = '$poid' WHERE req_item_id IN ".$in." AND pr_id = ".$readpr_id." ";
			mysql_query($updatesql);
			
			#register po to list of funding
			mysql_query("LOCK TABLE funding WRITE;");
			try{
				mysql_query("INSERT INTO `funding`(`po_id`, `status`) VALUES ('$poid', 'pending')")or die(mysql_error());
				mysql_query("COMMIT");
			}catch(Exception $e){
				mysql_query("ROLLBACK");
				print "<script>alert('Something went wrong when getting your purchase order ready for funding. Please check your connection.')</script>";
			}
			mysql_query("UNLOCK TABLE;");
			
			#update requisition status for tracking of items
			mysql_query("LOCK TABLE requisition_status WRITE;");
			try{
				$setrecord = mysql_query(" INSERT INTO `requisition_status`(`po_id`, `status`, `requestor`)
					VALUES
					(
					'$poid',
					'pending',
					'$getpr[personnel_id]'
					)")or die(mysql_error());
					mysql_query("COMMIT");
			}catch(Exception $e){
				mysql_query("ROLLBACK");
				print "<script>alert('Something went wrong when placing your order in the tracking. Please check your connection.')</script>";
			}
			mysql_query("UNLOCK TABLE;");
			
		print "<script>alert('Your Purchase Order has been saved successfully.'); window.location='purchase_order.php';</script>";
	}

?>