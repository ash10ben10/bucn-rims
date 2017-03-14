<?php
	
	//include "../connect.php";

	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");

	function escapeString($str){
		return mysql_real_escape_string($str);
	}
	
	#Update Personnel
		if(isset($_POST['update_viewinfo'])){
			mysql_query("SET AUTOCOMMIT=0");
			mysql_query("START TRANSACTION");
			
			mysql_query("LOCK TABLE personnel WRITE;");
			
			try{
				mysql_query("UPDATE personnel SET
					personnel_lname = '".escapeString($_POST['lname'])."',
					personnel_fname = '".escapeString($_POST['fname'])."',
					personnel_mname = '".escapeString($_POST['mname'])."',
					personnel_bday = '".escapeString($_POST['bdate'])."',
					personnel_bplace = '".escapeString($_POST['bplace'])."',
					personnel_civilstatus = '".escapeString($_POST['cvilstat'])."',
					personnel_sex = '".escapeString($_POST['sex'])."',
					personnel_address = '".escapeString($_POST['address'])."',
					personnel_email = '".escapeString($_POST['emailadd'])."',
					personnel_contact_no = '".escapeString($_POST['cpnum'])."',
					personnel_primary_education = '".escapeString($_POST['prieduc'])."',
					personnel_pe_year = '".escapeString($_POST['pe_year'])."',
					personnel_secondary_education = '".escapeString($_POST['seceduc'])."',
					personnel_se_year = '".escapeString($_POST['se_year'])."',
					personnel_tertiary_education = '".escapeString($_POST['tereduc'])."',
					personnel_bachelor_degree = '".escapeString($_POST['bacdeg'])."',
					personnel_te_year = '".escapeString($_POST['te_year'])."',
					personnel_graduate_school = '".escapeString($_POST['gradsch'])."',
					personnel_masters_degree = '".escapeString($_POST['masdeg'])."',
					personnel_gs_year = '".escapeString($_POST['gs_year'])."',
					personnel_empid = '".escapeString($_POST['empid'])."'
					WHERE personnel_id = '$read_id'
				")or die(mysql_error());
				mysql_query("COMMIT");
			}catch(Exception $e){
				mysql_query("ROLLBACK");
				print "<script>alert('Something went wrong when saving changes. Please check your connection.'); window.location='viewinfo.php?id=".$read_id."';</script>";
			}
			mysql_query("UNLOCK TABLE;");
			
			#update work information
			mysql_query("LOCK TABLE personnel_work_info WRITE;");
			try{
				mysql_query("UPDATE `personnel_work_info` SET
					position_id = '$_POST[empost]',
					status = '$_POST[postat]',
					type = '$_POST[postype]',
					dept_id = '$_POST[emdept]'
					WHERE personnel_id = '$read_id'
				")or die(mysql_error());
				mysql_query("COMMIT");
			}catch(Exception $e){
				mysql_query("ROLLBACK");
				print "<script>alert('Something went wrong when saving Work Information to the system.')</script>";
			}
			mysql_query("UNLOCK TABLE;");
			
			/* #get personnel id in personnel_work_info
			$update = mysql_fetch_array(mysql_query("SELECT * FROM personnel_work_info WHERE personnel_id = '$read_id' "))or die(mysql_error());
			
			#record account info for updating privileges
			mysql_query("LOCK TABLE account WRITE;");
			try{
				if($update['position'] == "Dean" || $update['position'] == "DEAN" || $update['position'] == "dean"){
					mysql_query("UPDATE `account` SET
						account_type = 'Administrator'
						WHERE personnel_id = '$read_id'
					")or die(mysql_error());
				}else if($update['dept_id'] == "6"){
					mysql_query("UPDATE `account` SET
						account_type = 'System Administrator'
						WHERE personnel_id = '$read_id'
						")or die(mysql_error());
				}mysql_query("COMMIT");
			}catch(Exception $e){
				mysql_query("ROLLBACK");
				print "<script>alert('Error has been occured when saving the account information to the system.')</script>";
			}
			mysql_query("UNLOCK TABLE;"); */
			
			
			print "<script>alert('Changes has succesfully saved.'); window.location='viewinfo.php?id=".$read_id."'; </script>";
			mysql_close();
		}
?>