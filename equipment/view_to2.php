<!DOCTYPE html>
<?php
	include "../connect.php";
	
	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");
	$date = date("Y-m-d");
	$month = date("Y-m");
	
	$readeqp_id = $_GET['id'];
	
	$gettoid = mysql_fetch_array(mysql_query("SELECT ics_par_id FROM equipments WHERE eqp_id = '$readeqp_id'"))or die(mysql_error());
	$getto = mysql_fetch_array(mysql_query("SELECT * FROM eqp_turnover WHERE to_id = '$gettoid[ics_par_id]'"))or die (mysql_error());
	
	$selctPs = "CONCAT(p.personnel_fname,' ',p.personnel_lname) AS full_name, pp.position_name";
	$fromPs= "personnel_work_info AS pwi LEFT JOIN personnel AS p ON p.personnel_id = pwi.personnel_id LEFT JOIN personnel_position AS pp ON pp.position_id = pwi.position_id";
	$toTo = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM ".$fromPs." WHERE pwi.personnel_id = '$getto[toTo]' ")) or die(mysql_error());
	$toFr = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM ".$fromPs." WHERE pwi.personnel_id = '$getto[toFrom]' ")) or die(mysql_error());

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
				<li>
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
	<?php include "../requests/req_header.php"; ?>
	<!--/.Navigation -->
	
<!-- /.Header and Sidebar Page -->
			
<!-- Body will contain the Page Contents -->

<body>
	<!-- Content-Wrapper -->
	<div id="content-wrapper">
		
		<div class="container-fluid">
			
			<div class="row" style="margin-top:-20px;">
				
				<div class="col-lg-12">
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-file-text zmdi-hc-lg"></i>&nbsp;&nbsp;Turn Over Form</h1>
						<br />
						<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
							<div class="panel-heading">
								<div class="row">
									<div class="col-lg-8" align="left">
									<a href="../equipment/view_eq.php?id=<?php print $readeqp_id; ?>" class="btn btn-info"><span class="fa fa-arrow-left"></span>&nbsp;&nbsp;Go Back</a>
									</div>
									<div class="col-lg-4" align="right">
										<a target="_blank" href="print_to.php?id=<?php echo $getto['to_id']; ?>"><button type="button" class="btn btn-primary"><i class="fa fa-print fa-fw"></i>&nbsp;Print</button></a>
									</div>
								</div>
							</div>
							
							<div class="panel-body">
								
								<div class="row">
									<div class="col-lg-12">
										<div class="table-responsive">
											<table class="table table-striped table-bordered table-hover">
												<tbody>
													<tr>
														<td colspan="2">
															<div class="col-lg-12" style="font-size:16px;"><center>Bicol University</center></div>
															<div class="col-lg-12" style="font-size:16px;"><center><strong>College of Nursing</strong></center></div>
															<div class="col-lg-12" style="font-size:16px;"><center>Legazpi City</center></div>
															<div class="col-lg-12" style="font-size:20px;margin: 25px 0 10px 0;"><center><strong>TURN-OVER FORM</strong></center></div>
														</td>
													</tr>
													<tr>
														<td colspan="2">
															&nbsp;
															<div class="row">
																<div class="col-lg-1"></div>
																<div class="col-lg-7" style="font-size:15px;">
																	Turn Over No. <strong><?php print $getto['tonum'];?></strong>
																</div>
																<div class="col-lg-4" align="center" style="font-size:15px;">
																	Date Acquired: <?php print date("M j, Y", strtotime($getto['date_acquired'])); ?>
																</div>
															</div>
															&nbsp;
															<?php
																$geteqpdetails = mysql_query("SELECT * FROM equipments WHERE ics_par_id = '$gettoid[ics_par_id]'")or die (mysql_error());

																if(mysql_num_rows($geteqpdetails) == 0){
																	
																}else{
																	?>
																	<div class="table-responsive">
																		<table class = "table table-striped table-bordered table-hover">
																			<thead>
																				<tr>
																					<th>Item No.</th>
																					<th>Unit</th>
																					<th>Name</th>
																					<th>Description</th>
																					<th>Amount</th>
																					<th>Serial Number</th>
																					<th>Property Number</th>
																					<th>Status</th>
																				</tr>
																			</thead>
																			<tbody>
																	<?php
																	$count = 1;
																	while($getdata = mysql_fetch_array($geteqpdetails)){
																		$selitemunit = mysql_fetch_array(mysql_query("SELECT item_unit_name FROM item_unit WHERE item_unit_id = '$getdata[item_unit_id]'"))or die(mysql_error());
																		$selitem = mysql_fetch_array(mysql_query("SELECT item_name FROM items WHERE item_id = '$getdata[item_id]'"))or die(mysql_error());
																	?>
																			<tr>
																				<td style="text-align:center;vertical-align:middle;"><?php print $count; ?></td>
																				<td tyle="text-align:left;vertical-align:middle;">
																					<center><?php print $selitemunit['item_unit_name']; ?></center>
																				</td>
																				<td style="text-align:left;vertical-align:middle;">
																					<?php print $selitem['item_name'].", ".$getdata['brand']; ?>
																				</td>
																				<td style="text-align:left;vertical-align:middle;">
																					<?php print $getdata['description']; ?>
																				</td>
																				<td style="text-align:left;vertical-align:middle;">
																					<center><?php print "Php ".number_format($getdata['unit_value'], 2,'.',',');?></center>
																				</td>
																				<td style="text-align:center;vertical-align:middle;"><?php 
																					if($getdata['serialnum'] == ""){
																						print "Not available";
																					}else{
																						print $getdata['serialnum'];
																					}
																				?></td>
																				<td style="text-align:center;vertical-align:middle;"><?php print $getdata['prop_num']; ?></td>
																				<td style="text-align:center;vertical-align:middle;"><?php print $getdata['remarks']; ?></td>
																			</tr>
																	<?php
																	$count++;
																	}
																	?>		</tbody>
																		</table>
																	</div>
																	<?php
																}
															?>
														</td>
													</tr>
													<tr>
														<td>
															<div class="col-lg-12">
																<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																Turned Over To:
																<br /><br />
																<p style="text-transform:uppercase"><strong><center>
																	<u><?php print $toTo['full_name']; ?></u>
																</strong></center></p>
																<p><center>
																	<?php print $toTo['position_name']; ?>
																</center></p>
																</div>
															</div>
														</td>
														<td>
															<div class="col-lg-12">
																<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																Turned Over From:
																<br /><br />
																<p style="text-transform:uppercase"><strong><center>
																	<u><?php print $toFr['full_name']; ?></u>
																</strong></center></p>
																<p><center>
																	<?php print $toFr['position_name']; ?>
																</center></p>
																</div>
															</div>
														</td>
													</tr>
												</tbody>
											</table>
										</div>
										
									</div>
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