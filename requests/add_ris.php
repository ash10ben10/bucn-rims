<!DOCTYPE html>
<?php
	include "../connect.php";
	
	$readpr_id = $_GET['id'];
	
	$getprinfo = mysql_fetch_array(mysql_query("SELECT * FROM purchase_request WHERE pr_id ='$readpr_id'")) or die(mysql_error());
	$getdept = mysql_fetch_array(mysql_query("SELECT * FROM department WHERE dept_id ='$getprinfo[dept_id]'")) or die(mysql_error());
	
	$getpo = mysql_fetch_array(mysql_query("SELECT po_id FROM purchase_order WHERE pr_id = '$readpr_id' LIMIT 1")) or die(mysql_error());
	$getfunding = mysql_fetch_array(mysql_query("SELECT * FROM funding WHERE po_id ='$getpo[po_id]'")) or die(mysql_error());
	
	$selctPs = "CONCAT(p.personnel_fname,' ',p.personnel_lname) AS full_name, pp.position_name";
	$fromPs = "personnel_work_info AS pwi LEFT JOIN personnel AS p ON p.personnel_id = pwi.personnel_id LEFT JOIN personnel_position AS pp ON pp.position_id = pwi.position_id";
	$getrequestor = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM ".$fromPs." WHERE pwi.personnel_id = $getprinfo[personnel_id] ")) or die(mysql_error());
	
	$getsupplyposition = mysql_fetch_array(mysql_query("SELECT position_id FROM personnel_position WHERE position_name = 'Supply Officer' LIMIT 1")) or die(mysql_error());
	$getsupplyofficer = mysql_fetch_array(mysql_query("SELECT personnel_id FROM personnel_work_info WHERE position_id = $getsupplyposition[position_id] ")) or die(mysql_error());
	
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
	
	<?php include "addris_engine.php"; ?>
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
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-file zmdi-hc-lg"></i>&nbsp;&nbsp;Requisition and Issuance Slip</h1>
					<form method="POST" id="addiar" enctype="multipart/form-data">
						<br />
						<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
							<div class="panel-body">
								<div class="row">
									<div class="col-lg-12">
										<div class="col-lg-12">
											<div class="panel panel-default">
												<div class="panel-body">
													<div class="col-lg-8" style="margin-top:15px;">
														<div class="form-group col-lg-6" style="font-size:15px;">
															<label>Division:</label><br />
															BUCN
														</div>
														<div class="form-group col-lg-6" style="font-size:15px;">
															<label>Office:</label><br />
															<?php print $getdept['dept_name']; ?>
														</div>
														<div class="form-group col-lg-6" style="font-size:15px;">
															<label>Requested by:</label><br />
															<?php print $getrequestor['full_name']; ?>
														</div>
														<div class="form-group col-lg-6" style="font-size:15px;">
															<label>Position:</label><br />
															<?php print $getrequestor['position_name']; ?>
														</div>
													</div>
													
													<div class="col-lg-4">
														<div class="form-group col-lg-12" style="font-size:15px;">
															<label>SAI No:</label><br />
															<input class="form-control" name="sainum" id="sainum" placeholder="Input SAI No. if any" value="<?php
															if($getprinfo['sai_no'] == null){
																print "";
															}else{
																print $getprinfo['sai_no'];
															}?>" />
															<?php 
															
															
															
															?>
														</div>
														<div class="form-group col-lg-12" style="font-size:15px;">
															<label>SAI Date:</label><br />
															<input class="form-control" type="date" name="saidate" id="saidate" />
														</div>
													</div>
												</div>
											</div>
										</div>
										
										<div class="col-lg-12">
										
											<div class="panel panel-default">
												<div class="panel-body">
													<?php
														$getpritems = mysql_query("SELECT * FROM request_items WHERE pr_id = '$readpr_id' AND accstat = 'Accepted'");
													?>
													
													<div class="table-responsive">
														<table class="table table-striped table-bordered table-hover" id="showTable">
															<thead>
																<tr>
																	<th colspan="4">Requisition</th>
																	<th colspan="3">Issuance</th>
																	<th rowspan="2" width="20%">Status</th>
																</tr>
																<tr>
																	<th>Unit</th>
																	<th>Name</th>
																	<th>Description</th>
																	<th>Qty</th>
																	<th>Qty</th>
																	<th>Unit Cost</th>
																	<th>Amount</th>
																</tr>
															</thead>
															<tbody>
															
															<?php
																while($getdata = mysql_fetch_array($getpritems)){
																		$getunit = mysql_fetch_array(mysql_query("SELECT * FROM item_unit WHERE item_unit_id = $getdata[item_unit_id]"));
																		$showitems = mysql_fetch_array(mysql_query("SELECT * FROM items WHERE item_id = $getdata[item_id]"));
																		$getstockunits = mysql_fetch_array(mysql_query("SELECT * FROM `stock_units` WHERE `su_id` = $getdata[su_id]"));
															
																	?>
																	<tr>
																		<td style="text-align:center;vertical-align:middle;"><?php print $getunit['item_unit_name'];?></td>
																		<td style="text-align:center;vertical-align:middle;"><?php print $showitems['item_name'];?></td>
																		<td style="text-align:center;vertical-align:middle;"><?php print $getdata['description'];?></td>
																		<td style="text-align:center;vertical-align:middle;"><?php print $getdata['quantity'];?></td>
																		<td style="text-align:center;vertical-align:middle;"><?php print $getdata['del_quantity'];?></td>
																		<td style="text-align:center;vertical-align:middle;"><?php print "Php ".number_format($getdata['est_unit_cost'], 2,'.',',');?></td>
																		<td style="text-align:center;vertical-align:middle;"><?php print "Php ".number_format($getdata['est_total_cost'], 2,'.',',');?></td>
																		<td style="text-align:center;vertical-align:middle;">
																		<?php 
																		if($showitems['item_type'] == "Supply"){
																			if($getdata['issuance'] == "Ready"){
																				print "Request is ready for issuance.";
																			}else{
																				print "Request is not ready for issuance.";
																			}
																		}else if($showitems['item_type'] == "Equipment"){
																				if($getdata['issuance'] == "Not Ready"){
																					if($getdata['est_unit_cost'] >= 15000){
																						?>
																						<a href="add_par.php?id=<?php echo $getdata['req_item_id']; ?>" class="btn btn-default" title="Create Property Acknowledgement Receipt for the current item."><i class="fa fa-plus-circle fa-fw"></i>&nbsp;&nbsp;Create PAR</a>
																						<?php
																					}else if($getdata['est_unit_cost'] < 15000){
																						?>
																						<a href="add_ics.php?id=<?php echo $getdata['req_item_id']; ?>" class="btn btn-default" title="Create Inventory Custodian Slip for the current item."><i class="fa fa-plus-circle fa-fw"></i>&nbsp;&nbsp;Create ICS</a>
																						<?php
																					}
																				}else if($getdata['issuance'] == ""){
																					print "Request is not ready for issuance.";
																				}else if($getdata['issuance'] == "Ready"){
																					
																					$geteqpinfo = mysql_fetch_array(mysql_query("SELECT icspar FROM equipments WHERE req_item_id = '$getdata[req_item_id]' LIMIT 1"));
																					
																					if($geteqpinfo['icspar'] == 'PAR'){
																						?>
																						<div class="row">
																							<?php print "Ready"; ?>&nbsp;&nbsp;&nbsp;&nbsp;<a href="view_par.php?id=<?php echo $getdata['icspar_id']; ?>" class="btn btn-default" title="View Property Acknowledgement Receipt of the item."><i class="fa fa-external-link fa-fw"></i>&nbsp;&nbsp;View PAR</a>
																						</div>
																						<?php
																					}else if($geteqpinfo['icspar'] == 'ICS'){
																						?>
																						<div class="row">
																							<?php print "Ready"; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="view_ics.php?id=<?php echo $getdata['icspar_id']; ?>" class="btn btn-default" title="View Inventory Custodian Receipt of the item."><i class="fa fa-external-link fa-fw"></i>&nbsp;&nbsp;View ICS</a>
																						</div>
																						<?php
																					}
																					
																					//print "Display the ICS/PAR form here.";
																				}
																			
																		}
																		?>
																		</td>
																	</tr>
																	<?php
																}
															?>
															</tbody>
														</table>
													</div>
													
												</div>
											</div>
										
										</div>
									</div>
								</div>
							</div>
							<div class="panel-footer" align="right">
								<a href="../requests/issuance.php" class="btn btn-info"><span class="fa fa-arrow-left"></span>&nbsp;&nbsp;Go Back</a>
								<?php 
									$getdataone = "SELECT `issuance` FROM request_items WHERE pr_id ='$readpr_id' AND instat = 'Complete'";
									$getdatatwo = "SELECT `issuance` FROM request_items WHERE pr_id ='$readpr_id' AND instat = 'Complete' AND issuance = 'Ready'";
									
									$datarrayone = mysql_num_rows(mysql_query($getdataone));
									$datarraytwo = mysql_num_rows(mysql_query($getdatatwo));
									
									$subtract = ($datarrayone - $datarraytwo);
									
									if ($subtract == 0){
										$isDisabled = "";
										$btnTitle = "You can now complete the issuance.";
									}else if ($subtract > 0){
										$isDisabled = "disabled";
										$btnTitle = "You cannot complete issuance until all items are ready.";
									}
									
									print "<button type='submit' name='risave' id='risave' class='btn btn-success' title='".$btnTitle."' ".$isDisabled."><span class='glyphicon glyphicon-ok'></span>&nbsp;Submit</button>";
								
								?>
							</div>
						</div>
					</form>
				</div>
			</div>
		
			
		
		</div> <!--/.container-fluid-->
	
	</div> <!--/.content-wrapper-->
	
	
</body>
<!-- /.Body -->

</div> <!-- /.wrapper-->

</html>