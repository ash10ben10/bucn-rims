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
	<?php include "addisposal_engine.php"; ?>
	
	<script>
	
	$(document).ready(function() {
		var $eqpItem = $(".eqpitem");
		$("#eqpitemchkbx").off("change").on("change", function(){
			var thischk = $(this).prop("checked");
			if(thischk){
				$eqpItem.prop("checked", true);
			}else{
				$eqpItem.prop("checked", false);
			}
		});
	});
	
	function submitform(e){
		var isOk = true;
		var msg = "";
			var checkedEqpItem = $(".eqpitem:checked").map(function(){
				return $(this).val();
			});
			if(checkedEqpItem.length == 0){
				isOk = false;
				msg += "Please select item(s).";
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
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-receipt zmdi-hc-lg"></i>&nbsp;&nbsp;Dispose Equipment</h1>
						<form method="POST" id="disposal" enctype="multipart/form-data" onsubmit="submitform(event);">
						<br />
						<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
							<div class="panel-heading">
								<div class="row" style="margin: 10px 0 0 0;">
									<div class="col-lg-12">
										<div class="col-lg-1" style="margin: 6px 0 6px 0;" align="left">
											<strong>Chairman:</strong>
										</div>
										<div class="col-lg-4" style="" align="left">
											<input class="form-control" id="chairman" name="chairman" placeholder="Name of the Chairman" maxlength="1000" pattern="([A-Za-zñÑ]| |-)+" minlength="2" required />
										</div>
									</div>
								</div>
							</div>
							<div class="panel-body">
								<br/>
								<div class="col-lg-12">
									<p style="font-size:15px;"><i>Enter the names of the following committee:</i></p>
									<div class="panel panel-default">
										<div class="panel-body">
											<div class="col-lg-12">
												<div class="row">
													<div class="col-lg-3" style="margin-bottom: 10px;" align="left">
														<label>Member 1:</label>
														<input class="form-control" id="membera" name="membera" placeholder="Name of the Member" pattern="([A-Za-zñÑ]| |-)+" minlength="2" required />
													</div>
													<div class="col-lg-3" style="margin-bottom: 10px;" align="left">
														<label>Member 2:</label>
														<input class="form-control" id="memberb" name="memberb" placeholder="Name of the Member" pattern="([A-Za-zñÑ]| |-)+" minlength="2" required />
													</div>
													<div class="col-lg-3" style="margin-bottom: 10px;" align="left">
														<label>Member 3:</label>
														<input class="form-control" id="memberc" name="memberc" placeholder="Name of the Member" pattern="([A-Za-zñÑ]| |-)+" minlength="2" required />
													</div>
													<div class="col-lg-3" style="margin-bottom: 10px;" align="left">
														<label>COA Representative:</label>
														<input class="form-control" id="coarep" name="coarep" placeholder="Name of the Representative" pattern="([A-Za-zñÑ]| |-)+" minlength="2" required />
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								
								<div class="col-lg-12">
									<p style="font-size:15px;"><i>Select items:</i></p>
									<div class="panel panel-default">
										<div class="panel-body">
										
										<?php 
								
											$eqps = mysql_query("SELECT * FROM `equipments` WHERE `remarks` LIKE '%Working%' OR `remarks` LIKE '%Subject for Disposal%' ORDER BY remarks ASC")or die(mysql_error());
											
											if(mysql_num_rows($eqps) == 0){
												print "<br /><p align=center><i>There are no issued Equipment at this moment.</i></p><br />";
											}else{
												print "
												<div class='table-responsive'>
														<table class = 'table table-striped table-bordered table-hover display'>
														<thead>
															<tr>
																<th>Select<br /><input type='checkbox' id='eqpitemchkbx' /></th>
																<th>Unit</th>
																<th>Item</th>
																<th>Serial Number</th>
																<th>Property Number</th>
																<th>Issued To</th>
																<th>Department</th>
															</tr>
														</thead>
														<tbody>
												";
												
												while($geteqps = mysql_fetch_array($eqps)){
													$selitem = mysql_fetch_array (mysql_query("SELECT * FROM `items` WHERE `item_id` = '$geteqps[item_id]'"))or die(mysql_error());
													$selitemunit = mysql_fetch_array (mysql_query("SELECT * FROM `item_unit` WHERE `item_unit_id` = '$geteqps[item_unit_id]'"))or die(mysql_error());
													$getpersonnel = mysql_fetch_array(mysql_query("SELECT pwi.pwi_id, CONCAT(p.personnel_fname,' ',p.personnel_lname) AS full_name, d.dept_name, ps.position_name FROM `personnel_work_info` AS pwi LEFT JOIN personnel AS p ON p.personnel_id = pwi.personnel_id LEFT JOIN department AS d ON d.dept_id = pwi.dept_id LEFT JOIN personnel_position AS ps ON ps.position_id = pwi.position_id WHERE pwi.personnel_id = '$geteqps[received_by]'"))or die(mysql_error());
													
													print "<tr>";
														?>
														<td style="text-align:center;vertical-align:middle;" align="center"><input type="checkbox" class="eqpitem" name="geteqpid[]" id="geteqpid" value="<?php print $geteqps['eqp_id']; ?>" /></td>
														<?php
													print "<td style='text-align:center;vertical-align:middle;'>".$selitemunit['item_unit_name']."</td>";
													print "<td style='text-align:center;vertical-align:middle;'><a href='view_eq5.php?id=".$geteqps['eqp_id']."'>".$selitem['item_name'].", ".$geteqps['brand']."</td>";
													//print "<td style='text-align:center;vertical-align:middle;'>".$geteqps['description']."</td>";
													print "<td style='text-align:center;vertical-align:middle;'>".$geteqps['serialnum']."</td>";
													print "<td style='text-align:center;vertical-align:middle;'>".$geteqps['prop_num']."</td>";
													print "<td style='text-align:center;vertical-align:middle;'>".$getpersonnel['full_name']."</td>";
													print "<td style='text-align:center;vertical-align:middle;'>".$getpersonnel['dept_name']."</td>";
													print "</tr>";
												}
												
												print "</tbody></table></div>";
											}
											
										?>
										
										</div>
									</div>
								</div>
							</div>
							<div class="panel-footer" align="right">
								<button type="submit" name="dispnow" id="dispnow" class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;Submit</button>
								<a href="../equipment/eq_disposal.php" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span>&nbsp;Cancel</a>
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