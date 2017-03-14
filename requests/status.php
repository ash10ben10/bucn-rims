<!DOCTYPE html>
<?php
	
	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");
	$date = date("Y-m-d");
	$month = date("Y-m");
	
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
	<script src="req.approve.disapprove.js"></script>
	<script>
	
		$(document).ready(function(){
		
			$("#verifyFund").on("show.bs.modal", function(event){
				var button = $(event.relatedTarget);
				data = button.data('id');
				request_data = data.split('|');
				
				$("#getpoId").val(request_data[0]);
				$("#getpoNum").text(request_data[1]);
				$("#getTotalCost").val(request_data[2]);
				//alert(data);
			});
			
			$("#savePOFundBtn").click(function(event){
				var isOk = true;
				var msg = "Please fill in the required forms.";
				
				var $fund = $("#fund").val();
				if($fund == 0 || $fund == "" || $fund == null){
					isOk = false;
					msg;
				}
				
				var $getTotalCost = $("#getTotalCost").val();
				if($getTotalCost == 0 || $getTotalCost == "" || $getTotalCost == null){
					isOk = false;
					msg;
				}
				
				if(!isOk){
						alert(msg);
						return false;
				}else{
					var POid = $("#getpoId").val();
					var POfund = $("#fund").val();
					//var OSnum = $("#POOSNum").val();
					var FundAmount = $("#getTotalCost").val();

					var hr = new XMLHttpRequest();
						var url = "savePOFund.php";
						var vars = "POid="+POid+"&POfund="+POfund+"&FundAmount="+FundAmount;

						hr.open("POST", url, true);
						hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

						hr.onreadystatechange = function() {
						if(hr.readyState == 4 && hr.status == 200) {
						  var return_data = hr.responseText;
							document.getElementById("saveFundingResult").innerHTML = return_data;
							window.location = "status.php";
							
						  }
						}
						hr.send(vars);
						document.getElementById("saveFundingResult").innerHTML = "Funding the Order...";
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
				<li class="active">
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
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-flag zmdi-hc-1x"></i>&nbsp;&nbsp;Requisition Status</h1>
					
						<br />
						<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
							
							<div class="panel-body">
								<?php
									
									if($position['position_name'] == "Budget Officer" || $position['position_name'] == "BAC Officer" || $position['position_name'] == "Inspection Officer" || $position['position_name'] == "Supply Officer"){
										$getapprovedpo = mysql_query("SELECT * FROM requisition_status ORDER BY reqstatus_id DESC")or die (mysql_error());
									}else{
										$getapprovedpo = mysql_query("SELECT * FROM requisition_status WHERE requestor = '".$_SESSION['logged_personnel_id']."' ORDER BY reqstatus_id DESC")or die(mysql_error());
									}
									
									if(mysql_num_rows($getapprovedpo) == 0){
										print "<br /><p align=center><i>There are no active orders to monitor at this time.</i></p><br />";
									}else{
										print "
										<div class='table-responsive'>
											<table class = 'table table-striped table-bordered table-hover display'>
											<thead>
												<tr>";
												
													if($position['position_name'] == "Budget Officer" || $position['position_name'] == "BAC Officer" || $position['position_name'] == "Inspection Officer" || $position['position_name'] == "Supply Officer"){
														print "<th>Requestor</th>";
													}else{
														print "";
													}
													
													print "<th>Request Date</th>";
													print "<th>PR No.</th>";
													
													print "<th>PO No.</th>";
													print "<th>Order Date</th>";
													//print "<th>Purpose</th>";
													
													print "
													<th>Delivery Date</th>
													<th>Status</th>";
													
													if($position['position_name'] == "Budget Officer"){
														print "<th>Action</th>";
													}else if($position['position_name'] == "BAC Officer"){
														print "<th>Delivery</th>";
													}else if($position['position_name'] == "Inspection Officer"){
														print "<th width='200'>Inspection</th>";
													}else if($position['position_name'] == "Supply Officer"){
															print "<th>Acceptance</th>";
													}else{print "";}
													
										print "	</tr>
											</thead>
											<tbody>
										";
										
										while($getdata = mysql_fetch_array($getapprovedpo)){
											$getpo = mysql_fetch_array(mysql_query("SELECT * FROM purchase_order WHERE po_id = $getdata[po_id]")) or die(mysql_error());
											$getpr = mysql_fetch_array(mysql_query("SELECT CONCAT(p.personnel_fname,' ',p.personnel_lname) AS full_name, pr.pr_id, pr.prnum, pr.prdate, pr.purpose FROM purchase_request AS pr LEFT JOIN personnel AS p ON p.personnel_id = pr.personnel_id WHERE pr_id = $getpo[pr_id]")) or die(mysql_error());
											print "<tr>";
											
											if($position['position_name'] == "Budget Officer" || $position['position_name'] == "BAC Officer" || $position['position_name'] == "Inspection Officer" || $position['position_name'] == "Supply Officer"){
												print "<td style='text-align:center;vertical-align:middle;line-height:40px;'>".$getpr['full_name']."</td>";
											}else{
												print "";
											}
											
											print "<td style='text-align:center;vertical-align:middle;line-height:40px;'>".$getpr['prdate']."</td>";
											print "<td style='text-align:center;vertical-align:middle;line-height:40px;'>";
											?><a href="view_pr3.php?id=<?php echo $getpr['pr_id'];?>"><?php print $getpr['prnum'];?></a><?php
											print "</td>";
											
											if($position['position_name'] == "Budget Officer" || $position['position_name'] == "BAC Officer" || $position['position_name'] == "Inspection Officer" || $position['position_name'] == "Supply Officer"){
												print "<td style='text-align:center;vertical-align:middle;line-height:40px;'>";
												?><a href="view_po2.php?id=<?php echo $getpo['po_id'];?>"><?php print $getpo['ponumber'];?></a><?php
												print "</td>";
												print "<td style='text-align:center;vertical-align:middle;line-height:40px;'>";
												print date("M j, Y", strtotime($getpo['podate']));
												print "</td>";
												//print "<td style='text-align:center;vertical-align:middle;line-height:40px;'>".$getpr['purpose']."</td>";
											}else{
												print "<td style='text-align:center;vertical-align:middle;line-height:40px;'>";
												print $getpo['ponumber'];
												print "</td>";
												print "<td style='text-align:center;vertical-align:middle;line-height:40px;'>";
												print date("M j, Y", strtotime($getpo['podate']));
												print "</td>";
											}
											
											print "<td style='text-align:center;vertical-align:middle;line-height:40px;'>";
											
											if($getpo['delivery_date'] == "0000-00-00"){
												print "Setting up...";
											}else{
												print date("M d, Y", strtotime($getpo['delivery_date']));
											}
											
											print "</td>";
											
											#this part tracks the status of the requests. This classifies the tracking by personnel positions.
											print "<td style='text-align:center;vertical-align:middle;line-height:40px;'>";
											include "allusrstatview.php";
											print "</td>";
											
											if($position['position_name'] == "Budget Officer")
												{
													print "<td style='vertical-align:middle;'><center>";
													include "budget_viewstat.php";
													print "</center></td>";
												}else if($position['position_name'] == "BAC Officer")
												{
													print "<td style='text-align:center;vertical-align:middle;line-height:40px;'><center>";
													include "bac_viewstat.php";
													print "</center></td>";
												}else if($position['position_name'] == "Inspection Officer")
												{
													print "<td style='text-align:center;vertical-align:middle;line-height:40px;'>";
													include "ins_viewstat.php";
													print "</td>";
												}
											else if($position['position_name'] == "Supply Officer")
												{
													print "<td style='text-align:center;vertical-align:middle;line-height:40px;'>";
													include "sup_viewstat.php";
													print "</td>";
												}
											else{
												print "";
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