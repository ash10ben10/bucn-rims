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
					<li class="active">
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
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-assignment-o zmdi-hc-lg"></i>&nbsp;&nbsp;Purchase Orders</h1>

						<br />
						<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
							
							<div class="panel-body">
							<?php
							
								$getapprovedpr = mysql_query ("SELECT * FROM purchase_request ORDER BY prdate DESC")or die (mysql_error());
									
									if(mysql_num_rows($getapprovedpr) == 0){
										print "<br /><p align=center><i>There are No Requests at this time. Please check the list of requests.</i></p><br />";
									}else{
										print "
										<div class='table-responsive'>
											<table class = 'table table-striped table-bordered table-hover' id='showTable'>
											<thead>
												<tr>
													<th>Request Date</th>
													<th>Purchase Request No.</th>
													<th>Requestor</th>
													<th>Purchase Orders</th>
													<th width='20%'>Action</th>
												</tr>
											</thead>
											<tbody>
										";
										
										while($getdata = mysql_fetch_array($getapprovedpr)){
											
											$confirmapprovedreq = mysql_fetch_array(mysql_query("SELECT count(*) FROM request_items WHERE pr_id = '$getdata[pr_id]' AND pr_status = 'approved'"));
											
											if($confirmapprovedreq[0] != 0){
												$getpr = mysql_fetch_array(mysql_query("SELECT pr.pr_id, pr.prnum, pr.prdate, pr.purpose, CONCAT(p.personnel_fname,' ',p.personnel_lname) AS full_name FROM purchase_request AS pr LEFT JOIN personnel AS p ON p.personnel_id = pr.personnel_id WHERE pr.pr_id = $getdata[pr_id]")) or die(mysql_error());
											
												$getpo = mysql_query("SELECT rs.status, po.po_id, po.ponumber, po.podate, po.pr_id FROM requisition_status AS rs LEFT JOIN purchase_order AS po ON po.po_id = rs.po_id WHERE po.pr_id = $getdata[pr_id]") or die(mysql_error());
												$getporows = mysql_num_rows($getpo);
												
												print "<tr>";
												print "<td style='text-align:center;vertical-align:middle;line-height:40px;'>";
												print date("M j, Y", strtotime($getpr['prdate']));
												print "</td>";
												print "<td style='text-align:center;vertical-align:middle;line-height:40px;'>"?><a href="print_pr.php?id=<?php echo $getpr['pr_id'];?>" target="_blank"><?php print $getpr['prnum'];?></a><?php print "</td>";
												
												print "<td style='text-align:center;vertical-align:middle;line-height:40px;'>".$getpr['full_name']."</td>";
												
												
												print "<td style='text-align:left;vertical-align:middle;line-height:40px;'>";
												if ($getporows == 0){
													print "<center>Click the Create button to add Order from this request.</center>";
												}else{
													while($getpodata = mysql_fetch_array($getpo)){
														
														?>&nbsp;&nbsp;<span class="fa fa-arrow-circle-right fa-1x"></span>&nbsp;&nbsp;&nbsp;<a href="view_po.php?id=<?php echo $getpodata['po_id'];?>"><?php print $getpodata['ponumber']." dated <i>".date("M j, Y", strtotime($getpodata['podate']))."</i>"; ?></a>
														<?php
														/* if($getpodata['status'] == "pending"){
															print " - Pending";
														}else{
															print " - Funded";
														} */
														print "<br />";
													}
												}
												print "</td>";
												
												$getitems = mysql_query("SELECT req_item_id FROM request_items WHERE po_id = 0 AND pr_id = $getdata[pr_id] AND pr_status = 'approved'")or die (mysql_error());
												
												print "<td style='text-align:center;vertical-align:middle;line-height:40px'><center>";
												if (mysql_num_rows($getitems) > 0){
													?><a href="add_po.php?id=<?php echo $getdata['pr_id']; ?>" class="btn btn-default"><i class="fa fa-plus-circle fa-fw"></i>&nbsp;&nbsp;Create PO</a><?php
												}else{
													print "Request items complete.";
												}
												print "</center></td>";
												print "</tr>";
											}else{
												print "";
											}
											
											
											
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