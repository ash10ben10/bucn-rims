<!DOCTYPE html>

<?php
	include "../connect.php";
	$read_id = $_GET['id'];
	$readme = mysql_fetch_array(mysql_query("SELECT * FROM personnel WHERE personnel_id = '$read_id'")) or die(mysql_error());
	$readwork = mysql_fetch_array(mysql_query("SELECT * FROM personnel_work_info WHERE personnel_id = '$read_id'")) or die(mysql_error());
	
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
		$personnel = mysql_fetch_array(mysql_query("SELECT * FROM personnel WHERE personnel_id = '".$_SESSION['logged_personnel_id']."' ")) or die(mysql_error());
		$pworkinfo = mysql_fetch_array(mysql_query("SELECT * FROM personnel_work_info WHERE personnel_id = '".$_SESSION['logged_personnel_id']."' ")) or die(mysql_error());
		$position = mysql_fetch_array(mysql_query("SELECT * FROM personnel_position WHERE position_id = '$pworkinfo[position_id]' ")) or die(mysql_error());
		$account = mysql_fetch_array(mysql_query("SELECT * FROM account WHERE personnel_id = '".$_SESSION['logged_personnel_id']."' ")) or die(mysql_error());
	}
	
?>

<html lang="en">

<head>
	<!-- Calling Default CSS files -->
	<?php include "../engine/csscalls.php"; ?>
	<!-- Calling Default Javascript files -->
	<?php include "../engine/jscalls.php"; ?>
	<?php include "viewinfo_engine.php"; ?>
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
						<li class="active">
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
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-account-circle zmdi-hc-lg"></i>&nbsp;&nbsp;<?php print "".$readme['personnel_fname']."'s "; ?>Profile</h1>
						<form role="form" method="post" id="updatepersonnel" name="contentForm" enctype="multipart/form-data" onsubmit="submitform(event);">
							<br />
							<div class="panel panel-default" style="font-family: Segoe UI; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
								<div class="panel-heading">
									<div class="row">
										<div class="col-lg-12">
											<div id="enable_viewinfo_edit" class="pull-left">
												<a href="#" onClick="enable_edit('viewinfo')" data-toddle='tooltip' title="Edit Personnel Information" class="btn btn-default"><span class="zmdi zmdi-border-color"></span>&nbsp;&nbsp;Edit</a>
											</div>
											<div id="disable_viewinfo_edit" class="pull-left" hidden >
												<a href="viewinfo.php?id=<?php echo $read_id; ?>" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span>&nbsp;Cancel</a>
												<button type="submit" name="update_viewinfo" id="update_viewinfo" class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;Save</button>
											</div>
										</div>
									</div>
								</div>
								<div class="panel-body">
									<table class ="table table-striped table-bordered">
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
															<input class="form-control" name="lname" id="lname" placeholder="Family Name" pattern="([A-Za-zñÑ]| |-)+" required maxlength="100" minlength="2" disabled value="<?php print $readme['personnel_lname']; ?>" />
														</div>
														<div class="col-md-4">
															<label>First Name:</label>
															<input class="form-control" name="fname" id="fname" placeholder="Given Name" pattern="([A-Za-zñÑ]| |-)+" required maxlength="100" minlength="2" disabled value="<?php print $readme['personnel_fname']; ?>" />
														</div>
														<div class="col-md-4">
															<label>Middle Name:</label>
															<input class="form-control" name="mname" id="mname" placeholder="Middle Name" pattern="([A-Za-zñÑ]| |-)+" maxlength="100" minlength="2" disabled value="<?php print $readme['personnel_mname']; ?>" />
														</div>
													</div>
													<div class="row" style="margin-left:2px; margin-right:2px;">
														
														<div class="col-md-4">
															<label>Date of Birth:</label>
																<input type="date" name="bdate" id="bdate" class="form-control" required disabled value="<?php print $readme['personnel_bday']; ?>"/>
														</div>
														<div class="col-md-3">
															<label>Place of Birth:</label>
															<input class="form-control" name="bplace" id="bplace" placeholder="City or Province" required disabled value="<?php print $readme['personnel_bplace']; ?>"/>
														</div>
														<div class="col-md-3">
															<label>Civil Status:</label>
															<select class="form-control" name="cvilstat" id="cvilstat" required disabled>
																<?php
																	if($readme['personnel_civilstatus'] == "Single"){
																		print "<option disabled>-Select-</option>";
																		print "<option selected>Single</option>";
																		print "<option>Married</option>";
																		print "<option>Annulled</option>";
																		print "<option>Widowed</option>";
																		print "<option>Separated</option>";
																	}else if($readme['personnel_civilstatus'] == "Married"){
																		print "<option disabled>-Select-</option>";
																		print "<option>Single</option>";
																		print "<option selected>Married</option>";
																		print "<option>Annulled</option>";
																		print "<option>Widowed</option>";
																		print "<option>Separated</option>";
																	}else if($readme['personnel_civilstatus'] == "Annulled"){
																		print "<option disabled>-Select-</option>";
																		print "<option>Single</option>";
																		print "<option>Married</option>";
																		print "<option selected>Annulled</option>";
																		print "<option>Widowed</option>";
																		print "<option>Separated</option>";
																	}else if($readme['personnel_civilstatus'] == "Widowed"){
																		print "<option disabled>-Select-</option>";
																		print "<option>Single</option>";
																		print "<option>Married</option>";
																		print "<option>Annulled</option>";
																		print "<option selected>Widowed</option>";
																		print "<option>Separated</option>";
																	}else if($readme['personnel_civilstatus'] == "Separated"){
																		print "<option disabled>-Select-</option>";
																		print "<option>Single</option>";
																		print "<option>Married</option>";
																		print "<option>Annulled</option>";
																		print "<option>Widowed</option>";
																		print "<option selected>Separated</option>";
																	}
																?>
															</select>
														</div>
														<div class="col-md-2">
															<label>Sex:</label>
															<select class="form-control" name="sex" id="sex" required disabled value="<?php print $readme['personnel_sex']; ?>">
																<?php 
																	if($readme['personnel_sex'] == "Male"){
																		print "<option disabled>-Select-</option>";
																		print "<option selected>Male</option>";
																		print "<option>Female</option>";
																	}else{
																		print "<option disabled>-Select-</option>";
																		print "<option>Male</option>";
																		print "<option selected>Female</option>";
																	}
																?>
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
															<input class="form-control" name="address" id="address" placeholder="home/permanent address" minlength="3" required disabled value="<?php print $readme['personnel_address']; ?>" />
														</div>
														<div class="form-group col-md-4">
															<label>Email Address:</label>
															<input class="form-control" name="emailadd" id="emailadd" placeholder="e.g. user@email.com" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" disabled value="<?php print $readme['personnel_email']; ?>"/>
														</div>
														<div class="form-group col-md-3">
															<label>Contact Number:</label>
															<input class="form-control" name="cpnum" id="cpnum" placeholder="telephone/mobile #" pattern="([+0-9.-])+" disabled value="<?php print $readme['personnel_contact_no']; ?>" />
														</div>
														
													</div>
												</td>
												<td width="25%" align="center">
													<br/><br/>
													<img name="propic" id="propic" src="../personnel/<?php print $readme['personnel_photo']; ?>" class="img-circle" style="height:140px; width:140px; padding: 0px 0px 0px 0px;" >
													<br/><br/>
													<!--<input type="file" name="propic" id="propic" accept="image/*" required="required" value="" disabled />-->
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
														<input class="form-control" name="prieduc" id="prieduc" placeholder="Elementary School" required disabled value="<?php print $readme['personnel_primary_education']; ?>" />
													</div>
													<div class="col-md-2">
														<label>Year Graduated:</label>
														<input class="form-control" name="pe_year" id="pe_year" placeholder="Year" minlength="4" maxlength="4" pattern="([0-9.])+" required disabled value="<?php print $readme['personnel_pe_year']; ?>"/>
													</div>
													<div class="col-md-4">
														<label>Secondary Education:</label>
														<input class="form-control" name="seceduc" id="seceduc" placeholder="High School" disabled value="<?php print $readme['personnel_secondary_education']; ?>"/>
													</div>
													<div class="col-md-2">
														<label>Year Graduated:</label>
														<input class="form-control" name="se_year" id="se_year" placeholder="Year" minlength="4" maxlength="4" pattern="([0-9.])+" disabled value="<?php print $readme['personnel_se_year']; ?>"/>
													</div>
													<div class="col-md-5">
														<label>Tertiary Education:</label>
														<input class="form-control" name="tereduc" id="tereduc" placeholder="College School" disabled value="<?php print $readme['personnel_tertiary_education']; ?>"/>
													</div>
													<div class="col-md-5">
														<label>Bachelor's Degree:</label>
														<input class="form-control" name="bacdeg" id="bacdeg" placeholder="Degree of Study" disabled value="<?php print $readme['personnel_bachelor_degree']; ?>"/>
													</div>
													<div class="col-md-2">
														<label>Year Graduated:</label>
														<input class="form-control" name="te_year" id="te_year" placeholder="Year" minlength="4" maxlength="4" pattern="([0-9.])+" disabled value="<?php print $readme['personnel_te_year']; ?>"/>
													</div>
													<div class="col-md-5">
														<label>Graduate School:</label>
														<input class="form-control" name="gradsch" id="gradsch" placeholder="Graduate School" disabled value="<?php print $readme['personnel_graduate_school']; ?>"/>
													</div>
													<div class="col-md-5">
														<label>Master's Degree:</label>
														<input class="form-control" name="masdeg" id="masdeg" placeholder="Degree of Study" disabled value="<?php print $readme['personnel_masters_degree']; ?>"/>
													</div>
													<div class="col-md-2">
														<label>Year Graduated:</label>
														<input class="form-control" name="gs_year" id="gs_year" placeholder="Year" minlength="4" maxlength="4" pattern="([0-9.])+" disabled value="<?php print $readme['personnel_gs_year']; ?>"/>
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
															<input class="form-control" name="empid" id="empid" placeholder="ID number" pattern="([-0-9.])+" minlength="5" maxlength="15" required disabled value="<?php print $readme['personnel_empid']; ?>" />
													</div>
													<div class="col-md-4" id="empost_cont">
														<label>Position:</label>
														
														<select id="dummy_empost" class="form-control" disabled >
														<?php 
															$query = mysql_query("SELECT * FROM `personnel_position`");
															echo "<option selected disabled>-Select Position-</option>";
															while($row = mysql_fetch_array($query)){
																if($row['position_id'] == $readwork['position_id'])
																echo "<option value='".$row['position_id']."' selected>".ucfirst($row['position_name'])."</option>";
																else echo "<option value='".$row['position_id']."'>".ucfirst($row['position_name'])."</option>";
															}
														?>
														</select>
														
														<select name="empost" id="empost" class="selectpicker form-control hidden" data-hide-disabled="true" data-live-search="true" required disabled >
														<?php 
															$query = mysql_query("SELECT * FROM `personnel_position`");
															echo "<option selected disabled>-Select Position-</option>";
															while($row = mysql_fetch_array($query)){
																if($row['position_id'] == $readwork['position_id'])
																echo "<option value='".$row['position_id']."' selected>".ucfirst($row['position_name'])."</option>";
																else echo "<option value='".$row['position_id']."'>".ucfirst($row['position_name'])."</option>";
															}
														?>
														</select>
														
													</div>
													
													<div class="col-md-3">
														<label>Position Status:</label>
															<select class="form-control" name="postat" id="postat" required disabled>
																<?php 
																	if($readwork['status'] == "Teaching"){
																		print "<option disabled>-Select Status-</option>";
																		print "<option selected>Teaching</option>";
																		print "<option>Non - Teaching</option>";
																	}else{
																		print "<option disabled>-Select Status-</option>";
																		print "<option>Teaching</option>";
																		print "<option selected>Non - Teaching</option>";
																	}
																?>
															</select>
													</div>
													<div class="col-md-3">
														<label>Position type:</label>
															<select name="postype" id="postype" class="form-control" required disabled>
																<?php 
																	if($readwork['type'] == "Permanent"){
																		print "<option disabled>-Select Type-</option>";
																		print "<option selected>Permanent</option>";
																		print "<option>Full Time</option>";
																		print "<option>Part Time</option>";
																		print "<option>Job Order</option>";
																		print "<option>On Job Training</option>";
																		print "<option>Student Assistant</option>";
																	}else if($readwork['type'] == "Full Time"){
																		print "<option disabled>-Select Type-</option>";
																		print "<option>Permanent</option>";
																		print "<option selected>Full Time</option>";
																		print "<option>Part Time</option>";
																		print "<option>Job Order</option>";
																		print "<option>On Job Training</option>";
																		print "<option>Student Assistant</option>";
																	}else if($readwork['type'] == "Part Time"){
																		print "<option disabled>-Select Type-</option>";
																		print "<option>Permanent</option>";
																		print "<option>Full Time</option>";
																		print "<option selected>Part Time</option>";
																		print "<option>Job Order</option>";
																		print "<option>On Job Training</option>";
																		print "<option>Student Assistant</option>";
																	}else if($readwork['type'] == "Job Order"){
																		print "<option disabled>-Select Type-</option>";
																		print "<option>Permanent</option>";
																		print "<option selected>Full Time</option>";
																		print "<option>Part Time</option>";
																		print "<option selected>Job Order</option>";
																		print "<option>On Job Training</option>";
																		print "<option>Student Assistant</option>";
																	}else if($readwork['type'] == "On Job Training"){
																		print "<option disabled>-Select Type-</option>";
																		print "<option>Permanent</option>";
																		print "<option>Full Time</option>";
																		print "<option>Part Time</option>";
																		print "<option>Job Order</option>";
																		print "<option selected>On Job Training</option>";
																		print "<option>Student Assistant</option>";
																	}else if($readwork['type'] == "Student Assistant"){
																		print "<option disabled>-Select Type-</option>";
																		print "<option>Permanent</option>";
																		print "<option>Full Time</option>";
																		print "<option>Part Time</option>";
																		print "<option>Job Order</option>";
																		print "<option>On Job Training</option>";
																		print "<option selected>Student Assistant</option>";
																	}
																?>
															</select>
													</div>
													<div class="col-md-4" id="emdept_cont">
														<label>Department:</label>
														<select id="dummy_emdept" class="form-control" disabled >
															<?php 
																$query = mysql_query("SELECT dept_id, dept_name FROM `department` WHERE dept_id = '".$readwork['dept_id']."' LIMIT 1");
																echo "<option selected disabled>-Select Department-</option>";
																while($row = mysql_fetch_array($query)){
																	echo "<option value='".$row['dept_id']."' selected>".ucfirst($row['dept_name'])."</option>";
																}
															?>
														</select>
														<select name="emdept" id="emdept" class="selectpicker form-control hidden" data-hide-disabled="true" data-live-search="true" required disabled>
															<?php 
																$query = mysql_query("SELECT dept_id, dept_name FROM `department`");
																echo "<option selected disabled>-Select Department-</option>";
																while($row = mysql_fetch_array($query)){
																	if($row['dept_id'] == $readwork['dept_id'])
																	echo "<option value='".$row['dept_id']."' selected>".ucfirst($row['dept_name'])."</option>";
																	else echo "<option value='".$row['dept_id']."'>".ucfirst($row['dept_name'])."</option>";
																}
															?>
														</select>
													</div>
													<div class="col-md-8" style="margin: 20px 0 0 0;">
														<i>Note: If you will change your Employee ID, your old Employee ID will not work in logging in. Changes of Employee ID will be used when logging in to your account.</i>
													</div>
												</td>
												<br />
											</tr>
											<!--
											<tr>
												<td colspan="4" style="font-size: 110%;"><strong>Account Information</strong></td>
											</tr>
											<tr>
												<td colspan="4">
													<div class="col-md-3">
														<label>Account type:</label>
														<select class="form-control" name="acctype" required>
															<?php 
																	// $query = mysql_query("SELECT * FROM `account_type`");
																	// echo "<option value='' selected='selected' disabled='disabled'>-Set Privilege-</option>";
																	// while($row = mysql_fetch_array($query)){
																		// echo "<option value='".$row['account_type_id']."'>".ucfirst($row['account_type_name'])."</option>";
																	// }
																?>
														</select>
													</div>
													<div class="form-group col-md-3">
															<label>Username:</label>
															<input class="form-control" placeholder="username" name="usrname" required />
													</div>
													<div class="form-group col-md-3">
															<label>Password:</label>
															<input class="form-control" placeholder="password" type="password" name="pwd" required />
													</div>
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
								<!--<div class="panel-footer" align="right">
								</div>-->
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