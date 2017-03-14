<!DOCTYPE html>
<?php
	include "../connect.php";
	
	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");
	
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
	
	$cTab = (isset($_GET['cTab'])) ? $_GET['cTab'] : "p";
	
	//Queries
	if(isset($_POST['addposition'])){
		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("START TRANSACTION");
		
		mysql_query("LOCK TABLE personnel_position WRITE;");
		try{
			$add_note = mysql_query("INSERT INTO `personnel_position`(`position_name`) VALUES ('".mysql_real_escape_string($_POST['inputposition'])."')")or die(mysql_error());
			mysql_query("COMMIT");
			mysql_query("UNLOCK TABLE;");
		}catch(Exception $e){
			print "<script>alert('Something went wrong when adding position. Please check your connection.')</script>";
		}
		mysql_close();
		print "<script>window.location='emp_settings.php?cTab=p';</script>";
		
	}else if(isset($_POST['adddepartment'])){
		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("START TRANSACTION");
		
		mysql_query("LOCK TABLE department WRITE;");
		try{
			$add_note = mysql_query("INSERT INTO `department`(`dept_name`) VALUES ('".mysql_real_escape_string($_POST['inputdepartment'])."')")or die(mysql_error());
			mysql_query("COMMIT");
			mysql_query("UNLOCK TABLE;");
		}catch(Exception $e){
			print "<script>alert('Something went wrong when adding department. Please check your connection.')</script>";
		}
		mysql_close();
		print "<script>window.location='emp_settings.php?cTab=d';</script>";
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
		
        $("#editposition").on("show.bs.modal", function(event){
            var button = $(event.relatedTarget);
            data = button.data('id');
            request_data = data.split('|');
            
            $("#editPositionNo").val(request_data[0]);
            $("#editPositionName").val(request_data[1]);
            //alert(data);
        });
		
        $("#savePositionBtn").click(function(event){
				var isOk = true;
				var msg = "Please fill in the required forms.";
				
				var $editPositionName = $("#editPositionName").val();
				if($editPositionName == 0 || $editPositionName == "" || $editPositionName == null){
					isOk = false;
					msg;
				}
				
				if(!isOk){
						alert(msg);
						return false;
				}else{
					var savePositionNo = $("#editPositionNo").val();
					var savePositionName = $("#editPositionName").val();

					var hr = new XMLHttpRequest();
						var url = "savePosition.php";
						var vars = "savePositionNo="+savePositionNo+"&savePositionName="+savePositionName;

						hr.open("POST", url, true);
						hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

						hr.onreadystatechange = function() {
						if(hr.readyState == 4 && hr.status == 200) {
						  var return_data = hr.responseText;
							document.getElementById("savePositionResult").innerHTML = return_data;
							//window.alert("Changes has been saved. The positions are updated.");
							window.location = "emp_settings.php?cTab=p";
							
						  }
						}
						hr.send(vars);
						document.getElementById("savePositionResult").innerHTML = "Saving Changes...";
				}
        });
		
        $("#deleteposition").on("show.bs.modal", function(event){            
            var button = $(event.relatedTarget);
            data = button.data('id');
            request_data = data.split('|');
            
            $("#delPositionNo").val(request_data[0]);
            $("#delPositionName").text(request_data[1]);
            //alert(data);
        });
		
        $("#yesPositionBtn").click(function(event){
            var delPosition = $("#delPositionNo").val(); 

            var hr = new XMLHttpRequest();
                var url = "delPosition.php";
                var vars = "delPosition="+delPosition;

                hr.open("POST", url, true);
                hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                hr.onreadystatechange = function() {
                if(hr.readyState == 4 && hr.status == 200) {
                  var return_data = hr.responseText;
                    document.getElementById("delPositionResult").innerHTML = return_data;
                    //window.alert("Position entry has been deleted.");
                    window.location = "emp_settings.php?cTab=p";
                    
                  }
                }
                hr.send(vars);
                document.getElementById("delPositionResult").innerHTML = "Deleting Entry...";
            
        });
		
        $("#editdepartment").on("show.bs.modal", function(event){
            var button = $(event.relatedTarget);
            data = button.data('id');
            request_data = data.split('|');
            
            $("#editDepartmentNo").val(request_data[0]);
            $("#editDepartmentName").val(request_data[1]);
            //alert(data);
        });
		
        $("#saveDepartmentBtn").click(function(event){
				var isOk = true;
				var msg = "Please fill in the required forms.";
				
				var $editDepartmentName = $("#editDepartmentName").val();
				if($editDepartmentName == 0 || $editDepartmentName == "" || $editDepartmentName == null){
					isOk = false;
					msg;
				}
				
				if(!isOk){
						alert(msg);
						return false;
				}else{
					var saveDepartmentNo = $("#editDepartmentNo").val();
					var saveDepartmentName = $("#editDepartmentName").val();

					var hr = new XMLHttpRequest();
						var url = "saveDepartment.php";
						var vars = "saveDepartmentNo="+saveDepartmentNo+"&saveDepartmentName="+saveDepartmentName;

						hr.open("POST", url, true);
						hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

						hr.onreadystatechange = function() {
						if(hr.readyState == 4 && hr.status == 200) {
						  var return_data = hr.responseText;
							document.getElementById("saveDepartmentResult").innerHTML = return_data;
							//window.alert("Changes has been saved. The departments are updated.");
							window.location = "emp_settings.php?cTab=d";
							
						  }
						}
						hr.send(vars);
						document.getElementById("saveDepartmentResult").innerHTML = "Saving Changes...";
				}
        });
		
        $("#deletedepartment").on("show.bs.modal", function(event){            
            var button = $(event.relatedTarget);
            data = button.data('id');
            request_data = data.split('|');
            
            $("#delDepartmentNo").val(request_data[0]);
            $("#delDepartmentName").text(request_data[1]);
            //alert(data);
        });
		
        $("#yesDepartmentBtn").click(function(event){
            var delDepartment = $("#delDepartmentNo").val(); 

            var hr = new XMLHttpRequest();
                var url = "delDepartment.php";
                var vars = "delDepartment="+delDepartment;

                hr.open("POST", url, true);
                hr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

                hr.onreadystatechange = function() {
                if(hr.readyState == 4 && hr.status == 200) {
                  var return_data = hr.responseText;
                    document.getElementById("delDepartmentResult").innerHTML = return_data;
                    //window.alert("Position entry has been deleted.");
                    window.location = "emp_settings.php?cTab=d";
                    
                  }
                }
                hr.send(vars);
                document.getElementById("delDepartmentResult").innerHTML = "Deleting Entry...";
            
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
						<li>
							<a href="act.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-lock zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Personnel Accounts</a>
						</li>
						<li class="active">
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
						<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-settings zmdi-hc-lg"></i>&nbsp;&nbsp;Personnel Settings</h1>
						
						<br />
						<ul class="nav nav-tabs">
							<li <?php if($cTab ==  "p") echo "class='active'"; ?>>
								<a href="#position" data-toggle="tab" class="nav-tab-pane" alt="p"><i class="zmdi zmdi-card-travel zmdi-hc-1x"></i>&nbsp;Personnel Position</a>
							</li>
							<li <?php if($cTab ==  "d") echo "class='active'"; ?>>
								<a href="#department" data-toggle="tab" class="nav-tab-pane" alt="d"><i class="zmdi zmdi-city-alt zmdi-hc-1x"></i>&nbsp;Office Department</a>
							</li>
						</ul>
						<br />
						
						<div class="tab-content">
							<div class="tab-pane fade in <?php if($cTab ==  "p") echo "active"; ?>" id="position">
								
								<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
									
									<div class="panel-heading">
										<div class="row" align="center">
											<div class="col-md-12">
												<div class="col-md-12">
													<form role="form" method="post" name="contentForm" enctype="multipart/form-data">
														<div class="row" align="middle">
															<div class="col-lg-2" style="margin: 16px 0 0 0;" align="left">
																<label>Add Position:</label>
															</div>
															<div class="col-lg-4" style="margin: 10px 0 0 0;" align="left">
																<input class="form-control" name="inputposition" id="inputposition" placeholder="Enter employee position..." required />
															</div>
															<div class="col-lg-2" style="margin: 10px 0 0 0px;" align="left">
																<button name="addposition" id="addposition" class="btn btn-success" ><span class="fa fa-plus-circle fa-fw"></span>&nbsp;&nbsp;Add</button>
															</div>
														</div>
													</form>
												</div>
											</div>
										</div>
									</div>
									
									<div class="panel-body">
										<div class="row">
											<div class="col-lg-2"></div>
											<div class="col-lg-8" align="center">
												<?php
													$query = mysql_query("SELECT * FROM personnel_position ORDER BY position_name ASC")or die(mysql_error());
													
													if(mysql_num_rows($query) == 0){
														print "<br /><p align=center><i>There are no available Personnel Position/s on the system. Please add one.</i></p><br />";
													}else{
														print "
															<table class = 'table table-striped table-bordered table-hover display'>
															<thead>
																<tr>
																	<th width='40%'>Positions</th>
																	<th width='25%'>Option</th>
																</tr>
															</thead>
															<tbody>
														";
														while($getdata = mysql_fetch_array($query)){
															
															print "<tr><td style='text-align:center;vertical-align:middle;'>".$getdata['position_name']."</td>";
															print "<td><center>";
															?>
																<button name='edit' data-id="<?php echo $getdata['position_id']."|{$getdata['position_name']}"?>" class='btn btn-info' data-toggle="modal" data-target="#editposition" style="margin: 5px 0 5px 0;"><i class="zmdi zmdi-edit zmdi-hc-1x"></i>&nbsp;&nbsp;Edit</button>
																&nbsp;
																<!--<button name='del' data-id="<?php echo $getdata['position_id']."|{$getdata['position_name']}"?>" class='btn btn-warning' data-toggle="modal" data-target="#deleteposition" style="margin: 5px 0 5px 0;"><i class="zmdi zmdi-delete zmdi-hc-1x"></i>&nbsp;&nbsp;Delete</button>-->
															<?php
															print "</center></td>";
														}
														print "</tr></tbody></table>";
													}
												?>
												
													<div class="modal fade" id="editposition" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-header">
																	<h4 class="modal-title" id="myModalLabel">Edit Position</h4>
																	<input class="form-control" id="editPositionNo" type="hidden"/>
																</div>
																<div class="modal-body">
																	<div class="row" align="center">
																		<div class="col-lg-12">
																			<table border="0" align="center">
																				<tbody>
																					<tr>
																						<td width="25%" style="text-align:center;vertical-align:middle;"><label>Position Name:</label>&nbsp;</td>
																						<td><input class="form-control" style="margin-top:14px; margin-left:10px; width:250px;" id="editPositionName" required /></td>
																					</tr>
																				</tbody>
																			</table>
																		</div>
																	</div>
																</div>
																<div class="modal-footer">
																	<button type="button" id="savePositionBtn" class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;Save</button>
																	<button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>&nbsp;Cancel</button>
																</div>
																<div id="savePositionResult"></div>
															</div>
														</div>
													</div>
													
													<div class="modal fade" id="deleteposition" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-header">
																	<h4 class="modal-title" id="myModalLabel">Delete Entry</h4>
																	<input class="form-control" id="delPositionNo" type="hidden" />
																</div>
																<div class="modal-body">
																	<center>Are you sure you want to delete the position entry <label id="delPositionName"></label>?</center>
																</div>
																<div class="modal-footer">
																	<button type="button" id="yesPositionBtn" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span>&nbsp;Yes</button>
																	<button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>&nbsp;No</button>
																</div>
																<div id="delPositionResult"></div>
															</div>
														</div>
													</div>
											</div>
										</div>
									</div>
								
								</div>
							</div>
							
							<div class="tab-pane fade in <?php if($cTab ==  "d") echo "active"; ?>" id="department">
								<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
									<div class="panel panel-heading">
										<div class="row" align="center">
											<div class="col-md-12">
												<div class="col-md-12">
													<form role="form" method="post" name="contentForm" enctype="multipart/form-data">
														<div class="row" align="middle">
															<div class="col-lg-2" style="margin: 16px 0 0 0;" align="left">
																<label>Add Department:</label>
															</div>
															<div class="col-lg-4" style="margin: 10px 0 0 0;" align="left">
																<input class="form-control" name="inputdepartment" id="inputdepartment" placeholder="Enter office department..." required />
															</div>
															<div class="col-lg-2" style="margin: 10px 0 0 0px;" align="left">
																<button name="adddepartment" id="adddepartment" class="btn btn-success" ><span class="fa fa-plus-circle fa-fw"></span>&nbsp;&nbsp;Add</button>
															</div>
														</div>
													</form>
												</div>
											</div>
										</div>
									</div>
									
									<div class="panel panel-body">
										<div class="row">
											<div class="col-lg-2"></div>
											<div class="col-lg-8" align="center">
												<?php
													$querytwo = mysql_query("SELECT * FROM department ORDER BY dept_name ASC")or die(mysql_error());
													
													if(mysql_num_rows($querytwo) == 0){
														print "<br /><p align=center><i>There are no available Office Department/s on the system. Please add one.</i></p><br />";
													}else{
														print "
															<table class = 'table table-striped table-bordered table-hover display'>
															<thead>
																<tr>
																	<th width='40%'>Departments</th>
																	<th width='25%'>Option</th>
																</tr>
															</thead>
															<tbody>
														";
														while($getgo = mysql_fetch_array($querytwo)){
															
															print "<tr><td style='text-align:center;vertical-align:middle;'>".$getgo['dept_name']."</td>";
															print "<td><center>";
															?>
																<button name='edit' data-id="<?php echo $getgo['dept_id']."|{$getgo['dept_name']}"?>" class='btn btn-info' data-toggle="modal" data-target="#editdepartment" style="margin: 5px 0 5px 0;"><i class="zmdi zmdi-edit zmdi-hc-1x"></i>&nbsp;&nbsp;Edit</button>
																&nbsp;
																<!--<button name='del' data-id="<?php echo $getgo['dept_id']."|{$getgo['dept_name']}"?>" class='btn btn-warning' data-toggle="modal" data-target="#deletedepartment" style="margin: 5px 0 5px 0;"><i class="zmdi zmdi-delete zmdi-hc-1x"></i>&nbsp;&nbsp;Delete</button>-->
															<?php
															print "</center></td>";
														}
														print "</tr></tbody></table>";
													}
												?>
												
													<div class="modal fade" id="editdepartment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-header">
																	<h4 class="modal-title" id="myModalLabel">Edit Department</h4>
																	<input class="form-control" id="editDepartmentNo" type="hidden"/>
																</div>
																<div class="modal-body">
																	<div class="row" align="center">
																		<div class="col-lg-12">
																			<table border="0" align="center">
																				<tbody>
																					<tr>
																						<td width="25%" style="text-align:center;vertical-align:middle;"><label>Department Name:</label>&nbsp;</td>
																						<td><input class="form-control" style="margin-top:14px; margin-left:10px; width:250px;" id="editDepartmentName" required /></td>
																					</tr>
																				</tbody>
																			</table>
																		</div>
																	</div>
																</div>
																<div class="modal-footer">
																	<button type="button" id="saveDepartmentBtn" class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;Save</button>
																	<button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>&nbsp;Cancel</button>
																</div>
																<div id="saveDepartmentResult"></div>
															</div>
														</div>
													</div>
													
													<div class="modal fade" id="deletedepartment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-header">
																	<h4 class="modal-title" id="myModalLabel">Delete Entry</h4>
																	<input class="form-control" id="delDepartmentNo" type="hidden" />
																</div>
																<div class="modal-body">
																	<center>Are you sure you want to delete the department entry <label id="delDepartmentName"></label>?</center>
																</div>
																<div class="modal-footer">
																	<button type="button" id="yesDepartmentBtn" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span>&nbsp;Yes</button>
																	<button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>&nbsp;No</button>
																</div>
																<div id="delDepartmentResult"></div>
															</div>
														</div>
													</div>
											</div>
										</div>
									</div>
								</div>
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