<?php

	require "../connect.php";

	$SupplierNo = $_POST['saveSupplierNo'];
	$SupplierTin = $_POST['saveSupplierTin'];
	$SupplierName = $_POST['saveSupplierName'];
	$SupplierAddress = $_POST['saveSupplierAddress'];
	$SupplierContact = $_POST['saveSupplierContact'];
	
	mysql_query("SET AUTOCOMMIT=0");
	mysql_query("START TRANSACTION");
	
	mysql_query("LOCK TABLE supplier WRITE;");
	
	try{
		mysql_query("UPDATE `supplier` SET 
			`supplier_name`='$SupplierName',
			`supplier_tin_no`='$SupplierTin',
			`supplier_contact_no`='$SupplierContact',
			`supplier_address`='$SupplierAddress'
			WHERE supplier_id = '$SupplierNo'") or die(mysql_error());
		mysql_query("COMMIT");
	}catch(Exception $e){
		mysql_query("ROLLBACK");
		print "<script>alert('Something went wrong when saving the entries. Plase check your connection.')</script>";
	}
	mysql_query("UNLOCK TABLE;");
?>