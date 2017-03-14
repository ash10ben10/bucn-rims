<!DOCTYPE html>
<?php
	include "../connect.php";
	
	$readpo_id = $_GET['id'];
	
	$getpo = mysql_fetch_array(mysql_query("SELECT * FROM purchase_order WHERE po_id ='$readpo_id'"));
	$getsupplier = mysql_fetch_array(mysql_query("SELECT * FROM supplier WHERE supplier_id ='$getpo[supplier_id]'"));
	$getpr = mysql_fetch_array(mysql_query("SELECT dept_id FROM purchase_request WHERE pr_id ='$getpo[pr_id]'"));
	$getdept = mysql_fetch_array(mysql_query("SELECT * FROM department WHERE dept_id ='$getpr[dept_id]'"));
	//$getitems = mysql_fetch_array(mysql_query("SELECT * FROM `request_items` WHERE po_id = '$readpo_id'"));
	
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
	$date = date("Y-m-d");
	
?>
<html lang="en">

<head>
	<!-- Calling Default CSS files -->
	<?php include "../engine/csscalls.php"; ?>
	<!-- Calling Default Javascript files -->
	<?php include  "../engine/jscalls.php"; ?>
	<script src="req.approve.disapprove.js"></script>
	<script>
	$(document).ready(function(){
		
		$("#candel").on("show.bs.modal", function(event){
			var button = $(event.relatedTarget);
			data = button.data('id');
			request_data = data.split('|');
			
			$("#reqitemId").val(request_data[0]);
			$("#itemName").text(request_data[1]);
			//alert(data);
		});
		$("#pardel").on("show.bs.modal", function(event){
			var button = $(event.relatedTarget);
			data = button.data('id');
			request_data = data.split('|');
			
			$("#riId").val(request_data[0]);
			$("#iName").text(request_data[1]);
			$("#qtytem").val(request_data[2]);
			$("#delqty").val(request_data[3]);
			//alert(data);
		});
		
		$("#itemCancelBtn").click(function(event){
			var isOk = true;
			var msg = "Please fill in the remarks of this item.";
			
			var $remarks = $("#remarks").val();
			if($remarks == 0 || $remarks == "" || $remarks == null){
				isOk = false;
				msg;
			}
			
			if(!isOk){
					alert(msg);
					return false;
			}else{
				var reqitemid = $("#reqitemId").val();
				var remarks = $("#remarks").val();

				var hr = new XMLHttpRequest();
					var url = "setUNDel.php";
					var vars = "reqitemid="+reqitemid+"&remarks="+remarks;

					hr.open("POST", url, true);
					hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

					hr.onreadystatechange = function() {
					if(hr.readyState == 4 && hr.status == 200) {
					  var return_data = hr.responseText;
						document.getElementById("itemCancelResult").innerHTML = return_data;
						window.location = "add_ins.php?id=<?php print $readpo_id; ?>";
						
					  }
					}
					hr.send(vars);
					document.getElementById("itemCancelResult").innerHTML = "Cancelling request...";
			}
			
		});
		
		$("#inctype").on("change", function(){
				var type = $(this).val();
				if(type == "1"){
					$("#rmarks").val("Partial Order");
					$("#rmarks").attr('disabled','disabled');
				}else if(type == "2"){
					$("#rmarks").val("Replace Order");
					$("#rmarks").attr('disabled','disabled');
				}else if(type == "3"){
					$("#rmarks").removeAttr('disabled');
					$("#rmarks").val("");
				}else{
					$("#rmarks").attr('disabled','disabled');
				}
		});
		
		$("#itemIncBtn").click(function(event){
			var isOk = true;
			var msg;
			var $rmarks = $("#rmarks").val();
			var $inctype = $("#inctype").val();
			var $delqty = $("#delqty").val();
			var $overallqty = $("#qtytem").val();
			
				if($rmarks == 0 || $rmarks == "" || $rmarks == null){
					isOk = false;
					msg = "Please complete the forms.";
				}
				if($inctype == 0 || $inctype == "" || $inctype == null){
					isOk = false;
					msg = "\nPlease complete the forms.";
				}
				if($delqty == 0 || $delqty == "" || $delqty == null ){
					isOk = false;
					msg = "\nPlease complete the quantity.";
				}
				if($delqty > $overallqty || $delqty == $overallqty){
					isOk = false;
					msg = "\nThe quantity you have entered is equal or higher than the ordered quantity.";
				}
				
			if(!isOk){
					alert(msg);
					return false;
			}else{
				var reqitemid = $("#riId").val();
				var rmarks = $("#rmarks").val();
				var delqty = $("#delqty").val();

				var hr = new XMLHttpRequest();
					var url = "setUNDone.php";
					var vars = "reqitemid="+reqitemid+"&rmarks="+rmarks+"&delqty="+delqty;

					hr.open("POST", url, true);
					hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

					hr.onreadystatechange = function() {
					if(hr.readyState == 4 && hr.status == 200) {
					  var return_data = hr.responseText;
						document.getElementById("itemCancelResult").innerHTML = return_data;
						window.location = "add_ins.php?id=<?php print $readpo_id; ?>";
						
					  }
					}
					hr.send(vars);
					document.getElementById("itemCancelResult").innerHTML = "Remarking request...";
			}
			
		});
		
	});
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
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-search zmdi-hc-lg"></i>&nbsp;&nbsp;Inspect Delivery</h1>
					
						<br />
						<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
							<div class="panel-heading">
								<div class="row">
									<div class="col-lg-12">
										<a href="../requests/status.php" class="btn btn-info"><span class="fa fa-arrow-left"></span>&nbsp;&nbsp;Go Back</a>
									</div>
								</div>
							</div>
							<div class="panel-body">
						
								<br />
								<div class="form-group col-lg-12">
									<div class="panel panel-default">
										<div class="panel-body">
											<div class="row">
												<div class="col-lg-6">
													<div class="form-group col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
														<label>Supplier:</label>
														<?php print $getsupplier['supplier_name'] ?>
													</div>
													<div class="form-group col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
														<label>Address:</label>
														<?php print $getsupplier['supplier_address'] ?>
													</div>
												</div>
												<div class="col-lg-6">
													<div class="form-group col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
														<label>Purchase Order Number:</label>
														<?php print $getpo['ponumber'] ?>
													</div>
													<div class="form-group col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
														<label>Requesting Office:</label>
														<?php print $getdept['dept_name']; ?>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
											
								<br />
								<div class="form-group col-lg-12">
									<label>Inspect items from Delivery:</label>
									
									<div class="panel panel-default">
										<div class="panel-body">
											<?php
												$getpoitems = mysql_query("SELECT * FROM request_items WHERE po_id = '$readpo_id'");
											?>
											<div class="table-responsive">
												<table class = "table table-striped table-bordered table-hover display">
													<thead>
														<tr>
															<th>Unit</th>
															<th>Item Name</th>
															<th>Item Description</th>
															<th>Ordered<br/>Qty</th>
															<th>Delivered<br/>Qty</th>
															<th>Status</th>
															<th width="20%">Action</th>
															<th>Remarks</th>
														</tr>
													</thead>
													<tbody>
														<?php
														while($getdata = mysql_fetch_array($getpoitems)){
															$getunit = mysql_fetch_array(mysql_query("SELECT * FROM item_unit WHERE item_unit_id = $getdata[item_unit_id]"))or die (mysql_error());
															$showitems = mysql_fetch_array(mysql_query("SELECT * FROM items WHERE item_id = $getdata[item_id]"))or die (mysql_error());
														?>
															<tr>
																<td style="text-align:center;vertical-align:middle;"><?php print $getunit['item_unit_name'];?></td>
																<td style="text-align:center;vertical-align:middle;"><?php print $showitems['item_name'];?></td>
																<td style="text-align:center;vertical-align:middle;"><?php print $getdata['description'];?></td>
																<td style="text-align:center;vertical-align:middle;"><?php print $getdata['quantity'];?></td>
																<td style="text-align:center;vertical-align:middle;"><?php print $getdata['del_quantity'];?></td>
																<td style="text-align:center;vertical-align:middle;">
																<?php
																	if($getdata['instat'] == ""){
																		print "Item/s not yet set.";
																	}else if($getdata['instat'] == "Incomplete"){
																		print "Delivery in Progress.";
																	}else if($getdata['instat'] == "Complete"){
																		print "Delivery complete.";
																	}else if($getdata['instat'] == "Cancelled"){
																		print "Delivery cancelled.";
																	}
																?>
																</td>
																
																<td style="text-align:center;vertical-align:middle;">
																
																<?php 
																
																if($getdata['instat'] == "Complete"){
																	$doneDisable = "disabled";
																	$incDisable = "disabled";
																	$badDisable = "disabled";
																	$doneTitle = "This item has successfully delivered.";
																	$incTitle = "This item has successfully delivered.";
																	$badTitle = "This item has successfully delivered.";
																	$doneOnClick = "";
																	$incdataID = "";
																	$badataID = "";
																	$incModal = "";
																	$badModal = "";
																}else if($getdata['instat'] == "Incomplete"){
																	$doneDisable = "";
																	$incDisable = "";
																	$badDisable = "";
																	$doneTitle = "Set item as complete.";
																	$incTitle = "Update item status.";
																	$badTitle = "Cancel the remaining item/s.";
																	$doneOnClick = "onClick='set_complete(".$getdata['req_item_id'].")'";
																	$incdataID = $getdata['req_item_id']."|{$showitems['item_name']}|{$getdata['quantity']}|{$getdata['del_quantity']}";
																	$badataID = $getdata['req_item_id']."|{$showitems['item_name']}";
																	$incModal = "data-toggle='modal' data-target='#pardel'";
																	$badModal = "data-toggle='modal' data-target='#candel'";
																}else if($getdata['instat'] == "Cancelled"){
																	$doneDisable = "disabled";
																	$incDisable = "disabled";
																	$badDisable = "disabled";
																	$doneTitle = "This item is already cancelled.";
																	$incTitle = "This item is already cancelled.";
																	$badTitle = "This item is already cancelled.";
																	$doneOnClick = "";
																	$incdataID = "";
																	$badataID = "";
																	$incModal = "";
																	$badModal = "";
																}else{
																	$doneDisable = "";
																	$incDisable = "";
																	$badDisable = "";
																	$doneTitle = "Set item as complete.";
																	$incTitle = "Remark item.";
																	$badTitle = "Cancel item.";
																	$doneOnClick = "onClick='set_complete(".$getdata['req_item_id'].")'";
																	$incdataID = $getdata['req_item_id']."|{$showitems['item_name']}|{$getdata['quantity']}|{$getdata['del_quantity']}";
																	$badataID = $getdata['req_item_id']."|{$showitems['item_name']}";
																	$incModal = "data-toggle='modal' data-target='#pardel'";
																	$badModal = "data-toggle='modal' data-target='#candel'";
																}?>
																
																<a <?php print $doneOnClick; ?> class="btn btn-success" style="margin: 3px 3px 3px 3px;" title="<?php print $doneTitle; ?>" <?php print $doneDisable; ?> ><span class="fa fa-check"></span></a>
																<a name="pardel" data-id="<?php echo $incdataID; ?>" <?php print $incModal; ?> class="btn btn-warning" style="margin: 3px 3px 3px 3px;" title="<?php print $incTitle; ?>" <?php print $incDisable; ?> ><span class="fa fa-circle-o"></span></a>
																<a name="candel" data-id="<?php echo $badataID; ?>" <?php print $badModal; ?> class="btn btn-danger" style="margin: 3px 3px 3px 3px;" title="<?php print $badTitle; ?>" <?php print $badDisable; ?> ><span class="fa fa-remove"></span></a>
																
																<div class="modal fade" id="candel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
																	<div class="modal-dialog">
																		<div class="modal-content">
																			<form>
																				<div class="modal-header" align="left">
																					<h4 class="modal-title" id="myModalLabel"><i class="zmdi zmdi-collection-item zmdi-hc-2x"></i>&nbsp;&nbsp;Cancel item: <label id="itemName"></label></h4>
																					<input class="form-control" id="reqitemId" type="hidden" />
																				</div>
																				<div class="modal-body">
																					
																					<div class="row" align="center">
																						<div class="col-lg-12">
																							<div class="row">
																								<div class="col-lg-2"></div>
																								<div class="col-lg-2" align="left" style="margin:7px 0 0 0;">
																									<label>Remarks:</label>
																								</div>
																								<div class="col-lg-8" align="left">
																									<input class="form-control" id="remarks" required />
																								</div>
																							</div>
																						</div>
																					</div>
																					
																				</div>
																				<div class="modal-footer">
																					<button type="button" id="itemCancelBtn" class="btn btn-danger"><span class="fa fa-arrow-right"></span>&nbsp;Cancel item</button>
																					<button type="button" class="btn btn-warning" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>&nbsp;Close</button>
																				</div>
																				<div id="itemCancelResult" style="margin: 10px 0 10px 0;"></div>
																			</form>
																		</div>
																	</div>
																</div>
																
																<div class="modal fade" id="pardel" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
																	<div class="modal-dialog">
																		<div class="modal-content">
																			<form>
																				<div class="modal-header" align="left">
																					<h4 class="modal-title" id="myModalLabel"><i class="zmdi zmdi-collection-item zmdi-hc-2x"></i>&nbsp;&nbsp;Remarks for <label id="iName"></label></h4>
																					<input class="form-control" id="riId" type="hidden" />
																				</div>
																				<div class="modal-body">
																					
																					<div class="row" align="center">
																						<div class="col-lg-12" style="margin: 10px 0 10px 0;">
																							<div class="row">
																								<div class="col-lg-2"></div>
																								<div class="col-lg-2" align="left" style="margin:7px 0 0 0;">
																									<label>Type:</label>
																								</div>
																								<div class="col-lg-4" align="left">
																									<select class="selectpicker form-control" id="inctype">
																										<option selected disabled>-Select-</option>
																										<option value="1">Partial Order</option>
																										<option value="2">Replace Order</option>
																										<option value="3">Other</option>
																									</select>
																								</div>
																							</div>
																						</div>
																						<div class="col-lg-12" style="margin: 10px 0 10px 0;">
																							<div class="row">
																								<div class="col-lg-2"></div>
																								<div class="col-lg-2" align="left" style="margin:7px 0 0 0;">
																									<label>Remarks:</label>
																								</div>
																								<div class="col-lg-8" align="left">
																									<input class="form-control" id="rmarks" required disabled/>
																								</div>
																							</div>
																						</div>
																						<div class="col-lg-12" style="margin: 10px 0 10px 0;">
																							<div class="row">
																								<div class="col-lg-2"></div>
																								<div class="col-lg-2" align="left" style="margin:-5px 0 0 0;">
																									<label>Delivered Quantity:</label>
																								</div>
																								<div class="col-lg-8" align="left">
																									<input class="form-control" id="delqty" required/>
																									<input id="qtytem" type="hidden"/>
																								</div>
																							</div>
																						</div>
																					</div>
																					
																				</div>
																				<div class="modal-footer">
																					<button type="button" id="itemIncBtn" class="btn btn-success"><span class="fa fa-arrow-right"></span>&nbsp;Done</button>
																					<button type="button" class="btn btn-warning" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>&nbsp;Close</button>
																				</div>
																				<div id="itemIncResult" style="margin: 10px 0 10px 0;"></div>
																			</form>
																		</div>
																	</div>
																</div>
																
																</td>
																<td style="text-align:center;vertical-align:middle;">
																<?php 
																
																print $getdata['ins_remarks']; 
																
																/* if($getdata['instat'] == "Complete" || $getdata['instat'] == "Cancelled"){
																	print $getdata['ins_remarks'];
																}else if($getdata['instat'] == "Incomplete"){
																	if($getdata['insrmk_type'] == "1"){
																		print "<i>Under Partial</i>";
																		print "<br/>";
																	}else if($getdata['insrmk_type'] == "2"){
																		print "<i>Under Replacement</i>";
																		print "<br/>";
																	}else if($getdata['insrmk_type'] == "3"){
																		print "";
																	}else{
																		print "";
																	}
																	
																} */	
																?>
																</td>
																
															</tr>
												<?php	}?>
													</tbody>
												</table>
											</div>
										</div>
									</div>

								</div>
								
								<?php
								
								$getdataone = "SELECT `instat` FROM request_items WHERE po_id ='$readpo_id'";
								$getdatatwo = "SELECT `instat` FROM request_items WHERE po_id ='$readpo_id' AND instat IN ('Complete','Cancelled')";
								
								$datarrayone = mysql_num_rows(mysql_query($getdataone));
								$datarraytwo = mysql_num_rows(mysql_query($getdatatwo));
								
								$subtract = ($datarrayone - $datarraytwo);
								
								if($subtract == 0){
									$isDisabled = "";
									$btnTitle = "Submit the delivery status of items.";
								}else{
									$isDisabled = "disabled";
									$btnTitle = "You cannot submit until items are set to their status.";
								}
								
								?>
								
							</div>
							
							<form method="post">
							<div class="panel-footer" align="right">
								<?php
									print "<button type='submit' name='insave' id='insave' class='btn btn-success' title='".$btnTitle."' ".$isDisabled."><span class='glyphicon glyphicon-ok'></span>&nbsp;Submit</button>";
								?>
							</div>
							</form>
						</div>
				</div>
			</div>
		</div> <!--/.container-fluid-->
	</div> <!--/.content-wrapper-->
		
		<?php 
		
		if(isset($_POST['insave'])){
			mysql_query("SET AUTOCOMMIT=0");
			mysql_query("START TRANSACTION");
			
			mysql_query("LOCK TABLE inspection WRITE;");
			
			try{
				mysql_query("INSERT INTO inspection (`inspection_date`, `status`, `po_id`, `personnel_id`) VALUES
				(
				'$date',
				'Inspected',
				'$readpo_id',
				'".$_SESSION['logged_personnel_id']."'
				)");
				mysql_query("COMMIT");
			}catch(Exception $e){
				mysql_query("ROLLBACK");
				print "<script>alert('Something went wrong when submitting your inspection report to the System. Please check your connection.')</script>";
			}
			
			mysql_query("UNLOCK TABLE;");
			print "<script>alert('Your inspection has been submitted.'); window.location='status.php';</script>";
			
			mysql_query("UPDATE `requisition_status` SET `status`='Delivery Complete' WHERE `po_id` = '$readpo_id'");
		}
		
		?>
</body>
<!-- /.Body -->

</div> <!-- /.wrapper-->

</html>