<?php

	require_once "../connect.php";

	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");

	function escapeString($str){
		return mysql_real_escape_string($str);
	}
	
	#Add Personnel
	if(isset($_POST['empSave'])){
		
		$result = "success";
		$msg = "Personnel registration has been added to the system.";
		
		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("START TRANSACTION");
		
		$idexist = mysql_fetch_array(mysql_query("SELECT count(*) FROM personnel WHERE personnel_empid = '$_POST[empid]' OR (personnel_lname = '$_POST[lname]' && personnel_fname = '$_POST[fname]' && personnel_mname = '$_POST[mname]')"));
		if($idexist[0] > 0){
			$result = "failed";
			$msg = $_POST['fname']." ".$_POST['lname']." or ID number ".$_POST['empid']." is already registered.";	
		}else{
			#set variables for successful data recording per table
			//$status_personnel = false;
			
			#record basic information
			mysql_query("LOCK TABLE personnel WRITE;");
			try{
				mysql_query("INSERT INTO `personnel` 
				(
				`personnel_lname`,
				`personnel_fname`,
				`personnel_mname`,
				`personnel_bday`,
				`personnel_bplace`,
				`personnel_civilstatus`,
				`personnel_sex`,
				`personnel_address`,
				`personnel_email`,
				`personnel_contact_no`,
				`personnel_primary_education`,
				`personnel_pe_year`,
				`personnel_secondary_education`,
				`personnel_se_year`,
				`personnel_tertiary_education`,
				`personnel_bachelor_degree`,
				`personnel_te_year`,
				`personnel_graduate_school`,
				`personnel_masters_degree`,
				`personnel_gs_year`,
				`personnel_empid`
				)
				VALUES
				(
					'".escapeString($_POST['lname'])."',
					'".escapeString($_POST['fname'])."',
					'".escapeString($_POST['mname'])."',
					'".escapeString($_POST['bdate'])."',
					'".escapeString($_POST['bplace'])."',
					'".escapeString($_POST['cvilstat'])."',
					'".escapeString($_POST['sex'])."',
					'".escapeString($_POST['address'])."',
					'".escapeString($_POST['emailadd'])."',
					'".escapeString($_POST['cpnum'])."',
					'".escapeString($_POST['prieduc'])."',
					'".escapeString($_POST['pe_year'])."',
					'".escapeString($_POST['seceduc'])."',
					'".escapeString($_POST['se_year'])."',
					'".escapeString($_POST['tereduc'])."',
					'".escapeString($_POST['bacdeg'])."',
					'".escapeString($_POST['te_year'])."',
					'".escapeString($_POST['gradsch'])."',
					'".escapeString($_POST['masdeg'])."',
					'".escapeString($_POST['gs_year'])."',
					'".escapeString($_POST['empid'])."'
				)")or die(mysql_error());
				mysql_query("COMMIT");
			}catch(Exception $e){
				mysql_query("ROLLBACK");
				$result = "failed";
				$msg = "Something went wrong when saving your Basic Information to the system.";	
			}
			
			if($result == "success"){
				#get personnel id
				//$getid = mysql_fetch_array(mysql_query("SELECT personnel_id FROM personnel WHERE personnel_id IN (SELECT MAX(personnel_id) FROM personnel) ")) or die(mysql_error()); //this only works when table personnel is unlocked.
				$getid = mysql_fetch_array(mysql_query("SELECT personnel_id FROM personnel WHERE personnel_empid = '".escapeString($_POST['empid'])."' LIMIT 1"))or die(mysql_error());
				$personnel_id = $getid[0];
				
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
				
				if((!empty($_FILES["propic"]["name"]))){
					$img = $_FILES['propic']['name'];
					$tmp = $_FILES['propic']['tmp_name'];
					$temp = explode(".", $img); //splitting the filename by using the . format to get the extension file
					$tempCount = Count($temp);
					$ext = GetImageExtension($temp[$tempCount -1]); //get image format
					if($ext){
						$newfilename = 'displaypic/'.round(microtime(true)) . '.' . end($temp); //rename the new file
						move_uploaded_file($tmp,$newfilename); //upload image file
						
					}else{
						$result = "failed";
						$msg = "This is an invalid image format.";
					}
					
					try{
						mysql_query("UPDATE `personnel` SET personnel_photo = '$newfilename' WHERE personnel_id = $personnel_id ") or die(mysql_error());
						mysql_query("COMMIT");
						}catch(Exception $e){
							mysql_query("ROLLBACK");
							$result = "failed";
							$msg = "Something went wrong when saving the Profile Picture to the system.";
						}
				}
				
				mysql_query("UNLOCK TABLE;");
				
				#record work information
				mysql_query("LOCK TABLE personnel_work_info WRITE;");
				try{
					mysql_query("INSERT INTO `personnel_work_info` 
					(
					`personnel_id`,
					`position_id`,
					`status`,
					`type`, 
					`dept_id`
					)
					VALUES
					(
						'$personnel_id',
						'$_POST[empost]',
						'$_POST[postat]',
						'$_POST[postype]',
						'$_POST[emdept]'
					)")or die(mysql_error());
					mysql_query("COMMIT");
				}catch(Exception $e){
					mysql_query("ROLLBACK");
						$result = "failed";
						$msg = "Something went wrong when saving Work Information to the system.";
				}
				mysql_query("UNLOCK TABLE;");
				
				#get personnel id in personnel_work_info
				$work_id = mysql_fetch_array(mysql_query("SELECT * FROM personnel_work_info WHERE personnel_id = '$personnel_id' "))or die(mysql_error());
				
				#record account info
				mysql_query("LOCK TABLE account WRITE;");
				try{
					$password = md5("bucnrims_2016"); #hash password as security by using md5
					
					/* if($work_id['position'] == "Dean" || $work_id['position'] == "DEAN" || $work_id['position'] == "dean"){
						mysql_query("INSERT INTO `account` VALUES
						(
							'',
							'$password',
							'Administrator',
							'activated',
							'$personnel_id',
							'$datetime'
						)")or die(mysql_error());
					}else if($work_id['dept_id'] == "6"){
						mysql_query("INSERT INTO `account` VALUES
						(
							'',
							'$password',
							'System Administrator',
							'activated',
							'$personnel_id',
							'$datetime'
						)")or die(mysql_error());
					}else{
						mysql_query("INSERT INTO `account` VALUES
						(
							'',
							'$password',
							'End User',
							'activated',
							'$personnel_id',
							'$datetime'
						)")or die(mysql_error());
					} */
					
					mysql_query("INSERT INTO `account`
					(
					`password`,
					`account_type`,
					`account_status`,
					`personnel_id`,
					`datecreated`
					)
					VALUES
						(
							'$password',
							'End User',
							'activated',
							'$personnel_id',
							'$datetime'
						)")or die(mysql_error());
					mysql_query("COMMIT");
				}catch(Exception $e){
					mysql_query("ROLLBACK");
						$result = "failed";
						$msg = "Something went wrong when saving the account information to the system.";
				}
				mysql_query("UNLOCK TABLE;");
			}
			mysql_close();
		}
		echo json_encode(array("result" => $result, "msg" => $msg));
	}
