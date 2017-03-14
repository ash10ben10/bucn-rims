<?php

	$getid = $_GET['id'];
	$getqty = $_GET['type'];
	
	include "../connect.php";
	
	$stockcart = mysql_fetch_array(mysql_query("SELECT su.quantity FROM `cart_line` AS cl LEFT JOIN stock_units AS su ON su.su_id = cl.su_id WHERE cl.cart_line_id = $getid"));
	
	if($getqty == 0 || $getqty == "0" || $getqty > $stockcart['quantity']){
		print "<script>alert('You cannot enter an empty quantity or a quantity that exceeds the remaining quantity from stock.');</script>";
		$cartID = mysql_fetch_array(mysql_query("SELECT `cart_id` FROM `cart_line` WHERE `cart_line_id` = $getid"));
		print "<script>window.location='view_stock_req.php?id=".$cartID['cart_id']."';</script>";
	}else{
		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("START TRANSACTION");
		
		mysql_query("LOCK TABLE cart_line WRITE;");
		
		try{
			mysql_query("UPDATE cart_line SET quantity = '$getqty' WHERE cart_line_id = '$getid'")or die(mysql_error());
			mysql_query("UPDATE cart_line SET `approved_quantity` = '$getqty' WHERE cart_line_id = '$getid'")or die(mysql_error());
			mysql_query("COMMIT");
		}catch(Exception $e){
			mysql_query("ROLLBACK");
			print "<script>alert('Something went wrong then changing the quantity. Plase check your connection.')</script>";
		}
		
		mysql_query("UNLOCK TABLE;");
		
		$cartID = mysql_fetch_array(mysql_query("SELECT `cart_id` FROM `cart_line` WHERE `cart_line_id` = $getid"));
		
		print "<script>window.location='view_stock_req.php?id=".$cartID['cart_id']."';</script>";
	}
	
	
?>