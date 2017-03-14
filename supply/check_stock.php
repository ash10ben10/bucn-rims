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
	<?php include "../engine/jscalls.php"; ?>
	
	<?php include "cart_engine.php"; ?>
	
	<script>
	
	var itemCount = 0;
	
	function submitform(e){
			if(itemCount > 0){
				return true;
			}else{
				e.preventDefault();
					alert("Please add item(s).");
					return false;
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
				<li>
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
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-shopping-cart zmdi-hc-1x"></i>&nbsp;&nbsp;Check for Stocks</h1>
					
						<br />
						
					<form method="POST" id="addrequest" enctype="multipart/form-data" onsubmit="submitform(event);">	
						<div class="panel panel-default" div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
							<div class="panel-body">
							
								<div = "col-lg-12">
									<div class="panel panel-default">
										<div class="panel-body">
											<div class="col-lg-12">
												<div class="row">
													<div class="col-lg-5" style="margin: 10px 0 10px 0;">
														<label>Item Name:</label>
														<!--<select name="itemname" id="itemname" class="selectpicker form-control" data-hide-disabled="true" data-live-search="true" required />
															<?php 
																//$query = mysql_query("SELECT si.stock_no, i.item_id, i.item_name, iu.item_unit_id, iu.item_unit_name FROM `stock_items` AS si LEFT JOIN items AS i ON i.item_id = si.item_id LEFT JOIN item_unit AS iu ON iu.item_unit_id = si.item_unit_id WHERE si.stock_type = 'Supply'");
																/* $query = mysql_query("SELECT i.item_id, i.item_name, iu.item_unit_id, iu.item_unit_name FROM `items` AS i LEFT JOIN item_unit AS iu ON iu.item_unit_id = i.item_unit_id WHERE item_type = 'Supply' AND quantity ");
																echo "<option selected disabled>-Select Items-</option>";
																while($row = mysql_fetch_array($query)){
																	echo "<option value='".$row['item_id']."' alt='".$row['item_unit_name']."' data='".$row['item_unit_id']."' >".ucfirst($row['item_name'])."</option>";
																} */
															?>
														</select>-->
														<select name="itemname" id="itemname" class="selectpicker form-control" data-hide-disabled="true" data-live-search="true" required />
															<?php 
																$query = mysql_query("SELECT su.su_id, su.stock_id, su.stock_no, si.item_id, i.item_name, si.stock_type, si.description, su.item_unit_id, iu.item_unit_name, su.quantity FROM `stock_items` AS si LEFT JOIN stock_units AS su ON su.stock_id = si.stock_id LEFT JOIN items AS i ON i.item_id = si.item_id LEFT JOIN item_unit AS iu ON iu.item_unit_id = su.item_unit_id WHERE si.stock_type = 'Supply' AND su.quantity != 0");
																echo "<option selected disabled>-Select Items-</option>";
																while($row = mysql_fetch_array($query)){
																	echo "<option value='".$row['su_id']."' qty='".$row['quantity']."' iunit='".$row['item_unit_name']."' desc='".$row['item_name'].", ".$row['description']."' >".ucfirst($row['item_name'].", ".$row['description']." in ".$row['item_unit_name'])."</option>";
																}
															?>
														</select>
													</div>
													
													<div class="col-lg-2" style="margin: 36px 0 10px 0;">
														<a class="btn btn-default" onClick="addItem()" title="This will add your selected item to the table." ><span class="fa fa-plus-circle fa-fw"></span>&nbsp;&nbsp;Add item</a>
													</div>
												</div>
												<br/>
												<div style="font-size:13px;"><i>Can't find what you're looking for? You can ask for a purchase request by creating a <a href="../requests/add_req.php">purchase request</a>.</i></div>
											</div>
										</div>
									</div>
									
									<div class="panel panel-default">
										<div class="panel-body">
											<div class="col-lg-12">
												<input name="reqCtr" id="reqCtr" type="hidden" value="0"></input>
												<br /> <br />
												<div class="table-responsive">
													<table id="itemsTbl" class="table table-striped table-bordered table-hover">
														<thead>
															<tr id="idDefault">
																<th><center>Item Description</center></th>
																<th><center>Remaining Quantity</center></th>
																<th><center>Requesting Quantity</center></th>
																<th><center>Unit of Issue</center></th>
																<th><center>Option</center></th>
															</tr>
														</thead>
														<tbody></tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
									
									<script>
										var itemList = [];
										function addItem(){
											
											var $itemSelect = $("#itemname");
											var iID = $itemSelect.val();
											var result = $.grep(itemList, function(e){ return (e.iID == iID); });
											if(iID && !(result.length)){
												var reqCtr = $("#reqCtr").val();
												$("#reqCtr").val(++reqCtr); 
												
												itemList.push({"reqCtr" : reqCtr, "iID" : iID});
												
												var iName = $itemSelect.find("option:selected").text();
												var iUnit = $itemSelect.find("option:selected").attr("iunit");
												var iQty = $itemSelect.find("option:selected").attr("qty");
												var iDesc = $itemSelect.find("option:selected").attr("desc");
												
												$( "#itemsTbl tbody" ).append( "<tr id='item"+reqCtr+"'>" +
												'<td style="text-align:center;vertical-align:middle;"><center><input type="hidden" name="item'+reqCtr+'" value="'+iID+'" required >'+iDesc+'</center></td>' +
												'<td style="text-align:center;vertical-align:middle;"><center>'+iQty+'</center></td>'+
												'<td style="text-align:center;vertical-align:middle;"><center><input style="width:80px;" class="form-control" name="qty'+reqCtr+'" value="" pattern="([0-9.])+" min="1" max="'+iQty+'" type="number" required /></center></td>' +
												'<td style="text-align:center;vertical-align:middle;"><center>'+iUnit+'</center></td>'+
												'<td style="text-align:center;vertical-align:middle;"><center><a class="btn btn-default" onClick="delItem(\''+reqCtr+'\')" title="Delete item." ><span class="fa fa-minus-circle fa-fw"></span></a></center></td>' +
												"</tr>"
												);
												
												var row = $("#item"+reqCtr);
												
												itemCount++; 
											}else{
												if(result.length){
													alert("Description is already in the list.");
												}else{
													alert('Please select an item.');
												}
											}
										}
										function delItem(reqCtr){
											var row = $("#item"+reqCtr);
											row.remove();
											var index = itemList.findIndex(x => x.reqCtr == reqCtr);
											itemList.splice(index, 1);
											if(itemCount > 0){
												itemCount--;
											}
											
											console.log(itemCount);
											
										}
									</script>
								
								</div>
							</div>
							<div class="panel-footer" align="right">
								<button type="submit" name="cartsave" id="cartsave" class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;Submit</button>
								<a href="../supply/stock_req.php" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span>&nbsp;Cancel</a>
							</div>
						</div>
					</form>
				</div>
			</div>
			
		</div> <!--/.container-fluid-->
	
	</div> <!--/.content-wrapper-->
	
	
	
</body>
<!-- /.Body -->

</div> <!-- /.wrapper-->

</html>