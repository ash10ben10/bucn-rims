<?php

	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");
	$date = date("Y-m-d");
	$month = date("Y-m");

	function escapeString($str){
		return mysql_real_escape_string($str);
	}
	
	#Update Stock Info
		if(isset($_POST['update_stockinfo'])){
			mysql_query("SET AUTOCOMMIT=0");
			mysql_query("START TRANSACTION");
			
			mysql_query("UPDATE stock_items SET
				description = '".escapeString($_POST['stockdesc'])."',
				order_point = '".escapeString($_POST['orderpoint'])."'
				WHERE stock_id = '$getdesc[stock_id]' AND stock_type = 'Supply'
			");
			
			/* mysql_query("UPDATE stock_units SET
				stock_no = '".escapeString($_POST['stocknum'])."'
				WHERE su_id = '$readstockunit_id'
			"); */
			
			print "<script>alert('Changes has succesfully saved.'); window.location='stockview.php?id=".$readstockunit_id."'; </script>";
		
			}

?>