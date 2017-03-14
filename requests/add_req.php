<!DOCTYPE html>
<?php
	require_once "../connect.php";
	
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
	
	#this sets the current date and time everytime a process occurs
	date_default_timezone_set("Asia/Manila");
	$date = date("Y-m-d");
	$datetime = date("Y-m-d H:i:s");
	
?>
<html lang="en">

<head>
	<!-- Calling Default CSS files -->
	<?php include "../engine/csscalls.php"; ?>
	<!-- Calling Default Javascript files -->
	<?php include "../engine/jscalls.php"; ?>
	
	<?php include "addreq_engine.php"; ?>
	
	<script>
	
	$(document).ready(function() {
		
		var $iUnit = $("#itemunit");
		var $iDesc = $("#selectdesc");
		
		$("#itemname").on("change", function(){
			//$("#itemunit").val($(this).find("option:selected").attr("alt"));
			
			$.ajax({
					url: "addreq_engine.php",
					data: {
						item_id: $(this).val(),
						getunit: "getItemUnit",
						},
					type: "POST",
					dataType: "json"
				}).done(function(response){
					$iUnit.html("");
					$.each(response.data, function(index, element){
						console.log(element.unitname);
						$iUnit.append("<option value='"+element.unitid+"' alt='"+element.munit+"'>"+element.unitname+"</option>");
					});
					
					$iUnit.selectpicker("refresh");
					$iUnit.trigger("change");
				}).fail(function(){
					alert("error sending request.")
				});
		});
		
		$("#itemunit").on("change", function(){
			
			$.ajax({
				url: "addreq_engine.php",
				data: {
					munit_id: $(this).find("option:selected").attr("alt"),
					func: "getItemDescription",
					},
				type: "POST",
				dataType: "json"
			}).done(function(response){
				$iDesc.html("");
				$iDesc.append("<option alt='' data=''>Create Description</option>");
				$.each(response.data, function(index, element){
					console.log(element.desc);
					console.log(element.price);
					$iDesc.append("<option value='"+element.desc+"' alt='"+element.price+"' data='"+element.desc+"'>"+element.desc+"</option>");
				});
				
				$iDesc.selectpicker("refresh");
				$iDesc.trigger("change");
				
			}).fail(function(){
				alert("error sending request.")
			});
			
		});
		
		$iDesc.on("change", function(){
			$("#inputdesc").val($(this).find("option:selected").attr("data"));
		});
	});
	
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
					<h1 style="font-family: Calibri;">&nbsp;<i class="zmdi zmdi-file zmdi-hc-lg"></i>&nbsp;&nbsp;New Purchase Request</h1>
					
						<br />
						<div class="panel panel-default" style="box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);">
							
							<form method="POST" id="addrequest" enctype="multipart/form-data" onsubmit="submitform(event);">
								<div class="panel-body">
									<br />
									<div class="row">
										<div class="col-md-12">
											<div class="col-lg-6">
												<!--<div class="form-group col-md-6" type="hidden">
													<label>Department:</label>
													<input class="form-control" name="reqdept" id="reqdept" placeholder="Department" value="BUCN" readonly required />
												</div>-->
												<!--<div class="form-group col-md-6">
													<label>PR No.</label>
													<input class="form-control" name="reqnum" id="reqnum" value="<?php //print date("Y-m")."-"; ?>" required />
												</div>-->
												<div class="row" style="margin: 10px 0 10px 0;">
													<div class="col-lg-12">
													<label>Section:</label>
													<select name="reqsec" id="reqsec" class="selectpicker form-control" data-hide-disabled="true" data-live-search="true" required >
													<?php 
														$query = mysql_query("SELECT dept_id, dept_name FROM `department`");
														echo "<option selected disabled>-Select Department-</option>";
														while($row = mysql_fetch_array($query)){
															if($row['dept_id'] == $pworkinfo['dept_id'])
															echo "<option value='".$row['dept_id']."' selected>".ucfirst($row['dept_name'])."</option>";
															else echo "<option value='".$row['dept_id']."'>".ucfirst($row['dept_name'])."</option>";
														}
													?>
													</select>
													</div>
												</div>
												<div class="row" style="margin: 10px 0 10px 0;">
													<div class="col-lg-12">
														<label>SAI No:</label>
														<input class="form-control" name="reqsai" id="reqsai" pattern="([0-9])+" placeholder="e.g. 12345 or numbers only" />
													</div>
												</div>
											</div>
											<div class="col-lg-6">
												<div class="col-lg-12">
													<?php 
													if($position['position_name'] != "Supply Officer"){
														?>
															<label style="margin: 10px 0 5px 0;">Purpose:</label>
															<div class="col-lg-12" hidden>
															<div class="radio"><label><input type="radio" value="fper" name="purtype" id="purtype" checked />&nbsp;for Personnel/Office needs</label></div>
															</div>
															<textarea class="form-control" name="reqpur" id="reqpur" style="margin: 10px 0 0 0;resize:none;" rows="4" placeholder="Please indicate your purpose." required></textarea>
														<?php
													}else{
														?>
															<label style="margin: 10px 0 5px 0;">Purpose:</label>
															<textarea class="form-control" name="reqpur" id="reqpur" style="resize:none;" rows="3" placeholder="Please specify the selected purpose." required></textarea>
															<div class="row">
																<div class="col-lg-1"></div>
																<div class="col-lg-5" align="left" style="margin-top:-13px;">
																<div class="radio"><label><input type="radio" value="finv" name="purtype" id="purtype" checked />&nbsp;for Store Room needs</label></div>
																</div>
																<div class="col-lg-6" align="left" style="margin-top:-13px;">
																<div class="radio"><label><input type="radio" value="fper" name="purtype" id="purtype" />&nbsp;for Personnel/Office needs</label></div>
																</div>
															</div>
														<?php
													}
													?>
												</div>
											</div>
											<br/>
											<div class="form-group col-md-12">
													<label>Items to request:</label> 
													
													<div class="panel panel-default">
														<div class="panel-body">
														
															<div class="row">
																<div class="col-lg-3" style="margin: 10px 0 10px 0;">
																	<label>Item Name:</label>
																	<select name="itemname" id="itemname" class="selectpicker form-control" data-hide-disabled="true" data-live-search="true" required />
																		<?php 
																			$query = mysql_query("SELECT * FROM `items`");
																			echo "<option selected disabled>-Select Items-</option>";
																			while($row = mysql_fetch_array($query)){
																				echo "<option value='".$row['item_id']."' >".ucfirst($row['item_name'])."</option>";
																			}
																		?>
																	</select>
																</div>
																<div class="form-group col-md-3" style="margin: 10px 0 10px 0;">
																	<label>Item Unit:</label>
																	<select class="selectpicker form-control" name="itemunit" id="itemunit" value="" data-hide-disabled="true" data-live-search="true" onChange="" required >
																	</select>
																</div>
																<div class="col-lg-6" style="margin: 10px 0 10px 0;">	
																	<label>Item Description:</label>
																	<select name="selectdesc" id="selectdesc" class="selectpicker form-control" data-hide-disabled="true" data-live-search="true" onChange="" />
																	</select>
																</div>
															</div>
															<div class="row">
																
																<div class="col-lg-6" style="margin: 5px 0 10px 0;">
																	<textarea class="form-control" style="resize:none;" name="inputdesc" id="inputdesc" rows="3" placeholder="If item description you are looking for is not available on the drop - down list, please type the item description you wish to request here."></textarea>
																</div>
																
																<div class="col-lg-6" style="margin-top: 45px;" align="right">
																	<a class="btn btn-default" onClick="addItem()" title="This will add your selected item to the table." ><span class="fa fa-plus-circle fa-fw"></span>&nbsp;&nbsp;Add item</a>
																</div>
																
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
																				<th><center>Item Name</center></th>
																				<th><center>Unit of Issue</center></th>
																				<th><center>Item Description</center></th>
																				<th><center>Quantity</center></th>
																				<th><center>Unit Cost</center></th>
																				<th><center>Amount</center></th>
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
																var $iUnit = $("#itemunit");
																var iID = $itemSelect.val();
																var iUID = $iUnit.val();
																var $Description = $("#selectdesc");
																var iDesc = $.trim($("#inputdesc").val());
																var result = $.grep(itemList, function(e){ return (e.iID == iID && e.iDesc == iDesc && e.iUID == iUID); });
																if(iID && iUID && iDesc && !(result.length)){
																	var reqCtr = $("#reqCtr").val();
																	$("#reqCtr").val(++reqCtr);
																	
																	itemList.push({"reqCtr" : reqCtr, "iID" : iID, "iDesc" : iDesc, "iUID" : iUID});
																	
																	var iName = $itemSelect.find("option:selected").text();
																	var iUnit = $iUnit.find("option:selected").text();
																	//var iUCost = ($Description.attr("alt")) ? $Description.attr("alt") : 0;
																	//var iUCost = $iUnit.find("option:selected").attr("alt");
																	var iUCost = $Description.find("option:selected").attr("alt");
																	
																	$( "#itemsTbl tbody" ).append( "<tr id='item"+reqCtr+"'>" +
																	'<td style="text-align:center;vertical-align:middle;"><center><input type="hidden" name="item'+reqCtr+'" value="'+iID+'" required >'+iName+'</center></td>' +
																	'<td style="text-align:center;vertical-align:middle;"><center><input type="hidden" name="unit'+reqCtr+'" value="'+iUID+'" required >'+iUnit+'</center></td>'+
																	'<td style="text-align:center;vertical-align:middle;"><center><input type="hidden" style="width:210px;" class="form-control" name="desc'+reqCtr+'" value="'+iDesc.replace(/"/g, "&quot;")+'" required />'+iDesc+'</center></td>' +
																	'<td style="text-align:center;vertical-align:middle;"><center><input style="width:80px;" class="qty form-control" name="qty'+reqCtr+'" value="" pattern="([0-9.])+" min="1" type="number" required /></center></td>' +
																	'<td style="text-align:center;vertical-align:middle;"><center><input style="width:90px;" class="estone form-control" name="estone'+reqCtr+'" value="'+iUCost+'" pattern="([0-9.])+" min="0.01" type="number" step="0.01" required /></center></td>' +
																	'<td style="text-align:center;vertical-align:middle;"><center><input style="width:90px;" class="esttotal form-control" name="esttotal'+reqCtr+'" value="" pattern="([0-9.])+" min="0.01" type="number" step="0.01" readonly /></center></td>' +
																	'<td style="text-align:center;vertical-align:middle;"><center><a class="btn btn-default" onClick="delItem(\''+reqCtr+'\')" title="Delete item." ><span class="fa fa-minus-circle fa-fw"></span></a></center></td>' +
																	"</tr>"
																	);
																	
																	var row = $("#item"+reqCtr);
																	
																	var estTotal = row.find(".esttotal");
																	var estOne = row.find(".estone");
																	var qty = row.find(".qty");
																	var estTotalFunct = function(qtyVal, estOneVal){
																		estTotal.val(parseFloat(qtyVal * estOneVal).toFixed(2));
																	}
																	qty.off("keyup").on("keyup", function(){
																		estTotalFunct($(this).val(), estOne.val());
																	});
																	
																	estOne.off("keyup").on("keyup", function(){
																		estTotalFunct(qty.val(), $(this).val());
																	});
																	
																	itemCount++;
																}else{
																	if(result.length){
																		alert("Description is already in the list.");
																	}else{
																		alert('Please complete the form.');
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
									</div>
								</div>
								<div class="panel-footer" align="right">
									<button type="submit" name="prsave" id="prsave" class="btn btn-success"><span class="glyphicon glyphicon-floppy-disk"></span>&nbsp;Submit</button>
									<a href="../requests/req.php" class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span>&nbsp;Cancel</a>
								</div>
							</form>
						</div>
				</div>
			</div>
		
			
		
		</div> <!--/.container-fluid-->
	
	</div> <!--/.content-wrapper-->
	
	
</body>
<!-- /.Body -->

</div> <!-- /.wrapper-->

</html>