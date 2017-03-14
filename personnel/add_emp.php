<!DOCTYPE html>

<?php
include "../connect.php";
	
	session_start();
	
	if(!isset($_SESSION['logged_in'])){
		print "<script>alert('You need to log in to access this page.'); window.location='../login.php';</script>";
	}
	/* else if($_SESSION['account_type'] == "System Administrator" || $_SESSION['account_type'] == "Administrator"){
		$uInfo = $_SESSION['user_info'];
		$aInfo = $_SESSION['account_info'];
	} */
	/* else if($_SESSION['account_type'] == "End User" ){
		print "<script>window.location='../personnel/end_usr/eu_profile.php';</script>";
	} */
	else{
		$personnel = mysql_fetch_array(mysql_query("SELECT * FROM personnel WHERE personnel_id = '".$_SESSION['logged_personnel_id']."' "));
		$pworkinfo = mysql_fetch_array(mysql_query("SELECT * FROM personnel_work_info WHERE personnel_id = '".$_SESSION['logged_personnel_id']."' "));
		$position = mysql_fetch_array(mysql_query("SELECT * FROM personnel_position WHERE position_id = '$pworkinfo[position_id]' "));
		$account = mysql_fetch_array(mysql_query("SELECT * FROM account WHERE personnel_id = '".$_SESSION['logged_personnel_id']."' "));
	}
	
?>

<html lang="en">

<head>
	<!-- Calling Default CSS files -->
	<?php include "../engine/csscalls.php"; ?>
	<!-- Calling Default Javascript files -->
	<?php include  "../engine/jscalls.php"; ?>
	
	<script>
	var ajaxRequest;
	$(document).ready(function() {
		ajaxRequest = function(){
			var formData = new FormData($("#addpersonnel")[0]);
			
			$.ajax({
				url: "add_emp_engine.php",
				data: formData,
				type: "POST",
				contentType: false,
				processData: false,
				dataType: "json"
			}).done(function(response){
				alert(response.msg);
				if(response.result == "success"){
					window.location = "emp.php";
				}
			}).fail(function(){
				alert("error sending request.")
			});
		};
		
	});
	
	var validatetype = function(fileExt){
				var toReturn = false;
				switch(fileExt){
					case 'bmp':
					case 'BMP':
					case 'gif':
					case 'GIF':
					case 'jpg':
					case 'JPG':
					case 'jpeg':
					case 'JPEG':
					case 'png':
					case 'PNG': toReturn = true;
					break;
					default: toReturn = false;
				}
				return toReturn;
			}
	function submitform(e){
			var isOk = true;
			var msg;
				var $empost = $("#empost").val();
				if($empost == 0 || $empost == "" || $empost == null){
					isOk = false;
					msg = "Please select your position.";
				}
				var $emdept = $("#emdept").val();
				if($emdept == 0 || $emdept == "" || $emdept == null){
					isOk = false;
					msg = "\nPlease select your department.";
				}
				var $cvilstat = $("#cvilstat").val();
				if($cvilstat == 0 || $cvilstat == "" || $cvilstat == null){
					isOk = false;
					msg = "\nPlease select your civil status.";
				}
				var $postype = $("#postype").val();
				if($postype == 0 || $postype == "" || $postype == null){
					isOk = false;
					msg = "\nPlease select your Position status.";
				}
				var $postat = $("#postat").val();
				if($postat == 0 || $postat == "" || $postat == null){
					isOk = false;
					msg = "\nPlease select your Position type.";
				}
				
				if(!isOk){
						e.preventDefault();
						alert(msg);
						return false;
				}
		
			var $propic = $("#propic").val();
			var ext = $propic.split('.');
			var extCount = ext.length;
			//alert(ext[extCount -1]);
			
			if(!validatetype(ext[extCount -1])){
				e.preventDefault();
				alert("You selected an invalid image file type. Please select a valid image file.");
				return false;
			}else{
				e.preventDefault();
				ajaxRequest();
				return false;
			}
		}
	</script>
</head>

<!-- Header and Sidebar Page -->

<!-- Wrapper -->
<div id="wrapper" style="font-family: Segoe UI;">

	<!-- Sidebar -->
	<div id="sidebar-wrapper">
		<!-- Inside Sidebar -->
		
		<div class="panel panel-success" style="margin: 30px 15px 0 15px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
			<div class="panel-heading" style="font-family: Segoe UI Light;">
				<h3 class="panel-title" align="center"><strong>You are logged in as <?php echo $account['account_type']; ?></strong></h3>
			</div>
			<div class="panel-body" style="height:90px; overflow-y: auto;">
				<a href="../personnel/viewinfo.php?id=<?php echo $personnel['personnel_id']; ?>" style="margin: 0px  0 -5px;"><img src="<?php echo "../personnel/".$personnel['personnel_photo']; ?>" class="img-circle" style="height:60px; width:60px; padding: 0px 0px 0px 0px;"/></a> <div align="middle" style="margin: -59px 0 -5px 80px;"><strong><?php echo $personnel['personnel_fname']." ".$personnel['personnel_lname']; ?></strong><br /><i><?php echo $position['position_name']; ?></i></div>
			</div>
		</div>
		<!-- <div style="margin:15px 0 0 65px;"><a href="../index.php" data-toggle="tooltip" title="Go back to Dashboard Home."><button type="submit" class="btn btn-default" style="margin: 0 -15px 0 -5px;"><i class="fa fa-dashboard fa-fw"></i>&nbsp;&nbsp;Dashboard</button></a></div> -->
		<ul class="sidebar-nav nav-pills nav-stacked" id="menu" style="margin-top:200px;">
				
				<!-- User panel I made before in the sidebar. -->
				<!--
				<div class="panel panel-default" style="margin-left: 20px; margin-right: 20px; margin-top:0px;">
					<div class="panel-heading">
						<h3 class="panel-title" align="center"><strong>Personnel/Employee Name</strong></h3>
					</div>
					<div class="panel-body" style="margin-bottom:-16px;px; margin-top:-15px;">
					<a href="#" style="margin-left: -16px;"><img src="../engine/images/user.bmp" style="height:75px; width:75px;"/></a> <button type="button" class="btn btn-default" style="margin-left: 18px;"><i class="glyphicon glyphicon-log-out"></i>&nbsp;&nbsp;Logout</button>
					</div>
				</div>
				-->
				<?php
					if($account['account_type'] == "System Administrator"){
						?>
						<li>
							<a href="emp.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-accounts-list zmdi-hc-lg"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Personnel List</a>
						</li>
						<li>
							<a href="act.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-lock zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Personnel Accounts</a>
						</li>
						<li>
							<a href="emp_settings.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-settings zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Settings</a>
						</li>
						<br>
						<li>
							<a href="change_pass.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-key zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Change Password</a>
						</li>
						<?php
					}else{
						?>
						<li>
							<a href="viewinfo.php?id=<?php print $_SESSION['logged_personnel_id']; ?>"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-account-circle zmdi-hc-lg"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;My Profile</a>
						</li>
						<li>
							<a href="change_pass.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-key zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Change Password</a>
						</li>
						<?php
					}
				?>
			</ul> <!--/.Inside Sidebar -->
	</div><!--/.Sidebar -->
	
	<!-- Navigation -->
	<?php include "../personnel/emp_header.php"; ?>
	<!--/.Navigation -->
	
<!-- /.Header and Sidebar Page -->
			
<!-- Body will contain the Page Contents -->

<body>
	<!-- Content-Wrapper -->
	<div id="content-wrapper">
	
		<div class="container-fluid">
		
			<div class="row" style="margin-top:-20px;">
				<div class="col-lg-12">
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-account-add zmdi-hc-lg"></i>&nbsp;&nbsp;Add Personnel</h1>
						
						<form method="POST" id="addpersonnel" enctype="multipart/form-data" onsubmit="submitform(event);" >
							<input type="hidden" name="empSave" value="new"/>
							<br />
							<div class="panel panel-default" style="font-family: Segoe UI; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
								<div class="panel-heading"></div>
								<div class="panel-body">
									<table class ="table table-striped table-bordered" >
										<tbody>
											<tr>
												<td colspan="4" style="font-size: 110%;"><strong>Basic Information</strong></td>
											</tr>
											<tr>
												<td width="75%" colspan="3">
													<div class="row" style="margin-left:2px; margin-right:2px;">
														<br>
														
														<div class="col-md-4">
															<label>Last Name:</label>
															<input class="form-control" name="lname" id="lname" placeholder="Family Name" pattern="([A-Za-zñÑ]| |-)+" required maxlength="100" minlength="2" />
														</div>
														<div class="col-md-4">
															<label>First Name:</label>
															<input class="form-control" name="fname" id="fname" placeholder="Given Name" pattern="([A-Za-zñÑ]| |-)+" required maxlength="100" minlength="2" />
														</div>
														<div class="col-md-4">
															<label>Middle Name:</label>
															<input class="form-control" name="mname" id="mname" placeholder="Middle Name" pattern="([A-Za-zñÑ]| |-)+" maxlength="100" minlength="2" />
														</div>
													</div>
													<div class="row" style="margin-left:2px; margin-right:2px;">
														
														<div class="col-md-4">
															<label>Date of Birth:</label>
																<input type="date" name="bdate" id="bdate" class="form-control" required />
														</div>
														<div class="col-md-3">
															<label>Place of Birth:</label>
															<input class="form-control" name="bplace" id="bplace" placeholder="City or Province" required />
														</div>
														<div class="col-md-3">
															<label>Civil Status:</label>
															<select class="form-control" name="cvilstat" id="cvilstat" required >
																<option selected disabled>-Select-</option>
																<option>Single</option>
																<option>Married</option>
																<option>Annulled</option>
																<option>Widowed</option>
																<option>Separated</option>
															</select>
														</div>
														<div class="col-md-2">
															<label>Sex:</label>
															<select class="form-control" name="sex" id="sex" required >
																<option disabled selected value="">-Select-</option>
																<option>Male</option>
																<option>Female</option>
															</select>
																<!--<br>
																<div style="font-size: 110%; text-indent: 20px; margin-top:6px;">
																<input type="radio" name="gender" value="Male" checked> Male
																&nbsp;
																<input type="radio" name="gender" value="Female"> Female
																</div>-->
														</div>
														<div class="form-group col-md-5">
															<label>Address:</label>
															<input class="form-control" name="address" id="address" minlength="3" placeholder="home/permanent address" required />
														</div>
														<div class="form-group col-md-4">
															<label>Email Address:</label>
															<input class="form-control" type="email" name="emailadd" id="emailadd" placeholder="e.g. user@example.com" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" />
														</div>
														<div class="form-group col-md-3">
															<label>Contact Number:</label>
															<input class="form-control" name="cpnum" id="cpnum" placeholder="telephone/mobile #" pattern="([+0-9.-])+" />											
														</div>
														
													</div>
												</td>
												<td width="25%" align="center">
													<br/>
													<img name="dp" id="dp" src="../engine/images/user.png" class="img-circle" style="height:140px; width:140px; padding: 0px 0px 0px 0px;" >
													<br/><br/><br/>
													<input type="file" name="propic" id="propic" accept="image/*" required="required" value="" />
												</td>
											</tr>
											<tr>
												<td colspan="4" style="font-size: 110%;"><strong>Scholastic Information</strong></td>
											</tr>
											<tr>
												<td colspan="4">
													<br />
													<div class="col-md-4">
														<label>Primary Education:</label>
														<input class="form-control" name="prieduc" id="prieduc" placeholder="Elementary School" required />
													</div>
													<div class="col-md-2">
														<label>Year Graduated:</label>
														<input class="form-control" name="pe_year" id="pe_year" placeholder="Year" minlength="4" maxlength="4" pattern="([0-9.])+" required />
													</div>
													<div class="col-md-4">
														<label>Secondary Education:</label>
														<input class="form-control" name="seceduc" id="seceduc" placeholder="High School" />
													</div>
													<div class="col-md-2">
														<label>Year Graduated:</label>
														<input class="form-control" name="se_year" id="se_year" placeholder="Year" minlength="4" maxlength="4" pattern="([0-9.])+" />
													</div>
													<div class="col-md-5">
														<label>Tertiary Education:</label>
														<input class="form-control" name="tereduc" id="tereduc" placeholder="College School" />
													</div>
													<div class="col-md-5">
														<label>Bachelor's Degree:</label>
														<input class="form-control" name="bacdeg" id="bacdeg" placeholder="Degree of Study" />
													</div>
													<div class="col-md-2">
														<label>Year Graduated:</label>
														<input class="form-control" name="te_year" id="te_year" placeholder="Year" minlength="4" maxlength="4" pattern="([0-9.])+" />
													</div>
													<div class="col-md-5">
														<label>Graduate School:</label>
														<input class="form-control" name="gradsch" id="gradsch" placeholder="Graduate School" />
													</div>
													<div class="col-md-5">
														<label>Master's Degree:</label>
														<input class="form-control" name="masdeg" id="masdeg" placeholder="Degree of Study" />
													</div>
													<div class="col-md-2">
														<label>Year Graduated:</label>
														<input class="form-control" name="gs_year" id="gs_year" placeholder="Year" minlength="4" maxlength="4" pattern="([0-9.])+" />
													</div>
												</td>
											</tr>
											<tr>
												<td colspan="4" style="font-size: 110%;"><strong>Career Information</strong></td>
											</tr>
											<tr>
												<td colspan="4">
													<br />
													
													<div class="col-md-2">
														<label>Employee ID:</label>
															<input class="form-control" name="empid" id="empid" placeholder="ID number" pattern="([-0-9.])+" minlength="5" maxlength="15" required />
													</div>
													<div class="col-md-4">
														<label>Position:</label>
														<select name="empost" id="empost" class="selectpicker form-control" data-hide-disabled="true" data-live-search="true" required >
															<?php 
																$query = mysql_query("SELECT * FROM `personnel_position`");
																echo "<option selected disabled>-Select Position-</option>";
																while($row = mysql_fetch_array($query)){
																	echo "<option value='".$row['position_id']."'>".ucfirst($row['position_name'])."</option>";
																}
															?>
														</select>
														<!--<input class="form-control" name="empost" id="empost" placeholder="Office Position" required />-->
													</div>
													<div class="col-md-3">
														<label>Position Type:</label>
															<select class="form-control" name="postat" id="postat" required >
																<option selected disabled>-Select Type-</option>
																<option>Teaching</option>
																<option>Non - Teaching</option>
															</select>
													</div>
													<div class="col-md-3">
														<label>Position Status:</label>
															<select name="postype" id="postype" class="form-control" required >
																<option selected disabled>-Select Status-</option>
																<option>Permanent</option>
																<option>Full Time</option>
																<option>Part Time</option>
																<option>Job Order</option>
																<option>On Job Training</option>
																<option>Student Assistant</option>
															</select>
													</div>
													<br />
													<div class="col-md-4">
														<label>Department:</label>
														<select name="emdept" id="emdept" class="selectpicker form-control" data-hide-disabled="true" data-live-search="true" required >
															<?php 
																$query = mysql_query("SELECT * FROM `department`");
																echo "<option selected disabled>-Select Department-</option>";
																while($row = mysql_fetch_array($query)){
																	echo "<option value='".$row['dept_id']."'>".ucfirst($row['dept_name'])."</option>";
																}
															?>
														</select>
													</div>
													<div class="col-md-8" style="margin: 20px 0 0 0;">
														<i>Note: Upon creating your account, the system will automatically generate a default password for you to log in. Use your Employee ID as your Personnel ID Number and your default password is <strong>bucnrims_2016</strong>.</i>
													</div>
												</td>
											</tr>
											<!--
											<tr>
												<td colspan="4" style="font-size: 110%;"><strong>Account Information</strong></td>
											</tr>
											<tr>
												<td colspan="4">
													<div class="col-md-3">
														<label>Account type:</label>
														<select class="form-control" name="acctype" <!-- required -->
															<?php 
																	// $query = mysql_query("SELECT * FROM `account_type`");
																	// echo "<option value='' selected='selected' disabled='disabled'>-Set Privilege-</option>";
																	// while($row = mysql_fetch_array($query)){
																		// echo "<option value='".$row['account_type_id']."'>".ucfirst($row['account_type_name'])."</option>";
																	// }
																?>
													<!--
														</select>
													</div>
													-->
													<!--
													<div class="form-group col-md-3">
															<label>Username:</label>
															<input class="form-control" placeholder="username" name="usrname" />
													</div>
													<div class="form-group col-md-3">
															<label>Password:</label>
															<input class="form-control" placeholder="password" type="password" name="pwd"/>
													</div>
													-->
												</td>
											</tr>
											<!-- (these will add another set of row in the table)
											<tr>
												<td width="25%" colspan="4">
													<div class="row" style="margin-left:2px; margin-right:2px;">
													</div>
												</td>
											</tr>
											-->
											
										</tbody>
									</table>
								</div>
								<div class="panel-footer" align="right">
									<button type="submit" name="save" id="save" class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;Submit</button>
									<a href="../personnel/emp.php" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span>&nbsp;Cancel</a>
								</div>
							</div>
						</form>
				</div>
			</div>
			
		</div> <!--/.container-fluid-->
	
	</div> <!--/.content-wrapper-->
	
	<!-- DatePicker -->
	<script src="../engine/datepicker/bootstrap-datepicker.js" type="text/javascript"></script>
	<script>
	var max = new Date("01/01/2100").toISOString().split('T')[0] ;
	var today = new Date().toISOString().split('T')[0] ;
	document.getElementsByName("bdate")[0].setAttribute('max', max);
	</script>
	
	
</body>
<!-- /.Body -->

</div> <!-- /.wrapper-->

</html>