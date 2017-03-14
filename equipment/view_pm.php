<!DOCTYPE html>
<?php
	include "../connect.php";

	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");
	$date = date("Y-m-d");
	$month = date("Y-m");
	
	$readpmNum = $_GET['pmNum'];
	
	$getpm = mysql_fetch_array(mysql_query("SELECT * FROM `eqp_preventive_maintenance` WHERE `pmNum` = '$readpmNum'"))or die(mysql_error());
	
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
	<?php include  "../engine/jscalls.php"; ?>
	<script>
	$(document).ready(function(){
		
		$("#pmsuccess").on("show.bs.modal", function(event){            
            var button = $(event.relatedTarget);
            data = button.data('id');
            request_data = data.split('|');
            
            $("#pmitemid").val(request_data[0]);
            $("#eqname").text(request_data[1]);
            $("#eqbrand").text(request_data[2]);
			$("#pmid").val(request_data[3]);
            //alert(data);
        });
		
		$("#PMsuccessful").click(function(event){
			var isOk = true;
			var msg = "Please type your findings.";
				var $findings = $("#findings").val();
				if($findings == 0 || $findings == "" || $findings == null){
					isOk = false;
					msg;
				}
				
				if(!isOk){
						alert(msg);
						return false;
				}else{
					var pmItemId = $("#pmitemid").val();
					var pmFindings = $("#findings").val();
					var pmId = $("#pmid").val();

					var hr = new XMLHttpRequest();
						var url = "pmSuccess.php";
						var vars = "pmItemId="+pmItemId+"&pmFindings="+pmFindings+"&pmId="+pmId;

						hr.open("POST", url, true);
						hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

						hr.onreadystatechange = function() {
						if(hr.readyState == 4 && hr.status == 200) {
						  var return_data = hr.responseText;
							document.getElementById("SuccessResult").innerHTML = return_data;
							window.location = "view_pm.php?pmNum=<?php print $readpmNum; ?>";
						  }
						}
						hr.send(vars);
						document.getElementById("SuccessResult").innerHTML = "Updating...";
				}
        });
		
		$("#pmfailed").on("show.bs.modal", function(event){            
            var button = $(event.relatedTarget);
            data = button.data('id');
            request_data = data.split('|');
            
            $("#pmitem").val(request_data[0]);
            $("#name").text(request_data[1]);
            $("#brand").text(request_data[2]);
			$("#pmid2").val(request_data[3]);
            //alert(data);
        });
		
		$("#PMfail").click(function(event){
			var isOk = true;
			var msg = "Please complete the findings.";
				var $failedfindings = $("#failedfindings").val();
				if($failedfindings == 0 || $failedfindings == "" || $failedfindings == null){
					isOk = false;
					msg;
				}
				var $failtype = $("#failtype").val();
				if($failtype == 0 || $failtype == "" || $failtype == null){
					isOk = false;
					msg;
				}
				
				if(!isOk){
						alert(msg);
						return false;
				}else{
					var pmItem = $("#pmitem").val();
					var FailedFindings = $("#failedfindings").val();
					var FailedType = $("#failtype").val();
					var pmId = $("#pmid2").val();

					var hr = new XMLHttpRequest();
						var url = "pmFailed.php";
						var vars = "pmItem="+pmItem+"&FailedFindings="+FailedFindings+"&FailedType="+FailedType+"&pmId="+pmId;

						hr.open("POST", url, true);
						hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

						hr.onreadystatechange = function() {
						if(hr.readyState == 4 && hr.status == 200) {
						  var return_data = hr.responseText;
							document.getElementById("FailedResult").innerHTML = return_data;
							window.location = "view_pm.php?pmNum=<?php print $readpmNum; ?>";
						  }
						}
						hr.send(vars);
						document.getElementById("FailedResult").innerHTML = "Updating...";
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
					<a href="eq_issued.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-upload zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Issued Equipment</a>
				</li>
				<?php
				if($position['position_name'] == "Supply Officer"){
					?>
					<li>
						<a href="eq_disposal.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-delete zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Equipment Disposal</a>
					</li>
					<li>
						<a href="eq_turnover.php"><span class="fa-stack fa-lg pull-left"><i class="glyphicon glyphicon-share-alt"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Equipment Turn-over</a>
					</li>
					<?php
				}else{
					print "";
				}
				?>
				<li>
					<a href="eq_pmaintenance.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-wrench zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Corrective Maintenance</a>
				</li>
				<?php
				
				if($position['position_name'] == "Supply Officer"){
					?>
					<li>
						<a href="equipment_label.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-label zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Equipment Labels</a>
					</li>
					<li>
						<a href="equipment_specs.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-widgets zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Equipment Descs</a>
					</li>
					<li>
						<a href="eq_report.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-file-text zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Reports</a>
					</li>
					<?php
				}else{
					?>
					<li>
						<a href="eq_enduserdisposed.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-delete zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Disposed Equipment</a>
					</li>
					<?php
				}
				?>
			
		</ul> <!--/.Inside Sidebar -->
	</div><!--/.Sidebar -->
	
	<!-- Navigation -->
	<?php include "../equipment/eq_header.php"; ?>
	<!--/.Navigation -->
	
<!-- /.Header and Sidebar Page -->
			
<!-- Body will contain the Page Contents -->

<body>
	<!-- Content-Wrapper -->
	<div id="content-wrapper">
		
		<div class="container-fluid">
			
			<div class="row" style="margin-top:-20px;">
				<div class="col-lg-12">
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-plaster zmdi-hc-lg"></i>&nbsp;&nbsp;<?php print $getpm['pmNum']; ?></h1>
					
						<br />
						<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
							<div class="panel-heading">
								<div class="row">
									<div class="col-lg-12">
										<a href="eq_pmaintenance.php" class="btn btn-info"><span class="fa fa-arrow-left"></span>&nbsp;&nbsp;Go Back</a>
									</div>
								</div>
							</div>
							<div class="panel-body">
									
									<div class="col-lg-12">
										<div class="row" style="margin: 10px 0 10px 0;font-size:15px;">
											<div class="col-lg-9">
												<label>Date of Maintenance:</label>&nbsp;&nbsp;
												<?php print date("M d, Y", strtotime($getpm['pmSched']));?>
											</div>
											<div class="col-lg-3">
											<?php
											
											if($getpm['pmDateDone'] == "0000-00-00"){
												print "";
											}else{
												print "<strong>".$getpm['pmStatus']."</strong>";
											}
											
											?>
											</div>
										</div>
										<div class="panel panel-default" style="font-size:15px;">
											<div class="panel-body">
												<div class="row">
													<div class="col-lg-12">
														<div class="col-lg-3" style="margin-bottom: 10px;" align="left">
															<label>Company:</label><br/>
															<?php print $getpm['pmCompany'];?>
														</div>
														<div class="col-lg-3" style="margin-bottom: 10px;" align="left">
															<label>Address:</label><br/>
															<?php print $getpm['pmAddress'];?>
														</div>
														<div class="col-lg-3" style="margin-bottom: 10px;" align="left">
															<label>Contact No:</label><br/>
															<?php print $getpm['pmContact'];?>
														</div>
														<div class="col-lg-3" style="margin-bottom: 10px;" align="left">
															<label>Repairer:</label><br/>
															<?php print $getpm['pmRepairer'];?>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="panel panel-default">
											<div class="panel-body">
											
												<?php
												
												$pmitems = mysql_query("SELECT * FROM `eqp_pm_items` WHERE `eqp_pm_id` = '$getpm[eqp_pm_id]'")or die(mysql_error());
												
												if(mysql_num_rows($pmitems) == 0){
													print "<br /><p align=center><i>Something's wrong when retrieving items from this form.</i></p><br />";
												}else{
													
													print "
														<div class='table-responsive'>
															<table class = 'table table-striped table-bordered table-hover display'>
																<thead>
																	<tr>
																		<th>Unit</th>
																		<th>Item</th>
																		<th>Property No.</th>
																		<th>Owner</th>
																		<th>Department</th>
																		<th>Action</th>
																	</tr>
																</thead>
																<tbody>
													";
													
													while($getpmitems = mysql_fetch_array($pmitems)){
														$geteqpdetails = mysql_fetch_array(mysql_query("SELECT * FROM `equipments` WHERE `eqp_id` = '$getpmitems[eqp_id]'"))or die(mysql_error());
														$selitem = mysql_fetch_array (mysql_query("SELECT * FROM `items` WHERE `item_id` = '$geteqpdetails[item_id]'"))or die(mysql_error());
														$selitemunit = mysql_fetch_array (mysql_query("SELECT * FROM `item_unit` WHERE `item_unit_id` = '$geteqpdetails[item_unit_id]'"))or die(mysql_error());
														$getpersonnel = mysql_fetch_array(mysql_query("SELECT pwi.pwi_id, CONCAT(p.personnel_fname,' ',p.personnel_lname) AS full_name, d.dept_name, ps.position_name FROM `personnel_work_info` AS pwi LEFT JOIN personnel AS p ON p.personnel_id = pwi.personnel_id LEFT JOIN department AS d ON d.dept_id = pwi.dept_id LEFT JOIN personnel_position AS ps ON ps.position_id = pwi.position_id WHERE pwi.personnel_id = '$geteqpdetails[received_by]'"))or die(mysql_error());
														
														print "<tr>";
														print "<td style='text-align:center;vertical-align:middle;'>".$selitemunit['item_unit_name']."</td>";
														print "<td style='text-align:center;vertical-align:middle;'><a href='view_eq3.php?id=".$geteqpdetails['eqp_id']."'>".$selitem['item_name'].", ".$geteqpdetails['brand']."</a></td>";
														print "<td style='text-align:center;vertical-align:middle;'>".$geteqpdetails['prop_num']."</td>";
														print "<td style='text-align:center;vertical-align:middle;'>".$getpersonnel['full_name']."</td>";
														print "<td style='text-align:center;vertical-align:middle;'>".$getpersonnel['dept_name']."</td>";
														
														print "<td style='text-align:center;vertical-align:middle;'>";
														
														if($getpmitems['status'] == "Under Maintenance"){
															?>
															<a href="#" data-id="<?php echo $getpmitems['pmitems_id']."|{$selitem['item_name']}|{$geteqpdetails['brand']}|{$getpm['eqp_pm_id']}";?>" data-toggle="modal" data-target="#pmsuccess" class="btn btn-success" style="margin: 3px 3px 3px 3px;" title="Set item repair as successful."><span class="fa fa-check"></span></a>
															
															<div class="modal fade" id="pmsuccess" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
																<div class="modal-dialog">
																	<div class="modal-content">
																		<div class="modal-header">
																			<h4 class="modal-title" id="myModalLabel"><i class="zmdi zmdi-search-in-file zmdi-hc-2x"></i>&nbsp;&nbsp;Findings for <label id="eqname"></label>, <label id="eqbrand"></label></h4>
																			<input class="form-control" id="pmitemid" type="hidden" />
																		</div>
																		<div class="modal-body">
																			<p align="left">Please type the findings during maintenance:</p>
																				<div class="row">
																					<div class="col-lg-12">
																						<textarea class="textarea" id="findings" style="height:100px;resize:none;width:100%;" required ></textarea>
																						<input class="form-control" id="pmid" type="hidden"/>
																					</div>
																				</div>
																		</div>
																		<div class="modal-footer">
																			<button type="button" id="PMsuccessful" class="btn btn-info"><span class="glyphicon glyphicon-ok"></span>&nbsp;Submit</button>
																			<button type="button" class="btn btn-warning" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>&nbsp;Cancel</button>
																		</div>
																		<div id="SuccessResult" style="margin: 10px 0 10px 0;"></div>
																	</div>
																</div>
															</div>
															
															<a href="#" data-id="<?php echo $getpmitems['pmitems_id']."|{$selitem['item_name']}|{$geteqpdetails['brand']}|{$getpm['eqp_pm_id']}";?>" data-toggle="modal" data-target="#pmfailed" class="btn btn-danger" style="margin: 3px 3px 3px 3px;" title="Set item repair as not successful." ><span class="fa fa-remove"></span></a>
															
															<div class="modal fade" id="pmfailed" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
																<div class="modal-dialog">
																	<div class="modal-content">
																		<div class="modal-header">
																			<h4 class="modal-title" id="myModalLabel"><i class="zmdi zmdi-search-in-file zmdi-hc-2x"></i>&nbsp;&nbsp;Findings for <label id="name"></label>, <label id="brand"></label></h4>
																			<input class="form-control" id="pmitem" type="hidden" />
																		</div>
																		<div class="modal-body">
																			<p align="left">Please type the findings during maintenance:</p>
																				<div class="row">
																					<div class="col-lg-12">
																						<textarea class="textarea" id="failedfindings" style="height:100px;resize:none;width:100%;" required></textarea>
																						<input class="form-control" id="pmid2" type="hidden" />
																					</div>
																				</div>
																				<br/>
																				<div class="row">
																					<div class="col-lg-12">
																						<div class="col-lg-2" style="margin:6px 0 0 0;">
																							<p align="left">Action:</p>
																						</div>
																						<div class="col-lg-6">
																							<select class="selectpicker form-control" id="failtype">
																								<option selected disabled>-Select-</option>
																								<option value="Subject for New Repair">Subject for New Repair</option>
																								<option value="Subject for Disposal">Subject for Disposal</option>
																							</select>
																						</div>
																					</div>
																				</div>
																		</div>
																		<div class="modal-footer">
																			<button type="button" id="PMfail" class="btn btn-info"><span class="glyphicon glyphicon-ok"></span>&nbsp;Submit</button>
																			<button type="button" class="btn btn-warning" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>&nbsp;Cancel</button>
																		</div>
																		<div id="FailedResult" style="margin: 10px 0 10px 0;"></div>
																	</div>
																</div>
															</div>
															
															<?php
														}else{
															print "<div class='col-lg-6' style='margin:10px 0 10px 0'>";
															print $getpmitems['status'];
															print "</div>";
															print "<div class='col-lg-6'>";
															?>
															
															<script>
													
															$(document).ready(function(){
																$("#viewFindings").on("show.bs.modal", function(event){            
																	var button = $(event.relatedTarget);
																	data = button.data('id');
																	request_data = data.split('|');
																	
																	$("#pmId").val(request_data[0]);
																	$("#pmFindings").text(request_data[1]);
																	//alert(data);
																});
															});
													
															</script>
															
															<a href="#" data-id="<?php echo $getpmitems['pmitems_id']."|{$getpmitems['findings']}"?>" data-toggle="modal" data-target="#viewFindings" class="btn btn-warning" title="View Findings" style="margin:3px 3px 3px 3px;"><span class="zmdi zmdi-search-in-file"></span>&nbsp;&nbsp;Findings</a></div></div></td>
															
															<div class="modal fade" id="viewFindings" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
																<div class="modal-dialog">
																	<div class="modal-content">
																		<div class="modal-header">
																			<h4 class="modal-title" id="myModalLabel"><i class="zmdi zmdi-search-in-file zmdi-hc-2x"></i>&nbsp;&nbsp;Findings</h4>
																			<input class="form-control" id="pmId" type="hidden"/>
																		</div>
																		<div class="modal-body">
																			<p id="pmFindings"></p>
																		</div>
																		<div class="modal-footer">
																			<button type="button" class="btn btn-info" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>&nbsp;Close</button>
																		</div>
																	</div>
																</div>
															</div>
															
															<?php
															print "</div>";
														}
														print "</td>";
														print "</tr>";
														
													}
													print "</tbody></table></div>";
												}
												
												?>
												
											</div>
										</div>
									</div>
							</div>
							
							<?php
							
							$getdataone = "SELECT * FROM `eqp_pm_items` WHERE `eqp_pm_id` = '$getpm[eqp_pm_id]'";
							$getdatatwo = "SELECT * FROM `eqp_pm_items` WHERE `eqp_pm_id` = '$getpm[eqp_pm_id]' AND `findings` != '' ";
							
							$datarrayone = mysql_num_rows(mysql_query($getdataone));
							$datarraytwo = mysql_num_rows(mysql_query($getdatatwo));
							
							$subtract = ($datarrayone - $datarraytwo);
							
							if ($subtract == 0){
								$isDisabled = "";
								$btnTitle = "You can now finish the maintenance process.";
							}else if ($subtract > 0){
								$isDisabled = "disabled";
								$btnTitle = "You cannot finish the maintenance process until all equipment has findings.";
							}
							
							if($getpm['pmStatus'] == "Ongoing"){
								?>	
								<div class="panel-footer" align="right">
									<form method="post">
									<?php
										print "<button type='submit' name='pmdone' id='pmdone' class='btn btn-success' title='".$btnTitle."' ".$isDisabled."><span class='glyphicon glyphicon-ok'></span>&nbsp;Submit</button>";
									?>
									</form>
								</div>
								<?php
							}else{
								?>
								<div class="panel-footer" align="right">
								</div>
								<?php
							}
							
							?>
							
						</div>
				</div>
			</div>
			
		</div> <!--/.container-fluid-->
	
	</div> <!--/.content-wrapper-->
	
	<?php
	
	if(isset($_POST['pmdone'])){
		mysql_query("UPDATE `eqp_preventive_maintenance` SET `pmDateDone`='$date' WHERE `eqp_pm_id` = '$getpm[eqp_pm_id]'");
		
		$getQry = mysql_fetch_array(mysql_query("SELECT pmDateDone FROM eqp_preventive_maintenance WHERE `eqp_pm_id` = '$getpm[eqp_pm_id]'"));
		$getdateformat = date("M d, Y", strtotime($getQry['pmDateDone']));
		
		mysql_query("UPDATE `eqp_preventive_maintenance` SET `pmStatus`='Finished on ".$getdateformat."' WHERE `eqp_pm_id` = '$getpm[eqp_pm_id]'");
		
		print "<script>alert('Maintenance report has been submitted.'); window.location='eq_pmaintenance.php';</script>";
	}
	
	?>
	
</body>
<!-- /.Body -->

</div> <!-- /.wrapper-->

</html>