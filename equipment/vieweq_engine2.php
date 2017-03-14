<?php

	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");

	function escapeString($str){
		return mysql_real_escape_string($str);
	}

	#Update Equipment
	if(isset($_POST['update_eqpinfo'])){
		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("START TRANSACTION");
		
		mysql_query("LOCK TABLE equipments WRITE;");
		
		try{
			mysql_query("UPDATE `equipments` SET 
			`brand` = '".escapeString($_POST['eqpbrand'])."',
			`description` = '".escapeString($_POST['eqpdesc'])."',
			`serialnum` = '".escapeString($_POST['eqpsn'])."'
			WHERE `eqp_id` = $readeqp_id
			")or die(mysql_error());
			mysql_query("COMMIT");
		}catch(Exception $e){
			mysql_query("ROLLBACK");
			print "<script>alert('Something went wrong when saving changes. Please check your connection.');</script>";
		}
		
		#getphotoupload
		function GetImageExtension($imagetype){
			$toReturn = false;
			if(empty($imagetype)) $toReturn = false;
			switch($imagetype){
				case 'bmp':
				case 'BMP':
				case 'gif':
				case 'GIF':
				case 'jpg':
				case 'JPG':
				case 'jpeg':
				case 'JPEG':
				case 'png':
				case 'PNG': $toReturn = true;
				break;
				default: $toReturn = false;
			}
			return $toReturn;
		}
		
		if((!empty($_FILES["eqpic"]["name"]))){
			$img = $_FILES['eqpic']['name'];
			$tmp = $_FILES['eqpic']['tmp_name'];
			$temp = explode(".", $img); //splitting the filename by using the . format to get the extension file
			$tempCount = Count($temp);
			$ext = GetImageExtension($temp[$tempCount -1]); //get image format
			if($ext){
				$newfilename = 'eqpics/'.round(microtime(true)) . '.' . end($temp); //rename the new file
				move_uploaded_file($tmp,$newfilename); //upload image file
				
			}else{
				print "<script>alert('This is an invalid image format.')</script>";
			}
			
			try{
				mysql_query("UPDATE `equipments` SET `eqp_photo` = '$newfilename' WHERE `eqp_id` = '$readeqp_id' ") or die(mysql_error());
				mysql_query("COMMIT");
				}catch(Exception $e){
					mysql_query("ROLLBACK");
					print "<script>alert('Error has been occured when saving the Picture to the system.')</script>";
				}
		}
		
		mysql_query("UNLOCK TABLE;");
		print "<script>alert('Changes has succesfully saved.'); window.location='view_eq2.php?id=".$readeqp_id."'; </script>";
		mysql_close();
	}

?>