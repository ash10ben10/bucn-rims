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
				<li class="active">
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
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-dropbox zmdi-hc-1x"></i>&nbsp;&nbsp;Issuance</h1>
					
						<br />
						<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
							
							<div class="panel-body">
							
								<?php
								
									if($position['position_name'] == "Supply Officer"){
										$getpr = mysql_query("SELECT * FROM purchase_request WHERE iar_stat REGEXP 'Completed|Done' ORDER BY prdate DESC")or die(mysql_error());
									}else{
										$getpr = mysql_query("SELECT * FROM purchase_request WHERE iar_stat REGEXP 'Completed|Done' AND personnel_id = '".$_SESSION['logged_personnel_id']."' ORDER BY prdate DESC")or die(mysql_error());
									}
									
									if(mysql_num_rows($getpr) == 0){
										print "<br /><p align=center><i>There are no Inspection and Acceptance Report from requests.</i></p><br />";
									}else{
										print "
										<div class='table-responsive'>
											<table class = 'table table-striped table-bordered table-hover display'>
											<thead>
												<tr>
													<th>Request Date</th>
													<th>PR Number</th>
													<th>Requestor</th>
													<th>Issuance</th>
												</tr>
											</thead>
											<tbody>
										";
										
										while($getdata = mysql_fetch_array($getpr)){
											$getpersonnel = mysql_fetch_array (mysql_query("SELECT * FROM personnel WHERE personnel_id = $getdata[personnel_id]"))or die(mysql_error());
											
											print "<tr>";
												print "<td style='text-align:center;vertical-align:middle;'>";
												print date("M j, Y", strtotime($getdata['prdate']));
												print "</td>";
												print "<td style='text-align:center;vertical-align:middle;'>"?><a href="view_pr2.php?id=<?php echo $getdata['pr_id'];?>"><?php print $getdata['prnum'];?></a><?php print "</td>";
												print "<td style='text-align:center;vertical-align:middle;'>".$getpersonnel['personnel_lname'].', '.$getpersonnel['personnel_fname'].' '.$getpersonnel['personnel_mname']."</td>";
												print "<td style='text-align:center;vertical-align:middle;'>";
												
												$getris = mysql_query("SELECT ris_id, risnum FROM request_issue_slip WHERE pr_id = $getdata[pr_id]")or die(mysql_error());
												
												if(mysql_num_rows($getris) == 0){
													if($position['position_name'] == "Supply Officer"){
														?>
															<a href="add_ris.php?id=<?php echo $getdata['pr_id']; ?>" class="btn btn-default" title="Issue the requested items to the requestor."><i class="fa fa-plus-circle fa-fw"></i>&nbsp;&nbsp;Create Issuance</a>
														<?php
													}else{
														print "Creating Issuance";
													}
													
													
												}else{
													while($risid = mysql_fetch_array($getris)){
														?>
													<a href="view_ris.php?id=<?php print $risid['ris_id']; ?>"><?php print $risid['risnum']; ?></a>
														<?php
													}
												}
												
												print "</td>";
												
											print "</tr>";
											
										}
										print "</tbody>
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