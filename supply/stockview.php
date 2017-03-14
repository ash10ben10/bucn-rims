<!DOCTYPE html>
<?php
	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");
	$date = date("Y-m-d");
	$month = date("Y-m");

	include "../connect.php";
	
	$readstockunit_id = $_GET['id'];
	
	$selectme = "su.su_id, si.stock_id, su.stock_no, si.item_id, i.item_name, si.stock_type, si.description, si.order_point, su.item_unit_id, iu.item_unit_name, su.price, su.quantity, i.category_id, cat.category_name";
	$setfrom = "`stock_items` AS si LEFT JOIN stock_units AS su ON su.stock_id = si.stock_id LEFT JOIN items AS i ON i.item_id = si.item_id LEFT JOIN item_unit AS iu ON iu.item_unit_id = su.item_unit_id LEFT JOIN category AS cat ON cat.category_id = i.category_id";
	
	$getdesc = mysql_fetch_array(mysql_query("SELECT ".$selectme." FROM ".$setfrom." WHERE su.su_id = '$readstockunit_id' "));
	
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
	<?php include "stockview_engine.php"; ?>
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
			
			<?php 
			if($position['position_name'] == "Supply Officer"){
				?>
				<li>
					<a href="sup_list.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-store zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Supply Stocks</a>
				</li>
				<?php
			}else{
				print "";
			}
			?>
			<li>
				<a href="stock_req.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-shopping-cart zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Stock Requests</a>
			</li>
			<?php 
			if($position['position_name'] == "Supply Officer"){
				?>
				<li>
					<a href="supply_label.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-label zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Supply Labels</a>
				</li>
				<li>
					<a href="supply_specs.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-widgets zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Supply Descs</a>
				</li>
				<li>
					<a href="supply_report.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-file-text zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Reports</a>
				</li>
				<?php
			}else{
				print "";
			}
			?>
					
		</ul> <!--/.Inside Sidebar -->
	</div><!--/.Sidebar -->

	<!-- Navigation -->
	<?php include "../supply/sup_header.php"; ?>
	<!--/.Navigation -->
	
<!-- /.Header and Sidebar Page -->
	
<!-- Body will contain the Page Contents -->

<body>
	<!-- Content-Wrapper -->
	<div id="content-wrapper">
		
		<div class="container-fluid">
			
			<div class="row" style="margin-top:-20px;">
				<div class="col-lg-12">
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-google-pages zmdi-hc-lg"></i>&nbsp;&nbsp;Stock No. <?php print $getdesc['stock_no']; ?>: <?php print $getdesc['item_name']; ?></h1>
					
						<br />
						<form role="form" method="post" id="updatestock">
							<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
							
								<div class="panel-heading">
									<div class="row">
										<div class="col-lg-12">
											<div id="enable_stockview_edit" class="pull-left">
												<a href="sup_list.php" class="btn btn-info"><span class="fa fa-arrow-left"></span>&nbsp;&nbsp;Go Back</a>
												<a onClick="enable_edit('stockview')" data-toddle='tooltip' title="Edit Stock Info" class="btn btn-default"><span class="zmdi zmdi-border-color"></span>&nbsp;&nbsp;Edit</a>
											</div>
											<div id="disable_stockview_edit" class="pull-left" hidden >
												<a href="sup_list.php" class="btn btn-info"><span class="fa fa-arrow-left"></span>&nbsp;&nbsp;Go Back</a>
												<a href="stockview.php?id=<?php echo $readstockunit_id; ?>" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span>&nbsp;Cancel</a>
												<button type="submit" name="update_stockinfo" id="update_stockinfo" class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;Save</button>
											</div>
											<div class="pull-right" align="right" >
											<a target="_blank" href="print_stockcard.php?id=<?php echo $readstockunit_id; ?>" button type="button" class="btn btn-primary"><i class="fa fa-print fa-fw"></i>&nbsp;Print</button></a>
											&nbsp;
											</div>
										</div>
									</div>
								</div>
								
								<div class="panel-body">
									<div class="panel panel-default">
										<div class="panel-body">
											<div class="row">
												<div class="col-lg-6" style="font-size:15px;">
													<div class="col-lg-6" style="margin:5px 0 5px 0;">
														<label>Item Name:</label><br/>
														<?php print $getdesc['item_name']; ?>
													</div>
													<div class="col-lg-6" style="margin:5px 0 5px 0;">
														<label>Item Unit:</label><br/>
														<?php print $getdesc['item_unit_name']; ?>
													</div>
													<div class="col-lg-6" style="margin:5px 0 5px 0;">
														<label>Quantity:</label><br/>
														<?php print $getdesc['quantity']; ?>
													</div>
													<div class="col-lg-6" style="margin:5px 0 0 0;">
														<label>Category:</label><br/>
														<?php print $getdesc['category_name']; ?>
													</div>
												</div>
												<div class="col-lg-6">
													<div class="col-lg-6" style="font-size:15px;margin:5px 0 20px 0;">
													<label>Stock Number:</label><br/>
													<?php print $getdesc['stock_no']; ?>
													</div>
													<div class="col-lg-4">
														<label style="font-size:15px;">Reorder Point:</label>
														<input class="form-control" name="orderpoint" id="orderpoint" value="<?php print $getdesc['order_point']; ?>" pattern="([0-9.])+" required disabled>
													</div>
													<div class="col-lg-10">
														<label style="font-size:15px;">Description:</label>
														<input class="form-control" name="stockdesc" id="stockdesc" value="<?php print $getdesc['description']; ?>" required disabled>
													</div>
													
													
												</div>
												
												
											</div>
										</div>
									</div>
						</form>
									<div class="panel panel-default">
										<div class="panel-body">
											
											<?php 
											
											$retrivestock = mysql_query("SELECT * FROM stock_card WHERE su_id = '$readstockunit_id' ");
											
											if(mysql_num_rows($retrivestock) == 0){
												print "<br /><p align=center><i>There is no recorded history of the selected stock on the system.</i></p><br />";
											}else{
												
												print "
													<div class='table-responsive'>
														<table class = 'table table-striped table-bordered table-hover display'>
														<thead>
															<tr>
																<th rowspan='2'>Date</th>
																<th rowspan='2'>Reference</th>
																<th>RIS Receipt</th>
																<th colspan='3'>Stock Issuance</th>
																<th>Balance</th>
															</tr>
															<tr>
																<th>Quantity</th>
																<th>Quantity</th>
																<th>Personnel</th>
																<th>Office</th>
																<th>Qty</th>
															</tr>
														</thead>
														<tbody>
													";
											while($getdata = mysql_fetch_array($retrivestock)){
												$selctPs = "pwi.personnel_id, CONCAT(p.personnel_fname,' ',p.personnel_lname) AS full_name, d.dept_name";
												$fromPs= "personnel_work_info AS pwi LEFT JOIN personnel AS p ON p.personnel_id = pwi.personnel_id LEFT JOIN department AS d ON d.dept_id = pwi.dept_id";
												$getrequestor = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM ".$fromPs." WHERE pwi.personnel_id = $getdata[personnel_id] "));
												
												print "<tr>";
												
												print "<td style='text-align:center;vertical-align:middle;'>";
												print date("M d, Y", strtotime($getdata['recdate']));
												print "</td>";
												print "<td style='text-align:center;vertical-align:middle;'>";
												print $getdata['reference'];
												print "</td>";
												print "<td style='text-align:center;vertical-align:middle;'>";
													if($getdata['qty_receipt'] == 0){
														print "";
													}else{
														print $getdata['qty_receipt'];
													}
												print "</td>";
												print "<td style='text-align:center;vertical-align:middle;'>";
												
													if($getdata['issue_qty'] == 0){
														print "";
													}else{
														print $getdata['issue_qty'];
													}
												
												print "</td>";
												print "<td style='text-align:center;vertical-align:middle;'>";
												
													if($getdata['personnel_id'] == 0){
														print "";
													}else{
														print $getrequestor['full_name'];
													}
													
												print "</td>";
												print "<td style='text-align:center;vertical-align:middle;'>";
												
													if($getdata['personnel_id'] == 0){
														print "";
													}else{
														print $getrequestor['dept_name'];
													}
													
												print "</td>";
												print "<td style='text-align:center;vertical-align:middle;'>";
												print $getdata['issue_stock_bal'];
												print "</td>";
												
												print "</tr>";
											}
												
											}
											print "</tbody></table></div>";
											
											?>
											
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