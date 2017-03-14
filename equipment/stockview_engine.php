<?php

	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");

	function escapeString($str){
		return mysql_real_escape_string($str);
	}
	
	#Update Stock Info
		if(isset($_POST['update_stockinfo'])){
			mysql_query("SET AUTOCOMMIT=0");
			mysql_query("START TRANSACTION");
			
			/* $entryexist = mysql_fetch_array(mysql_query("SELECT * FROM stock_items WHERE stock_no = '$_POST[stocknum]' "));
			if($entryexist[0] > 0){
				print "<script>alert('Stock Number ".$_POST['stocknum']." is already in the stocks.')</script>";
			}else{ */
		
			mysql_query("LOCK TABLE stock_items WRITE;");
			
			try{
				mysql_query("UPDATE stock_items SET
					stock_no = '".escapeString($_POST['stocknum'])."',
					description = '".escapeString($_POST['stockdesc'])."',
					unit_cost = '".escapeString($_POST['stockamount'])."',
					order_point = '".escapeString($_POST['stockorderpoint'])."'
					WHERE stock_id = '$readstock_id' AND stock_type = 'Supply'
				")or die(mysql_error());
				mysql_query("COMMIT");
			}catch(Exception $e){
				mysql_query("ROLLBACK");
				print "<script>alert('Something went wrong when saving changes. Please check your connection.'); window.location='stockview.php?id=".$readstock_id."';</script>";
			}
			
			mysql_query("UNLOCK TABLE;");
			print "<script>alert('Changes has succesfully saved.'); window.location='stockview.php?id=".$readstock_id."'; </script>";
			mysql_close();
			
			//}
		}

?>