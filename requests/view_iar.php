<!DOCTYPE html>
<?php
	include "../connect.php";
	
	$readiar_id = $_GET['id'];
	$getiar = mysql_fetch_array(mysql_query("SELECT * FROM inspect_accept_report WHERE iar_id ='$readiar_id'")) or die(mysql_error());
	$getins = mysql_fetch_array(mysql_query("SELECT * FROM inspection WHERE inspection_id ='$getiar[inspection_id]'")) or die(mysql_error());
	
	$getpo = mysql_fetch_array(mysql_query("SELECT * FROM purchase_order WHERE po_id = '$getiar[po_id]'")) or die(mysql_error());
	$getsupplier = mysql_fetch_array(mysql_query("SELECT * FROM supplier WHERE supplier_id ='$getpo[supplier_id]'")) or die(mysql_error());
	$getpr = mysql_fetch_array(mysql_query("SELECT pr.pr_id, d.dept_name FROM purchase_request AS pr LEFT JOIN department AS d ON d.dept_id = pr.dept_id WHERE pr_id = '$getpo[pr_id]'")) or die(mysql_error());
	
	$selctPs = "CONCAT(p.personnel_fname,' ',p.personnel_lname) AS full_name, pp.position_name";
	$fromPs= "personnel_work_info AS pwi LEFT JOIN personnel AS p ON p.personnel_id = pwi.personnel_id LEFT JOIN personnel_position AS pp ON pp.position_id = pwi.position_id";
	$getacceptor = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM ".$fromPs." WHERE pwi.personnel_id = $getiar[personnel_id] ")) or die(mysql_error());
	$getinspector = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM ".$fromPs." WHERE pwi.personnel_id = $getins[personnel_id] ")) or die(mysql_error());
	
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
				
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-file-text zmdi-hc-lg"></i>&nbsp;&nbsp;IAR No. <?php echo $getiar['iarnumber']; ?> dated <?php print date("M j, Y", strtotime($getiar['iardate'])) ?></h1>
						
						<br />
						<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
							<div class="panel-heading">
								<div class="row">
									<div class="col-lg-4">
										<a href="../requests/status.php" class="btn btn-info"><span class="fa fa-arrow-left"></span>&nbsp;&nbsp;Go Back</a>
									</div>
									<div class="col-lg-8" align="right">
										<a target="_blank" href="print_iar.php?id=<?php echo $getiar['iar_id']; ?>"><button type="button" class="btn btn-primary"><i class="fa fa-print fa-fw"></i>&nbsp;Print</button></a>
									</div>
								</div>
							</div>
							<div class="panel-body">
									<div class="row">
										<div class="col-md-12">
												<div class="panel panel-default">
													<div class="panel-body">
													
														<div class="table-responsive">
															<table class="table table-striped table-bordered table-hover">
																<tbody>
																	<tr>
																		<td colspan="2">
																			<div class="col-lg-12" style="font-size:20px;"><center><strong>INSPECTION AND ACCEPTANCE REPORT</strong></center></div>
																			<div class="col-lg-12" style="font-size:16px;"><center>Bicol University College of Nursing</center></div>
																		</td>
																	</tr>
																		<tr>
																			<td width="50%">
																					<div class="col-lg-12">
																						<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																						<strong>Supplier:</strong>&nbsp;&nbsp;<?php print $getsupplier['supplier_name']?>
																						</div>
																						<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																						<strong>Purchase Order No.</strong>&nbsp;&nbsp;<?php print $getpo['ponumber']?>
																						</div>
																						<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																						<strong>Requesting Office:</strong>&nbsp;&nbsp;<?php print $getpr['dept_name'];?>
																						</div>
																					</div>
																			</td>
																			<td width="50%">
																					<div class="col-lg-12">
																						<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																						<strong>IAR No.</strong>&nbsp;&nbsp;<?php echo $getiar['iarnumber']; ?>
																						</div>
																						<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																						<strong>Invoice No.</strong>&nbsp;&nbsp;<?php print $getiar['invoice_num'] ?>
																						</div>
																						<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																						<strong>Invoice No. Date:</strong>&nbsp;&nbsp;<?php print date("M j, Y", strtotime($getiar['invoice_date']))?>
																						</div>
																					</div>
																			</td>
																		</tr>
																		<tr>
																			<td colspan="2">
																				<?php 
																					
																					$getitems = mysql_query("SELECT * FROM request_items WHERE iar_id = '$readiar_id' ") or die(mysql_error());
																					
																					if(mysql_num_rows($getitems) == 0){
																						print "<br /><p align=center><i>Accepted items are not available.</i></p><br />";
																					}else{
																						print "
																							<table class = 'table table-striped table-bordered table-hover'>
																							<thead>
																								<tr>
																									<th>Unit</th>
																									<th>Item Name</th>
																									<th>Item Description</th>
																									<th>Quantity</th>
																								</tr>
																							</thead>
																							<tbody>
																						";
																						
																						while($getdata = mysql_fetch_array($getitems)){
																							$getunit = mysql_fetch_array(mysql_query("SELECT * FROM item_unit WHERE item_unit_id = $getdata[item_unit_id]"))or die (mysql_error());
																							$showitems = mysql_fetch_array(mysql_query("SELECT * FROM items WHERE item_id = $getdata[item_id]"))or die (mysql_error());
																							
																							print "<tr>";
																							print "<td width = '15%'>".$getunit['item_unit_name']."</td>";
																							print "<td>".$showitems['item_name']."</td>";
																							print "<td>".$getdata['description']."</td>";
																							print "<td width ='10%'>".$getdata['del_quantity']."</td>";
																							
																						}
																						print "</tr></tbody>";
																						
																						print "</table>";
																					}
																				?>
																			</td>
																		</tr>
																		<tr>
																			<td>
																				<center><strong>INSPECTION</strong></center>
																			</td>
																			<td>
																				<center><strong>ACCEPTANCE</strong></center>
																			</td>
																		</tr>
																		<tr>
																			<td>
																				<div class="col-lg-12">
																					<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																						Date Inspected: <?php print date("M j, Y", strtotime($getins['inspection_date'])) ?>
																					</div>
																					<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																						
																						<table>
																							<tr>
																								<td><span class="fa fa-check fa-2x"></span></td>
																								<td style="padding-left:10px;">Inspected, verified, and found on order as to quantity and specifications.</td>
																							</tr>
																						</table>
																						
																					</div>
																					<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																					<br /><br />
																					<p style="text-transform:uppercase"><strong><center>
																						<u><?php print $getinspector['full_name']; ?></u>
																					</strong></center></p>
																					<p><center>
																						<?php print $getinspector['position_name']; ?>
																					</center></p>
																					</div>
																				</div>
																			</td>
																			<td>
																				<div class="col-lg-12">
																					<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																						Date Received: <?php print date("M j, Y", strtotime($getiar['iardate'])) ?>
																					</div>
																					<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																						
																						<table>
																							<tr>
																								<td><span class="fa fa-check fa-2x"></span></td>
																								<td style="padding-left:10px;">Items that are listed above are complete of its quantity based from the said request.</td>
																							</tr>
																						</table>
																						
																					</div>
																					<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																					<br /><br />
																					<p style="text-transform:uppercase"><strong><center>
																						<u><?php print $getacceptor['full_name']; ?></u>
																					</strong></center></p>
																					<p><center>
																						<?php print $getacceptor['position_name']; ?>
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
				</div>
			</div>
		
		</div> <!--/.container-fluid-->
	
	</div> <!--/.content-wrapper-->
	
	
</body>
<!-- /.Body -->

</div> <!-- /.wrapper-->

</html>