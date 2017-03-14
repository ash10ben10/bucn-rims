<!DOCTYPE html>
<?php
	include "../connect.php";
	
	$readpr_id = $_GET['id'];
	$getpr = mysql_fetch_array(mysql_query("SELECT * FROM purchase_request WHERE pr_id ='$readpr_id'")) or die(mysql_error());
	$getpr_items = mysql_fetch_array(mysql_query("SELECT * FROM request_items WHERE pr_id = '$readpr_id' AND pr_status = 'approved'")) or die(mysql_error());
	
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
	
	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$date = date("Y-m-d");
	
?>
<html lang="en">

<head>
	<!-- Calling Default CSS files -->
	<?php include "../engine/csscalls.php"; ?>
	<!-- Calling Default Javascript files -->
	<?php include  "../engine/jscalls.php"; ?>
	
	<?php include  "addpo_engine.php"; ?>
	
	<script>

		$(document).ready(function() {
			var $poItem = $(".poitem");
			var $gTotalNums = $(".gTotalNums");
			var showGTotal = function(){
				var selectedPOItems = $(".poitem:checked").map(function(){
					return $(this).attr("alt");
				});
				var gTotal = 0;
				for(i=0, selectedPOItemsLength = selectedPOItems.length; i < selectedPOItemsLength; i++){
					gTotal += parseFloat(selectedPOItems[i]);
				}
				
				$gTotalNums.find("input").val(gTotal.toFixed(2));
				$gTotalNums.find("span").html(gTotal.toFixed(2));
				$(".gTotalWord").html(n2w(gTotal.toFixed(2)));
			};
			$("#poitemchkbx").off("change").on("change", function(){
				var thischk = $(this).prop("checked");
				if(thischk){
					$poItem.prop("checked", true);
				}else{
					$poItem.prop("checked", false);
				}
				showGTotal();
			});
			$poItem.off("change").on("change", showGTotal);
			
			$("#supplier").on("change", function(){
			$("#supplieraddress").val($(this).find("option:selected").attr("alt"));
			$("#potin").val($(this).find("option:selected").attr("tin"));
			});
		
		});
		
		function submitform(e){
			var isOk = true;
			var msg = "";
				var $supplier = $("#supplier").val();
				if($supplier == 0 || $supplier == "" || $supplier == null){
					isOk = false;
					msg = "Please select a supplier.";
				}
			
				var checkedPoItem = $(".poitem:checked").map(function(){
					return $(this).val();
				});
				if(checkedPoItem.length == 0){
					isOk = false;
					msg += "\nPlease select item(s).";
				}
				
				if(!isOk){
					e.preventDefault();
						alert(msg);
						return false;
				}
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
					
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-file zmdi-hc-lg"></i>&nbsp;&nbsp;New Purchase Order</h1>
						
						<br />
						<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
							<div class="panel-body">
								<form method="POST" id="addorder" enctype="multipart/form-data" onsubmit="submitform(event);">
									<div class="row">
										<div class="col-md-12">
												<div class="panel panel-default">
													<div class="panel-body">
														<div class="col-lg-12">
															<div class="form-group col-md-3">
																<label>Supplier:</label>
																<select name="supplier" id="supplier" class="selectpicker form-control" data-hide-disabled="true" data-live-search="true" placeholder="Supplier Name" required >
																	<?php 
																		$query = mysql_query("SELECT * FROM `supplier`");
																		echo "<option selected disabled>-Select Supplier-</option>";
																		while($row = mysql_fetch_array($query)){
																			echo "<option value='".$row['supplier_id']."' alt='".$row['supplier_address']."' tin='".$row['supplier_tin_no']."'>".ucfirst($row['supplier_name'])."</option>";
																		}
																?>
																</select>
															</div>
															<div class="form-group col-md-3">
																<label>Address:</label>
																<input class="form-control" name="supplieraddress" id="supplieraddress" value="" disabled required />
																
															</div>
															<div class="form-group col-md-2">
																<label>TIN:</label>
																<input class="form-control" name="potin" id="potin" value="" disabled required />
															</div>
															<div class="form-group col-md-2">
																<label>Delivery Term:</label>
																<input name="delivery_term" id="delivery_term" class="form-control" pattern="([0-9])+" maxlength="3" placeholder="cd after recv. PO" required/>
															</div>
															<div class="form-group col-md-2">
																<label>Payment of Term:</label>
																<input name="payment_term" id="payment_term" class="form-control" pattern="([A-Za-zñÑ]| |-)+" />
															</div>
															<!--<div class="form-group col-md-3">
																<label>Mode of Payment:</label>
																<input name="modepayment" id="modepayment" class="form-control" value="Check" />
															</div>-->
														</div>
													</div>
												</div>
												
												<!--<div class="panel panel-default">
													<div class="panel-body" align="center">
														<div class="col-md-12">
															<div class="form-group col-md-12" style="margin: 5px 0 -5px 0;">
																<p><strong>Gentlemen:</strong> Please furnish the following articles subject to the terms and conditions contained herein.</p>
															</div>
														</div>
													</div>
												</div>-->
												
												<!--<div class="panel panel-default">
													<div class="panel-body">
														<div class="col-lg-12">
															<!--<div class="col-lg-3">
																<label>Place of Delivery:</label>
																<input name="delivery_place" id="delivery_place" class="form-control" value="BUCN" required />
															</div>-->
															<!--<div class="form-group col-md-3">
																<label>Date of Delivery:</label>
																<input type="date" name="date_delivery" id="date_delivery" class="form-control" />
															</div>-->
															<!--
															<div class="col-md-2"></div>
															<div class="col-lg-4" align="center">
															
																
																
																
															</div>
															<div class="col-md-2"></div>
															<div class="col-md-3" align="center">
																
															</div>
														</div>
													</div>
												</div>-->
												
												
												<div class="panel panel-default">
													<div class="panel-body">
													
													<div class="row">
														<div class="col-lg-12">
															<div class="col-lg-12" style="margin-top:12px;" align="left">
																<label>Select items from Purchase Request No. <?php print $getpr['prnum']; ?>: </label>
															</div>
															
															
															<!--<div class="col-lg-6" align="center" style="margin: 5px 0 5px 0;">
																<select class="selectpicker form-control" name="pickreq" id="pickreq" data-hide-disabled="true" data-live-search="true" required >
																<?php 
																	/* $query = mysql_query("SELECT * FROM `purchase_request`");
																	
																	print "<option selected disabled>-Select Request-</option>";
																	while($row = mysql_fetch_array($query)){
																		$people = mysql_fetch_array(mysql_query("SELECT * FROM `personnel` WHERE personnel_id = $row[personnel_id] LIMIT 1"));
																		print "<option value='".$row['pr_id']."'>".ucfirst($row['prnum'])." - ".ucfirst($people['personnel_fname'])." ".ucfirst($people['personnel_lname'])."</option>";
																	} */
																?>
																</select>
															</div>
															<div class="col-lg-2" style="margin: 5px 0 5px 0;" align="center">
																	<button class="btn btn-default"><i class="fa fa-plus-circle fa-fw"></i>&nbsp;&nbsp;Add items...</button>
															</div>-->
														</div>
													</div>
													
													<br />
													
														<div class="col-lg-12">
															<div class="form-group col-md-12">
																
																<?php 
																	$prSelect = "ri.req_item_id, i.item_name, iu.item_unit_name, ri.description, ri.quantity, ri.est_unit_cost, ri.est_total_cost";
																	$prFrom = "request_items AS ri LEFT JOIN items AS i ON i.item_id = ri.item_id";
																	$prFrom .= " LEFT JOIN item_unit AS iu ON iu.item_unit_id = ri.item_unit_id";
																	$pr = mysql_query("SELECT ".$prSelect." FROM ".$prFrom." WHERE ri.pr_id = '$readpr_id' AND ri.po_id = 0 AND pr_status = 'approved' ORDER BY ri.req_item_id ASC")or die(mysql_error());
																?>
																<div class="table-responsive">
																<table class = "table table-striped table-bordered table-hover display" >
																	<thead>
																		<tr>
																			<th>Select<br /><input type="checkbox" id="poitemchkbx" /></th>
																			
																			<th>Unit</th>
																			<th>Item</th>
																			<th>Description</th>
																			<th>Quantity</th>
																			<th>Unit Cost</th>
																			<th>Amount</th>
																		</tr>
																	</thead>
																
																<?php
																	while($getdata = mysql_fetch_array($pr)){
																	//$gTotal += $getdata['est_total_cost'];
																?>
																
																	<tbody>
																		<tr>
																			<td align="center"><input type="checkbox" class="poitem" name="getitemid[]" id="getitemid" value="<?php print $getdata['req_item_id']; ?>" alt="<?php echo $getdata['est_total_cost'];?>" /></td>
																			<td><?php print $getdata['item_unit_name']; ?></td>
																			<td><?php print $getdata['item_name']; ?></td>
																			<td><?php print $getdata['description']; ?></td>
																			<td><?php print $getdata['quantity']; ?></td>
																			<td>Php <?php print number_format($getdata['est_unit_cost'], 2,'.',','); ?>/qty</td>
																			<td><center>Php <?php print number_format($getdata['est_total_cost'], 2,'.',','); ?></center></td>
																		</tr>
																	</tbody>
																	
																<?php } 
																	/* $gTotalToWord = number_format($gTotal, 2,'.', '');
																	$gTotalWord = explode(".", $gTotalToWord);
																	
																	$gTotalPeso = $gTotalWord[0];
																	$gTotalCents = (COUNT($gTotalWord) > 1) ? number_format($gTotalWord[1]) : 0;
																	$gTotalWord = convert_number_to_words($gTotalPeso)." Peso". (($gTotalPeso > 1) ? "s" : "");
																	$gTotalWord .= ($gTotalCents > 0 ) ? " & ".convert_number_to_words($gTotalCents)." cent" . ($gTotalCents > 1 ? "s" : ""): "";
																	$gTotalWord .= " Only"; 
																	
																	*/
																?>
																
																	<tbody>
																		<tr>
																			<td colspan='2'><center>Total Amount in Words:</center></td>
																			<td colspan='4' class="gTotalWord" ><span></span></td>
																			<td class="gTotalNums"><center>Php <input class="form-control" type="hidden" name="total_items_nums" id="total_items_nums" value="0.00"><span>0.00</span></center></td>
																		</tr>
																	</tbody>
																
																</table>
																</div>
															</div>
														</div>
													</div>
												</div>
												
												<!--<div class="panel panel-default">
													<div class="panel-body">
													
													</div>
												</div>-->
												
												<!--<div class="panel panel-default">
													<div class="panel-body">
														<div class="col-lg-12">
															<div class="row">
																<div class="form-group col-md-12">
																	<p>
																	In case of failure to make the full delivery within the time specified above, a penalty of one-tenth (1/10) of one percent for every delay shall be imposed.
																	
																	</p>
																</div>
															</div>
															<div class="row">
																<div class="col-lg-8"></div>
																<div class="col-lg-4">
																<p>Very Truly Yours,</p>&nbsp;
																<p>Ruby L. Mediona</p>
																<p>Authorized Official</p>
																</div>
															</div>
														</div>
													</div>
												</div>-->
										</div>
									</div>
								
							</div>
									<div class="panel-footer" align="right">
										<button type="submit" name="posave" id="posave" class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;Submit</button>
										<a href="../requests/purchase_order.php" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span>&nbsp;Cancel</a>
									</div>
								</form>
						</div>
				</div>
			</div>
		
			
		
		</div> <!--/.container-fluid-->
	
	</div> <!--/.content-wrapper-->
	
	
</body>
<!-- /.Body -->

</div> <!-- /.wrapper-->

</html>