<?php

	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");
	$date = date("Y-m-d");
	$month = date("Y-m");
	
	function escapeString($str){
		return mysql_real_escape_string($str);
	}
	
	if(isset($_POST['stckreqsave'])){
		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("START TRANSACTION");
		
		$getcrtlines = mysql_query("SELECT * FROM `cart_line` WHERE `cart_id` = '$readcart_id'");
		
		while($crtlinedata = mysql_fetch_array($getcrtlines)){
			$updatestock = mysql_query("UPDATE `stock_units` SET `quantity` = quantity - '$crtlinedata[quantity]' WHERE su_id = '$crtlinedata[su_id]'");
			$updateline = mysql_query("UPDATE `cart_line` SET `approved_quantity` = '$crtlinedata[quantity]' WHERE `cart_line_id` = '$crtlinedata[cart_line_id]'");
		
			$getstock = mysql_fetch_array(mysql_query("SELECT * FROM stock_units WHERE su_id = '$crtlinedata[su_id]'"));
		
			mysql_query("LOCK TABLE stock_card WRITE;");
			
			$genStockCardSql = "SELECT recdate,".
				" CONCAT('".$month."-',(COUNT(DATE_FORMAT(recdate, '%Y-%m')) + 1)) AS reference".
				" FROM stock_card".
				" GROUP BY DATE_FORMAT(recdate, '%Y-%m')".
				" HAVING DATE_FORMAT(recdate, '%Y-%m') = '".$month."'".
				" ORDER BY recdate DESC LIMIT 1";
			$genStockCardQry = mysql_query($genStockCardSql) ;
			if(mysql_num_rows($genStockCardQry) == 0){
				$scNum = $month."-1";
			}else{
				$genStockCardArr = mysql_fetch_array($genStockCardQry);
				$scNum = $genStockCardArr['reference'];
			}
			
			try{
				mysql_query("
				INSERT INTO `stock_card`(
				`su_id`,
				`recdate`,
				`reference`,
				`issue_qty`,
				`personnel_id`,
				`issue_stock_bal`
				) VALUES (
				'$getstock[su_id]',
				'$date',
				'$scNum',
				'$crtlinedata[quantity]',
				'$getcart[personnel_id]',
				'$getstock[quantity]'
				)");
				mysql_query("COMMIT");
			}catch(Exception $e){
				mysql_query("ROLLBACK");
				print "<script>alert('Something went wrong when saving issuance to stock card in the System. Please check your connection.')</script>";
			}
			
			mysql_query("UNLOCK TABLE;");
		
		}
		
		mysql_query("LOCK TABLE cart_status WRITE;");
		try{
			mysql_query("UPDATE `cart_status` SET `cart_status_name`='Issued' WHERE `cart_id` = '$readcart_id'");
		}catch(Exception $e){
			mysql_query("ROLLBACK");
			print "<script>alert('Something went wrong when updating the status of stock request. Please check your connection.')</script>";
		}
		mysql_query("UNLOCK TABLE;");
		
		print "<script>alert('Request has been submitted.')</script>";
		print "<script>window.location='stock_req.php';</script>";
	}
	
?>