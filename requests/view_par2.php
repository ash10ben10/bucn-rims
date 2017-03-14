<!DOCTYPE html>
<?php
	include "../connect.php";
	
	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");
	$date = date("Y-m-d");
	$month = date("Y-m");
	
	$readpar_id = $_GET['id'];
	
	$getpar = mysql_fetch_array(mysql_query("SELECT * FROM eqp_par WHERE par_id = '$readpar_id'"))or die (mysql_error());
	$geteqpdetails = mysql_fetch_array(mysql_query("SELECT * FROM equipments WHERE icspar = 'PAR' AND ics_par_id = '$readpar_id'"))or die (mysql_error());
	$getitemdetails = mysql_fetch_array(mysql_query("SELECT * FROM request_items WHERE req_item_id = '$geteqpdetails[req_item_id]'"))or die (mysql_error());
	$selitemunit = mysql_fetch_array(mysql_query("SELECT item_unit_name FROM item_unit WHERE item_unit_id = '$geteqpdetails[item_unit_id]'"))or die(mysql_error());
	$selitem = mysql_fetch_array(mysql_query("SELECT item_name FROM items WHERE item_id = '$geteqpdetails[item_id]'"))or die(mysql_error());
	
	#ris to return back to ris form
	$getris = mysql_fetch_array(mysql_query("SELECT ris_id FROM request_issue_slip WHERE ris_id = '$getitemdetails[ris_id]'"))or die (mysql_error());
	
	$getpr = mysql_fetch_array(mysql_query("SELECT * FROM purchase_request WHERE pr_id='$getpar[pr_id]'"))or die(mysql_error());
	$getpo = mysql_fetch_array(mysql_query("SELECT * FROM purchase_order WHERE po_id='$getpar[po_id]'"))or die(mysql_error());
	$getfunding = mysql_fetch_array(mysql_query("SELECT * FROM `funding` WHERE `fund_id`='$getpar[fund_id]'"))or die(mysql_error());
	$getsupplier = mysql_fetch_array(mysql_query("SELECT * FROM supplier WHERE supplier_id='$getpar[supplier_id]'"))or die(mysql_error());
	
	$selctPs = "CONCAT(p.personnel_fname,' ',p.personnel_lname) AS full_name, pp.position_name";
	$fromPs= "personnel_work_info AS pwi LEFT JOIN personnel AS p ON p.personnel_id = pwi.personnel_id LEFT JOIN personnel_position AS pp ON pp.position_id = pwi.position_id";
	$receivedBy = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM ".$fromPs." WHERE pwi.personnel_id = '$getpar[receivedBy]' ")) or die(mysql_error());
	$receivedFr = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM ".$fromPs." WHERE pwi.personnel_id = '$getpar[receivedFrom]' ")) or die(mysql_error());

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
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-file-text zmdi-hc-lg"></i>&nbsp;&nbsp;Property Acknowledgement Receipt</h1>
						<br />
						<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
							<div class="panel-heading">
								<div class="row">
									<div class="col-lg-8" align="left">
									<a href="../requests/view_ris.php?id=<?php print $getris['ris_id']; ?>" class="btn btn-info"><span class="fa fa-arrow-left"></span>&nbsp;&nbsp;Go Back</a>
									</div>
									<div class="col-lg-4" align="right">
										<a target="_blank" href="print_par.php?id=<?php echo $readpar_id; ?>"><button type="button" class="btn btn-primary"><i class="fa fa-print fa-fw"></i>&nbsp;Print</button></a>
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
															<div class="col-lg-12" style="font-size:20px;margin: 25px 0 10px 0;"><center><strong>PROPERTY ACKNOWLEDGEMENT RECEIPT</strong></center></div>
														</td>
													</tr>
													<tr>
														<td colspan="2">
															&nbsp;
															<div class="row">
																<div class="col-lg-1"></div>
																<div class="col-lg-7" style="font-size:15px;">
																	PAR No. <strong><?php print $getpar['parnum'];?></strong>
																</div>
																<div class="col-lg-4" align="center" style="font-size:15px;">
																	Date: <?php print date("M j, Y", strtotime($getpar['pardate'])); ?>
																</div>
															</div>
															&nbsp;
														
															<div class="table-responsive">
																<table class = "table table-striped table-bordered table-hover">
																	<thead>
																		<tr>
																			<th>Qty</th>
																			<th>Unit</th>
																			<th width="400">Description</th>
																			<th>Date Acquired</th>
																			<th>Unit Price</th>
																			<th>Total</th>
																		</tr>
																	</thead>
																	<tbody>
																	
																	<?php 
																	?>
																	
																		<tr>
																			<td rowspan="2" style="text-align:center;vertical-align:middle;">
																			<center><?php print $getpar['quantity']; ?></center>
																			</td>
																			<td rowspan="2" style="text-align:center;vertical-align:middle;">
																			<center><?php print $selitemunit['item_unit_name']; ?></center>
																			</td>
																			<td style="text-align:left;vertical-align:middle;">
																			<?php print $selitem['item_name'].", ".$geteqpdetails['brand']; ?>
																			</td>
																			<td rowspan="2" style="text-align:center;vertical-align:middle;">
																			<center>
																			<?php print date("M/d/Y", strtotime($getpar['date_acquired'])); ?>
																			</center>
																			</td>
																			<td rowspan="2" style="text-align:center;vertical-align:middle;">
																			<center><?php print "Php ".number_format($geteqpdetails['unit_value'], 2,'.',',');?></center>
																			</td>
																			<td rowspan="2" style="text-align:center;vertical-align:middle;">
																			<center><?php print "Php ".number_format($getpar['total_cost'], 2,'.',',');?></center>
																			</td>
																		</tr>
																		<tr>
																			<td style="text-align:left;vertical-align:middle;">
																			<?php print $geteqpdetails['description']; ?>
																			</td>
																		</tr>
																	</tbody>
																	
																	<?php
																		$pareqps = mysql_query("SELECT * FROM equipments WHERE icspar = 'PAR' AND ics_par_id = '$readpar_id'")or die (mysql_error());
																		if(mysql_num_rows($pareqps) == 0){
																			
																		}else{
																			?>
																			<thead>
																				<tr>
																					<th colspan="2">Item No.</th>
																					<th>Serial Number</th>
																					<th colspan="2">Property Number</th>
																					<th colspan="1">Status</th>
																				</tr>
																			</thead>
																			<tbody>
																			<?php
																			$count = 1;
																			while($getdata = mysql_fetch_array($pareqps)){
																			?>
																			<tr>
																				<td colspan="2" style="text-align:center;vertical-align:middle;"><?php print $count; ?></td>
																				<td style="text-align:center;vertical-align:middle;"><?php 
																				if($getdata['serialnum'] == ""){
																					print "Not available";
																				}else{
																					print $getdata['serialnum'];
																				}
																				?></td>
																				<td colspan="2" style="text-align:center;vertical-align:middle;"><?php print $getdata['prop_num']; ?></td>
																				<td style="text-align:center;vertical-align:middle;"><?php print $getdata['remarks']; ?></td>
																			</tr>
																			<?php
																			$count++;
																			}
																			?></tbody><?php
																		}
																	?>
																	
																</table>
															</div>
														</td>
													</tr>
													<tr>
														<td colspan="2">
															<br />
															<div class="row">
																<div class="col-lg-1">
																</div>
																<div class="form-group col-lg-5" style="font-size:15px;" align="left">
																	<label>PR Number: </label> <?php print $getpr['prnum']." dated ".$getpr['prdate']; ?>
																</div>
																<div class="form-group col-lg-5" style="font-size:15px;" align="left">
																	<label>PO Number: </label> <?php print $getpo['ponumber']." dated ".$getpo['podate']; ?>
																</div>
															</div>
															<div class="row">
																<div class="col-lg-1">
																</div>
																<div class="form-group col-lg-5" style="font-size:15px;" align="left">
																	<label>OS Number:</label> <?php print $getfunding['os_num']; ?>
																</div>
																<div class="form-group col-lg-5" style="font-size:15px;" align="left">
																	<label>Served by:</label> <?php print $getsupplier['supplier_name'].", ".$getsupplier['supplier_address']; ?>
																</div>
															</div>
														</td>
													</tr>
													<tr>
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
														<td>
															<div class="col-lg-12">
																<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																Received From:
																<br /><br />
																<p style="text-transform:uppercase"><strong><center>
																	<u><?php print $receivedFr['full_name']; ?></u>
																</strong></center></p>
																<p><center>
																	<?php print $receivedFr['position_name']; ?>
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