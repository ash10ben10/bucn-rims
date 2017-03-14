<!DOCTYPE html>
<?php
	include "../connect.php";
	
	$readpo_id = $_GET['id'];
	
	$getpo = mysql_fetch_array(mysql_query("SELECT * FROM purchase_order WHERE po_id ='$readpo_id'"));
	$getsupplier = mysql_fetch_array(mysql_query("SELECT * FROM supplier WHERE supplier_id='$getpo[supplier_id]'"));
	$getpr = mysql_fetch_array(mysql_query("SELECT dept_id FROM purchase_request WHERE pr_id='$getpo[pr_id]'"));
	$getdept = mysql_fetch_array(mysql_query("SELECT * FROM department WHERE dept_id='$getpr[dept_id]'"));
	$getins = mysql_fetch_array(mysql_query("SELECT * FROM inspection WHERE po_id='$readpo_id'"));
	
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
	
	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");
	
?>
<html lang="en">

<head>
	<!-- Calling Default CSS files -->
	<?php include "../engine/csscalls.php"; ?>
	<!-- Calling Default Javascript files -->
	<?php include  "../engine/jscalls.php"; ?>
	
	<?php include "addiar_engine.php"; ?>
	
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
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-file zmdi-hc-lg"></i>&nbsp;&nbsp;Inspection and Acceptance Report</h1>
					<form method="POST" id="addiar" enctype="multipart/form-data">
						<br />
						<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
							<div class="panel-body">
								<div class="row">
									<div class="col-lg-12">
										<div class="col-lg-6">
											<div class="panel panel-default">
												<div class="panel-body">
													<div class="form-group col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
														<label>Supplier:</label>
														<?php print $getsupplier['supplier_name'] ?>
													</div>
													<div class="form-group col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
														<label>Purchase Order Number:</label>
														<?php print $getpo['ponumber'] ?>
													</div>
													<div class="form-group col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
														<label>Requesting Office:</label>
														<?php print $getdept['dept_name']; ?>
													</div>
													<div class="form-group col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
														<label>Date Inspected:</label>
														<?php print date("M j, Y", strtotime($getins['inspection_date'])) ?>
													</div>
												</div>
											</div>
										</div>
										
										<div class="col-lg-4">
											<div class="panel panel-default">
												<div class="panel-body" style="margin-bottom:5px;">
													<div class="form-group col-lg-12">
														<div class="form-group col-lg-12">
															<label>Invoice Number:</label>
															<input class="form-control" name="invoicenum" id="invoicenum" placeholder="Invoice Number" pattern="([0-9])+" required />
														</div>
														<div class="form-group col-lg-12">
															<label>Invoice Number Date:</label>
															<input type="date" name="invoicedate" id="invoicedate" class="form-control" required />
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								
								<div class="form-group col-lg-12">
									<div class="panel panel-default">
										<div class="panel panel-body">
											<?php
															
												$select = "ri.req_item_id, ri.item_id, i.item_name, i.item_type, iu.item_unit_name, ri.description, ri.del_quantity, ri.est_unit_cost";
												$from = "request_items AS ri LEFT JOIN items AS i ON i.item_id = ri.item_id LEFT JOIN item_unit AS iu ON iu.item_unit_id = ri.item_unit_id";
												$getpoitems = mysql_query("SELECT ".$select." FROM ".$from." WHERE po_id = '$readpo_id' AND del_quantity != '0'");
												
											?>
												<div class="table-responsive">
													<table class = "table table-striped table-bordered table-hover display">
														<thead>
															<tr>
																<th>Unit</th>
																<th>Item Name</th>
																<th>Item Description</th>
																<!--<th>Amount</th>-->
																<th>Quantity</th>
																<!--<th>Acceptance</th>-->
															</tr>
														</thead>
														<tbody>
											
											
													<?php
														while($getdata = mysql_fetch_array($getpoitems)){
													?>
															<tr>
															<td><?php print $getdata['item_unit_name'];?></td>
															<td><?php print $getdata['item_name'];?></td>
															<td><?php print $getdata['description'];?></td>
															<!--<td style="text-align:center;vertical-align:middle;"><?php //print "Php ".number_format($getdata['est_unit_cost'], 2,'.',',')."/qty";?></td>-->
															<td style="text-align:center;vertical-align:middle;"><?php print $getdata['del_quantity'];?></td>
															
															<!--<td><center>
															
															<button name="addItem" class="btn btn-info" ><span class="fa fa-plus-circle fa-fw"></span>&nbsp;&nbsp;Add to Inventory</button>
															
															</center></td>-->
															
															</tr>
												<?php	}?>
														</tbody>
													
													<!--<td class="gTotalNums" style="text-align:center;vertical-align:middle;">Php <span>//print $getpo['allitem_nums'];</span></td>-->
													
													
													</table>
												</div>
										</div>
									</div>
								</div>
							</div>
							<div class="panel-footer" align="right">
								<div class="row">
									<div class="col-lg-12">
										<a href="../requests/status.php" class="btn btn-info"><span class="fa fa-arrow-left"></span>&nbsp;&nbsp;Go Back</a>
										<button type="submit" name="iarsave" id="iarsave" class="btn btn-success" title="Click here to complete acceptance report."><span class="glyphicon glyphicon-ok"></span>&nbsp;Complete Acceptance</button>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		<!--</form>-->
		
		</div> <!--/.container-fluid-->
		
		<?php
		
			/* $select = "ri.req_item_id, i.item_id, i.item_name, i.item_type, iu.item_unit_name, ri.description, ri.quantity, ri.est_unit_cost";
			$from = "request_items AS ri LEFT JOIN items AS i ON i.item_id = ri.item_id LEFT JOIN item_unit AS iu ON iu.item_unit_id = ri.item_unit_id";
			$getpoitems = mysql_query("SELECT ".$select." FROM ".$from." WHERE po_id = '$readpo_id' AND instat = 'Complete'");
			
			while($getme = mysql_fetch_array($getpoitems)){
				
				
				//$getdescrow = mysql_num_rows($getdesc);
				
				if($getme['item_type'] == "Supply"){
					
					$getdesc = mysql_query("SELECT description FROM stock_items WHERE description = '$getme[description]' LIMIT 1");
					$getdescrows = mysql_num_rows($getdesc);
					
					if($getdescrows == 0){
							print $getme['description'];
							print " - WALA SA RECORD";
							print "<br />";
					}else{
						while($getdescinfo = mysql_fetch_array($getdesc)){
							print $getdescinfo['description']." - (";
							print $getme['description'];
							print ") - MERON NA SA RECORD!";
							print "<br />";
						}
					}
					
				}else if($getme['item_type'] == "Equipment"){
					
					$getdesc = mysql_query("SELECT description FROM stock_items WHERE description = '$getme[description]' LIMIT 1");
					$getdescrows = mysql_num_rows($getdesc);
					
					if($getdescrows == 0){
							print $getme['description'];
							print " - WALA SA RECORD";
							print "<br />";
					}else{
						while($getdescinfo = mysql_fetch_array($getdesc)){
							print $getdescinfo['description']." - ";
							print $getme['description'];
							print " - MERON NA SA RECORD!";
							print "<br />";
						}
					}
					
				}
			} */
		
		?>
	
	</div> <!--/.content-wrapper-->
	
	
</body>
<!-- /.Body -->

</div> <!-- /.wrapper-->

</html>