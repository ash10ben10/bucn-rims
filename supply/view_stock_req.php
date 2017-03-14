<!DOCTYPE html>
<?php
	include "../connect.php";
	
	$readcart_id = $_GET['id'];
	
	$getcart = mysql_fetch_array(mysql_query("SELECT * FROM `cart` WHERE `cart_id` = '$readcart_id'"));
	$getpersonnel = mysql_fetch_array(mysql_query("SELECT CONCAT (personnel_fname,' ',personnel_lname) AS full_name FROM personnel WHERE personnel_id = $getcart[personnel_id]"));
	$cartstat = mysql_fetch_array(mysql_query("SELECT * FROM `cart_status` WHERE `cart_id` = '$readcart_id'"));
	
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
	<?php include "stock_approve.php"; ?>
	<script>
	function change_type_edit( id ){
		document.getElementById('type'+id).disabled = false;
		document.getElementById('edit_'+id).hidden = true;
		document.getElementById('cancel_'+id).hidden = false;
	}
	function change_type_cancel( id ){
		document.getElementById('type'+id).disabled = true;
		document.getElementById('edit_'+id).hidden = false;
		document.getElementById('cancel_'+id).hidden = true;
		window.location.reload();
	}
	</script>
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
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-shopping-cart zmdi-hc-lg"></i>&nbsp;&nbsp;Request Cart No. <?php print $getcart['cartnum']; ?></h1>
						<form method="POST" id="addstockrequest" enctype="multipart/form-data">
						<br />
						<div class="panel panel-default" div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
							<div class="panel-heading">
								<a href="../supply/stock_req.php" class="btn btn-info"><span class="fa fa-arrow-left"></span>&nbsp;&nbsp;Go Back</a>
							</div>
							<div class="panel-body">
							
								<div class="panel panel-default" style="font-size:15px;">
									<div class="panel-body">
										<div class="col-lg-12">
											<div class="row">
												<div class="col-lg-4">
													<label>Request Number:</label>
													<?php print $getcart['cartnum']; ?>
												</div>
												<div class="col-lg-4">
													<label>Request Date:</label>
													<?php print date("M d, Y", strtotime($getcart['cartdate'])); ?>
												</div>
												<div class="col-lg-4">
													<label>Requested by:</label>
													<?php print $getpersonnel['full_name']; ?>
												</div>
											</div>
										</div>
									</div>
								</div>
							
								<?php 
								
								$getcartline = mysql_query("SELECT * FROM `cart_line` WHERE `cart_id` = '$readcart_id'");
								
								if(mysql_num_rows($getcartline) == 0){
									print "<br /><p align=center><i>Something went wrong when retrieving cart items in this request.</i></p><br />";
								}else{
									
									print "
									<div class='table-responsive'>
										<table class = 'table table-striped table-bordered table-hover display'>
										<thead>
											<tr>";
											
												if($cartstat['cart_status_name'] == "Requested"){
													if($position['position_name'] == "Supply Officer"){
														print "
														<th width='100'>Requesting Quantity</th>
														<th width='100'>Quantity</th>
														<th width='100'>Approved Quantity</th>
														<th width='100'>Remaining Quantity</th>
														";
													}else{
														print "
														<th width='100'>Requesting Quantity</th>
														<th width='100'>Approved Quantity</th>
														<th width='100'>Remaining Quantity</th>
														";
													}
												}else if($cartstat['cart_status_name'] == "Issued"){
													if($position['position_name'] == "Supply Officer"){
														print "
														<th width='100'>Requesting Quantity</th>
														<th width='100'>Approved Quantity</th>
														";
													}else{
														print "
														<th width='100'>Requesting Quantity</th>
														<th width='100'>Approved Quantity</th>
														";
													}
												}
													
												
										print "	<th>Unit</th>
												<th>Description</th>";
												
										if($cartstat['cart_status_name'] == "Requested"){
											if($position['position_name'] == "Supply Officer"){
												print "<th>Action</th>";
											}else{
												print "";
											}
										}else if($cartstat['cart_status_name'] == "Issued"){
											if($position['position_name'] == "Supply Officer"){
												print "";
											}else{
												print "";
											}
										}
													
										print "
											</tr>
										</thead>
										<tbody>
									";
								
									while($getdata = mysql_fetch_array($getcartline)){
									$stockdesc = mysql_fetch_array(mysql_query("SELECT si.*, su.* FROM `stock_units` AS su LEFT JOIN stock_items AS si ON si.stock_id = su.stock_id WHERE su.su_id = '$getdata[su_id]'"));
									$items = mysql_fetch_array(mysql_query("SELECT * FROM `items` WHERE `item_id` = '$stockdesc[item_id]'"));
									$itemunit = mysql_fetch_array(mysql_query("SELECT * FROM `item_unit` WHERE `item_unit_id` = '$stockdesc[item_unit_id]'"));
									
									print "<tr>";
									if($cartstat['cart_status_name'] == "Requested"){
										if($position['position_name'] == "Supply Officer"){
											print "<td style='text-align:center;vertical-align:middle;'><center>".$getdata['requesting_quantity']."</center></td>";
											print "<td style='text-align:center;vertical-align:middle;'><center>";
											print "<input class='form-control' name='type".$getdata['cart_line_id']."' id='type".$getdata['cart_line_id']."' value='".$getdata['quantity']."' min='1' max='".$stockdesc['quantity']."' type='number' disabled required>";
											print "</center></td>";
											print "<td style='text-align:center;vertical-align:middle;'><center>".$getdata['approved_quantity']."</center></td>";
											print "<td style='text-align:center;vertical-align:middle;'><center>".$stockdesc['quantity']."</center></td>";
										}else{
											print "<td style='text-align:center;vertical-align:middle;'><center>".$getdata['requesting_quantity']."</center></td>";
											print "<td style='text-align:center;vertical-align:middle;'><center>".$getdata['approved_quantity']."</center></td>";
											print "<td style='text-align:center;vertical-align:middle;'><center>".$stockdesc['quantity']."</center></td>";
										}
									}else if($cartstat['cart_status_name'] == "Issued"){
										if($position['position_name'] == "Supply Officer"){
											print "<td style='text-align:center;vertical-align:middle;'><center>".$getdata['requesting_quantity']."</center></td>";
											print "<td style='text-align:center;vertical-align:middle;'><center>".$getdata['approved_quantity']."</center></td>";
										}else{
											print "<td style='text-align:center;vertical-align:middle;'><center>".$getdata['requesting_quantity']."</center></td>";
											print "<td style='text-align:center;vertical-align:middle;'><center>".$getdata['approved_quantity']."</center></td>";
										}
									}
										
									print "<td style='text-align:center;vertical-align:middle;'><center>".$itemunit['item_unit_name']."</center></td>";
									print "<td style='text-align:center;vertical-align:middle;'><center>".$items['item_name'].", ".$stockdesc['description']."</center></td>";
									
									
									if($cartstat['cart_status_name'] == "Requested"){
										if($position['position_name'] == "Supply Officer"){
											print "<td id='edit_".$getdata['cart_line_id']."' style='text-align:center;vertical-align:middle;'><center>";
											print "<a onClick='change_type_edit(".$getdata['cart_line_id'].")' class='btn btn-warning'><span class='zmdi zmdi-shield-security zmdi-hc-lg'></span>&nbsp;&nbsp;Change</a>
											<td style='text-align:center;vertical-align:middle' id='cancel_".$getdata['cart_line_id']."' hidden>";?>
													<a onClick='if(confirm("Are you sure about this change of the quantity?")) window.location="change_qty.php?id=<?php print $getdata['cart_line_id']; ?>&type="+document.getElementById("type<?php print $getdata['cart_line_id']; ?>").value' class='btn btn-success' title='Save'><span class='glyphicon glyphicon-ok' hidden></span></a>
													<a onClick='change_type_cancel(<?php print $getdata['cart_line_id']; ?>)' class='btn btn-danger' title='Cancel'><span class='glyphicon glyphicon-remove' hidden></span></a><?php print "
												</td>
											";
											print "</center></td>";
										}else{
											print "";
										}
									}else if($cartstat['cart_status_name'] == "Issued"){
										if($position['position_name'] == "Supply Officer"){
											print "";
										}else{
											print "";
										}
									}
									
									print "</tr>";
									}
									print "</tbody></table></div>";
								}
				
								?>
							
							</div>
							<div class="panel-footer" align="right">
							<?php
							
							if($cartstat['cart_status_name'] == "Requested"){
								if($position['position_name'] == "Supply Officer"){
									?>
									<button type="submit" name="stckreqsave" id="stckreqsave" class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;Submit</button>
									<a href="../supply/stock_req.php" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span>&nbsp;Cancel</a>
									<?php
								}else{
									print "";
								}
							}else if($cartstat['cart_status_name'] == "Issued"){
								if($position['position_name'] == "Supply Officer"){
									print "";
								}else{
									print "";
								}
							}
							
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