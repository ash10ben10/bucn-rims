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
		$personnel = mysql_fetch_array(mysql_query("SELECT * FROM personnel WHERE personnel_id = '".$_SESSION['logged_personnel_id']."' "));
		$pworkinfo = mysql_fetch_array(mysql_query("SELECT * FROM personnel_work_info WHERE personnel_id = '".$_SESSION['logged_personnel_id']."' "));
		$position = mysql_fetch_array(mysql_query("SELECT * FROM personnel_position WHERE position_id = '$pworkinfo[position_id]' "));
		$account = mysql_fetch_array(mysql_query("SELECT * FROM account WHERE personnel_id = '".$_SESSION['logged_personnel_id']."' "));
	}
	
?>
<html lang="en">

<head>
	<!-- Calling Default CSS files -->
	<?php include "../engine/csscalls.php"; ?>
	<!-- Calling Default Javascript files -->
	<?php include  "../engine/jscalls.php"; ?>
	
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
				
				<li class="active">
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
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-collection-text zmdi-hc-lg"></i>&nbsp;&nbsp;Purchase Requests</h1>
					
						<br />
						<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
							<div class="panel-heading">
								<div class="row">
									<div class="col-lg-12">
										<a href="../requests/add_req.php" class="btn btn-default"><i class="fa fa-plus-circle fa-fw"></i>&nbsp;&nbsp;Create Purchase Request</a>
									</div>
								</div>
							</div>
							<div class="panel-body">
							<?php
							
							if($position['position_name'] == "BAC Officer" || $position['position_name'] == "Supply Officer" || $position['position_name'] == "Dean" || $position['position_name'] == "OIC Dean"){
								$pr = mysql_query("SELECT * FROM purchase_request ORDER BY prnum DESC");
							}else{
								$pr = mysql_query("SELECT * FROM purchase_request WHERE personnel_id = '".$_SESSION['logged_personnel_id']."' ORDER BY prnum DESC");
							}
							
								if(mysql_num_rows($pr) == 0){
									print "<br /><p align=center><i>There are no available Personnel Records on the system. Please add one.</i></p><br />";
								}else{
									print "
									<div class='table-responsive'>
										<table class = 'table table-striped table-bordered table-hover display'>
										<thead>
											<tr>
												<th>Request Date</th>
												<th>PR Number</th>
												<th>Purpose</th>";
										
										if($position['position_name'] == "BAC Officer" || $position['position_name'] == "Supply Officer" || $position['position_name'] == "Dean" || $position['position_name'] == "OIC Dean"){
											print "<th>Requestor</th>";
										}else{
											print "";
										}
												
									print "
												<th>Status</th>
												<th>Option</th>
											</tr>
										</thead>
										<tbody>
									";
									
									while($getdata = mysql_fetch_array($pr)){
										$getpersonnel = mysql_fetch_array (mysql_query("SELECT * FROM personnel WHERE personnel_id = $getdata[personnel_id]"));
									
										print "<tr>";
										print "<td style='text-align:center;vertical-align:middle;'>";
										print date("M j, Y", strtotime($getdata['prdate']));
										print "</td>";
										print "<td style='text-align:center;vertical-align:middle;'>"?><a href="view_pr.php?id=<?php echo $getdata['pr_id'];?>"><?php print $getdata['prnum'];?></a><?php print "</td>";
										/* print "<td><select class='form-control' name='status".$status['request_status_name']."' id='status".$status['request_status_name']."' required>";
											if($status['request_status_name'] == "Pending"){
												print "<option selected disabled>Pending</option>";
												print "<option>Approve</option>";
												print "<option>Disapprove</option>";
											}else if($status['request_status_name'] == "Approved"){
												print "<option selected disabled>Approved</option>";
												print "<option>Disapprove</option>";
											}else if($status['request_status_name'] == "Disapproved"){
												print "<option selected disabled>Disapproved</option>";
												print "<option>Approve</option>";
											}
										print "</selected></td>"; */
										print "<td style='text-align:center;vertical-align:middle;'>".$getdata['purpose']."</td>";
										
										if($position['position_name'] == "BAC Officer" || $position['position_name'] == "Supply Officer" || $position['position_name'] == "Dean"){
											print "<td style='text-align:center;vertical-align:middle;'>".$getpersonnel['personnel_lname'].', '.$getpersonnel['personnel_fname'].' '.$getpersonnel['personnel_mname']."</td>";
										}else{
											print "";
										}
										
										print "<td style='text-align:center;vertical-align:middle;'>";
										$reqitems = mysql_fetch_array(mysql_query("SELECT count(*) FROM request_items WHERE pr_id = '$getdata[pr_id]'"));
										$appreqitems = mysql_fetch_array(mysql_query("SELECT count(*) FROM request_items WHERE pr_id = '$getdata[pr_id]' AND pr_status = 'approved'"));
										
										print $appreqitems[0]." of ".$reqitems[0]." items has been approved.";
										
										print "</td>";
										
										print "<td style='text-align:center;vertical-align:middle;'><center>";?><a target="_blank" href="print_pr.php?id=<?php echo $getdata['pr_id']; ?>"><button type="button" class="btn btn-primary" title="Print Purchase Request." style="margin: 0 0 3px 0;"><i class="fa fa-print fa-fw"></i>&nbsp;Print</button></a><?php print "</center></td>";
										
										//print "<td style='text-align:center;vertical-align:middle;'>".$getstatus['remarks']."</td>";
										//print "<td style='text-align:center;vertical-align:middle;'><a href='#' onClick='delete_req(".$getdata['pr_id'].")' class='btn btn-warning' title='Delete Request'><span class='zmdi zmdi-delete zmdi-hc-lg'></span>&nbsp;&nbsp;Delete</a></td>";
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