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
	<script>
	
	$(document).ready(function(){
		
		$("#submitsort").click(function(event){
			var isOk = true;
				var msg = "Please fill in the forms.";
				
				var $invyear = $("#invyear").val();
				if($invyear == 0 || $invyear == "" || $invyear == null){
					isOk = false;
					msg;
				}
				
				var $invmonth = $("#invmonth").val();
				if($invmonth == 0 || $invmonth == "" || $invmonth == null){
					isOk = false;
					msg;
				}
				
				var $eqpdept = $("#eqpdept").val();
				if($eqpdept == 0 || $eqpdept == "" || $eqpdept == null){
					isOk = false;
					msg;
				}
				
				if(!isOk){
					alert(msg);
					return false;
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
					<li class="active">
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
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-print zmdi-hc-lg"></i>&nbsp;&nbsp;Equipment Report</h1>
					
						<br />
						<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
							<div class="panel-heading">
								<form role="form" id="selreport" method="POST" name="contentForm" enctype="multipart/form-data" onsubmit="submitform(event);">
								<div class="row" style="margin:10px 0 10px 0;">
									<div class="col-lg-12">
										<div class="col-lg-3" align="left" style="margin-bottom:10px;">
											<label>Select Year:</label>
											<select name="invyear" id="invyear" class="selectpicker form-control" data-hide-disabled="true" data-live-search="true" >
												<option value="" selected disabled>- Select Year -</option>
												<?php 
													date_default_timezone_get("Asia/Manila");
													$date = date("Y");

													for($i=2016;$i<=$date;$i++){
														echo "<option value=".$i.">".$i."</option>";
													}
												?>
											</select>
										</div>
										<div class="col-lg-3" align="left" style="margin-bottom:10px;">
											<label>Select Month:</label>
											<select name="invmonth" id="invmonth" class="selectpicker form-control" data-hide-disabled="true" data-live-search="true" >
												<option value="" selected disabled>- Select Month -</option>
												<option value="13">All Month</option>
												<option value="1">January</option>
												<option value="2">February</option>
												<option value="3">March</option>
												<option value="4">April</option>
												<option value="5">May</option>
												<option value="6">June</option>
												<option value="7">July</option>
												<option value="8">August</option>
												<option value="9">September</option>
												<option value="10">October</option>
												<option value="11">November</option>
												<option value="12">December</option>
											</select>
										</div>
										<div class="col-lg-3" align="left" style="margin-bottom:10px;">
											<label>Select Department:</label>
											<select name="eqpdept" id="eqpdept" class="selectpicker form-control" data-hide-disabled="true" data-live-search="true" required >
												<?php 
													$query = mysql_query("SELECT dept_id, dept_name FROM `department`");
													echo "<option selected disabled>-Select Department-</option>";
													echo "<option value='all'>All Department</option>";
													while($row = mysql_fetch_array($query)){
														print "<option value='".$row['dept_id']."'>".ucfirst($row['dept_name'])."</option>";
													}
												?>
											</select>
										</div>
										<div class="col-lg-1" align="left" style="margin: 25px 0 0 0;">
											<button name="submitsort" id="submitsort" class="btn btn-success" ><span class="fa fa-arrow-right"></span>&nbsp;&nbsp;View</button>
										</div>
										<div class="col-lg-1" align="left" style="margin: 25px 0 0 0;">
										<a target="_blank" href="print_physicalcount.php"><button type="button" class="btn btn-primary"><i class="fa fa-print fa-fw"></i>&nbsp;Print All</button></a>
										</div>
									</div>
								</div>
								</form>
							</div>
							<div class="panel-body">
							
							
								
							<?php
							
							if(isset($_POST['submitsort'])){
								if($_POST['eqpdept'] == "all"){
									if($_POST['invmonth'] == 13){
										$geteqps = mysql_query("SELECT * FROM `equipments` WHERE YEAR(eqpdate) = '".$_POST['invyear']."' AND remarks = 'Working' ORDER BY date_acquired DESC");
									}else{
										$geteqps = mysql_query("SELECT * FROM `equipments` WHERE YEAR(eqpdate) = '".$_POST['invyear']."' AND MONTH(eqpdate) = '".$_POST['invmonth']."' AND remarks = 'Working' ORDER BY date_acquired DESC");
									}
								}else{
									if($_POST['invmonth'] == 13){
										$geteqps = mysql_query("SELECT * FROM `equipments` WHERE YEAR(eqpdate) = '".$_POST['invyear']."' AND dept_id = '".$_POST['eqpdept']."' AND remarks = 'Working' ORDER BY date_acquired DESC");
									}else{
										$geteqps = mysql_query("SELECT * FROM `equipments` WHERE YEAR(eqpdate) = '".$_POST['invyear']."' AND MONTH(eqpdate) = '".$_POST['invmonth']."' AND dept_id = '".$_POST['eqpdept']."' AND remarks = 'Working' ORDER BY date_acquired DESC");
									}
								}
								
								
								
								if(mysql_num_rows($geteqps) == 0){
									print "<br /><p align=center><i>There are no Equipment at this date or department.</i></p><br />";
								}else{
									print "
									<div class='table-responsive'>
										<table class='table table-striped table-bordered table-hover'>
										
											<tbody>
												<tr>
													<td style='font-size:16px;' align='center'>
													<strong>REPORT ON THE PHYSICAL COUNT OF PROPERTY, PLANT AND EQUIPMENT</strong>
													<br />
													as of ";
													if($_POST['invmonth'] == 13){
														print $_POST['invyear'];
													}else{
														if($_POST['invmonth'] == 1){
															print "January";
														}else if($_POST['invmonth'] == 2){
															print "February";
														}else if($_POST['invmonth'] == 3){
															print "March";
														}else if($_POST['invmonth'] == 4){
															print "April";
														}else if($_POST['invmonth'] == 5){
															print "May";
														}else if($_POST['invmonth'] == 6){
															print "June";
														}else if($_POST['invmonth'] == 7){
															print "July";
														}else if($_POST['invmonth'] == 8){
															print "August";
														}else if($_POST['invmonth'] == 9){
															print "September";
														}else if($_POST['invmonth'] == 10){
															print "October";
														}else if($_POST['invmonth'] == 11){
															print "November";
														}else if($_POST['invmonth'] == 12){
															print "December";
														}
														print ", ".$_POST['invyear'];
													}
													print "
													</td>
												</tr>
												<tr>
													<td style='font-size:16px;'><i>
													";
													if($_POST['eqpdept'] == "all"){
														print "&nbsp;All Offices";
													}else{
														$dept = mysql_fetch_array(mysql_query("SELECT dept_name FROM department WHERE dept_id = '".$_POST['eqpdept']."'"));
													print "&nbsp;".$dept['dept_name'];
													}
												print "
													</i></td>
												</tr>
												<tr>
													<td>
														<br/>
														<div class='col-lg-12'>
											<table class = 'table table-striped table-bordered table-hover display'>
											<thead>
												<tr>
													<th>Article</th>
													<th>Description</th>
													<th>Date Acquired</th>
													<th>Property No.</th>
													<th>Unit</th>
													<th>Unit Value</th>
													<th>Issued To</th>
												</tr>
											</thead>
											<tbody>
									";
									
									while($eqpdata = mysql_fetch_array($geteqps)){
										$items = mysql_fetch_array(mysql_query("SELECT * FROM `items` WHERE `item_id` = '$eqpdata[item_id]'"));
										$itemunit = mysql_fetch_array(mysql_query("SELECT * FROM `item_unit` WHERE `item_unit_id` = '$eqpdata[item_unit_id]'"));
										$getpersonnel = mysql_fetch_array(mysql_query("SELECT pwi.pwi_id, CONCAT(p.personnel_fname,' ',p.personnel_lname) AS full_name, d.dept_name, ps.position_name FROM `personnel_work_info` AS pwi LEFT JOIN personnel AS p ON p.personnel_id = pwi.personnel_id LEFT JOIN department AS d ON d.dept_id = pwi.dept_id LEFT JOIN personnel_position AS ps ON ps.position_id = pwi.position_id WHERE pwi.personnel_id = '$eqpdata[received_by]'"));
										
									
										print "<tr>";
										print "<td style='text-align:left;vertical-align:middle;'><center>".$items['item_name']."</center></td>";
										print "<td style='text-align:left;vertical-align:middle;'><center>".$eqpdata['brand'].", ".$eqpdata['description']."</center></td>";
										print "<td style='text-align:left;vertical-align:middle;'><center>".date("M d, Y", strtotime($eqpdata['date_acquired']))."</center></td>";
										print "<td style='text-align:left;vertical-align:middle;'><center>".$eqpdata['prop_num']."</center></td>";
										print "<td style='text-align:left;vertical-align:middle;'><center>".$itemunit['item_unit_name']."</center></td>";
										print "<td style='text-align:left;vertical-align:middle;'><center>".number_format($eqpdata['unit_value'], 2,'.',',')."</center></td>";
										print "<td style='text-align:left;vertical-align:middle;'><center>".$getpersonnel['full_name']."</center></td>";
										print "</tr>";
									
									}
									print "</tbody></table></div>";
									?>
									<br/>
									<div class="row">
										<div class="col-lg-12" align="left" style="margin:0px 0 10px 15px;">
										<?php
										
										if($_POST['eqpdept'] == "all"){
											if($_POST['invmonth'] == 13){
												?>
												<a target="_blank" href="print_physicalcountbydeptallmonth.php?year=<?php echo $_POST['invyear']; ?>"><button type="button" class="btn btn-primary"><i class="fa fa-print fa-fw"></i>&nbsp;Print</button></a>
												<?php
											}else{
												?>
												<a target="_blank" href="print_physicalcountbydeptselmonth.php?year=<?php echo $_POST['invyear'];?>&month=<?php print $_POST['invmonth']; ?>"><button type="button" class="btn btn-primary"><i class="fa fa-print fa-fw"></i>&nbsp;Print</button></a>
												<?php
											}
											
										}else{
											if($_POST['invmonth'] == 13){
												?>
												<a target="_blank" href="print_physicalcountbyseldeptallmonth.php?year=<?php echo $_POST['invyear']; ?>&dept=<?php echo $_POST['eqpdept'];?>"><button type="button" class="btn btn-primary"><i class="fa fa-print fa-fw"></i>&nbsp;Print</button></a>
												<?php
											}else{
												?>
												<a target="_blank" href="print_physicalcountbyseldeptselmonth.php?year=<?php echo $_POST['invyear'];?>&month=<?php print $_POST['invmonth']; ?>&dept=<?php echo $_POST['eqpdept'];?>"><button type="button" class="btn btn-primary"><i class="fa fa-print fa-fw"></i>&nbsp;Print</button></a>
												<?php
											}
											
										}
										
										?>
											
										</div>
									</div>
									<?php
								}
								print "
									</td>
									</tr>
									</tbody>
									</table>
								</div>";
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