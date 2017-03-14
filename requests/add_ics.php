<!DOCTYPE html>
<?php
	include "../connect.php";
	
	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$datetime = date("Y-m-d H:i:s");
	$date = date("Y-m-d");
	$month = date("Y-m");
	
	$readreqitem_id = $_GET['id'];
	
	$getitemdetails = mysql_fetch_array(mysql_query("SELECT * FROM request_items WHERE req_item_id = '$readreqitem_id'"));
	$selitemunit = mysql_fetch_array(mysql_query("SELECT item_unit_name FROM item_unit WHERE item_unit_id = '$getitemdetails[item_unit_id]'"));
	$selitem = mysql_fetch_array(mysql_query("SELECT item_name FROM items WHERE item_id = '$getitemdetails[item_id]'"));
	$getstockitems = mysql_fetch_array(mysql_query("SELECT * FROM `stock_units` WHERE `su_id` = $getitemdetails[su_id]"));
	$getiar = mysql_fetch_array(mysql_query("SELECT * FROM `inspect_accept_report` WHERE `iar_id` = $getitemdetails[iar_id]"));
	
	$getpr = mysql_fetch_array(mysql_query("SELECT * FROM purchase_request WHERE pr_id='$getitemdetails[pr_id]'"));
	$getpo = mysql_fetch_array(mysql_query("SELECT * FROM purchase_order WHERE po_id='$getitemdetails[po_id]'"));
	$getfunding = mysql_fetch_array(mysql_query("SELECT * FROM `funding` WHERE `po_id`='$getpo[po_id]'"));
	$getsupplier = mysql_fetch_array(mysql_query("SELECT * FROM supplier WHERE supplier_id='$getpo[supplier_id]'"));
	
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
		$personnel = mysql_fetch_array(mysql_query("SELECT * FROM personnel WHERE personnel_id = '".$_SESSION['logged_personnel_id']."' ")) ;
		$pworkinfo = mysql_fetch_array(mysql_query("SELECT * FROM personnel_work_info WHERE personnel_id = '".$_SESSION['logged_personnel_id']."' ")) ;
		$position = mysql_fetch_array(mysql_query("SELECT * FROM personnel_position WHERE position_id = '$pworkinfo[position_id]' ")) ;
		$account = mysql_fetch_array(mysql_query("SELECT * FROM account WHERE personnel_id = '".$_SESSION['logged_personnel_id']."' ")) ;
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
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-file zmdi-hc-lg"></i>&nbsp;&nbsp;Inventory Custodian Slip</h1>
					<br />
					<form method="POST" id="addics" enctype="multipart/form-data">
						<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
							<div class="panel-body">
								
								<!--<div class="panel panel-default">
									<div class="panel-body">
										<div class="col-lg-12" style="margin-top:15px;">
											<div class="form-group col-lg-3" style="font-size:15px;">
												<label>Quantity:</label><br />
												<input class="form-control" />
											</div>
										</div>
									</div>
								</div>-->
								
								<div class="panel panel-default">
									<div class="panel-body">
										<div class="col-lg-6" style="margin-top:15px;">
											<div class="panel panel-default">
												<div class="panel-body">
													<div class="form-group col-lg-6" style="font-size:15px;">
														<label>Date:</label><br/>
														<?php print date("M d, Y", strtotime($date)); ?>
													</div>
													<div class="form-group col-lg-6" style="font-size:15px;">
														<label>Funding:</label><br/>
														<?php print $getfunding['type']; ?>
													</div>
													<div class="form-group col-lg-6" style="font-size:15px;">
														<label>Amount:</label><br/>
														<?php print "Php ".number_format($getitemdetails['est_unit_cost'], 2,'.',',');?>
													</div>
													<div class="form-group col-lg-6" style="font-size:15px;">
														<label>Total:</label><br/>
														<?php 
														$totalcost = $getitemdetails['est_total_cost'];
														print "Php ".number_format($totalcost, 2,'.',',');?>
													</div>
													<div class="form-group col-lg-6" style="font-size:15px;">
														<label>Date Acquired:</label><br />
														<input class="form-control" type="date" id="dateacq" name="dateacq" required />
													</div>
													<div class="form-group col-lg-6" style="font-size:15px;">
														<label>Est. Useful Life:</label><br />
														<input class="form-control" id="estlife" name="estlife" />
													</div>
												</div>
											</div>
										</div>
										<div class="col-lg-6" style="margin-top:15px;">
											<div class="form-group col-lg-6" style="font-size:15px;">
												<label>Item Name:</label><br/>
												<?php print $selitem['item_name']; ?>
											</div>
											<div class="form-group col-lg-3" style="font-size:15px;">
												<label>Item Unit:</label><br/>
												<?php print $selitemunit['item_unit_name']; ?>
											</div>
											<div class="form-group col-lg-3" style="font-size:15px;">
												<label>Quantity:</label><br/>
												<?php print $getitemdetails['del_quantity']; ?>
											</div>
											<div class="form-group col-lg-12" style="font-size:15px;">
												<label>Brand:</label><br />
												<input class="form-control" id="eqpbrand" name="eqpbrand" placeholder="Item brand name" required />
											</div>
											<div class="form-group col-lg-12" style="font-size:15px;">
												<label>Description:</label><br/>
												<?php print $getitemdetails['description']; ?>
											</div>
										</div>
										<div class="form-group col-lg-6" style="font-size:15px;">
													</div>
													
										
									</div>
								</div>
								
								<div class="panel panel-default">
									<div class="panel-body">
										<div class="row">
											<div class="col-lg-1">
											</div>
											<div class="col-lg-10">
												<div class="table-responsive">
													<table class="table table-striped table-bordered table-hover">
														<thead>
															<tr>
																<th>Item No.</th>
																<th>Serial Number</th>
															</tr>
														</thead>
														<tbody>
														
														<?php 
																
																for($row = 1; $row<=$getitemdetails['quantity']; $row++){
														?>
															<tr>
																<td style="text-align:center;vertical-align:middle;"><?php print $row; ?></td>
																<td style="text-align:left;vertical-align:middle;"><input class="form-control" id="serialnum<?php print $row; ?>" name="serialnum<?php print $row; ?>" placeholder="" /></td>
															</tr>
															<!--<tr>
																<td rowspan="2" style="text-align:center;vertical-align:middle;"><?php //print $getstockitems['quantity']; ?></td>
																<td rowspan="2" style="text-align:center;vertical-align:middle;"><?php //print $selitemunit['item_unit_name']; ?></td>
																<td style="text-align:left;vertical-align:middle;"><?php //print $selitem['item_name'] ?></td>
																<td rowspan="2" style="text-align:center;vertical-align:middle;">
																	<input class="form-control" type="date" id="dateacq" name="dateacq" required />
																</td>
																<td rowspan="2" style="text-align:center;vertical-align:middle;">
																	<?php //print "Php ".number_format($getitemdetails['est_unit_cost'], 2,'.',',');?>
																</td>
																<td rowspan="2" style="text-align:center;vertical-align:middle;">
																	<input class="form-control" id="estlife" name="estlife" />
																</td>
															</tr>
															<tr>
																<td style="text-align:left;vertical-align:middle;"><?php //print $getitemdetails['description']; ?></td>
															</tr>
															<tr>
																<td colspan="6" style="text-align:left;vertical-align:middle;">
																<label>Equipment Details</label>
																</td>
															</tr>
															<tr>
																<td colspan="2" style="text-align:center;vertical-align:middle;">
																Serial Number/s:
																</td>
																<td style="text-align:left;vertical-align:middle;">
																<input class="form-control" id="serialnum" name="serialnum" placeholder="Serial Number/s if any" />
																</td>
																<td style="text-align:center;vertical-align:middle;">
																Property Number:
																</td>
																<td colspan="2" style="text-align:left;vertical-align:middle;"><textarea class="form-control" style="resize:none;" id="propnum" name="propnum" placeholder="Property Number" required></textarea></td>
															</tr>-->
																<?php }?>
														</tbody>
													</table>
												</div>
											</div>
											<div class="col-lg-1">
											</div>
										</div>
									</div>
								</div>	
								<div class="panel panel-default">
									<div class="panel-body">
										<div class="row">
											<div class="col-lg-12">
											<br />
												<div class="row">
													<div class="col-lg-1">
													</div>
													<div class="form-group col-lg-5" style="font-size:15px;" align="left">
														<label>PR Number: </label> <?php print $getpr['prnum']." dated ".$getpr['prdate']; ?>
													</div>
													<div class="form-group col-lg-5" style="font-size:15px;" align="left">
														<label>Invoice Number: </label> <?php print $getiar['invoice_num']." dated ".$getiar['invoice_date']; ?>
													</div>
												</div>
												<div class="row">
													<div class="col-lg-1">
													</div>
													<div class="form-group col-lg-5" style="font-size:15px;" align="left">
														<label>OS Number:</label> <?php print $getfunding['os_num']; ?>
													</div>
													<div class="form-group col-lg-5" style="font-size:15px;" align="left">
														<label>Served by:</label> <?php print $getsupplier['supplier_name'].", ".$getsupplier['supplier_address']; ?>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="panel-footer" align="right">
								<button type="submit" name="icssave" id="icssave" class="btn btn-success"><span class='glyphicon glyphicon-floppy-disk'></span>&nbsp;Submit</button>
								<a href="add_ris.php?id=<?php echo $getitemdetails['pr_id']; ?>" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span>&nbsp;Cancel</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		
		</div> <!--/.container-fluid-->
	
	</div> <!--/.content-wrapper-->
	
	<?php
	
	if(isset($_POST['icssave'])){
		mysql_query("SET AUTOCOMMIT=0");
		mysql_query("START TRANSACTION");
		
		function escapeString($str){
			return mysql_real_escape_string($str);
		}
			
		$genICSSql = "SELECT icsdate,".
			" CONCAT('".$month."-',(COUNT(DATE_FORMAT(icsdate, '%Y-%m')) + 1)) AS icsnum".
			" FROM eqp_ics".
			" GROUP BY DATE_FORMAT(icsdate, '%Y-%m')".
			" HAVING DATE_FORMAT(icsdate, '%Y-%m') = '".$month."'".
			" ORDER BY icsdate DESC LIMIT 1";
		$genICSQry = mysql_query($genICSSql) ;
		if(mysql_num_rows($genICSQry) == 0){
			$icsnum = "ICS-".$month."-1";
		}else{
			$genICSArr = mysql_fetch_array($genICSQry);
			$icsnum = "ICS-".$genICSArr['icsnum'];
		}
		
		mysql_query("LOCK TABLE eqp_ics WRITE;");
		
		try{
			mysql_query("INSERT INTO `eqp_ics`(
			`icsdate`, 
			`icsnum`, 
			`receivedBy`, 
			`receivedFrom`, 
			`est_useful_life`, 
			`quantity`, 
			`total_cost`, 
			`date_acquired`, 
			`pr_id`, 
			`fund_id`, 
			`iar_id`, 
			`supplier_id`
			) VALUES (
			'$date',
			'$icsnum',
			'$getpr[personnel_id]',
			'".$_SESSION['logged_personnel_id']."',
			'".escapeString($_POST['estlife'])."',
			'$getitemdetails[quantity]',
			'$totalcost',
			'".escapeString($_POST['dateacq'])."',
			'$getpr[pr_id]',
			'$getfunding[fund_id]',
			'$getiar[iar_id]',
			'$getsupplier[supplier_id]'
			)");
		}catch(Exception $e){
				mysql_query("ROLLBACK");
				print "<script>alert('Something went wrong when saving ICS to the System. Please check your connection.')</script>";
		}
			
		mysql_query("UNLOCK TABLE;");
		
		#get ics_id
		$geticsid = mysql_fetch_array(mysql_query("SELECT ics_id FROM eqp_ics WHERE ics_id IN (SELECT MAX(ics_id) FROM eqp_ics) ")); //this makes the select last id recorded.
		$icsid = $geticsid[0];
		
		if($getitemdetails['quantity'] > 0){
			for($row = 1; $row<=$getitemdetails['quantity']; $row++){
				$sn = escapeString($_POST["serialnum".$row]);
				
				$genEqpSql = "SELECT eqpdate,".
					" CONCAT('".$month."-',(COUNT(DATE_FORMAT(eqpdate, '%Y-%m')) + 1)) AS eqpNum".
					" FROM equipments".
					" GROUP BY DATE_FORMAT(eqpdate, '%Y-%m')".
					" HAVING DATE_FORMAT(eqpdate, '%Y-%m') = '".$month."'".
					" ORDER BY eqpdate DESC LIMIT 1";
				$genEqpQry = mysql_query($genEqpSql) ;
				if(mysql_num_rows($genEqpQry) == 0){
					$eqpNum = $month."-1";
				}else{
					$genEqpArr = mysql_fetch_array($genEqpQry);
					$eqpNum = $genEqpArr['eqpNum'];
				}
				
				$genPropNumSql = "SELECT eqpdate,".
					" CONCAT('".$month."-',(COUNT(DATE_FORMAT(eqpdate, '%Y-%m')) + 1)) AS prop_num".
					" FROM equipments".
					" GROUP BY DATE_FORMAT(eqpdate, '%Y-%m')".
					" HAVING DATE_FORMAT(eqpdate, '%Y-%m') = '".$month."'".
					" ORDER BY eqpdate DESC LIMIT 1";
				$genPropNumQry = mysql_query($genPropNumSql) ;
				if(mysql_num_rows($genPropNumQry) == 0){
					$propNum = "BU-CN-".$getfunding['type']."-".$getstockitems['stock_no']."-CTE-".$month."-1";
				}else{
					$genPropNumArr = mysql_fetch_array($genPropNumQry);
					$propNum = "BU-CN-".$getfunding['type']."-".$getstockitems['stock_no']."-CTE-".$genPropNumArr['prop_num'];
				}
				
				$getdept = mysql_fetch_array(mysql_query("SELECT `dept_id` FROM `personnel_work_info` WHERE `personnel_id` = '$getpr[personnel_id]'"));
				
				mysql_query("LOCK TABLE equipments WRITE;");
			
				try{
					mysql_query("INSERT INTO `equipments` (
					`eqpnum`, 
					`eqpdate`, 
					`prop_num`, 
					`item_id`, 
					`item_unit_id`, 
					`brand`, 
					`description`, 
					`serialnum`, 
					`unit_value`, 
					`est_useful_life`, 
					`icspar`, 
					`ics_par_id`, 
					`received_by`, 
					`dept_id`, 
					`date_acquired`, 
					`req_item_id`, 
					`su_id`, 
					`remarks`
					) VALUES (
					'$eqpNum',
					'$date',
					'$propNum',
					'$getitemdetails[item_id]',
					'$getitemdetails[item_unit_id]',
					'".escapeString($_POST['eqpbrand'])."',
					'$getitemdetails[description]',
					'$sn',
					'$getitemdetails[est_unit_cost]',
					'".escapeString($_POST['estlife'])."',
					'ICS',
					'$icsid',
					'$getpr[personnel_id]',
					'$getdept[dept_id]',
					'".escapeString($_POST['dateacq'])."',
					'$readreqitem_id',
					'$getstockitems[su_id]',
					'Working'
					)");
					mysql_query("COMMIT");
				}catch(Exception $e){
					mysql_query("ROLLBACK");
					print "<script>alert('Something went wrong when saving Equipment to the System. Please check your connection.')</script>";
				}
				mysql_query("UNLOCK TABLE;");
				
				#get eqp_id
				$geteqpid = mysql_fetch_array(mysql_query("SELECT eqp_id FROM equipments WHERE eqp_id IN (SELECT MAX(eqp_id) FROM equipments) ")); //this makes the select last id recorded.
				$eqpid = $geteqpid[0];
				
				mysql_query("LOCK TABLE eqp_history WRITE;");
				
				try{
					mysql_query("INSERT INTO `eqp_history`(
					`eqp_id`, 
					`receivedBy`, 
					`historydate`, 
					`icspar`, 
					`icspar_id`, 
					`remarks`
					) VALUES (
					'$eqpid',
					'$getpr[personnel_id]',
					'".escapeString($_POST['dateacq'])."',
					'ICS',
					'$icsid',
					'New Condition'
					)");
				}catch(Exception $e){
					mysql_query("ROLLBACK");
					print "<script>alert('Something went wrong when saving Equipment history to the System. Please check your connection.')</script>";
				}
				mysql_query("UNLOCK TABLE;");
			}
		}
		
		$updateRI = "UPDATE `request_items` SET `icspar` = 'ICS', `icspar_id` = '$icsid', issuance = 'Ready' WHERE `req_item_id` = '$readreqitem_id' ";
		mysql_query($updateRI);

		print "<script>alert('Inventory Custodian Slip has been saved successfully.'); window.location='add_ris.php?id=".$getitemdetails['pr_id']."';</script>";
	}
	
	?>
	
	
</body>
<!-- /.Body -->

</div> <!-- /.wrapper-->

</html>