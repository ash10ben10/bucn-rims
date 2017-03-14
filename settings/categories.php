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
	<?php include "../engine/jscalls.php"; ?>
	
	<?php include "invsettings_category_engine.php"; ?>
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
			
				<!--<li>
					<a href="items.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-labels zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Item Labels</a>
				</li>-->
				<li>
					<a href="suppliers.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-truck zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Suppliers</a>
				</li>
				<li>
					<a href="unitmeasure.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-ruler zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Unit of Measurements</a>
				</li>
				<!--<li>
					<a href="colors.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-invert-colors zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Colors</a>
				</li>-->
				<li class="active">
					<a href="categories.php"><span class="fa-stack fa-lg pull-left"><i class="zmdi zmdi-case zmdi-hc-1x"></i></span>&nbsp;&nbsp;&nbsp;&nbsp;Item Categories</a>
				</li>
				<br>
					
		</ul> <!--/.Inside Sidebar -->
	</div><!--/.Sidebar -->

	<!-- Navigation -->
	<?php include "../settings/setting_header.php"; ?>
	<!--/.Navigation -->
	
<!-- /.Header and Sidebar Page -->
	
<!-- Body will contain the Page Contents -->

<body>
	<!-- Content-Wrapper -->
	<div id="content-wrapper">
		
		<div class="container-fluid">
			
			<div class="row" style="margin-top:-20px;">
				<div class="col-lg-12">
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-case zmdi-hc-lg"></i>&nbsp;&nbsp;Categories</h1>
					
						<br />
						<div class="panel panel-default" div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
							<div class="panel-heading">
								
								<div class="row" align="center">
									<div class="col-md-12">
										<div class="col-md-12">
											<form role="form" method="post" name="contentForm" enctype="multipart/form-data" onsubmit="submitform(event);">
												<div class="row" align="middle">
													<div class="col-lg-2" style="margin: 15px 0 0 0;" align="left">
														<label>Add Category:</label>
													</div>
													<div class="col-lg-4" style="margin: 10px 0 0 0;" align="left">
														<input class="form-control" name="inputcategory" id="inputcategory" placeholder="Category Name:" required />
													</div>
													<div class="col-lg-4" style="margin: 10px 0 0 0;" align="left">
														<select class="selectpicker form-control" name="selectcatype" id="selectcatype">
															<option selected disabled>-Select category type-</option>
															<option value="Category for Supplies">Category for Supplies</option>
															<option value="Category for Equipment">Category for Equipment</option>
															<option value="Category for All Items">Category for All Items</option>
														</select>
													</div>
													<div class="col-lg-2" style="margin: 10px 0 0 0px;" align="left">
														<button type="submit" name="addcategory" id="addcategory" class="btn btn-success" ><span class="fa fa-plus-circle fa-fw"></span>&nbsp;&nbsp;Add</button>
													</div>
												</div>
											</form>
										</div>
									</div>
								</div>
								
							</div>
							<div class="panel-body">
							
								<div class="row">
									<div class="col-lg-12">
										<br />
										<div class="row">
											<div class="col-lg-12" align="center">
												<?php
													$getcats = mysql_query("SELECT * FROM category ORDER BY category_name ASC")or die(mysql_error());
													
													if(mysql_num_rows($getcats) == 0){
														print "<br /><p align=center><i>There are no available unit of color/s on the system. Please add one.</i></p><br />";
													}else{
														print "
															<table class = 'table table-striped table-bordered table-hover' id='showTable'>
															<thead>
																<tr>
																	<th width='40%'>Category Name</th>
																	<th width='40%'>Category Type</th>
																	<th width='25%'>Option</th>
																</tr>
															</thead>
															<tbody>
														";
														while($categs = mysql_fetch_array($getcats)){
															
															print "<tr><td style='text-align:center;vertical-align:middle;'>".$categs['category_name']."</td>";
															print "<td style='text-align:center;vertical-align:middle;'>".$categs['category_type']."</td>";
															print "<td><center>";
															?>
																<button name='edit' data-id="<?php echo $categs['category_id']."|{$categs['category_name']}|{$categs['category_type']}"?>" class='btn btn-info' data-toggle="modal" data-target="#editCategory" style="margin: 5px 0 5px 0;"><i class="zmdi zmdi-edit zmdi-hc-1x"></i>&nbsp;&nbsp;Edit</button>
																&nbsp;
																<!--<button name='del' data-id="<?php echo $categs['category_id']."|{$categs['category_name']}"?>" class='btn btn-warning' data-toggle="modal" data-target="#delCategory" style="margin: 5px 0 5px 0;"><i class="zmdi zmdi-delete zmdi-hc-1x"></i>&nbsp;&nbsp;Delete</button>-->
															<?php
															print "</center></td>";
														}
														print "</tr></tbody></table>";
													}
												?>
												
													<div class="modal fade" id="editCategory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-header">
																	<h4 class="modal-title" id="myModalLabel">Edit Category</h4>
																	<input class="form-control" id="editCategNo" type="hidden" />
																</div>
																<div class="modal-body">
																	<div class="row" align="center">
																		<div class="col-lg-1">
																		</div>
																		<div class="col-lg-10">
																			<div class="row">
																				<div class="col-lg-4" style="margin-top:5px;" align="left">
																				<label>Category Name:</label>
																				</div>
																				<div class="col-lg-6" align="left">
																					<input class="form-control" id="editCategName" required />
																				</div>
																			</div>
																			<div class="row">
																				<div class="col-lg-4" style="margin-top:5px;" align="left">
																					<label>Category Type:</label>
																				</div>
																				<div class="col-lg-6" align="left">
																					<select class="form-control" id="editCategType">
																						<option selected disabled>-Select category type-</option>
																						<option value="Category for Supplies">Category for Supplies</option>
																						<option value="Category for Equipment">Category for Equipment</option>
																						<option value="Category for All Items">Category for All Items</option>
																					</select>
																				</div>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="modal-footer">
																	<button type="button" id="saveCategoryBtn" class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;Save</button>
																	<button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>&nbsp;Cancel</button>
																</div>
																<div id="saveCategoryResult" style="margin: 10px 0 10px 0;"></div>
															</div>
														</div>
													</div>
													
													<div class="modal fade" id="delCategory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-header">
																	<h4 class="modal-title" id="myModalLabel">Delete Category</h4>
																	<input class="form-control" id="delCategNo" type="hidden" />
																</div>
																<div class="modal-body">
																	<center>Are you sure you want to delete this category named <label id="delCategName"></label>?</center>
																</div>
																<div class="modal-footer">
																	<button type="button" id="yesCategoryBtn" class="btn btn-success"><span class="glyphicon glyphicon-ok"></span>&nbsp;Yes</button>
																	<button type="button" class="btn btn-danger" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span>&nbsp;No</button>
																</div>
																<div id="delCategoryResult"></div>
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