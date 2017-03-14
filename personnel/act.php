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
	function change_type_edit( id ){
		document.getElementById('type'+id).disabled = false;
		document.getElementById('edit_'+id).hidden = true;
		document.getElementById('cancel_'+id).hidden = false;
	}
	function change_type_cancel( id ){
		document.getElementById('type'+id).disabled = true;
		document.getElementById('edit_'+id).hidden = false;
		document.getElementById('cancel_'+id).hidden = true;
		window.location.reload();
	}
	
	var ajaxRequest = function(url, param, callback){
		$.ajax({
			url: url,
			data: param,
			type: "GET",
			dataType: "json"
		}).done(function(response){
				alert(response.msg);
			if(response.result == "success"){
				callback();
			}
		}).fail(function(){
			alert("Error sending request.")
		});
	};
	
	function toggleEmpStat(id, isActive){
		var $empTr = $("tr#emp_"+id);
		if(isActive){
			$empTr.find(".emp_stat").html("activated");
			$empTr.find(".emp_opt").find(".btn").attr("onclick", "deactivate_acc("+id+");");
			$empTr.find(".emp_opt").find(".btn").attr("title", "Deactivate Account");
			$empTr.find(".emp_opt").find(".btn").removeClass("btn-success");
			$empTr.find(".emp_opt").find(".btn").addClass("btn-danger");
			$empTr.find(".emp_opt").find(".glyphicon").removeClass("glyphicon-play");
			$empTr.find(".emp_opt").find(".glyphicon").addClass("glyphicon-stop");
		}else{
			$empTr.find(".emp_stat").html("deactivated");
			$empTr.find(".emp_opt").find(".btn").attr("onclick", "activate_acc("+id+");");
			$empTr.find(".emp_opt").find(".btn").attr("title", "Activate Account");
			$empTr.find(".emp_opt").find(".btn").removeClass("btn-danger");
			$empTr.find(".emp_opt").find(".btn").addClass("btn-success");
			$empTr.find(".emp_opt").find(".glyphicon").removeClass("glyphicon-stop");
			$empTr.find(".emp_opt").find(".glyphicon").addClass("glyphicon-play");
		}
	}
	function deactivate_acc( id ){
		if(confirm('Are you sure you want to deactivate this account?')){
			ajaxRequest("deactivate.php", {"id":id}, function(){
				toggleEmpStat(id, false);
			});
		}
	}
	function activate_acc( id ){
		if(confirm('Are you sure you want to activate this account?')){
			ajaxRequest("activate.php", {"id":id}, function(){
				toggleEmpStat(id, true);
			});
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
				
				<!-- User panel I made before in the sidebar. -->
				<!--
				<div class="panel panel-default" style="margin-left: 20px; margin-right: 20px; margin-top:0px;">
					<div class="panel-heading">
						<h3 class="panel-title" align="center"><strong>Personnel/Employee Name</strong></h3>
					</div>
					<div class="panel-body" style="margin-bottom:-16px;px; margin-top:-15px;">
					<a href="#" style="margin-left: -16px;"><img src="../engine/images/user.bmp" style="height:75px; width:75px;"/></a> <button type="button" class="btn btn-default" style="margin-left: 18px;"><i class="glyphicon glyphicon-log-out"></i>&nbsp;&nbsp;Logout</button>
					</div>
				</div>
				-->
				<?php
					if($account['account_type'] == "System Administrator"){
						?>
						<li>
							<a href="emp.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-accounts-list zmdi-hc-lg"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Personnel List</a>
						</li>
						<li class="active">
							<a href="act.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-lock zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Personnel Accounts</a>
						</li>
						<li>
							<a href="emp_settings.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-settings zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Settings</a>
						</li>
						<br>
						<li>
							<a href="change_pass.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-key zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Change Password</a>
						</li>
						<?php
					}else{
						?>
						<li>
							<a href="viewinfo.php?id=<?php print $_SESSION['logged_personnel_id']; ?>"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-account-circle zmdi-hc-lg"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;My Profile</a>
						</li>
						<li>
							<a href="change_pass.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-key zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Change Password</a>
						</li>
						<?php
					}
				?>
			</ul> <!--/.Inside Sidebar -->
	</div><!--/.Sidebar -->
	
	<!-- Navigation -->
	<?php include "../personnel/emp_header.php"; ?>
	<!--/.Navigation -->
	
<!-- /.Header and Sidebar Page -->
			
<!-- Body will contain the Page Contents -->

<body>
	<!-- Content-Wrapper -->
	<div id="content-wrapper">
		
		<div class="container-fluid">
			
			<div class="row" style="margin-top:-20px;">
				<div class="col-lg-12">
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-lock zmdi-hc-lg"></i>&nbsp;&nbsp;Personnel Accounts</h1>
					
						<br />
						<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
							<div class="panel-heading">
							</div>
							<div class="panel-body">
								<?php
									$foraccount = mysql_query("SELECT * FROM personnel ORDER BY personnel_lname ASC") or die(mysql_error());
			
									if(mysql_num_rows($foraccount) == 0){
												print "<br /><p align=center><i>There are no available Personnel Records on the system. Please add one.</i></p><br />";
									}else{
										
										print "
											<div class='table-responsive'>
											<table class = 'table table-striped table-bordered table-hover'>
											<thead>
												<tr>
													<th width='30%'>Personnel Name</th>
													<th width='20%'>Personnel ID</th>
													<th colspan='2' width='30%'>Account Type</th>
													<th colspan='2' width='20%'>Status</th>
												</tr>
											</thead>
											<tbody>
										";
										
										while($getinfo = mysql_fetch_array($foraccount)){
												$getaccountid = mysql_fetch_array(mysql_query("SELECT * FROM account WHERE personnel_id = $getinfo[personnel_id]")) or die(mysql_error());
												
												print "<tr id='emp_".$getaccountid['account_id']."'><td style='text-align:center;vertical-align:middle;line-height:40px;'>"?><a href="viewinfo.php?id=<?php echo $getinfo['personnel_id']; ?>&tab=1"><?php print "".$getinfo['personnel_fname']." ".$getinfo['personnel_lname'].""; ?></a><?php print "</td>";
												print "<td style='text-align:center;vertical-align:middle;line-height:40px;'>".$getinfo['personnel_empid']."</td>";
												print "<td style='text-align:center;vertical-align:middle;line-height:40px;'><select class='form-control' name='type".$getaccountid['account_id']."' id='type".$getaccountid['account_id']."' disabled required>";
													if($getaccountid['account_type'] == "System Administrator"){
														print "<option selected>System Administrator</option>";
														print "<option>Administrator</option>";
														print "<option>End User</option>";
													}else if($getaccountid['account_type'] == "Administrator"){
														print "<option>System Administrator</option>";
														print "<option selected>Administrator</option>";
														print "<option>End User</option>";
													}else if($getaccountid['account_type'] == "End User"){
														print "<option>System Administrator</option>";
														print "<option>Administrator</option>";
														print "<option selected>End User</option>";
													}
												print "</select></td>";
												
												print "<td style='text-align:center;vertical-align:middle' id='edit_".$getaccountid['account_id']."'><a onClick='change_type_edit(".$getaccountid['account_id'].")' class='btn btn-warning'><span class='zmdi zmdi-shield-security zmdi-hc-lg'></span>&nbsp;&nbsp;Change</a></td>
													   <td style='text-align:center;vertical-align:middle' id='cancel_".$getaccountid['account_id']."' hidden>";?>
															<a onClick='if(confirm("Are you sure about this change of account type?")) window.location="change_type.php?id=<?php print $getaccountid['account_id']; ?>&type="+document.getElementById("type<?php print $getaccountid['account_id']; ?>").value' class='btn btn-success' title='Save'><span class='glyphicon glyphicon-ok' hidden></span></a>
															<a onClick='change_type_cancel(<?php print $getaccountid['account_id']; ?>)' class='btn btn-danger' title='Cancel'><span class='glyphicon glyphicon-remove' hidden></span></a><?php print "
														</td>
												";
												//print "<td>".$gettype['account_type_name']."</td>";
												print "<td class='emp_stat' style='text-align:center;vertical-align:middle'>".$getaccountid['account_status']."</td>";
												//$btnTitle; $btnOnClick; $btnClass; $btnSpanClass; $isDisabled;
												
												if($getaccountid['account_status'] == "activated" && $getaccountid['account_type'] == "System Administrator"){
													$btnTitle = "System Admins cannot deactivate accounts.";
													$btnOnClick = "";
													$btnClass = "btn-danger";
													$btnSpanClass = "glyphicon-stop";
													$isDisabled = "disabled";
													
												}else if($getaccountid['account_status'] == "activated" && $getaccountid['account_type'] != "System Administrator"){ 
													$btnTitle = "Deactivate Account";
													$btnOnClick = "deactivate_acc(".$getaccountid['account_id'].")";
													$btnClass = "btn-danger";
													$btnSpanClass = "glyphicon-stop";
													$isDisabled = "";	
												
												}
												else {
													$btnTitle = "Activate Account";
													$btnOnClick = "activate_acc(".$getaccountid['account_id'].")";
													$btnClass = "btn-success";
													$btnSpanClass = "glyphicon-play";
													$isDisabled = "";
													
												}
												print "<td class='emp_opt' style='text-align:center;vertical-align:middle'>
															<a onClick='".$btnOnClick."' class='btn ".$btnClass."' title='".$btnTitle."' ".$isDisabled.">
																<span class='glyphicon ".$btnSpanClass."'></span>
															</a>
														</td>";
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