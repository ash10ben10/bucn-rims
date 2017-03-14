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
			
				<li>
					<a href="eq_issued.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-upload zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Issued Equipment</a>
				</li>
				<?php
				if($position['position_name'] == "Supply Officer"){
					?>
					<li>
						<a href="eq_disposal.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-delete zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Equipment Disposal</a>
					</li>
					<li class="active">
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
					<h1 style="font-family: Calibri;">&nbsp;<i class="glyphicon glyphicon-share-alt"></i>&nbsp;&nbsp;Equipment Turn-over</h1>
					
						<br />
						<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
							<div class="panel-heading">
								<div class="row" style="margin-bottom:-10px;">
									<div class="col-lg-2" align="left" style="margin:18px 0 0 5px;">
									Group Department by:
									</div>
									<div class="col-lg-4" align="left" style="margin:10px 0 0 0;">
										<form method="post">
											<select class="selectpicker form-control" name="groupdept" id="groupdept" data-hide-disabled="true" data-live-search="true" onChange="this.form.submit()">
												<?php
												$query = mysql_query("SELECT dept_id, dept_name FROM `department`");
												echo "<option disabled selected>-Select Department-</option>";
												echo "<option value='0'>All Department</option>";
												while($row = mysql_fetch_array($query)){
													echo "<option value='".$row['dept_id']."'>".ucfirst($row['dept_name'])."</option>";
												}
												?>
											</select>
										</form>
										&nbsp;
									</div>
								</div>
							</div>
							<div class="panel-body">
							
								<?php 
									if(isset($_POST['groupdept'])){
										if($_POST['groupdept'] == 0){
											$showpersonnel = mysql_query("SELECT pwi.pwi_id, pwi.position_id, pwi.personnel_id, CONCAT (p.personnel_fname,' ',p.personnel_lname) AS full_name, d.dept_name FROM `personnel_work_info` AS pwi LEFT JOIN personnel AS p ON p.personnel_id = pwi.personnel_id LEFT JOIN department AS d ON d.dept_id = pwi.dept_id ORDER BY p.personnel_lname ASC")or die(mysql_error());
											$deptname = "All Department";
										}else{
											$showpersonnel = mysql_query("SELECT pwi.pwi_id, pwi.position_id, pwi.personnel_id, CONCAT (p.personnel_fname,' ',p.personnel_lname) AS full_name, d.dept_name FROM `personnel_work_info` AS pwi LEFT JOIN personnel AS p ON p.personnel_id = pwi.personnel_id LEFT JOIN department AS d ON d.dept_id = pwi.dept_id WHERE pwi.dept_id = '".$_POST['groupdept']."' ORDER BY p.personnel_lname ASC")or die(mysql_error());
											$getdept = mysql_fetch_array(mysql_query("SELECT dept_name FROM `department` WHERE `dept_id` = '".$_POST['groupdept']."'"))or die(mysql_error());
											$deptname = $getdept['dept_name'];
										}
									}else{
										$showpersonnel = mysql_query("SELECT pwi.pwi_id, pwi.position_id, pwi.personnel_id, CONCAT (p.personnel_fname,' ',p.personnel_lname) AS full_name, d.dept_name FROM `personnel_work_info` AS pwi LEFT JOIN personnel AS p ON p.personnel_id = pwi.personnel_id LEFT JOIN department AS d ON d.dept_id = pwi.dept_id ORDER BY p.personnel_lname ASC")or die(mysql_error());
										$deptname = "All Department";
									}
									
									if(mysql_num_rows($showpersonnel) == 0){
											print "<br /><p align=center><i>There are no Personnels who are in this department.</i></p><br />";
									}else{
										
											print "
											<div class='table-responsive'>
												<table class = 'table table-striped table-bordered table-hover display'>
												<thead>
													<tr>
														<td colspan='4' align='center'>
														<strong>".$deptname."</strong>
														</td>
													</tr>
													<tr>
														<th>Personnel</th>
														<th>Position</th>
														<th>Acknowledged Items</th>
														<th>Option</th>
													</tr>
												</thead>
												<tbody>
											";
										
											while($getpersonnel = mysql_fetch_array($showpersonnel)){
												$position = mysql_fetch_array(mysql_query("SELECT `position_name` FROM `personnel_position` WHERE `position_id` = '$getpersonnel[position_id]'"))or die(mysql_error());
												
												print "<tr>";
													print "<td style='text-align:center;vertical-align:middle;'><center>".$getpersonnel['full_name']."</center></td>";
													print "<td style='text-align:center;vertical-align:middle;'><center>".$position['position_name']."</center></td>";
													
													
													$countack = mysql_query("SELECT * FROM `equipments` WHERE `received_by` = '$getpersonnel[personnel_id]' AND remarks = 'Working'")or die(mysql_error());
													
													if(mysql_num_rows($countack) == 0){
														print "<td style='text-align:center;vertical-align:middle;'><center>";
														print "No items.";
														print "</center></td>";
														print "<td style='text-align:center;vertical-align:middle;'><center>";
														?>
														<button class="btn btn-default" title="This personnel has no acknowledged items." disabled><i class="zmdi zmdi-folder"></i>&nbsp;&nbsp;View Items</button>
														<?php
													print "</center></td>";
													}else if(mysql_num_rows($countack) == 1){
														print "<td style='text-align:center;vertical-align:middle;'><center>";
														print mysql_num_rows($countack)." Item";
														print "</center></td>";
														print "<td style='text-align:center;vertical-align:middle;'><center>";
														?>
														<a href="eq_viewitems.php?id=<?php print $getpersonnel['personnel_id']; ?>" class="btn btn-default" title="This personnel has acknowledged items."><i class="zmdi zmdi-folder"></i>&nbsp;&nbsp;View Items</a>
														<?php
														print "</center></td>";
													}else{
														print "<td style='text-align:center;vertical-align:middle;'><center>";
														print mysql_num_rows($countack)." Items";
														print "</center></td>";
														print "<td style='text-align:center;vertical-align:middle;'><center>";
														?>
														<a href="eq_viewitems.php?id=<?php print $getpersonnel['personnel_id']; ?>" class="btn btn-default" title="This personnel has acknowledged items."><i class="zmdi zmdi-folder"></i>&nbsp;&nbsp;View Items</a>
														<?php
														print "</center></td>";
													}
												print "</tr>";
											}	
											print "</tbody></table></div>";
										}
				
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