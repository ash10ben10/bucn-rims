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
	<?php include  "../engine/jscalls.php"; ?>
	
	<?php include "change_pass_engine.php"; ?>
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
						<li class="active">
							<a href="change_pass.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-key zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Change Password</a>
						</li>
						<?php
					}else{
						?>
						<li>
							<a href="viewinfo.php?id=<?php print $_SESSION['logged_personnel_id']; ?>"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-account-circle zmdi-hc-lg"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;My Profile</a>
						</li>
						<li class="active">
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
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-key zmdi-hc-lg"></i>&nbsp;&nbsp;Change Password</h1>
					
						<div class="col-lg-1"></div>
						<div class="col-lg-10">
						
							<br />
							<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
								<div class="panel-heading"></div>
								<div class="panel-body">
									<?php
										if(isset($_POST['ch_pwd'])){
											update_pwd($_POST['new_pwd'], $_POST['check_pwd']);
										}
									?>
									<div class="row">
										<div class="col-lg-4"></div>
										<div class="col-lg-4"><br />
											<form method="POST">
												<label>New Password:</label>
												<input type="password" class="form-control" name="new_pwd" placeholder="New Password" required max=10 >
												<br />
												<label>Confirm Password:</label>
												<input type="password" class="form-control" name="check_pwd" placeholder="Confirm Password" required max=10 ></br>
												<div align="center"><button type="submit" name="ch_pwd" id="ch_pwd" class="btn btn-success" align="center"><i class="zmdi zmdi-shield-security zmdi-hc-lg"></i>&nbsp;&nbsp;Update Password</button></div>
											</form>
											<br />
										</div>
										<div class="col-lg-4"></div>
									</div>
								</div>
								<div class="panel-footer">
									<i><strong>Note:</strong>&nbsp;Your password is case sensitive and should be typed correctly. Mistyped letters or numbers when changing your password might keep you from logging in. Also, the pasword should be a combination of letters, numbers and special symbols with a minimum of 5 (five) to 10 (ten) characters.</i>
								</div>
							</div>
						</div>
				</div>
			</div>
			
		</div> <!--/.container-fluid-->
	
	</div> <!--/.content-wrapper-->
	
</body>
<!-- /.Body -->

</div> <!-- /.wrapper-->

</html>