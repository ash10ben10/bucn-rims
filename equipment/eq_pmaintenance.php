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
			
				<li>
					<a href="eq_issued.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-upload zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Issued Equipment</a>
				</li>
				<?php
				if($position['position_name'] == "Supply Officer"){
					?>
					<li>
						<a href="eq_disposal.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-delete zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Equipment Disposal</a>
					</li>
					<li>
						<a href="eq_turnover.php"><span class="fa-stack fa-lg pull-left"><i class="glyphicon glyphicon-share-alt"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Equipment Turn-over</a>
					</li>
					<?php
				}else{
					print "";
				}
				?>
				<li class="active">
					<a href="eq_pmaintenance.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-wrench zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Corrective Maintenance</a>
				</li>
				<?php
				
				if($position['position_name'] == "Supply Officer"){
					?>
					<li>
						<a href="equipment_label.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-label zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Equipment Labels</a>
					</li>
					<li>
						<a href="equipment_specs.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-widgets zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Equipment Descs</a>
					</li>
					<li>
						<a href="eq_report.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-file-text zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Reports</a>
					</li>
					<?php
				}else{
					?>
					<li>
						<a href="eq_enduserdisposed.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-delete zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Disposed Equipment</a>
					</li>
					<?php
				}
				?>
			
		</ul> <!--/.Inside Sidebar -->
	</div><!--/.Sidebar -->
	
	<!-- Navigation -->
	<?php include "../equipment/eq_header.php"; ?>
	<!--/.Navigation -->
	
<!-- /.Header and Sidebar Page -->
			
<!-- Body will contain the Page Contents -->

<body>
	<!-- Content-Wrapper -->
	<div id="content-wrapper">
		
		<div class="container-fluid">
			
			<div class="row" style="margin-top:-20px;">
				<div class="col-lg-12">
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-wrench zmdi-hc-lg"></i>&nbsp;&nbsp;Corrective Maintenance</h1>
					
						<br />
						<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
							<div class="panel-heading">
								<div class="row" style="margin-bottom:-10px;">
									<div class="col-md-12" align="left" style="margin-bottom:10px;">
										<a href="../equipment/add_pm.php" class="btn btn-default"><i class="fa fa-plus-circle fa-fw"></i>&nbsp;&nbsp;Create Maintenance</a>
										&nbsp;
									</div>
								</div>
							</div>
							<div class="panel-body">
							
								<?php
								
								if($position['position_name'] == "Supply Officer"){
									$pms = mysql_query("SELECT * FROM `eqp_preventive_maintenance` ORDER BY pmDate DESC");
								}else{
									$pms = mysql_query("SELECT * FROM `eqp_preventive_maintenance` WHERE pmRequestedBy = '".$_SESSION['logged_personnel_id']."' ORDER BY pmDate DESC");
								}
								
								
								
								if(mysql_num_rows($pms) == 0){
									print "<br /><p align=center><i>There are no Equipments undertaking Corrective Maintenance.</i></p><br />";
								}else{
									print "
									<div class='table-responsive'>
										<table class = 'table table-striped table-bordered table-hover display'>
											<thead>
												<tr>
													<th>Date</th>
													<th>Maintenance ID</th>
													<th>Company</th>
													<th>Repairer</th>
													<th>Schedule</th>
													<th>Status</th>
												</tr>
											</thead>
											<tbody>
									";
									
									while($getpms = mysql_fetch_array($pms)){
										
										print "<tr>";
										print "<td style='text-align:center;vertical-align:middle;'>".date("M d, Y", strtotime($getpms['pmDate']))."</td>";
										print "<td style='text-align:center;vertical-align:middle;'><a href='view_pm.php?pmNum=".$getpms['pmNum']."'>".$getpms['pmNum']."</a></td>";
										print "<td style='text-align:center;vertical-align:middle;'>".$getpms['pmCompany']."</td>";
										print "<td style='text-align:center;vertical-align:middle;'>".$getpms['pmRepairer']."</td>";
										print "<td style='text-align:center;vertical-align:middle;'>".date("M d, Y", strtotime($getpms['pmSched']))."</td>";
										print "<td style='text-align:center;vertical-align:middle;'>".$getpms['pmStatus']."</td>";
										print "</tr>";
										
									}
									
									print "</tbody></table></div>";
								}
								
								?>
							
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