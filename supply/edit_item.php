<!DOCTYPE html>
<?php
	include "../connect.php";

	$readitem_id = $_GET['id'];
	
	$getitems = mysql_fetch_array(mysql_query("SELECT * FROM `items` WHERE `item_id` = '$readitem_id'"));
	
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
	<?php include "setting_supply_engine.php"; ?>
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
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-border-color zmdi-hc-lg"></i>&nbsp;&nbsp;Change Item Info for <?php print $getitems['item_name']; ?></h1>
						<form role="form" id="editsupplyname" method="post" name="contentForm" enctype="multipart/form-data" onsubmit="submitform(event);">
						<br />
						<div class="panel panel-default" div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
							<div class="panel-body">
								<div class="panel panel-default">
									<div class="panel-body">
										<div class="row">
											<div class="col-lg-12">
												<div class="col-lg-4">
													<label>Supply Name:</label>
													<input class="form-control" name="inputsupply" id="inputsupply" placeholder="Supply Name:" value="<?php print $getitems['item_name']; ?>" required />
												</div>
												<div class="col-lg-3" style="margin:0 0 16px 0;">
													<label>Item Unit:</label>
													<select class="selectpicker form-control" multiple name="inputsupplyunit[]" id="inputsupplyunit" data-hide-disabled="true" data-live-search="true" required>
														<?php
															$query = mysql_query("SELECT * FROM `item_unit`");
															echo "<option selected disabled>-Select Unit-</option>";
															while($row = mysql_fetch_array($query)){
																$moreunits = mysql_fetch_array(mysql_query("SELECT * FROM `more_units` WHERE `item_id` = '$readitem_id' AND `item_unit_id` = '$row[item_unit_id]'"));
																if($row['item_unit_id'] == $moreunits['item_unit_id']){
																	echo "<option value='".$row['item_unit_id']."' selected>".ucfirst($row['item_unit_name'])."</option>";
																}else{
																	echo "<option value='".$row['item_unit_id']."'>".ucfirst($row['item_unit_name'])."</option>";
																}
															}
														?>
													</select>
												</div>
												<!--<div class="col-lg-2">
													<label>Unit Cost:</label>
													<input class="form-control" name="itemprice" id="itemprice" placeholder="Item Price" value="<?php print $getitems['price']; ?>" pattern="([0-9])+" min="1" type="number" step="any" required />
												</div>-->
												<div class="col-lg-3">
													<label>Item Category:</label>
													<select class="selectpicker form-control" name="supplycateg" id="supplycateg" data-hide-disabled="true" data-live-search="true" required >
														<?php 
															$query = mysql_query("SELECT * FROM `category` WHERE category_type IN ('Category for Supplies', 'Category for All Items')");
															echo "<option selected disabled>-Select Category-</option>";
															while($row = mysql_fetch_array($query)){
																if($row['category_id'] == $getitems['category_id']){
																	echo "<option value='".$row['category_id']."' selected>".ucfirst($row['category_name'])."</option>";
																}else{
																	echo "<option value='".$row['category_id']."'>".ucfirst($row['category_name'])."</option>";
																}
															}
														?>
													</select>
												</div>
												<div class="col-lg-12">
													<p style="color:red;"><i>
													Note: Submitting the changes will remove the item descriptions and their prices that you saved from Supply Descriptions.
													</i></p>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="panel-footer" align="right">
								<button type="submit" name="editsupitem" id="editsupitem" class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;Submit</button>
								<a href="../supply/supply_label.php" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span>&nbsp;Cancel</a>
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