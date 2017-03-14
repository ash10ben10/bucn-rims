<!DOCTYPE html>
<?php
	include "../connect.php";
	
	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");
	$date = date("Y-m-d");
	$month = date("Y-m");
	
	$readris_id = $_GET['id'];
	
	$getrisdetails = mysql_fetch_array(mysql_query("SELECT * FROM request_issue_slip WHERE ris_id = '$readris_id'"))or die (mysql_error());
	$getprdetails = mysql_fetch_array(mysql_query("SELECT pr.office_dept, d.dept_name FROM purchase_request AS pr LEFT JOIN department AS d ON d.dept_id = pr.dept_id WHERE pr_id = '$getrisdetails[pr_id]'"))or die (mysql_error());
	
	$selctPs = "CONCAT(p.personnel_fname,' ',p.personnel_lname) AS full_name, pp.position_name";
	$fromPs= "personnel_work_info AS pwi LEFT JOIN personnel AS p ON p.personnel_id = pwi.personnel_id LEFT JOIN personnel_position AS pp ON pp.position_id = pwi.position_id";
	
	$requestedBy = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM ".$fromPs." WHERE pwi.personnel_id = '$getrisdetails[requestedBy]'")) ;
	$approvedBy = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM ".$fromPs." WHERE pwi.personnel_id = '$getrisdetails[approvedBy]'")) ;
	$issuedBy = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM ".$fromPs." WHERE pwi.personnel_id = '$getrisdetails[issuedBy]'")) ;
	$receivedBy = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM ".$fromPs." WHERE pwi.personnel_id = '$getrisdetails[receivedBy]'")) ;
	
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
		$personnel = mysql_fetch_array(mysql_query("SELECT * FROM personnel WHERE personnel_id = '".$_SESSION['logged_personnel_id']."' ")) ;
		$pworkinfo = mysql_fetch_array(mysql_query("SELECT * FROM personnel_work_info WHERE personnel_id = '".$_SESSION['logged_personnel_id']."' ")) ;
		$position = mysql_fetch_array(mysql_query("SELECT * FROM personnel_position WHERE position_id = '$pworkinfo[position_id]' ")) ;
		$account = mysql_fetch_array(mysql_query("SELECT * FROM account WHERE personnel_id = '".$_SESSION['logged_personnel_id']."' ")) ;
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
					<a href="req.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-collection-text zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Purchase Requests</a>
				</li>
				<?php 
				if($position['position_name'] == "BAC Officer"){
					?>
					<li>
						<a href="purchase_order.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-assignment-o zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Purchase Orders</a>
					</li>
					<?php
				}else{
					print "";
				}
				?>
				<li>
					<a href="status.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-flag zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Request Status</a>
				</li>
				<li>
					<a href="issuance.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-dropbox zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Issuance</a>
				</li>
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
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-file-text zmdi-hc-lg"></i>&nbsp;&nbsp;Requisition and Issue Slip</h1>
						<br />
						<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
							<div class="panel-heading">
								<div class="row">
									<div class="col-lg-8" align="left">
									<a href="../requests/issuance.php" class="btn btn-info"><span class="fa fa-arrow-left"></span>&nbsp;&nbsp;Go Back</a>
									</div>
									<div class="col-lg-4" align="right">
										<a target="_blank" href="print_ris.php?id=<?php echo $readris_id; ?>"><button type="button" class="btn btn-primary"><i class="fa fa-print fa-fw"></i>&nbsp;Print</button></a>
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
														<td colspan="4">
															<div class="col-lg-12" style="font-size:20px;"><center><strong>REQUISITION AND ISSUE SLIP</strong></center></div>
															<div class="col-lg-12" style="font-size:16px;"><center>Bicol University College of Nursing</center></div>
														</td>
													</tr>
													<tr>
														<td colspan="4">
															<div class="col-lg-4">
																<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																<strong>Division:</strong>&nbsp;&nbsp;<?php print $getprdetails['office_dept']; ?>
																</div>
																<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																<strong>Section:</strong>&nbsp;&nbsp;<?php print $getprdetails['dept_name']; ?>
																</div>
															</div>
															<div class="col-lg-4">
																<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																<strong>RIS No.:</strong>&nbsp;&nbsp;<?php print $getrisdetails['risnum']; ?>
																</div>
																<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																<strong>SAI No.:</strong>&nbsp;&nbsp;<?php print $getrisdetails['sai_no']; ?>
																</div>
															</div>
															<div class="col-lg-4">
																<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																<strong>RIS Date:</strong>&nbsp;&nbsp;<?php print date("M d, Y", strtotime($getrisdetails['risdate'])); ?>
																</div>
																<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																<strong>SAI Date:</strong>&nbsp;&nbsp;
																<?php 
																
																if($getrisdetails['sai_date'] == "0000-00-00"){
																	print "";
																}else{
																	print date("M d, Y", strtotime($getrisdetails['sai_date']));
																}
																
																?>
																</div>
															</div>
														</td>
													<tr>
														<td colspan="4">
															
															<?php
																$readitems = mysql_query("SELECT * FROM request_items WHERE ris_id = '$readris_id'");
															?>
															
															<div class="table-responsive">
																<table class = "table table-striped table-bordered table-hover" id="showTable">
																	<thead>
																		<tr>
																			<th colspan="4">Requisition</th>
																			<th colspan="3">Issuance</th>
																		</tr>
																		<tr>
																			<th>Unit</th>
																			<th>Name</th>
																			<th width="400">Description</th>
																			<th>Qty</th>
																			<th>Qty</th>
																			<th>Unit Cost</th>
																			<th>Amount</th>
																		</tr>
																	</thead>
																	<tbody>
																	
																		<?php
																		
																		while($getitemdetails = mysql_fetch_array($readitems)){
																			$selitemunit = mysql_fetch_array(mysql_query("SELECT item_unit_name FROM item_unit WHERE item_unit_id = '$getitemdetails[item_unit_id]'"));
																			$selitem = mysql_fetch_array(mysql_query("SELECT item_name FROM items WHERE item_id = '$getitemdetails[item_id]'"));
																			$type = mysql_fetch_array(mysql_query("SELECT si.stock_type FROM stock_units AS su LEFT JOIN stock_items AS si ON si.stock_id = su.stock_id WHERE su.su_id = '$getitemdetails[su_id]'"));
																		?>
																			<tr>
																				<td style="text-align:left;vertical-align:middle;">
																				<center><?php print $selitemunit['item_unit_name']; ?></center>
																				</td>
																				<td style="text-align:left;vertical-align:middle;">
																				<center><?php print $selitem['item_name']; ?></center>
																				</td>
																				<td style="text-align:left;vertical-align:middle;">
																				<center>
																				<?php 
																				
																				if($type['stock_type'] == "Supply"){
																					print $getitemdetails['description'];
																				}else if($type['stock_type'] = "Equipment"){
																					
																					if($getitemdetails['icspar'] == "ICS"){
																						?>
																						<a href="view_ics2.php?id=<?php print $getitemdetails['icspar_id'] ?>"><?php print $getitemdetails['description']; ?></a>
																						<?php
																					}else if($getitemdetails['icspar'] == "PAR"){
																						?>
																						<a href="view_par2.php?id=<?php print $getitemdetails['icspar_id'] ?>"><?php print $getitemdetails['description']; ?></a>
																						<?php
																					}
																				}
																				
																				
																				?>
																				</center>
																				</td>
																				<td style="text-align:left;vertical-align:middle;">
																				<center><?php print $getitemdetails['quantity']; ?></center>
																				</td>
																				<td style="text-align:left;vertical-align:middle;">
																				<center><?php print $getitemdetails['del_quantity']; ?></center>
																				</td>
																				<td style="text-align:left;vertical-align:middle;">
																				<center><?php print "Php ".number_format($getitemdetails['est_unit_cost'], 2,'.',','); ?></center>
																				</td>
																				<td style="text-align:left;vertical-align:middle;">
																				<center><?php print "Php ".number_format($getitemdetails['est_total_cost'], 2,'.',','); ?></center>
																				</td>
																			</tr>
																		<?php
																		}
																		?>
																	</tbody>
																</table>
															</div>
														</td>
													</tr>
													<tr>
														<td>
															<div class="col-lg-12">
																<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																Requested By:
																<br /><br />
																<p style="text-transform:uppercase"><strong><center>
																	<u><?php print $requestedBy['full_name']; ?></u>
																</strong></center></p>
																<p><center>
																	<?php print $requestedBy['position_name']; ?>
																</center></p>
																</div>
															</div>
														</td>
														<td>
															<div class="col-lg-12">
																<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																Approved By:
																<br /><br />
																<p style="text-transform:uppercase"><strong><center>
																	<u><?php print $approvedBy['full_name']; ?></u>
																</strong></center></p>
																<p><center>
																	<?php print $approvedBy['position_name']; ?>
																</center></p>
																</div>
															</div>
														</td>
														<td>
															<div class="col-lg-12">
																<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																Issued By:
																<br /><br />
																<p style="text-transform:uppercase"><strong><center>
																	<u><?php print $issuedBy['full_name']; ?></u>
																</strong></center></p>
																<p><center>
																	<?php print $issuedBy['position_name']; ?>
																</center></p>
																</div>
															</div>
														</td>
														<td>
															<div class="col-lg-12">
																<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																Received By:
																<br /><br />
																<p style="text-transform:uppercase"><strong><center>
																	<u><?php print $receivedBy['full_name']; ?></u>
																</strong></center></p>
																<p><center>
																	<?php print $receivedBy['position_name']; ?>
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