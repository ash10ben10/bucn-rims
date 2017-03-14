<!DOCTYPE html>
<?php
include "../connect.php";

	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");
	$date = date("Y-m-d");
	$month = date("Y-m");
	
	$readdisp_id = $_GET['id'];
	
	$getdispinfo = mysql_fetch_array(mysql_query("SELECT * FROM `eqp_disposal` WHERE `eqpd_id` = '$readdisp_id'"));

	$geteqpid = mysql_fetch_array(mysql_query("SELECT `eqp_id` FROM `eqp_disposal_items` WHERE `eqpd_id` = '$readdisp_id'"));
	
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
	function submitform(e){
		var isOk = true;
		var msg = "";
			var $decision = $("#decision").val();
			if($decision == 0 || $decision == "" || $decision == null){
				isOk = false;
				msg = "Please select your decision.";
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
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-receipt zmdi-hc-lg"></i>&nbsp;&nbsp;<?php print $getdispinfo['dispnum']; ?></h1>
						<br />
						<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
							<div class="panel-heading">
								<div class="row">
									<div class="col-lg-6" align="left">
										<a href="view_eq7.php?id=<?php print $geteqpid['eqp_id']; ?>" class="btn btn-info"><span class="fa fa-arrow-left"></span>&nbsp;&nbsp;Go Back</a>
										<a target="_blank" href="print_disposal.php?id=<?php echo $readdisp_id ?>"><button type="button" class="btn btn-primary"><i class="fa fa-print fa-fw"></i>&nbsp;Print</button></a>
									</div>
									<div class="col-lg-6" align="right">
										<div style="font-family: Segoe UI; font-size:18px;margin: 8px 0 0 0;">
										<?php
										
										if($getdispinfo['dispstatus'] == "Pending"){
											?>
											<i>Pending for Decision.</i>&nbsp;&nbsp;&nbsp;
											<?php
										}else{
											?>
											<i><?php print $getdispinfo['dispstatus']; ?></i>&nbsp;&nbsp;&nbsp;
											<?php
										}
										?>
										</div>
									</div>
								</div>
							</div>
							<div class="panel-body">
							
								<div class="col-lg-12">
									<div class="row" style="margin: 10px 0 10px 0;font-size:15px;">
										<div class="col-lg-3" style="margin:6px 0 0 0;">
											<label>Date:</label>&nbsp;&nbsp;
											<?php print date("M d, Y", strtotime($getdispinfo['dispdate']));?>
										</div>
										<div class="col-lg-4" style="margin:6px 0 0 0;">
											<label>Chairman:</label>&nbsp;&nbsp;
											<?php print $getdispinfo['disp_chairman'];?>
										</div>
									</div>
									<div class="panel panel-default" style="font-size:15px;">
										<div class="panel-body">
											<div class="row">
												<div class="col-lg-12">
													<div class="col-lg-3" style="margin-bottom: 10px;" align="left">
														<label>Member 1:</label><br/>
														<?php print $getdispinfo['disp_memberA'];?>
													</div>
													<div class="col-lg-3" style="margin-bottom: 10px;" align="left">
														<label>Member 2:</label><br/>
														<?php print $getdispinfo['disp_memberB'];?>
													</div>
													<div class="col-lg-3" style="margin-bottom: 10px;" align="left">
														<label>Member 3:</label><br/>
														<?php print $getdispinfo['disp_memberC'];?>
													</div>
													<div class="col-lg-3" style="margin-bottom: 10px;" align="left">
														<label>COA Representative:</label><br/>
														<?php print $getdispinfo['disp_coa'];?>
													</div>
												</div>
											</div>
										</div>
									</div>
									
									<div class="panel panel-default">
										<div class="panel-body">
										
										<?php 
								
											$dispitems = mysql_query("SELECT * FROM `eqp_disposal_items` WHERE `eqpd_id` = '$getdispinfo[eqpd_id]'") or die(mysql_error());
						
											if(mysql_num_rows($dispitems) == 0){
												print "<br /><p align=center><i>Something went wrong when retrieving equipment for disposal.</i></p><br />";
											}else{
												
													print "
													<div class='table-responsive'>
														<table class = 'table table-striped table-bordered table-hover display'>
														<thead>
															<tr>
																<th>Unit</th>
																<th>Name</th>
																<th>Property No.</th>
																<th>Issued To</th>
																<th>Department</th>
															</tr>
														</thead>
														<tbody>
													";
												
													while($getdata = mysql_fetch_array($dispitems)){
														$geteqpdetails = mysql_fetch_array(mysql_query("SELECT * FROM `equipments` WHERE `eqp_id` = '$getdata[eqp_id]'"))or die(mysql_error());
														$selitem = mysql_fetch_array (mysql_query("SELECT * FROM `items` WHERE `item_id` = '$geteqpdetails[item_id]'"))or die(mysql_error());
														$selitemunit = mysql_fetch_array (mysql_query("SELECT * FROM `item_unit` WHERE `item_unit_id` = '$geteqpdetails[item_unit_id]'"))or die(mysql_error());
														$getpersonnel = mysql_fetch_array(mysql_query("SELECT pwi.pwi_id, CONCAT(p.personnel_fname,' ',p.personnel_lname) AS full_name, d.dept_name, ps.position_name FROM `personnel_work_info` AS pwi LEFT JOIN personnel AS p ON p.personnel_id = pwi.personnel_id LEFT JOIN department AS d ON d.dept_id = pwi.dept_id LEFT JOIN personnel_position AS ps ON ps.position_id = pwi.position_id WHERE pwi.personnel_id = '$geteqpdetails[received_by]'"))or die(mysql_error());
														
														print "<tr>";
															print "<td style='text-align:center;vertical-align:middle;'><center>".$selitemunit['item_unit_name']."</center></td>";
															print "<td style='text-align:center;vertical-align:middle;'><center><a href='view_eq7.php?id=".$geteqpdetails['eqp_id']."'>".$selitem['item_name'].", ".$geteqpdetails['brand']."</a></center></td>";
															print "<td style='text-align:center;vertical-align:middle;'><center>".$geteqpdetails['prop_num']."</center></td>";
															print "<td style='text-align:center;vertical-align:middle;'><center>".$getpersonnel['full_name']."</center></td>";
															print "<td style='text-align:center;vertical-align:middle;'><center>".$getpersonnel['dept_name']."</center></td>";
														print "</tr>";
													}	
											}
											print "</tbody></table></div>";
						
										?>
										
										</div>
									</div>
									
								</div>
							
							</div>
						</div>
				</div>
			</div>
			
		</div> <!--/.container-fluid-->
	
	</div> <!--/.content-wrapper-->
	
	<?php
	
	function escapeString($str){
		return mysql_real_escape_string($str);
	}
	
	if(isset($_POST['subdisp'])){
		
		$decision = escapeString($_POST['decision']);
		
		mysql_query("UPDATE `eqp_disposal` SET `dispstatus` = '$decision' WHERE `eqpd_id` = '$readdisp_id'");
		
		$eqpdisp = mysql_query("SELECT eqp_id FROM `eqp_disposal_items` WHERE `eqpd_id` = '$getdispinfo[eqpd_id]'") or die(mysql_error());
		
		while($geteqpdisp = mysql_fetch_array($eqpdisp)){
			$geteqp = mysql_fetch_array(mysql_query("SELECT * FROM `equipments` WHERE `eqp_id` = '$geteqpdisp[eqp_id]'"))or die(mysql_error());
			$getdecs = mysql_fetch_array(mysql_query("SELECT `dispstatus` FROM `eqp_disposal` WHERE `eqpd_id` = '$readdisp_id'"))or die(mysql_error());
			
			if($getdecs['dispstatus'] == "For Auction"){
				mysql_query("UPDATE `equipments` SET `remarks` = 'Auctioned' WHERE `eqp_id` = '$geteqpdisp[eqp_id]'");
			
				mysql_query("LOCK TABLE eqp_history WRITE;");
				try{
					mysql_query("INSERT INTO `eqp_history`(
					`eqp_id`, 
					`receivedBy`, 
					`historydate`, 
					`icspar`, 
					`icspar_id`, 
					`remarks`
					) VALUES (
					'$geteqp[eqp_id]',
					'$geteqp[received_by]',
					'$date',
					'DSP',
					'$readdisp_id',
					'Auctioned'
					)")or die(mysql_error());
				}catch(Exception $e){
					mysql_query("ROLLBACK");
					print "<script>alert('Something went wrong when updating items to the System.')</script>";
				}
				mysql_query("UNLOCK TABLE;");
			
			}else if($getdecs['dispstatus'] == "For Disposal"){
				mysql_query("UPDATE `equipments` SET `remarks` = 'Disposed' WHERE `eqp_id` = '$geteqpdisp[eqp_id]'");
				
				mysql_query("LOCK TABLE eqp_history WRITE;");
				try{
					mysql_query("INSERT INTO `eqp_history`(
					`eqp_id`, 
					`receivedBy`, 
					`historydate`, 
					`icspar`, 
					`icspar_id`, 
					`remarks`
					) VALUES (
					'$geteqp[eqp_id]',
					'$geteqp[received_by]',
					'$date',
					'DSP',
					'$readdisp_id',
					'Disposed'
					)")or die(mysql_error());
				}catch(Exception $e){
					mysql_query("ROLLBACK");
					print "<script>alert('Something went wrong when updating items to the System.')</script>";
				}
				mysql_query("UNLOCK TABLE;");
				
			}
		}
		print "<script>alert('Disposal complete.');window.location='view_disposal.php?id=$readdisp_id';</script>";
	}
	
	?>
	
</body>
<!-- /.Body -->

</div> <!-- /.wrapper-->

</html>