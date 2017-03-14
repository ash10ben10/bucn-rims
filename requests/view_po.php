<!DOCTYPE html>
<?php
	include "../connect.php";
	$readpo_id = $_GET['id'];
	$getpo = mysql_fetch_array(mysql_query("SELECT * FROM purchase_order WHERE po_id ='$readpo_id'")) or die(mysql_error());
	$getsupplier = mysql_fetch_array(mysql_query("SELECT * FROM supplier WHERE supplier_id ='$getpo[supplier_id]'")) or die(mysql_error());
	
	$selctPs = "CONCAT(p.personnel_fname,' ',p.personnel_lname) AS full_name, pp.position_name";
	$fromPs= "personnel_work_info AS pwi LEFT JOIN personnel AS p ON p.personnel_id = pwi.personnel_id LEFT JOIN personnel_position AS pp ON pp.position_id = pwi.position_id";
	
	$getpos = mysql_fetch_array(mysql_query("SELECT `position_id` FROM personnel_position WHERE position_name = 'Dean' OR position_name = 'OIC Dean' ")) or die(mysql_error());
	$getdean = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM ".$fromPs." WHERE pwi.position_id = $getpos[position_id] LIMIT 1 ")) or die(mysql_error());
	
	$getpostwo = mysql_fetch_array(mysql_query("SELECT `position_id` FROM personnel_position WHERE position_name = 'Budget Officer' ")) or die(mysql_error());
	$getbudget = mysql_fetch_array(mysql_query("SELECT ".$selctPs." FROM ".$fromPs." WHERE pwi.position_id = $getpostwo[position_id] LIMIT 1 ")) or die(mysql_error());
	
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
	
	<?php include "../engine/converter.php"; ?>
	
	<script>
	
		$(document).ready(function(){
		
			$("#verifyFund").on("show.bs.modal", function(event){
				var button = $(event.relatedTarget);
				data = button.data('id');
				request_data = data.split('|');
				
				$("#getpoId").val(request_data[0]);
				$("#getpoNum").text(request_data[1]);
				$("#getTotalCost").val(request_data[2]);
				//alert(data);
			});
			
			$("#savePOFundBtn").click(function(event){
				var isOk = true;
				var msg = "Please fill in the required forms.";
				
				var $fund = $("#fund").val();
				if($fund == 0 || $fund == "" || $fund == null){
					isOk = false;
					msg;
				}
				
				var $getTotalCost = $("#getTotalCost").val();
				if($getTotalCost == 0 || $getTotalCost == "" || $getTotalCost == null){
					isOk = false;
					msg;
				}
				
				if(!isOk){
						alert(msg);
						return false;
				}else{
					var POid = $("#getpoId").val();
					var POfund = $("#fund").val();
					//var OSnum = $("#POOSNum").val();
					var FundAmount = $("#getTotalCost").val();

					var hr = new XMLHttpRequest();
						var url = "savePOFund.php";
						var vars = "POid="+POid+"&POfund="+POfund+"&FundAmount="+FundAmount;

						hr.open("POST", url, true);
						hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

						hr.onreadystatechange = function() {
						if(hr.readyState == 4 && hr.status == 200) {
						  var return_data = hr.responseText;
							document.getElementById("saveFundingResult").innerHTML = return_data;
							window.location = "purchase_order.php";
							
						  }
						}
						hr.send(vars);
						document.getElementById("saveFundingResult").innerHTML = "Funding the Order...";
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
				
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-file-text zmdi-hc-lg"></i>&nbsp;&nbsp;PO No. <?php echo $getpo['ponumber']; ?> dated <?php print date("M j, Y", strtotime($getpo['podate'])) ?></h1>
						
						<br />
						<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
							<div class="panel-heading">
								<div class="row">
								
									<div class="col-lg-8">
								
										<?php 
										
										$stat = mysql_fetch_array(mysql_query("SELECT status FROM funding WHERE po_id = '$readpo_id'"))or die(mysql_error());
										
										if($position['position_name'] == "Budget Officer" || $position['position_name'] == "OIC Budget Officer" || $position['position_name'] == "Budget"){
											if($stat['status'] == "pending"){
												?>
												
												<button name="fund" data-id="<?php echo $getpo['po_id']."|{$getpo['ponumber']}|{$getpo['allitem_nums']}" ?>" data-toggle="modal" data-target="#verifyFund" class="btn btn-warning"><i class="zmdi zmdi-balance zmdi-hc-1x"></i>&nbsp;&nbsp;Fund this Order</button>
											
												<div class="modal fade" id="verifyFund" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
													<div class="modal-dialog">
														<div class="modal-content">
															<form>
																<div class="modal-header">
																	<h4 class="modal-title" id="myModalLabel"><i class="zmdi zmdi-balance zmdi-hc-2x"></i>&nbsp;&nbsp;Funding for Purchase Order No. <label id="getpoNum"></label></h4>
																	<input class="form-control" id="getpoId" type="hidden" />
																</div>
																<div class="modal-body">
																	
																	<div class="row" align="center">
																		<div class="col-lg-1">
																		</div>
																		<div class="col-lg-10">
																			<div class="row">
																				<div class="col-lg-4" style="margin-top:10px;" align="left">
																					<label>Select Fund:</label>
																				</div>
																				<div class="col-lg-6" style="margin-top:5px;" align="left">
																					<select class="selectpicker form-control" id="fund">
																						<option selected disabled>-Select Fund-</option>
																						<option value="101">101</option>
																						<option value="164">164</option>
																						<option value="BEMONC">BEMONC</option>
																						<option value="MNCHN">MNCHN</option>
																					</select>
																				</div>
																			</div>
																			&nbsp;
																			<!--<div class="row">
																				<div class="col-lg-4" style="margin-top:5px;" align="left">
																					<label>OS Number:</label>
																				</div>
																				<div class="col-lg-6" align="left">
																					<input class="form-control" id="POOSNum" />
																				</div>
																			</div>-->
																			<div class="row">
																				<div class="col-lg-4" style="margin-top:5px;" align="left">
																					<label>Amount:</label>
																				</div>
																				<div class="col-lg-6" align="left">
																					<input class="form-control" id="getTotalCost" name="getTotalCost" pattern="([0-9.,])+">
																				</div>
																			</div>
																		</div>
																	</div>
																	
																</div>
																<div class="modal-footer">
																	<button type="button" id="savePOFundBtn" class="btn btn-success"><span class="fa fa-thumbs-up fa-fw"></span>&nbsp;Approve Funding</button>
																	<button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>&nbsp;Cancel</button>
																</div>
																<div id="saveFundingResult" style="margin: 10px 0 10px 0;"></div>
															</form>
														</div>
													</div>
												</div>
												
												<?php
											}else if($stat['status'] == "funded"){
												print "<div style='font-family: Segoe UI; font-size:18px; margin:6px 0 0 0; '><i>Funding Accomplished.</i></div>";
											}
										}else{
											if($stat['status'] == "pending"){
												print "<div style='font-family: Segoe UI; font-size:18px; margin:6px 0 0 0; '><i>Pending for funds available.</i></div>";
											}else if($stat['status'] == "funded"){
												print "<div style='font-family: Segoe UI; font-size:18px; margin:6px 0 0 0; '><i>Funding Accomplished.</i></div>";
											}
										}
										?>
								
									</div>
									<div class="col-lg-4" align="right">
										<a href="../requests/purchase_order.php" class="btn btn-info"><span class="fa fa-arrow-left"></span>&nbsp;&nbsp;Go Back</a>
										<?php
										if($stat['status'] == "pending"){
											?>
											<?php
										}else{
											?>
											<a target="_blank" href="print_po.php?id=<?php echo $getpo['po_id']; ?>"><button type="button" class="btn btn-primary"><i class="fa fa-print fa-fw"></i>&nbsp;Print</button></a>
											<?php
										}
										?>
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
																			<div class="col-lg-12" style="font-size:20px;"><center><strong>PURCHASE ORDER</strong></center></div>
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
																						<strong>Address:</strong>&nbsp;&nbsp;<?php print $getsupplier['supplier_address']?>
																						</div>
																						<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																						<strong>TIN:</strong>&nbsp;&nbsp;<?php print $getsupplier['supplier_tin_no'];?>
																						</div>
																					</div>
																			</td>
																			<td width="50%">
																					<div class="col-lg-12">
																						<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																						<strong>PO No.</strong>&nbsp;&nbsp;<?php echo $getpo['ponumber']; ?>
																						</div>
																						<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																						<strong>Date:</strong>&nbsp;&nbsp;<?php print date("M j, Y", strtotime($getpo['podate'])) ?>
																						</div>
																						<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																						<strong>Mode of Payment:</strong>&nbsp;&nbsp;<?php print $getpo['modepayment'];?>
																						</div>
																					</div>
																			</td>
																		</tr>
																		<tr>
																			<td colspan="2">
																				<div class="col-lg-12">
																					<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																					Gentlemen: Please furnish the following articles subject to the terms and conditions contained herein.
																					</div>
																				</div>
																			</td>
																		</tr>
																		<tr>
																			<td colspan="2">
																				<div class="col-lg-6">
																					<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																					<strong>Place of Delivery:</strong>&nbsp;&nbsp;<?php print $getpo['delivery_place'];?>
																					</div>
																					<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																					<strong>Date of Delivery:</strong>&nbsp;&nbsp;<?php 
																					if($getpo['orig_deliverydate'] == "0000-00-00"){
																						print "Delivery Date is Setting up...";
																					}else{
																						print date("M d, Y", strtotime($getpo['orig_deliverydate']));
																					}
																					?>
																					</div>
																				</div>
																				<div class="col-lg-6">
																					<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																					<strong>Delivery Term:</strong>&nbsp;&nbsp;<?php print $getpo['orig_deliveryterm'];?> cd after received PO
																					</div>
																					<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																					<strong>Payment of Term:</strong>&nbsp;&nbsp;<?php print $getpo['payment_term'];?>
																					</div>
																				</div>
																			</td>
																		</tr>
																		<tr>
																			<td colspan="2">
																				<?php 
																					
																					$getpoitems = mysql_query("SELECT * FROM request_items WHERE po_id = '$readpo_id'") or die(mysql_error());
																					
																					if(mysql_num_rows($getpoitems) == 0){
																						print "<br /><p align=center><i>Purchase Order Items are not available.</i></p><br />";
																					}else{
																						print "
																							<table class = 'table table-striped table-bordered table-hover'>
																							<thead>
																								<tr>
																									<th>Unit</th>
																									<th>Item Name</th>
																									<th>Item Description</th>
																									<th>Quantity</th>
																									<th>Unit Cost</th>
																									<th>Amount</th>
																								</tr>
																							</thead>
																							<tbody>
																						";
																						
																						while($getdata = mysql_fetch_array($getpoitems)){
																							$getunit = mysql_fetch_array(mysql_query("SELECT * FROM item_unit WHERE item_unit_id = $getdata[item_unit_id]"))or die (mysql_error());
																							$showitems = mysql_fetch_array(mysql_query("SELECT * FROM items WHERE item_id = $getdata[item_id]"))or die (mysql_error());
																							
																							print "<tr>";
																							print "<td width = '15%'>".$getunit['item_unit_name']."</td>";
																							print "<td>".$showitems['item_name']."</td>";
																							print "<td>".$getdata['description']."</td>";
																							print "<td width ='10%'>".$getdata['quantity']."</td>";
																							print "<td width = '15%'>Php ".number_format($getdata['est_unit_cost'], 2,'.',',')."/qty</td>";
																							print "<td width = '15%'><center>Php ".number_format($getdata['est_total_cost'], 2,'.',',')."</center></td>";
																							
																						}
																						print "</tr></tbody>";
																						
																						$gTotalToWord = number_format($getpo['allitem_nums'], 2,'.',',');
																						$gTotalWord = explode(".", $getpo['allitem_nums']);
																						$gTotalPeso = $gTotalWord[0];
																						$gTotalCents = (COUNT($gTotalWord) > 1) ? number_format($gTotalWord[1]) : 0;
																						$gTotalWord = convert_number_to_words($gTotalPeso)." Peso". (($gTotalPeso > 1) ? "s" : "");
																						$gTotalWord .= ($gTotalCents > 0 ) ? " & ".convert_number_to_words($gTotalCents)." Cent" . ($gTotalCents > 1 ? "s" : ""): "";
																						$gTotalWord .= " Only"; 
																						
																						print "
																						<tbody>
																							<tr>
																								<td colspan='5' class='gTotalWord'>Total Amount in Words:  ";
																								if($getpo['orig_allitemnums'] == 0){
																									print $gTotalWord;
																								}else{
																									print $gTotalWord;
																									print "<br/><i>(Penalty is added from extension of delivery)</i>";
																								}
																								print "</td>
																								<td class='gTotalNums'><center>Php ".number_format($getpo['allitem_nums'], 2,'.',',')."</center></td>
																							</tr>
																						</tbody>
																						";
																						
																						print "</table>";
																					}
																				?>
																			</td>
																		</tr>
																		<tr>
																			<td colspan="2">
																				<div class="col-lg-12">
																					<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																						<p style="text-indent: 50px;">
																							In case of failure to make the full delivery within the time specified above, a penalty of one - tenth (1/10) of one percent for every day of delay shall be imposed.
																						</p>
																						<div class="col-lg-6">
																						</div>
																						<div class="col-lg-6">
																							Very truly yours,
																							<br /><br />
																							<p style="text-transform:uppercase"><strong><center>
																								<u><?php print $getdean['full_name']; ?></u>
																							</strong></center></p>
																							<p><center>
																								(Authorized Official)
																							</center></p>
																						</div>
																						<div class="col-lg-6">
																							Conforme:
																							<br /><br />
																							<p style="text-transform:uppercase"><strong><center>
																								<?php print $getsupplier['supplier_name']; ?>
																							</strong></center></p>
																							<p><center>
																								<u>(Signature Over Printed Name)</u>
																							</center></p>
																						</div>
																						<div class="col-lg-6">
																						</div>
																					</div>
																				</div>
																			</td>
																		</tr>
																		<?php 
														
																		$fundstat = mysql_query("SELECT * FROM funding WHERE po_id = '$readpo_id'")or die(mysql_error());
																		
																		
																			while($getstat = mysql_fetch_array($fundstat)){
																				if($getstat['status'] == "pending"){
																					?>
																					<tr>
																						<td>
																							<div class="col-lg-12">
																								<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																								Funds Available:
																								<br /><br />
																								<p style="text-transform:uppercase"><strong><center>
																									<u><?php print $getbudget['full_name']; ?></u>
																								</strong></center></p>
																								<p><center>
																									<?php print $getbudget['position_name']; ?>
																								</center></p>
																								</div>
																							</div>
																						</td>
																						<td>
																							<div class="col-lg-12">
																								<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																								<strong>OS No.</strong>&nbsp;&nbsp;
																								</div>
																								<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																								<strong>Amount:</strong>&nbsp;&nbsp;
																								</div>
																							</div>
																						</td>
																					</tr>
																					<?php
																			}else if($getstat['status'] == "funded"){
																				//$getfunding = mysql_fetch_array(mysql_query("SELECT * FROM funding WHERE po_id = '$getstat[po_id]' "))or die(mysql_error());
																				?>
																				
																				<tr>
																					<td>
																						<div class="col-lg-12">
																							<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																							Funds Available:
																							<br /><br />
																							<p style="text-transform:uppercase"><strong><center>
																								<u><?php print $getbudget['full_name']; ?></u>
																							</strong></center></p>
																							<p><center>
																								<?php print $getbudget['position_name']; ?>
																							</center></p>
																							</div>
																						</div>
																					</td>
																					<td>
																						<div class="col-lg-12">
																							<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																							<strong>OS No.</strong>&nbsp;&nbsp;<?php print $getstat['os_num']; ?>
																							</div>
																							<div class="col-lg-12" style="font-size:15px; margin: 8px 8px 8px 8px;">
																							<strong>Amount:</strong>&nbsp;&nbsp;
																								<?php
																									if($getstat['amount'] == ""){
																										print "Amount not specified.";
																									}else{
																										print "Php ".number_format($getstat['amount'], 2,'.',',');
																									}}}
																								?>
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