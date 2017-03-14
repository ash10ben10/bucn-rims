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
		$personnel = mysql_fetch_array(mysql_query("SELECT * FROM personnel WHERE personnel_id = '".$_SESSION['logged_personnel_id']."' ")) ;
		$pworkinfo = mysql_fetch_array(mysql_query("SELECT * FROM personnel_work_info WHERE personnel_id = '".$_SESSION['logged_personnel_id']."' ")) ;
		$position = mysql_fetch_array(mysql_query("SELECT * FROM personnel_position WHERE position_id = '$pworkinfo[position_id]' ")) ;
		$account = mysql_fetch_array(mysql_query("SELECT * FROM account WHERE personnel_id = '".$_SESSION['logged_personnel_id']."' ")) ;
	}
?>
<html lang="en">

<head>
	<!-- Calling Default CSS files -->
	<?php include "../engine/csscalls.php"; ?>
	<!-- Calling Default Javascript files -->
	<?php include  "../engine/jscalls.php"; ?>
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
			
				<li class="active">
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
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-upload zmdi-hc-lg"></i>&nbsp;&nbsp;Issued Equipment</h1>
					
						<br />
						<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
							<!--<div class="panel-heading">
								<div class="row" style="margin-bottom:-10px;">
										<div class="col-md-12" align="left" style="margin-bottom:10px;">
											<a target="_blank" href="print_physicalcount.php"><button type="button" class="btn btn-primary"><i class="fa fa-print fa-fw"></i>&nbsp;Print</button></a>
											&nbsp;
										</div>
								</div>
							</div>-->
							<div class="panel-body">
							
								<?php 
								
									if($position['position_name'] == "Supply Officer"){
										$showequip = mysql_query("SELECT * FROM `equipments` WHERE remarks REGEXP 'Working|Under Maintenance|Subject for New Repair|Subject for Disposal' ORDER BY eqpdate DESC");
									}else{
										$showequip = mysql_query("SELECT * FROM `equipments` WHERE received_by = '".$_SESSION['logged_personnel_id']."' AND remarks REGEXP 'Working|Under Maintenance|Subject for New Repair|Subject for Disposal' ORDER BY eqpdate DESC");
									}
									
				
									if(mysql_num_rows($showequip) == 0){
										print "<br /><p align=center><i>There are no issued Equipment at this moment.</i></p><br />";
									}else{
										
											print "
											<div class='table-responsive'>
												<table class = 'table table-striped table-bordered table-hover display'>
												<thead>
													<tr>
														<th>Unit</th>
														<th>Equipment Name</th>";
											
											if($position['position_name'] == "Supply Officer"){
												print "<th>Issued to</th>";
											}else{
												print "";
											}
											
											print "			
														<th>Amount</th>
														<th>Property Number</th>
														<th>Form</th>
														<th>Remarks</th>
													</tr>
												</thead>
												<tbody>
											";
										
											while($getdata = mysql_fetch_array($showequip)){
												$getitem = mysql_fetch_array(mysql_query("SELECT * FROM items WHERE item_id = $getdata[item_id]")) ;	
												$getunit = mysql_fetch_array(mysql_query("SELECT * FROM item_unit WHERE item_unit_id = $getdata[item_unit_id]")) ;
												$getpersonnel = mysql_fetch_array (mysql_query("SELECT CONCAT (personnel_fname,' ',personnel_lname) AS full_name FROM personnel WHERE personnel_id = $getdata[received_by]"));
												
												print "<tr>";
													print "<td style='text-align:center;vertical-align:middle;'><center>".$getunit['item_unit_name']."</center></td>";
													print "<td style='text-align:center;vertical-align:middle;'><center>";
													?>
													<a href="view_eq.php?id=<?php echo $getdata['eqp_id']; ?>"><?php print $getitem['item_name'].", ".$getdata['brand'];?></a>
													<?php
													print "</center></td>";
													
													if($position['position_name'] == "Supply Officer"){
														print "<td style='text-align:center;vertical-align:middle;'><center>".$getpersonnel['full_name']."</center></td>";
													}else{
														print "";
													}
													
													print "<td style='text-align:center;vertical-align:middle;'><center>Php ".number_format($getdata['unit_value'], 2,'.',',')."</center></td>";
													print "<td style='text-align:center;vertical-align:middle;'><center>".$getdata['prop_num']."</center></td>";
													
													
													print "<td style='text-align:center;vertical-align:middle;'><center>";
													
													if($getdata['icspar'] == "ICS"){
														$getics = mysql_fetch_array(mysql_query("SELECT * FROM `eqp_ics` WHERE ics_id = '$getdata[ics_par_id]'"));
														?>
														<a href="view_ics3.php?id=<?php print $getdata['ics_par_id']; ?>"><?php print $getics['icsnum'];?></a>
														<?php
													}else if($getdata['icspar'] == "PAR"){
														$getpar = mysql_fetch_array(mysql_query("SELECT * FROM `eqp_par` WHERE par_id = '$getdata[ics_par_id]'"));
														?>
														<a href="view_par3.php?id=<?php print $getdata['ics_par_id']; ?>"><?php print $getpar['parnum'];?></a>
														<?php
													}else if($getdata['icspar'] == "TO"){
														$getTo = mysql_fetch_array(mysql_query("SELECT * FROM `eqp_turnover` WHERE to_id = '$getdata[ics_par_id]'"));
														?>
														<a href="view_to.php?id=<?php print $getdata['ics_par_id']; ?>"><?php print $getTo['tonum'];?></a>
														<?php
													}
													
													print "</center></td>";
													
													print "<td style='text-align:center;vertical-align:middle;'><center>";
													
													//$history = mysql_fetch_array(mysql_query("SELECT * FROM `eqp_history` WHERE `eqp_id` = '$getdata[eqp_id]'"));
													
													if($getdata['remarks'] == "Working"){
														print "Active";
													}else{
														print $getdata['remarks'];
													}
													print "</center></td>";
													
												print "</tr>";
											}	
									}
									print "</tbody></table></div>";
				
								?>
							
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