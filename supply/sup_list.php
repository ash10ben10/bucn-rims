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
		$personnel = mysql_fetch_array(mysql_query("SELECT * FROM personnel WHERE personnel_id = '".$_SESSION['logged_personnel_id']."' "));
		$pworkinfo = mysql_fetch_array(mysql_query("SELECT * FROM personnel_work_info WHERE personnel_id = '".$_SESSION['logged_personnel_id']."' "));
		$position = mysql_fetch_array(mysql_query("SELECT * FROM personnel_position WHERE position_id = '$pworkinfo[position_id]' "));
		$account = mysql_fetch_array(mysql_query("SELECT * FROM account WHERE personnel_id = '".$_SESSION['logged_personnel_id']."' "));
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
			
			<?php 
			if($position['position_name'] == "Supply Officer"){
				?>
				<li class="active">
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
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-store zmdi-hc-lg"></i>&nbsp;&nbsp;Supply Stocks</h1>
					
						<br />
						<div class="panel panel-default" div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
							<div class="panel-heading">
								<form method="POST">
									<div class="row">
										<div class="col-lg-12">
											<div class="col-lg-1" style="margin:15px 0 0 0;">
												<label>Filter:</label>
											</div>
											<div class="col-lg-3" style="margin:8px 0 0 0;">
												<select name="filstock" id="filstock" class="selectpicker form-control" data-hide-disabled="true" data-live-search="true" required >
													<option value="" selected disabled>- Select -</option>
													<option value="all">All Stocks</option>
													<option value="sa">Stocks Available</option>
													<option value="cls">On Critical Level</option>
												</select>
											</div>
											<div class="col-lg-1">
												<button name="filterstocks" id="filterstocks" class="btn btn-info" style="margin:8px 0 8px 0;" ><span class="fa fa-arrow-right fa-fw"></span>&nbsp;Go</button>
											</div>
										</div>
									</div>
								</form>
							</div>
							<div class="panel-body">
							
								<?php 
								
								if(isset($_POST['filterstocks'])){
									
									if($_POST['filstock'] == "all"){
										$showsupply = mysql_query("SELECT su.su_id, si.stock_id, su.stock_no, si.item_id, si.stock_type, si.description, si.order_point, su.item_unit_id, su.price, su.quantity FROM `stock_items` AS si LEFT JOIN stock_units AS su ON su.stock_id = si.stock_id WHERE si.stock_type = 'Supply'");
									}else if($_POST['filstock'] == "sa"){
										$showsupply = mysql_query("SELECT su.su_id, si.stock_id, su.stock_no, si.item_id, si.stock_type, si.description, si.order_point, su.item_unit_id, su.price, su.quantity FROM `stock_items` AS si LEFT JOIN stock_units AS su ON su.stock_id = si.stock_id WHERE si.stock_type = 'Supply' AND su.quantity != 0");
									}else if($_POST['filstock'] == "cls"){
										$showsupply = mysql_query("SELECT su.su_id, si.stock_id, su.stock_no, si.item_id, si.stock_type, si.description, si.order_point, su.item_unit_id, su.price, su.quantity FROM `stock_items` AS si LEFT JOIN stock_units AS su ON su.stock_id = si.stock_id WHERE si.stock_type = 'Supply' AND su.quantity <= si.order_point");
									}
								}else{
									$showsupply = mysql_query("SELECT su.su_id, si.stock_id, su.stock_no, si.item_id, si.stock_type, si.description, si.order_point, su.item_unit_id, su.price, su.quantity FROM `stock_items` AS si LEFT JOIN stock_units AS su ON su.stock_id = si.stock_id WHERE si.stock_type = 'Supply'");
								}
								
									if(mysql_num_rows($showsupply) == 0){
										print "<br /><p align=center><i>There are no available Stocks of Supplies on the system. Please request for purchases.</i></p><br />";
									}else{
										
											print "
											<div class='table-responsive'>
												<table class = 'table table-striped table-bordered table-hover display'>
												<thead>
													<tr>
														<th>Stock No.</th>
														<th>Description</th>
														<th>Quantity on Hand</th>
														<th>Category</th>
													</tr>
												</thead>
												<tbody>
											";
										
											while($getdata = mysql_fetch_array($showsupply)){
											$getitem = mysql_fetch_array(mysql_query("SELECT * FROM items WHERE item_id = $getdata[item_id]"));
											$getunit = mysql_fetch_array(mysql_query("SELECT * FROM item_unit WHERE item_unit_id = $getdata[item_unit_id]"));
											$getcategory = mysql_fetch_array(mysql_query("SELECT * FROM category WHERE category_id = $getitem[category_id]"));
											
											print "<tr>";
											print "<td style='text-align:center;vertical-align:middle;'><center>";
											
											if($getdata['quantity'] <= $getdata['order_point']){
												print "<div style='color:red;'>";
												print $getdata['stock_no'];
												print "</div>";
											}else{
												print $getdata['stock_no'];
											}
											
											print "</center></td>";
											
											print "<td style='text-align:center;vertical-align:middle;'><center>";
											
											if($getdata['quantity'] <= $getdata['order_point']){
												print "<div style='color:red;'>";
												?><a href="stockview.php?id=<?php echo $getdata['su_id']; ?>"><?php print $getitem['item_name'].", ".$getdata['description']." by ".$getunit['item_unit_name'];?></a><?php
												print "</div>";
											}else{
												?><a href="stockview.php?id=<?php echo $getdata['su_id']; ?>"><?php print $getitem['item_name'].", ".$getdata['description']." by ".$getunit['item_unit_name'];?></a><?php
											}
											
											print "<td style='text-align:center;vertical-align:middle;'><center>";
											
											if($getdata['quantity'] <= $getdata['order_point']){
												print "<div style='color:red;'>";
												print $getdata['quantity']." ".$getunit['item_unit_name']." left";
												print "</div>";
											}else{
												print $getdata['quantity']." ".$getunit['item_unit_name'];
											}
											
											print "</center></td>";
											print "<td style='text-align:center;vertical-align:middle;'><center>";
											
											if($getdata['quantity'] <= $getdata['order_point']){
												print "<div style='color:red;'>";
												print $getcategory['category_name'];
												print "</div>";
											}else{
												print $getcategory['category_name'];
											}
											
											print "</center></td>";
											
											print "</tr>";
											//print "<td><center><form method='POST'><input type='hidden' name='delpersonnel' id='delpersonnel' value='$getdata[personnel_id]' /><button class='btn btn-default'><i class='glyphicon glyphicon-trash'></i></button></form></td>";
										}
									}
									print "</tbody></table></div>";
								
				
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