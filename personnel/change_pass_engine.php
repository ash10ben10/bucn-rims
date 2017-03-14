<?php

	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");


	#Update Password
	function update_pwd($new, $check){
		$password = $new;
		if(strlen($password) < 4 )
			echo '<div class="form-group"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Your password is weak. Minimum number of characters is 5.</div></div>';
		else{
			#validate password by letter, number, or symbols
			$letter = False;
			$number = False;
			$specialChar = False;
			
			$letterU_list = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z'];
			$letterL_list = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
			$specialChar_list = [',', '.', '/', "'", ';', ']', '[', '=', '-'. '<', '>', '?', '"', ':', '}', '{', '+', '_', ')', '(', '*', '&', '^', '%', '$', '#', '@', '!', '`', '~'];
			
			for($i = 0 ; $i < strlen ($password); $i++){
				//check the letter/s
				for($j = 0; $j < 26; $j++){
					if($password[$i] == $letterU_list[$j] )
						$letter = True;
					if($password[$i] == $letterL_list[$j] )
						$letter = True;
				}
				//check the number/s
				for($n = 1; $n < 10; $n++){
					if($password[$i] == $n)
						$number = True;
				}
				//check the symbol/s
				for($sym = 0; $sym < 29; $sym++){
					if($password[$i] == $specialChar_list[$sym])
						$specialChar = True;
				}
			}
			if($letter == True AND $number == True AND $specialChar = True){
				if($new == $check){
					$savepwd = md5($new);
					
					mysql_query("LOCK TABLE account WRITE;");
					try{
						mysql_query("UPDATE account SET password = '$savepwd' WHERE personnel_id = '".$_SESSION['logged_personnel_id']."' ") or die(mysql_error());
						mysql_query("COMMIT");
						echo '<div class="form-group"><div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Password has been changed successfully.</div></div>';
					}catch (Exception $e){
						mysql_query("ROLLBACK");
						echo '<div class="form-group"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Something went wrong when changing your password.</div></div>';
					}
				}else{
					echo '<div class="form-group"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Your passwords did not match.</div></div>';
				}
			}else echo '<div class="form-group"><div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Your password is weak. It should be a combination of letters, numbers and symbols.</div></div>';
		}
	}
		
?>