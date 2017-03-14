<!DOCTYPE html>
<?php
	include "../connect.php";
	$readpr_id = $_GET['id'];
	$selectattr = "pr.pr_id, pr.office_dept, dp.dept_name, pr.prnum, pr.sai_no, pr.purpose, pr.prdate, pr.personnel_id";
	$getfrom = "purchase_request AS pr LEFT JOIN department AS dp ON dp.dept_id = pr.dept_id";
	$getpr = mysql_fetch_array(mysql_query("SELECT ".$selectattr." FROM ".$getfrom." WHERE pr_id ='$readpr_id'")) or die(mysql_error());
	
	$selctPs = "CONCAT(p.personnel_fname,' ',p.personnel_lname) AS full_name, pp.position_name";
	$fromPs= "personnel_work_info AS pwi LEFT JOIN personnel AS p ON p.personnel_id = pwi.personnel_id LEFT JOIN personnel_position AS pp ON pp.position_id = pwi.position_id";
	$getrequestor = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM ".$fromPs." WHERE pwi.personnel_id = $getpr[personnel_id] ")) or die(mysql_error());
	
	$getpos = mysql_fetch_array(mysql_query("SELECT `position_id` FROM personnel_position WHERE position_name = 'Dean' OR position_name = 'OIC Dean' ")) or die(mysql_error());
	$getdean = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM ".$fromPs." WHERE pwi.position_id = $getpos[position_id] LIMIT 1 ")) or die(mysql_error());
	
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
	
	<script src="req.approve.disapprove.js"></script>
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
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-file-text zmdi-hc-lg"></i>&nbsp;&nbsp;PR No. <?php echo $getpr['prnum'] ?> dated <?php print date("M j, Y", strtotime($getpr['prdate'])) ?></h1>
					
						<br />
						<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
							<div class="panel-heading">
								<div class="row">
									<div class="col-lg-8">
									
									</div>
									<div class="col-lg-4" align="right">
										<a href="../requests/status.php" class="btn btn-info"><span class="fa fa-arrow-left"></span>&nbsp;&nbsp;Go Back</a>
										<a target="_blank" href="print_pr.php?id=<?php echo $readpr_id; ?>"><button type="button" class="btn btn-primary"><i class="fa fa-print fa-fw"></i>&nbsp;Print</button></a>
									</div>
								</div>
							</div>
							<div class="panel-body">
							
								<div class="panel panel-default">
									<div class="panel-body">
										
										<div class="table-responsive">
											<table class="table table-striped table-bordered table-hover">
											
												<tbody>
													<tr>
														<td colspan="2">
															<div class="col-lg-12" style="font-size:20px;"><center><strong>PURCHASE REQUEST</strong></center></div>
															<div class="col-lg-12" style="font-size:16px;"><center>Bicol University College of Nursing</center></div>
														</td>
													</tr>
													<tr>
														<td width="50%">
																<div class="col-lg-12">
																	<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																	<strong>Department:</strong>&nbsp;&nbsp;<?php print $getpr['office_dept']; ?>
																	</div>
																	<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																	<strong>Section:</strong>&nbsp;&nbsp;<?php print $getpr['dept_name']; ?>
																	</div>
																</div>
														</td>
														<td width="50%">
																<div class="col-lg-12">
																	<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																	<strong>PR No.</strong>&nbsp;&nbsp;<?php echo $getpr['prnum'] ?> dated <?php print date("M d, Y", strtotime($getpr['prdate'])) ?>
																	</div>
																	<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																	<strong>SAI No.</strong>&nbsp;&nbsp;<?php print $getpr['sai_no']; ?>
																	</div>
																</div>
														</td>
													</tr>
													<tr>
														<td colspan="2">
															<?php 
																$reqitems = mysql_query("SELECT * FROM request_items WHERE pr_id = '$readpr_id' ORDER BY req_item_id ASC")or die(mysql_error());
																
																if(mysql_num_rows($reqitems) == 0){
																	print "<br /><p align=center><i>Purchase Request Items are not available.</i></p><br />";
																}else{
																	
																	print "
																	<div class='table-responsive'>
																		<table class = 'table table-striped table-bordered table-hover'>
																		<thead>
																			<tr>";
																	if($position['position_name'] == "Dean" || $position['position_name'] == "OIC Dean"){
																		//$ask = mysql_fetch_array(mysql_query("SELECT pr_status FROM `purchase_request_status` WHERE `pr_id` = '$readpr_id'"))or die (mysql_error());
																			print "<th>Quantity</th>";
																			print "<th>Requested Qty</th>";
																			print "<th>Approved Qty</th>";
																	}else{
																		print "<th>Requested Qty</th>";
																		print "<th>Approved Qty</th>";
																	}
																	
																	print "		<th>Unit</th>
																				<th>Item Name</th>
																				<th>Item Description</th>
																				<th>Unit Cost</th>
																				<th>Amount</th>";
																				
																if($position['position_name'] == "Dean" || $position['position_name'] == "OIC Dean"){
																	print "<th>Action</th>";
																	print "<th width='20%'>Status</th>";
																}else{
																	print "<th>Status</th>";
																}
																
																print "
																			</tr>
																		</thead>
																		<tbody>
																	";
																	
																	while($getdata = mysql_fetch_array($reqitems)){
																		$getunit = mysql_fetch_array(mysql_query("SELECT * FROM item_unit WHERE item_unit_id = $getdata[item_unit_id]"))or die (mysql_error());
																		$showitems = mysql_fetch_array(mysql_query("SELECT * FROM items WHERE item_id = $getdata[item_id]"))or die (mysql_error());
																		
																		print "<tr>";
																		if($position['position_name'] == "Dean" || $position['position_name'] == "OIC Dean"){
																			
																			//$confirm = mysql_fetch_array(mysql_query("SELECT pr_status FROM `purchase_request_status` WHERE `pr_id` = '$readpr_id'"))or die (mysql_error());
																			
																			print "<td style='text-align:center;vertical-align:middle;line-height:40px;' width ='10%'>";
																			print "<input class='form-control' name='type".$getdata['req_item_id']."' id='type".$getdata['req_item_id']."' value='".$getdata['quantity']."' min='1' type='number' disabled required>";
																			print "</td>";
																			print "<td style='text-align:center;vertical-align:middle;line-height:40px;' width ='10%'>";
																			print $getdata['qty_orig'];
																			print "</td>";
																			print "<td style='text-align:center;vertical-align:middle;line-height:40px;' width ='10%'>";
																			print $getdata['qty_approved'];
																			print "</td>";
																		}else{
																			print "<td style='text-align:center;vertical-align:middle;line-height:40px;' width ='10%'>";
																			print $getdata['qty_orig'];
																			print "</td>";
																			print "<td style='text-align:center;vertical-align:middle;line-height:40px;' width ='10%'>";
																			print $getdata['qty_approved'];
																			print "</td>";
																		}
																		
																		print "<td width = '15%' style='text-align:center;vertical-align:middle;line-height:40px;'>".$getunit['item_unit_name']."</td>";
																		print "<td style='text-align:center;vertical-align:middle;line-height:40px;'>".$showitems['item_name']."</td>";
																		print "<td style='text-align:center;vertical-align:middle;line-height:40px;'>".$getdata['description']."</td>";
																		print "<td width = '15%' style='text-align:center;vertical-align:middle;line-height:40px;'>Php ".number_format($getdata['est_unit_cost'], 2,'.',',')."</td>";
																		print "<td width = '15%' style='text-align:center;vertical-align:middle;line-height:40px;'>Php ".number_format($getdata['est_total_cost'], 2,'.',',')."</td>";
																		
																		if($position['position_name'] == "Dean" || $position['position_name'] == "OIC Dean"){
																			if($getdata['pr_status'] == "pending"){
																				print "<td id='edit_".$getdata['req_item_id']."' style='text-align:center;vertical-align:middle;'><center><a onClick='change_type_edit(".$getdata['req_item_id'].")' class='btn btn-warning'><span class='zmdi zmdi-shield-security zmdi-hc-lg'></span>&nbsp;&nbsp;Change</a></center></td>
																				<td style='text-align:center;vertical-align:middle' id='cancel_".$getdata['req_item_id']."' hidden>";?>
																						<a onClick='if(confirm("Are you sure about this change of the quantity?")) window.location="change_qty.php?id=<?php print $getdata['req_item_id']; ?>&type="+document.getElementById("type<?php print $getdata['req_item_id']; ?>").value' class='btn btn-success' title='Save'><span class='glyphicon glyphicon-ok' hidden></span></a>
																						<a onClick='change_type_cancel(<?php print $getdata['req_item_id']; ?>)' class='btn btn-danger' title='Cancel'><span class='glyphicon glyphicon-remove' hidden></span></a><?php print "
																					</td>
																				";
																			}else if($getdata['pr_status'] == "approved"){
																				print "<td style='text-align:center;vertical-align:middle;'><center>";
																				print "<a class='btn btn-warning' title='Quantity cannot be changed because this request has been approved.' disabled><span class='zmdi zmdi-shield-security zmdi-hc-lg'></span>&nbsp;&nbsp;Change</a>";
																				print "</center></td>";
																			}else if($getdata['pr_status'] == "disapproved"){
																				print "<td style='text-align:center;vertical-align:middle;'><center>";
																				print "<a class='btn btn-warning' title='Quantity cannot be changed because this request has been approved.' disabled><span class='zmdi zmdi-shield-security zmdi-hc-lg'></span>&nbsp;&nbsp;Change</a>";
																				print "</center></td>";
																			}
																			
																		}else{
																		}
																		
																		print "<td style='text-align:center;vertical-align:middle;'>";
																		
																		if($position['position_name'] == "Dean" || $position['position_name'] == "OIC Dean"){
																			if($getdata['pr_status'] == "pending"){
																			?>
																			<a onClick='approve_req("<?php print $getdata['req_item_id'];?>")' class='btn btn-success' title='Approve Request' style='margin: 0 0 3px 0;'><span class='glyphicon glyphicon-ok'></span></a>&nbsp;
																			<a data-id="<?php echo $getdata['req_item_id']."|{$showitems['item_name']}|{$readpr_id}"?>" data-toggle="modal" data-target="#unlikerequest" class='btn btn-danger' title='Disapprove Request' style='margin: 0 0 3px 0;'><span class='glyphicon glyphicon-remove'></span></a>
																		
																			<div class="modal fade" id="unlikerequest" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
																				<div class="modal-dialog">
																					<div class="modal-content">
																						<div class="modal-header">
																							<h4 class="modal-title" id="myModalLabel"><i class="fa fa-thumbs-o-down fa-2x"></i>&nbsp;&nbsp;Disapprove Request: <label id="ReqItemName"></label></h4>
																							<input class="form-control" id="ReqItemNo" type="hidden" />
																							<input class="form-control" id="ReqNo" />
																						</div>
																						<div class="modal-body">
																							<p>Please state the reason of disapproval:</p>
																								<div class="row">
																									<div class="col-lg-12">
																										<textarea class="textarea" id="remarks" style="height:100px;resize:none;width:100%;"></textarea>
																									</div>
																								</div>
																						</div>
																						<div class="modal-footer">
																							<button type="button" id="DisapproveBtn" class="btn btn-danger"><span class="glyphicon glyphicon-ok"></span>&nbsp;Disapprove</button>
																							<button type="button" class="btn btn-warning" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>&nbsp;Cancel</button>
																						</div>
																						<div id="DisapproveResult" style="margin: 10px 0 10px 0;"></div>
																					</div>
																				</div>
																			</div>
																			<?php
																			}else if($getdata['pr_status'] == "approved"){
																				print "Approved";
																			}else if($getdata['pr_status'] == "disapproved"){
																				print "<div class='row'>Disapproved";
												
																				?>
																				
																				<script>
																				
																				$(document).ready(function(){
																					$("#viewReason").on("show.bs.modal", function(event){            
																						var button = $(event.relatedTarget);
																						data = button.data('id');
																						request_data = data.split('|');
																						
																						$("#reqItemId").val(request_data[0]);
																						$("#reqItem").text(request_data[1]);
																						$("#reMarks").text(request_data[2]);
																						//alert(data);
																					});
																				});
																				
																				</script>
																				
																				<a href="#" data-id="<?php echo $getdata['req_item_id']."|{$showitems['item_name']}|{$getdata['remarks']}"?>" data-toggle="modal" data-target="#viewReason" class="btn btn-info" title="View Remarks" style="margin:3px 3px 3px 3px;"><span class="zmdi zmdi-comment-text-alt"></span>&nbsp;&nbsp;Why?</a></div></div></td>
																				
																					<div class="modal fade" id="viewReason" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
																						<div class="modal-dialog">
																							<div class="modal-content">
																								<div class="modal-header">
																									<h4 class="modal-title" id="myModalLabel"><i class="fa fa-thumbs-o-down fa-2x"></i>&nbsp;&nbsp;Remarks for <label id="reqItem"></label>:</h4>
																									<input class="form-control" id="reqItemId" type="hidden" />
																								</div>
																								<div class="modal-body">
																									<p id="reMarks"></p>
																								</div>
																								<div class="modal-footer">
																									<button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>&nbsp;Close</button>
																								</div>
																							</div>
																						</div>
																					</div>
																				
																				<?php
																				
																				print "</div>";
																			}
																		}else{
																			if($getdata['pr_status'] == "pending"){
																				print "Pending";
																			}else if($getdata['pr_status'] == "approved"){
																				print "Approved";
																			}else if($getdata['pr_status'] == "disapproved"){
																				print "<div class='row'>Disapproved";
												
																				?>
																				
																				<script>
																				
																				$(document).ready(function(){
																					$("#viewReason").on("show.bs.modal", function(event){            
																						var button = $(event.relatedTarget);
																						data = button.data('id');
																						request_data = data.split('|');
																						
																						$("#reqItemId").val(request_data[0]);
																						$("#reqItem").text(request_data[1]);
																						$("#reMarks").text(request_data[2]);
																						//alert(data);
																					});
																				});
																				
																				</script>
																				
																				<a href="#" data-id="<?php echo $getdata['req_item_id']."|{$showitems['item_name']}|{$getdata['remarks']}"?>" data-toggle="modal" data-target="#viewReason" class="btn btn-info" title="View Remarks" style="margin:3px 3px 3px 3px;"><span class="zmdi zmdi-comment-text-alt"></span>&nbsp;&nbsp;Why?</a></div></div></td>
																				
																					<div class="modal fade" id="viewReason" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
																						<div class="modal-dialog">
																							<div class="modal-content">
																								<div class="modal-header">
																									<h4 class="modal-title" id="myModalLabel"><i class="fa fa-thumbs-o-down fa-2x"></i>&nbsp;&nbsp;Remarks for <label id="reqItem"></label>:</h4>
																									<input class="form-control" id="reqItemId" type="hidden" />
																								</div>
																								<div class="modal-body">
																									<p id="reMarks"></p>
																								</div>
																								<div class="modal-footer">
																									<button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>&nbsp;Close</button>
																								</div>
																							</div>
																						</div>
																					</div>
																				
																				<?php
																				
																				print "</div>";
																			}
																		}
																		
																		
																		
																		print "</td>";
																	}
																	print "</tr></tbody></table></div>";
																}
															?>
														</td>
													</tr>
													<tr>
														<td colspan="2">
															<div class="col-lg-12">
																<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																<label>Purpose:</label>
																<p>
																	<?php print $getpr['purpose'] ?>
																</p>
																</div>
															</div>
														</td>
													</tr>
													<tr>
														<td>
															<div class="col-lg-12">
																<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																Requested By:
																<br /><br />
																<p style="text-transform:uppercase"><strong><center>
																	<u><?php print $getrequestor['full_name']; ?></u>
																</strong></center></p>
																<p><center>
																	<?php print $getrequestor['position_name']; ?>
																</center></p>
																</div>
															</div>
														</td>
														<td>
															<div class="col-lg-12">
																<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																Approved By:
																<br /><br />
																<p style="text-transform:uppercase"><strong><center>
																	<u><?php print $getdean['full_name']; ?></u>
																</strong></center></p>
																<p><center>
																	<?php print $getdean['position_name']; ?>
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