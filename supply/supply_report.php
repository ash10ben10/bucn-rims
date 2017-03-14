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
	
	$cTab = (isset($_GET['cTab'])) ? $_GET['cTab'] : "i";
	
	if(isset($_POST['submitsort'])){
		$cTab = (isset($_GET['cTab'])) ? $_GET['cTab'] : "i";
	}else if(isset($_POST['submitfilter'])){
		$cTab = (isset($_GET['cTab'])) ? $_GET['cTab'] : "s";
	}
	
?>
<html lang="en">

<head>
	<!-- Calling Default CSS files -->
	<?php include "../engine/csscalls.php"; ?>
	<!-- Calling Default Javascript files -->
	<?php include "../engine/jscalls.php"; ?>
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
			
			<?php 
			if($position['position_name'] == "Supply Officer"){
				?>
				<li>
					<a href="sup_list.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-store zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Supply Stocks</a>
				</li>
				<?php
			}else{
				print "";
			}
			?>
			<li>
				<a href="stock_req.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-shopping-cart zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Stock Requests</a>
			</li>
			<?php 
			if($position['position_name'] == "Supply Officer"){
				?>
				<li>
					<a href="supply_label.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-label zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Supply Labels</a>
				</li>
				<li>
					<a href="supply_specs.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-widgets zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Supply Descs</a>
				</li>
				<li class="active">
					<a href="supply_report.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-file-text zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Reports</a>
				</li>
				<?php
			}else{
				print "";
			}
			?>
					
		</ul> <!--/.Inside Sidebar -->
	</div><!--/.Sidebar -->

	<!-- Navigation -->
	<?php include "../supply/sup_header.php"; ?>
	<!--/.Navigation -->
	
<!-- /.Header and Sidebar Page -->
	
<!-- Body will contain the Page Contents -->

<body>
	<!-- Content-Wrapper -->
	<div id="content-wrapper">
		
		<div class="container-fluid">
			
			<div class="row" style="margin-top:-20px;">
				<div class="col-lg-12">
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-print zmdi-hc-lg"></i>&nbsp;&nbsp;Inventory Report</h1>
						
						<br />
						<ul class="nav nav-tabs">
							<li <?php if($cTab ==  "i") echo "class='active'";  ?>>
								<a href="#issued" data-toggle="tab" class="nav-tab-pane" alt="i"><i class="zmdi zmdi-share zmdi-hc-1x"></i>&nbsp;&nbsp;Issuance Reports</a>
							</li>
							<li <?php if($cTab ==  "s") echo "class='active'"; ?>>
								<a href="#status" data-toggle="tab" class="nav-tab-pane" alt="s"><i class="zmdi zmdi-desktop-windows zmdi-hc-1x"></i>&nbsp;&nbsp;Status Reports</a>
							</li>
						</ul>
						<br />
						
						<div class="tab-content">
							<div class="tab-pane fade in <?php if($cTab ==  "i") echo "active"; ?>" id="issued">
							
								<div class="panel panel-default" div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
									<div class="panel-heading">
										<form role="form" id="selreportissued" method="POST" name="contentForm" enctype="multipart/form-data">
										<div class="row" style="margin:10px 0 10px 0;">
											<div class="col-lg-12">
												<div class="col-lg-3" align="left">
													<label>Select Year:</label>
													<select name="invyear" id="invyear" class="selectpicker form-control" data-hide-disabled="true" data-live-search="true" required >
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
												<div class="col-lg-3" align="left">
													<label>Select Month:</label>
													<select name="invmonth" id="invmonth" class="selectpicker form-control" data-hide-disabled="true" data-live-search="true" required >
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
												<!--<div class="col-lg-3" align="left">
													<label>Filter stocks:</label>
													<select name="filstock" id="filstock" class="selectpicker form-control" data-hide-disabled="true" data-live-search="true" required >
														<option value="" selected disabled>- Select -</option>
														<option value="all">All Stocks</option>
														<option value="sa">Stocks Available</option>
														<option value="cls">Critical Level Stocks</option>
													</select>
												</div>-->
												<div class="col-lg-1" align="left" style="margin: 25px 0 0 0;">
													<button name="submitsort" id="submitsort" class="btn btn-success" ><span class="fa fa-arrow-right"></span>&nbsp;&nbsp;View</button>
												</div>
											</div>
										</div>
										</form>
									</div>
									<div class="panel-body">
									
										<?php
										
										if(isset($_POST['submitsort'])){
											
											if($_POST['invmonth'] == 13){
												$getdates = mysql_query("SELECT * FROM stock_card WHERE YEAR(recdate) = '".$_POST['invyear']."' AND issue_qty != '0'");
											}else{
												$getdates = mysql_query("SELECT * FROM stock_card WHERE YEAR(recdate) = '".$_POST['invyear']."' AND MONTH(recdate) = '".$_POST['invmonth']."' AND issue_qty != '0'");
											}
											
											if(mysql_num_rows($getdates) == 0){
												print "<br /><p align=center><i>There are no issued items.</i></p><br />";
											}else{
												print "
												
												<div class='table-responsive'>
												<table class='table table-striped table-bordered table-hover'>
												
													<tbody>
														<tr>
															<td style='font-size:16px;'><center>
															<strong>REPORT ON ISSUED INVENTORY SUPPLIES</strong>
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
															</center></td>
														</tr>
														<tr>
															<td>
																<br/>
																<div class='col-lg-12'>
														<table class = 'table table-striped table-bordered table-hover display'>
														<thead>
															<tr>
																<th rowspan='2'>Date</th>
																<th colspan='2'>Stock</th>
																<th colspan='3'>Issuance</th>
																<th rowspan='2'>Category</th>
															</tr>
															<tr>
																
																<th>Stock No.</th>
																<th>Description</th>
																<th>Issued To</th>
																<th>Qty Issued</th>
																<th>Department</th>
																
															</tr>
														</thead>
														<tbody>
												";
												
												while($retdata = mysql_fetch_array($getdates)){
													$stockitems = mysql_fetch_array(mysql_query("SELECT si.*, su.* FROM stock_units AS su LEFT JOIN stock_items AS si ON si.stock_id = su.stock_id WHERE su.su_id = '$retdata[su_id]'"));
													$items = mysql_fetch_array(mysql_query("SELECT * FROM `items` WHERE `item_id` = '$stockitems[item_id]'"));
													$itemunit = mysql_fetch_array(mysql_query("SELECT * FROM `item_unit` WHERE `item_unit_id` = '$stockitems[item_unit_id]'"));
													$cat = mysql_fetch_array(mysql_query("SELECT i.item_id, cat.category_name FROM items AS i LEFT JOIN category AS cat ON cat.category_id = i.category_id WHERE i.item_id = '$stockitems[item_id]'"));
													$getpersonnel = mysql_fetch_array(mysql_query("SELECT pwi.pwi_id, CONCAT(p.personnel_fname,' ',p.personnel_lname) AS full_name, d.dept_name, ps.position_name FROM `personnel_work_info` AS pwi LEFT JOIN personnel AS p ON p.personnel_id = pwi.personnel_id LEFT JOIN department AS d ON d.dept_id = pwi.dept_id LEFT JOIN personnel_position AS ps ON ps.position_id = pwi.position_id WHERE pwi.personnel_id = '$retdata[personnel_id]'"))or die(mysql_error());
													
													print "<tr>";
													print "<td style='text-align:left;vertical-align:middle;'><center>".date("M d, Y", strtotime($retdata['recdate']))."</center></td>";
													print "<td style='text-align:left;vertical-align:middle;'><center>".$stockitems['stock_no']."</center></td>";
													print "<td style='text-align:left;vertical-align:middle;'><center>".$items['item_name'].", ".$stockitems['description']."</center></td>";
													print "<td style='text-align:left;vertical-align:middle;'><center>".$getpersonnel['full_name']."</center></td>";
													print "<td style='text-align:left;vertical-align:middle;'><center>".$retdata['issue_qty']." ".$itemunit['item_unit_name']."</center></td>";
													print "<td style='text-align:left;vertical-align:middle;'><center>".$getpersonnel['dept_name']."</center></td>";
													print "<td style='text-align:left;vertical-align:middle;'><center>".$cat['category_name']."</center></td>";
													print "</tr>";
												}
												print "</tbody></table></div>";
												?>
												<br/>
												<div class="row">
													<div class="col-lg-12" align="left" style="margin:0px 0 10px 15px;">
													<?php 
													if($_POST['invmonth'] == 13){
														?>
														<a target="_blank" href="print_supplyreportallmonth.php?year=<?php echo $_POST['invyear'];?>"><button type="button" class="btn btn-primary"><i class="fa fa-print fa-fw"></i>&nbsp;Print</button></a>
														<?php
													}else{
														?>
														<a target="_blank" href="print_supplyreport.php?year=<?php echo $_POST['invyear'];?>&month=<?php print $_POST['invmonth']; ?>"><button type="button" class="btn btn-primary"><i class="fa fa-print fa-fw"></i>&nbsp;Print</button></a>
														<?php
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
							
							<div class="tab-pane fade in <?php if($cTab ==  "s") echo "active"; ?>" id="status">
								<?php include "report_stat.php"; ?>
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