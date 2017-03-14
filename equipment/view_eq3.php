<!DOCTYPE html>
<?php
	include "../connect.php";
	
	$readeqp_id = $_GET['id'];
	
	$geteqps = mysql_fetch_array(mysql_query("SELECT * FROM `equipments` WHERE `eqp_id` = '$readeqp_id'"))or die(mysql_error());
	
	#get credentials
	$getitems = mysql_fetch_array(mysql_query("SELECT * FROM `items` WHERE `item_id` = '$geteqps[item_id]'"))or die(mysql_error());
	$getitemunit = mysql_fetch_array(mysql_query("SELECT * FROM `item_unit` WHERE `item_unit_id` = '$geteqps[item_unit_id]'"))or die(mysql_error());
	
	
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
	<?php include "vieweq_engine3.php"; ?>
	<script>
		var validatetype = function(fileExt){
				var toReturn = false;
				switch(fileExt){
					case 'bmp':
					case 'BMP':
					case 'gif':
					case 'GIF':
					case 'jpg':
					case 'JPG':
					case 'jpeg':
					case 'JPEG':
					case 'png':
					case 'PNG': toReturn = true;
					break;
					default: toReturn = false;
				}
				return toReturn;
			}
			
		function submitform(e){
				var $eqpic = $("#eqpic").val();
				if($eqpic == ''){
					return true;
				}else{
					var ext = $eqpic.split('.');
					var extCount = ext.length;
					//alert(ext[extCount -1]);
					if(!validatetype(ext[extCount -1])){
						e.preventDefault();
						alert("You selected an invalid image file type. Please select a valid image file.");
						return false;
					}else{
						return true;
					}
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
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-dns zmdi-hc-lg"></i>&nbsp;&nbsp;<?php print $getitems['item_name']; ?></h1>
					
						<br />
						<form role="form" method="POST" id="updateequipment" enctype="multipart/form-data" onsubmit="submitform(event);">
							<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
								<div class="panel-heading">
									<div class="row">
										<div class="col-lg-4">
											<div id="enable_equipment_edit" class="pull-left">
												<a onClick="enable_edit('equipment')" data-toddle='tooltip' title="Edit Stock Info" class="btn btn-default"><span class="zmdi zmdi-border-color"></span>&nbsp;&nbsp;Edit</a>
											</div>
											<div id="disable_equipment_edit" class="pull-left" hidden >
												<a href="view_eq3.php?id=<?php echo $readeqp_id; ?>" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span>&nbsp;Cancel</a>
												<button type="submit" name="update_eqpinfo" id="update_eqpinfo" class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;Save</button>
											</div>
										</div>
									</div>
								</div>
								
								<div class="panel-body">
									<table class ="table table-striped table-bordered">
										<tbody>
											<tr>
												<td colspan="4" style="font-size: 110%;"><strong>Information</strong></td>
											</tr>
											<tr>
												<td width="60%" colspan="3">
													
													<div class="row" style="margin-left:2px; margin: 10px 2px 10px 2px;">
														<div class="col-lg-12">
															<div class="col-lg-4" style="font-size:16px;margin: 10px 0 10px 0;">
																<label>Amount:</label><br/><?php print "Php ".number_format($geteqps['unit_value'], 2,'.',','); ?>
															</div>
															<div class="col-lg-4" style="font-size:16px;margin: 10px 0 10px 0;">
																<label>Unit:</label><br/><?php print $getitemunit['item_unit_name']; ?>
															</div>
															<?php
																//$totalqty = mysql_fetch_assoc(mysql_query("SELECT SUM(quantity) AS qtysum FROM equipments WHERE stock_id = '$readstock_id' ")) or die(mysql_error());
															?>
															<div class="col-lg-4" style="font-size:16px;margin: 10px 0 10px 0;">
																<label>Date Acquired:</label><br/><?php print date("M d, Y", strtotime($geteqps['date_acquired'])); ?>
															</div>
															<div class="col-lg-8" style="font-size:16px;margin: 10px 0 10px 0;">
																<label>Property Number:</label><br/><?php print $geteqps['prop_num']; ?>
															</div>
															<div class="col-lg-4" style="font-size:16px;margin: 10px 0 10px 0;">
																<label>Status:</label><br/><?php print $geteqps['remarks']; ?>
															</div>
													</div>
												</td>
												<td width="40%" colspan="1" rowspan="2" align="center">
												
												<?php

												if($geteqps['eqp_photo'] == ""){
													?>
													<br/><br/>
													<img name="eqppics" id="eqppics" src="../engine/images/eqp.png" class="img-circle" style="height:200px; width:200px; padding: 0px 0px 0px 0px;" >
													<br/><br/><br/>
													<input type="file" name="eqpic" id="eqpic" accept="image/*" value="" disabled/>
													<?php
												}else{
													?>
													<br/><br/>
													<img name="eqppics" id="eqppics" src="../equipment/<?php print $geteqps['eqp_photo']; ?>" style="height:200px; width:200px; padding: 0px 0px 0px 0px;" >
													<br/><br/><br/>
													<input type="file" name="eqpic" id="eqpic" accept="image/*" value="" disabled/>
													<?php
												}
												
												?>
												
												
												</td>
											</tr>
											<tr>
												<td width="60%" colspan="3">
													<div class="row" style="margin-left:2px; margin-right:2px;">
														<div class="col-lg-6">
															<label>Brand:</label>
															<input class="form-control" name="eqpbrand" id="eqpbrand" value="<?php print $geteqps['brand']; ?>" required disabled />
														</div>
														<div class="col-lg-6">
															<label>Serial Number:</label>
															<input class="form-control" name="eqpsn" id="eqpsn" value="<?php print $geteqps['serialnum']; ?>" required disabled />
														</div>
														<div class="col-lg-12">
															<label>Description:</label>
															<input class="form-control" name="eqpdesc" id="eqpdesc" value="<?php print $geteqps['description']; ?>" required disabled />
														</div>
													</div>
													<!--<div class="row" style="margin-left:2px; margin-right:2px;">
														<div class="col-lg-6">
															<label>Stock Number:</label>
															<input class="form-control" name="eqpstocknum" id="eqpstocknum" value="<?php //print $getdesc['stock_no']; ?>" placeholder="Please enter the item stock number." required disabled>
														</div>
														<div class="col-lg-6">
															<label>Amount:</label>
															<input class="form-control" name="eqpamount" id="eqpamount" value="<?php //print number_format($getdesc['unit_cost'], 2,'.',''); ?>" pattern="([0-9.])+" required disabled>
														</div>
													</div>-->
												</td>
											</tr>
											<tr>
												<td colspan="4" style="font-size: 110%;"><strong>History</strong></td>
											</tr>
											<tr>
												<td width="100%" colspan="4">
													
													<?php
													
													$history = mysql_query("SELECT * FROM `eqp_history` WHERE `eqp_id` = '$geteqps[eqp_id]' ORDER BY historydate DESC")or die(mysql_error());
													
													if(mysql_num_rows($history) == 0){
														print "<br /><p align=center><i>This equipment has no history at the moment.</i></p><br />";
													}else{
														
														print "
														
															<table class = 'table table-striped table-bordered table-hover display'>
															<thead>
																<tr>
																	<th>Date</th>
																	<th>Issued To</th>
																	<th>Form</th>
																	<th>Remarks</th>
																</tr>
															</thead>
															<tbody>
														";
														
														while($gethistory = mysql_fetch_array($history)){
															$getpersonnel = mysql_fetch_array(mysql_query("SELECT CONCAT (personnel_fname,' ',personnel_lname) AS full_name FROM personnel WHERE personnel_id = $gethistory[receivedBy]"))or die(mysql_error());
															
															print "<tr>";
															
															print "<td style='text-align:center;vertical-align:middle;'><center>";
															print date("M d, Y", strtotime($gethistory['historydate']));
															print "</center></td>";
															print "<td style='text-align:center;vertical-align:middle;'><center>";
															print $getpersonnel['full_name'];
															print "</center></td>";
															
															print "<td style='text-align:center;vertical-align:middle;'><center>";
																if($gethistory['icspar'] == 'PAR'){
																	$parform = mysql_fetch_array(mysql_query("SELECT * FROM `eqp_par` WHERE par_id = '$gethistory[icspar_id]'"))or die(mysql_error());
																	?>
																	<a href="view_par4.php?id=<?php print $gethistory['icspar_id']; ?>"><?php print $parform['parnum'];?></a>
																	<?php
																}else if($gethistory['icspar'] == 'ICS'){
																	$icsform = mysql_fetch_array(mysql_query("SELECT * FROM `eqp_ics` WHERE ics_id = '$gethistory[icspar_id]'"))or die(mysql_error());
																	?>
																	<a href="view_ics4.php?id=<?php print $gethistory['icspar_id']; ?>"><?php print $icsform['icsnum'];?></a>
																	<?php
																}else if($gethistory['icspar'] == 'TO'){
																	$toform = mysql_fetch_array(mysql_query("SELECT * FROM `eqp_turnover` WHERE to_id = '$gethistory[icspar_id]'"))or die(mysql_error());
																	?>
																	<a href="view_to2.php?id=<?php print $readeqp_id; ?>"><?php print $toform['tonum'];?></a>
																	<?php
																}else if($gethistory['icspar'] == 'PM'){
																	$pmform = mysql_fetch_array(mysql_query("SELECT * FROM `eqp_preventive_maintenance` WHERE `eqp_pm_id` = '$gethistory[icspar_id]'"))or die(mysql_error());
																	?>
																	<a href="view_pm.php?pmNum=<?php print $pmform['pmNum']; ?>"><?php print $pmform['pmNum'];?></a>
																	<?php
																}else if($gethistory['icspar'] == 'DSP'){
																	$dspform = mysql_fetch_array(mysql_query("SELECT * FROM `eqp_disposal` WHERE `eqpd_id` = '$gethistory[icspar_id]'"))or die(mysql_error());
																	?>
																	<a href="view_disposal.php?id=<?php print $dspform['eqpd_id']; ?>"><?php print $dspform['dispnum'];?></a>
																	<?php
																}
															print "</center></td>";
															
															print "<td style='text-align:center;vertical-align:middle;'><center>";
															print $gethistory['remarks'];
															print "</center></td>";
															
															
															print "</tr>";
															
														}
														print "</tbody></table>";
														
													}
													
													?>
													
												</td>
											</tr>
										</tbody>
									</table>
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