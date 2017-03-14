<!DOCTYPE html>
<?php

	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");
	$date = date("Y-m-d");
	$month = date("Y-m");

	include "connect.php";
	session_start();
	//session_destroy();
	
	if(!isset($_SESSION['logged_in'])){
		print "<script>alert('You need to log in to access this page.'); window.location='login.php';</script>";
	}
	/* else if($_SESSION['account_type'] == "System Administrator" || $_SESSION['account_type'] == "Administrator"){
		$uInfo = $_SESSION['user_info'];
		$aInfo = $_SESSION['account_info'];
	} */
	/* else if($_SESSION['account_type'] == "End User" ){
		//header('Location:personnel/end_usr/eu_profile.php');
		print "<script>window.location='personnel/end_usr/eu_profile.php';</script>";
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
	<?php include "engine/dashboardcss.php"; ?>
	<!-- Calling Default Javascript files -->
	<?php include  "engine/jsdashboard.php"; ?>
	<script>
	//$(document).ready(function() {
		var updateClock = function() {
			function pad(n) {
				return (n < 10) ? '0' + n : n;
			}

			var now = new Date();
			var hours = now.getHours();
			var mins = now.getMinutes();
			var secs = now.getSeconds();
			var ampm = hours >= 12 ? 'PM' : 'AM';
			if (hours > 12) {
				hours -= 12;
			} else if (hours === 0) {
				hours = 12;
			}
			var showclock = pad(hours) + ':' +
							pad(mins) + ':' +
							pad(secs) + ' ' +
							pad(ampm);

			$("#displayclock").html(showclock);

			var delay = 1000 - (now % 1000);
			setTimeout(updateClock, delay);
		};
	//});
	</script>
</head>

<!-- Header and Sidebar Page -->

<!-- Wrapper -->
<div id="wrapper" style="font-family: Segoe UI;">
	
	<!-- Sidebar -->
	<div id="sidebar-wrapper">
		<!-- Inside Sidebar -->
		
		<!-- <div style="margin:15px 0 0 65px;"><a href="../index.php" data-toggle="tooltip" title="Go back to Dashboard Home."><button type="submit" class="btn btn-default" style="margin: 0 -15px 0 -5px;"><i class="fa fa-dashboard fa-fw"></i>&nbsp;&nbsp;Dashboard</button></a></div> -->
		<ul class="sidebar-nav nav-pills nav-stacked" id="menu" style="margin: 30px 0 0 0;">
			<div class="panel panel-success" style="margin: 0px 15px 0 15px; box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
			<div class="panel-heading" style="font-family: Segoe UI Light;">
				<h3 class="panel-title" align="center"><strong>You are logged in as <?php echo $account['account_type']; ?></strong></h3>
			</div>
			<div class="panel-body" style="height:90px; overflow-y: auto;">
				<a href="personnel/viewinfo.php?id=<?php echo $personnel['personnel_id']; ?>" style="margin: 0px  0 -5px;"><img src="<?php echo "personnel/".$personnel['personnel_photo']; ?>" class="img-circle" style="height:60px; width:60px; padding: 0px 0px 0px 0px;"/></a> <div align="middle" style="margin: -59px 0 -5px 80px;"><strong><?php echo $personnel['personnel_fname']." ".$personnel['personnel_lname']; ?></strong><br /><i><?php echo $position['position_name']; ?></i></div>
			</div>
		</div>
			<br />
			<div style="margin: 0 15px 0 15px;">
				<?php include 'notes.php'; ?>
			</div>
		</ul> <!--/.Inside Sidebar -->
	</div><!--/.Sidebar -->

	<!-- Navigation -->
	<div class="navbar navbar-default navbar-static-top" role="navigation" style="font-family: Segoe UI;">
			<div>
				<img src="engine/images/header.png" class="img-responsive" style="width:100%;">
			</div>
			
				<div class="navbar-header" style="margin-right: 20px;">
					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse" style="margin-right:10px;">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					
					
					<a class="navbar-brand" data-toggle="tooltip" title="Toggle side menu"><button class="btn btn-default" data-toggle="collapse" id="menu-toggle" style="margin-top:-7px;"><span class="fa fa-th-list fa-fw" aria-hidden="true" style="margin-top: 5px;"></span></button></a>
					
				</div>
					<div class="navbar-collapse collapse" id="bs-example-navbar-collapse-1" align="center" style="font-family: Segoe UI; font-size: 98%;">
								<div class="container-fluid">	
									<ul class="nav navbar-nav" style="margin: 2px 0 0 -15px;">
										<li><a href="index.php" data-toggle="tooltip" title="Return to Dashboard Home Page."><button type="button" class="btn btn-primary" style="margin-bottom:-8px; margin-top:-11px;"><i class="zmdi zmdi-view-dashboard zmdi-hc-lg"></i>&nbsp;&nbsp;Dashboard</button></a></li>
										<?php
										if($position['position_name'] == "Supply Officer"){
											?>
											<li><a href="supply/sup_list.php" data-toggle="tooltip" title="Manage Supply and the status in the inventory."><i class="zmdi zmdi-mall zmdi-hc-lg"></i>&nbsp;&nbsp;Supply</a></li>
											<?php
										}else{
											?>
											<li><a href="supply/stock_req.php" data-toggle="tooltip" title="Manage Supply and the status in the inventory."><i class="zmdi zmdi-mall zmdi-hc-lg"></i>&nbsp;&nbsp;Supply</a></li>
											<?php
										}
										?>
										<li><a href="equipment/eq_issued.php" data-toggle="tooltip" title="Manage Equipment and the status in the inventory."><i class="zmdi zmdi-washing-machine zmdi-hc-lg"></i>&nbsp;&nbsp;Equipment</a></li>
										<li><a href="requests/req.php" data-toggle="tooltip" title="Manage Requests and the transactions."><i class="zmdi zmdi-mail-send zmdi-hc-lg"></i>&nbsp;&nbsp;Requests</a></li>
										<?php
										if($account['account_type'] == "System Administrator"){
											?>
											<li><a href="personnel/emp.php" data-toggle="tooltip" title="Manage Personnel and their information."><i class="zmdi zmdi-accounts-list-alt zmdi-hc-lg"></i>&nbsp;&nbsp;Personnel</a></li>
											<?php
										}else{
											?>
											<li><a href="personnel/viewinfo.php?id=<?php print $_SESSION['logged_personnel_id']; ?>" data-toggle="tooltip" title="Manage Personnel and their information."><i class="zmdi zmdi-accounts-list-alt zmdi-hc-lg"></i>&nbsp;&nbsp;Personnel</a></li>
											<?php
										}
										?>
										<?php
										if($account['account_type'] == "System Administrator"){
											?>
											<li><a href="settings/suppliers.php" data-toggle="tooltip" title="Manage System Settings."><i class="zmdi zmdi-settings zmdi-hc-lg"></i>&nbsp;&nbsp;Settings</a></li>
											<?php
										}else{
											print "";
										}
										?>
										
										
									</ul>
									
									<div class="container-fluid">
										<form method="POST">
											<ul class="nav navbar-nav navbar-right">
													<li class="dropdown"><?php include "notif.php";?></li>
													<li style="margin-left:5px;"><button type="submit" name="outnow" id="outnow" class="btn btn-default btn-transparent" style="margin: 10px 0 10px 0;"><i class="glyphicon glyphicon-log-out"></i>&nbsp;&nbsp;Logout</button></li>
													<!--<a href="login.php"><i class="glyphicon glyphicon-log-out"></i>&nbsp;&nbsp;Logout</a></li>-->
											</ul>
										</form>
										<?php 
											if(isset($_POST['outnow'])){
												session_destroy();
												print "<script>window.location='login.php';</script>";
											}
										?>
									</div>
								</div>
					</div>
	</div> <!--/.Navigation -->
	
<!-- /.Header and Sidebar Page -->
	
<!-- Body will contain the Page Contents -->
			
<body onload="updateClock()">
	<!-- Content-Wrapper -->
	<div id="content-wrapper">
		
		<div class="container-fluid">
			
			<div class="row" style="margin-top:-20px;">
				<div class="col-lg-12">
				
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-view-dashboard zmdi-hc-lg"></i>&nbsp;&nbsp;Welcome, <?php print $personnel['personnel_fname']." ".$personnel['personnel_lname']; ?>!</h1>
					
						<div class="row" style="margin-top:-20px;">
							
							<div class="col-lg-8" style="margin-bottom:50px;">
								<br /><br /><br /><br /><br />
								<div class="row">
									<div class="col-lg-1">
									</div>
									<div class="col-lg-10">
										<div class="row">
											<div class="col-lg-6">
											
											<?php
											if($position['position_name'] == "Supply Officer" || $position['position_name'] == "Dean"){
												$countpr1 = mysql_fetch_array(mysql_query("SELECT count(*) FROM purchase_request WHERE ris_stat != 'Done'"));
											}else{
												$countpr1 = mysql_fetch_array(mysql_query("SELECT count(*) FROM purchase_request WHERE ris_stat != 'Done' AND personnel_id = '".$_SESSION['logged_personnel_id']."'"));
											}
											?>
											
											<a href="requests/req.php">
											<div class="tile orange" align="left">
												<i class="zmdi zmdi-timer zmdi-hc-5x" style="color:white;"></i>
												<div align="right" style="margin:-77px 0 0 0;">
													<div style="font-size:240%;">
													<?php
														print $countpr1[0];
													?>
													</div>
													<div>Ongoing<br>Requests</div>
												</div>
											</div>
											</a>
											</div>
											<div class="col-lg-6">
												
											<?php
											if($position['position_name'] == "Supply Officer"){
												?>
												<a href="supply/sup_list.php">
													<div class="tile blue" align="left">
														<i class="zmdi zmdi-shopping-cart zmdi-hc-5x" style="color:white;"></i>
														<div align="right" style="margin:-77px 0 0 0;">
														<?php
														$count = mysql_fetch_array(mysql_query("SELECT SUM(su.quantity) FROM stock_units AS su LEFT JOIN stock_items AS si ON si.stock_id = su.stock_id WHERE si.stock_type = 'Supply'"));
														
														print "<div style='font-size:240%;'>";
														
														if($count[0] == null){
															print "0";
														}else{
															print $count[0];
														}
														print "</div>";
														?>
															<div>Available<br>Stocks</div>
														</div>
													</div>
												</a>
												<?php
											}else{
												?>
												<a href="supply/stock_req.php">
												<div class="tile blue" align="left">
														<i class="zmdi zmdi-shopping-cart zmdi-hc-5x" style="color:white;"></i>
														<div align="right" style="margin:-77px 0 0 0;">
														<?php
														$count = mysql_fetch_array(mysql_query("SELECT SUM(quantity) FROM `cart_line` WHERE requestor = '".$_SESSION['logged_personnel_id']."'"));
														
														print "<div style='font-size:240%;'>";
														
														if($count[0] == null){
															print "0";
														}else{
															print $count[0];
														}
														print "</div>";
														?>
															<div>Requested<br>Stocks</div>
														</div>
													</div>
												</a>
												<?php
											}
											?>
												
												
												
													
											</div>
											<div class="col-lg-6">
											
											
											<?php
											
											if($position['position_name'] == "Supply Officer"){
												?>
												<a href="equipment/eq_issued.php">
												<div class="tile green" align="left">
													<i class="zmdi zmdi-dropbox zmdi-hc-5x" style="color:white;"></i>
													<div align="right" style="margin:-77px 0 0 0;">
														<?php
														$count_eq = mysql_fetch_array(mysql_query("SELECT count(*) FROM equipments WHERE remarks REGEXP 'Working|Under Maintenance|Subject for Disposal|Subject for New Repair'")) or die(mysql_error());
														print '<div style="font-size:240%;">'.$count_eq[0].'</div>';
														?>
														<div>Acknowledged<br>Equipment</div>
													</div>
												</div>
												</a>
												<?php
											}else{
												?>
												<a href="equipment/eq_issued.php">
												<div class="tile green" align="left">
													<i class="zmdi zmdi-dropbox zmdi-hc-5x" style="color:white;"></i>
													<div align="right" style="margin:-77px 0 0 0;">
														<?php
														$count_eq = mysql_fetch_array(mysql_query("SELECT count(*) FROM equipments WHERE received_by = '".$_SESSION['logged_personnel_id']."' AND remarks REGEXP 'Working|Under Maintenance|Subject for Disposal|Subject for New Repair'")) or die(mysql_error());
														print '<div style="font-size:240%;">'.$count_eq[0].'</div>';
														?>
														<div>Acknowledged<br>Equipment</div>
													</div>
												</div>
												</a>
												<?php
											}
											
											?>
											
												
											</div>
											<div class="col-lg-6">
											
											<?php
											
											if($account['account_type'] == "System Administrator"){
												?>
												<a href="personnel/emp.php">
												<div class="tile red" align="left">
													<i class="zmdi zmdi-accounts-list zmdi-hc-5x" style="color:white;"></i>
													<div align="right" style="margin:-77px 0 0 0;">
														<?php	
														$personnel = mysql_fetch_array(mysql_query("SELECT count(*) FROM personnel")) or die(mysql_error());
														
														print '<div style="font-size:240%;">'.$personnel[0].'</div>';
														?>
														<div>Registered<br>Personnel</div>
													
													</div>
												</div>
												</a>
												<?php
											}else{
												?>
												<a href="equipment/eq_enduserdisposed.php">
												<div class="tile red" align="left">
													<i class="zmdi zmdi-delete zmdi-hc-5x" style="color:white;"></i>
													<div align="right" style="margin:-77px 0 0 0;">
														<?php	
														$eqp = mysql_fetch_array(mysql_query("SELECT count(*) FROM `equipments` WHERE remarks REGEXP 'Pending for Disposal|Auctioned|Disposed' AND received_by = '".$_SESSION['logged_personnel_id']."'")) or die(mysql_error());
														
														print '<div style="font-size:240%;">'.$eqp[0].'</div>';
														?>
														<div>Disposed<br>Equipment</div>
													
													</div>
												</div>
												</a>
												<?php
											}
											?>
											
												
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-4">
								<div class="col-lg-12" align="center" style="margin-top:-38px;">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h1 id="displayclock" style="margin-top:10px;"></h1>
											<br/>
										</div>
									</div>
									
								</div>
								<div class="col-lg-12" style="margin-bottom: 30px; margin-top: -45px;">
									<?php include 'calendar.php'; ?>
								</div>
							</div>
						</div>
				</div>
			</div>
		
		</div> <!--/.container-fluid-->
	
	</div> <!--/.content-wrapper-->
	
	<!-- Sidebar Function -->
	<script src="engine/js/sidebar_menu.js"></script>
	
</body>
<!-- /.Body -->

</div> <!-- /.wrapper-->

</html>