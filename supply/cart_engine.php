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
	
	#Add Carting
	if(isset($_POST['cartsave'])){
		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("START TRANSACTION");
		
		$genCartSql = "SELECT cartdate,".
			" CONCAT('".$month."-',(COUNT(DATE_FORMAT(cartdate, '%Y-%m')) + 1)) AS cartnum".
			" FROM cart".
			" GROUP BY DATE_FORMAT(cartdate, '%Y-%m')".
			" HAVING DATE_FORMAT(cartdate, '%Y-%m') = '".$month."'".
			" ORDER BY cartdate DESC LIMIT 1";
		$genCartQry = mysql_query($genCartSql) ;
		if(mysql_num_rows($genCartQry) == 0){
			$cartNum = "CART-".$month."-1";
		}else{
			$genCartArr = mysql_fetch_array($genCartQry);
			$cartNum = "CART-".$genCartArr['cartnum'];
		}
		
		mysql_query("LOCK TABLE cart WRITE;");
		
		try{
			mysql_query("INSERT INTO `cart`(
			`cartnum`,
			`cartdate`,
			`personnel_id`
			) VALUES (
			'$cartNum',
			'$date',
			'".$_SESSION['logged_personnel_id']."'
			)");
			mysql_query("COMMIT");
		}catch(Exception $e){
			mysql_query("ROLLBACK");
			print "<script>alert('Something went wrong when saving your request to the System. Please check your connection.')</script>";
		}
		
		mysql_query("UNLOCK TABLE;");
		
		#get request id
		$getcartid = mysql_fetch_array(mysql_query("SELECT cart_id FROM cart WHERE cart_id IN (SELECT MAX(cart_id) FROM cart) ")); //this makes the select last id recorded.
		$cartid = $getcartid[0];
		
		#record request items from carting
		if($_POST['reqCtr'] > 0){
			for($a=1; $a<=$_POST['reqCtr']; $a++){
				if(isset($_POST["item".$a])){
					$item = escapeString($_POST["item".$a]);
					$qty = escapeString($_POST["qty".$a]);
					
					#insert saving query here
					mysql_query("LOCK TABLE cart_line WRITE;");
					try{
						mysql_query("INSERT INTO `cart_line`(
						`cart_id`,
						`su_id`,
						`quantity`,
						`requesting_quantity`,
						`requestor`
						) VALUES (
						'$cartid',
						'$item',
						'$qty',
						'$qty',
						'".$_SESSION['logged_personnel_id']."'
						)");
						mysql_query("COMMIT");
					}catch(Exception $e){
						mysql_query("ROLLBACK");
						print "<script>alert('Something went wrong when saving the requested items to the system.')</script>";
					}
					mysql_query("UNLOCK TABLE");
				}
			}
		}
		
		#set carting status
		mysql_query("LOCK TABLE cart_status WRITE;");
		
		try{	
			mysql_query("INSERT INTO `cart_status` (`cart_id`, `cart_status_name`)
			VALUES
			(
				'$cartid',
				'Requested'
			)");
			mysql_query("COMMIT");
		}catch(Exception $e){
			mysql_query("ROLLBACK");
			print "<script>alert('Something went wrong when setting your carting status to the System.')</script>";
		}
		mysql_query("UNLOCK TABLE;");
		print "<script>alert('Your request has been sent to the supply office.'); window.location='stock_req.php';</script>";
		
	}
?>